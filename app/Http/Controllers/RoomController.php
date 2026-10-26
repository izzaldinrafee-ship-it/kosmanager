<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::withCount([
            'tenants as active_tenants_count' => function ($query) {
                $query->where('status', 'Aktif');
            },
        ])
            ->latest()
            ->get();

        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => [
                'required',
                'string',
                'max:50',
                'unique:rooms,room_number',
            ],
            'room_type' => ['required', 'string', 'max:100'],
            'price' => ['required', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['Kosong', 'Terisi'])],
            'description' => ['nullable', 'string'],
        ]);

        // Status kamar baru harus Kosong karena belum memiliki penghuni.
        $validated['status'] = 'Kosong';

        Room::create($validated);

        return redirect()
            ->route('rooms.index')
            ->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('rooms', 'room_number')->ignore($room->id),
            ],
            'room_type' => ['required', 'string', 'max:100'],
            'price' => ['required', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['Kosong', 'Terisi'])],
            'description' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $room) {
            $lockedRoom = Room::whereKey($room->id)
                ->lockForUpdate()
                ->firstOrFail();

            $hasActiveTenant = Tenant::where('room_id', $lockedRoom->id)
                ->where('status', 'Aktif')
                ->exists();

            // Status kamar ditentukan oleh penghuni aktif,
            // bukan pilihan manual dari formulir.
            $validated['status'] = $hasActiveTenant
                ? 'Terisi'
                : 'Kosong';

            $lockedRoom->update($validated);
        });

        return redirect()
            ->route('rooms.index')
            ->with('success', 'Data kamar berhasil diperbarui.');
    }

    public function destroy(Room $room)
    {
        if ($room->tenants()->exists()) {
            return redirect()
                ->route('rooms.index')
                ->with(
                    'error',
                    'Kamar tidak dapat dihapus karena masih memiliki riwayat penghuni.'
                );
        }

        $room->delete();

        return redirect()
            ->route('rooms.index')
            ->with('success', 'Kamar berhasil dihapus.');
    }
}
