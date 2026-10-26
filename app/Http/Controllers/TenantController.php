<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TenantController extends Controller
{
    /**
     * Menampilkan daftar penghuni dengan pencarian,
     * filter status, dan pagination.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $tenants = Tenant::with('room')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhereHas('room', function ($roomQuery) use ($search) {
                            $roomQuery->where(
                                'room_number',
                                'like',
                                "%{$search}%"
                            );
                        });
                });
            })
            ->when(
                in_array($status, ['Aktif', 'Keluar'], true),
                fn ($query) => $query->where('status', $status)
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('tenants.index', compact(
            'tenants',
            'search',
            'status'
        ));
    }

    /**
     * Menampilkan formulir tambah penghuni.
     */
    public function create()
    {
        $rooms = Room::whereDoesntHave('tenants', function ($query) {
            $query->where('status', 'Aktif');
        })
            ->orderBy('room_number')
            ->get();

        return view('tenants.create', compact('rooms'));
    }

    /**
     * Menyimpan penghuni baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => [
                'required',
                'string',
                'max:25',
                'regex:/^[0-9+\-\s()]+$/',
            ],
            'address' => ['nullable', 'string', 'max:2000'],
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'move_in_date' => ['required', 'date'],
        ], [
            'phone.regex' => 'Nomor telepon hanya boleh berisi angka, spasi, dan simbol + - ( ).',
        ]);

        DB::transaction(function () use ($validated) {
            $room = Room::whereKey($validated['room_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $hasActiveTenant = Tenant::where('room_id', $room->id)
                ->where('status', 'Aktif')
                ->exists();

            if ($hasActiveTenant) {
                $room->update(['status' => 'Terisi']);

                throw ValidationException::withMessages([
                    'room_id' => 'Kamar sudah memiliki penghuni aktif. Pilih kamar lain.',
                ]);
            }

            $validated['status'] = 'Aktif';

            Tenant::create($validated);

            $room->update(['status' => 'Terisi']);
        }, 3);

        return redirect()
            ->route('tenants.index')
            ->with('success', 'Penghuni berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir edit penghuni.
     */
    public function edit(Tenant $tenant)
    {
        $rooms = Room::whereDoesntHave('tenants', function ($query) {
            $query->where('status', 'Aktif');
        })
            ->orWhere('id', $tenant->room_id)
            ->orderBy('room_number')
            ->get();

        return view('tenants.edit', compact('tenant', 'rooms'));
    }

    /**
     * Memperbarui data penghuni dan menyinkronkan status kamar.
     */
    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => [
                'required',
                'string',
                'max:25',
                'regex:/^[0-9+\-\s()]+$/',
            ],
            'address' => ['nullable', 'string', 'max:2000'],
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'move_in_date' => ['required', 'date'],
            'status' => ['required', Rule::in(['Aktif', 'Keluar'])],
        ], [
            'phone.regex' => 'Nomor telepon hanya boleh berisi angka, spasi, dan simbol + - ( ).',
        ]);

        DB::transaction(function () use ($validated, $tenant) {
            $lockedTenant = Tenant::whereKey($tenant->id)
                ->lockForUpdate()
                ->firstOrFail();

            $oldRoomId = (int) $lockedTenant->room_id;
            $newRoomId = (int) $validated['room_id'];

            $roomIds = array_unique([$oldRoomId, $newRoomId]);
            sort($roomIds);

            $rooms = Room::whereIn('id', $roomIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $newRoom = $rooms->get($newRoomId);

            if (!$newRoom) {
                throw ValidationException::withMessages([
                    'room_id' => 'Kamar yang dipilih tidak ditemukan.',
                ]);
            }

            // Jangan izinkan satu kamar memiliki lebih dari
            // satu penghuni aktif.
            if ($validated['status'] === 'Aktif') {
                $hasOtherActiveTenant = Tenant::where(
                    'room_id',
                    $newRoomId
                )
                    ->where('status', 'Aktif')
                    ->where('id', '!=', $lockedTenant->id)
                    ->exists();

                if ($hasOtherActiveTenant) {
                    throw ValidationException::withMessages([
                        'room_id' => 'Kamar sudah memiliki penghuni aktif. Pilih kamar lain.',
                    ]);
                }
            }

            $lockedTenant->update($validated);

            // Sinkronkan status semua kamar yang terdampak.
            foreach ($rooms as $room) {
                $hasActiveTenant = Tenant::where('room_id', $room->id)
                    ->where('status', 'Aktif')
                    ->exists();

                $room->update([
                    'status' => $hasActiveTenant ? 'Terisi' : 'Kosong',
                ]);
            }
        }, 3);

        return redirect()
            ->route('tenants.index')
            ->with('success', 'Data penghuni berhasil diperbarui.');
    }

    /**
     * Menghapus penghuni tanpa merusak riwayat tagihan.
     */
    public function destroy(Tenant $tenant)
    {
        if ($tenant->payments()->exists()) {
            return redirect()
                ->route('tenants.index')
                ->with(
                    'error',
                    'Penghuni tidak dapat dihapus karena masih memiliki riwayat tagihan. Ubah status menjadi Keluar jika penghuni sudah tidak tinggal di kos.'
                );
        }

        DB::transaction(function () use ($tenant) {
            $lockedTenant = Tenant::whereKey($tenant->id)
                ->lockForUpdate()
                ->firstOrFail();

            $room = Room::whereKey($lockedTenant->room_id)
                ->lockForUpdate()
                ->firstOrFail();

            // Periksa kembali riwayat tagihan di dalam transaksi.
            if ($lockedTenant->payments()->exists()) {
                throw ValidationException::withMessages([
                    'tenant' => 'Penghuni tidak dapat dihapus karena memiliki riwayat tagihan.',
                ]);
            }

            $lockedTenant->delete();

            $hasActiveTenant = Tenant::where('room_id', $room->id)
                ->where('status', 'Aktif')
                ->exists();

            $room->update([
                'status' => $hasActiveTenant ? 'Terisi' : 'Kosong',
            ]);
        }, 3);

        return redirect()
            ->route('tenants.index')
            ->with('success', 'Data penghuni berhasil dihapus.');
    }
}
