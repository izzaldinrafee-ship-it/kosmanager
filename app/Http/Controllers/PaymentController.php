<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    /**
     * Menampilkan daftar pembayaran, filter, pagination,
     * dan ringkasan keuangan.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $month = $request->input('month');
        $year = $request->input('year');

        $query = Payment::with('tenant.room')
            ->when($search, function ($query, $search) {
                $query->whereHas('tenant', function ($tenantQuery) use ($search) {
                    $tenantQuery->where(function ($q) use ($search) {
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
                });
            })
            ->when(
                in_array($status, ['Belum Bayar', 'Sebagian', 'Lunas'], true),
                fn ($query) => $query->where('status', $status)
            )
            ->when(
                is_numeric($month)
                    && (int) $month >= 1
                    && (int) $month <= 12,
                fn ($query) => $query->where(
                    'billing_month',
                    (int) $month
                )
            )
            ->when(
                is_numeric($year)
                    && (int) $year >= 2020
                    && (int) $year <= 2100,
                fn ($query) => $query->where(
                    'billing_year',
                    (int) $year
                )
            );

        $totalTagihan = (clone $query)->sum('amount');
        $totalDibayar = (clone $query)->sum('paid_amount');

        // CASE WHEN kompatibel dengan SQLite dan MySQL.
        $totalSisa = (clone $query)
            ->selectRaw(
                'COALESCE(SUM(
                    CASE
                        WHEN amount > paid_amount
                        THEN amount - paid_amount
                        ELSE 0
                    END
                ), 0) AS total'
            )
            ->value('total');

        $payments = (clone $query)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('payments.index', compact(
            'payments',
            'search',
            'status',
            'month',
            'year',
            'totalTagihan',
            'totalDibayar',
            'totalSisa'
        ));
    }

    /**
     * Menampilkan formulir pembuatan tagihan.
     */
    public function create()
    {
        $tenants = Tenant::with('room')
            ->where('status', 'Aktif')
            ->orderBy('name')
            ->get();

        return view('payments.create', compact('tenants'));
    }

    /**
     * Membuat tagihan untuk penghuni aktif.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => [
                'required',
                'integer',
                'exists:tenants,id',
            ],
            'billing_month' => [
                'required',
                'integer',
                'between:1,12',
            ],
            'billing_year' => [
                'required',
                'integer',
                'min:2020',
                'max:2100',
            ],
            'amount' => [
                'required',
                'integer',
                'min:1',
            ],
            'due_date' => [
                'required',
                'date',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        DB::transaction(function () use ($validated) {
            $tenant = Tenant::whereKey($validated['tenant_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($tenant->status !== 'Aktif') {
                throw ValidationException::withMessages([
                    'tenant_id' => 'Tagihan hanya dapat dibuat untuk penghuni aktif.',
                ]);
            }

            $exists = Payment::where('tenant_id', $tenant->id)
                ->where('billing_month', $validated['billing_month'])
                ->where('billing_year', $validated['billing_year'])
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'billing_month' => 'Tagihan untuk penghuni dan periode tersebut sudah ada.',
                ]);
            }

            Payment::create([
                'tenant_id' => $tenant->id,
                'billing_month' => $validated['billing_month'],
                'billing_year' => $validated['billing_year'],
                'amount' => $validated['amount'],
                'paid_amount' => 0,
                'due_date' => $validated['due_date'],
                'status' => 'Belum Bayar',
                'paid_at' => null,
                'notes' => $validated['notes'] ?? null,
            ]);
        }, 3);

        return redirect()
            ->route('payments.index')
            ->with('success', 'Tagihan berhasil dibuat.');
    }

    /**
     * Menampilkan formulir edit tagihan.
     */
    public function edit(Payment $payment)
    {
        $payment->load('tenant.room');

        return view('payments.edit', compact('payment'));
    }

    /**
     * Memperbarui detail tagihan tanpa mengubah
     * riwayat transaksi pembayaran.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'amount' => [
                'required',
                'integer',
                'min:1',
            ],
            'due_date' => [
                'required',
                'date',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        DB::transaction(function () use ($payment, $validated) {
            $lockedPayment = Payment::whereKey($payment->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                (int) $validated['amount']
                < (int) $lockedPayment->paid_amount
            ) {
                throw ValidationException::withMessages([
                    'amount' => 'Total tagihan tidak boleh lebih kecil daripada jumlah yang sudah dibayar.',
                ]);
            }

            $lockedPayment->amount = $validated['amount'];
            $lockedPayment->due_date = $validated['due_date'];
            $lockedPayment->notes = $validated['notes'] ?? null;

            if (
                (int) $lockedPayment->paid_amount
                >= (int) $lockedPayment->amount
            ) {
                $lockedPayment->status = 'Lunas';
            } elseif ((int) $lockedPayment->paid_amount > 0) {
                $lockedPayment->status = 'Sebagian';
                $lockedPayment->paid_at = null;
            } else {
                $lockedPayment->status = 'Belum Bayar';
                $lockedPayment->paid_at = null;
            }

            $lockedPayment->save();
        }, 3);

        return redirect()
            ->route('payments.index')
            ->with('success', 'Detail tagihan berhasil diperbarui.');
    }

    /**
     * Menghapus tagihan hanya jika belum dibayar
     * dan belum mempunyai riwayat transaksi.
     */
    public function destroy(Payment $payment)
    {
        DB::transaction(function () use ($payment) {
            $lockedPayment = Payment::whereKey($payment->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                (int) $lockedPayment->paid_amount > 0
                || $lockedPayment->transactions()->exists()
            ) {
                throw ValidationException::withMessages([
                    'payment' => 'Tagihan tidak dapat dihapus karena sudah memiliki pembayaran atau riwayat transaksi.',
                ]);
            }

            $lockedPayment->delete();
        }, 3);

        return redirect()
            ->route('payments.index')
            ->with('success', 'Tagihan berhasil dihapus.');
    }
}
