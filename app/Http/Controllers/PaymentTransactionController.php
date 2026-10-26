<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentTransactionController extends Controller
{
    /**
     * Menampilkan riwayat transaksi untuk satu tagihan.
     */
    public function index(Payment $payment)
    {
        $payment->load('tenant.room');

        $transactions = $payment->transactions()
            ->latest('transaction_date')
            ->latest('id')
            ->paginate(10);

        return view('payments.transactions.index', compact(
            'payment',
            'transactions'
        ));
    }

    /**
     * Menampilkan formulir pencatatan pembayaran.
     */
    public function create(Payment $payment)
    {
        $payment->load('tenant.room');

        $remainingAmount = max(
            0,
            (int) $payment->amount - (int) $payment->paid_amount
        );

        return view('payments.transactions.create', compact(
            'payment',
            'remainingAmount'
        ));
    }

    /**
     * Mencatat pembayaran dan memperbarui status tagihan
     * dalam satu transaksi database.
     */
    public function store(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'amount' => [
                'required',
                'integer',
                'min:1',
            ],
            'transaction_date' => [
                'required',
                'date',
            ],
            'payment_method' => [
                'required',
                'in:Tunai,Transfer,QRIS,E-Wallet',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'amount.required' => 'Nominal pembayaran wajib diisi.',
            'amount.integer' => 'Nominal pembayaran harus berupa bilangan bulat.',
            'amount.min' => 'Nominal pembayaran minimal Rp1.',
            'transaction_date.required' => 'Tanggal pembayaran wajib diisi.',
            'transaction_date.date' => 'Tanggal pembayaran tidak valid.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method.in' => 'Metode pembayaran yang dipilih tidak valid.',
        ]);

        DB::transaction(function () use ($payment, $validated) {
            // Kunci tagihan agar transaksi bersamaan tidak
            // menghitung sisa pembayaran dari data yang sama.
            $lockedPayment = Payment::whereKey($payment->id)
                ->lockForUpdate()
                ->firstOrFail();

            $amount = (int) $validated['amount'];
            $totalAmount = (int) $lockedPayment->amount;
            $paidAmount = (int) $lockedPayment->paid_amount;

            $remainingAmount = max(
                0,
                $totalAmount - $paidAmount
            );

            if ($remainingAmount <= 0) {
                throw ValidationException::withMessages([
                    'amount' => 'Tagihan sudah lunas. Tidak ada sisa pembayaran.',
                ]);
            }

            if ($amount > $remainingAmount) {
                throw ValidationException::withMessages([
                    'amount' => 'Nominal pembayaran melebihi sisa tagihan. Sisa tagihan saat ini Rp'
                        . number_format($remainingAmount, 0, ',', '.')
                        . '.',
                ]);
            }

            PaymentTransaction::create([
                'payment_id' => $lockedPayment->id,
                'amount' => $amount,
                'transaction_date' => $validated['transaction_date'],
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'] ?? null,
            ]);

            $newPaidAmount = $paidAmount + $amount;

            $lockedPayment->paid_amount = $newPaidAmount;

            if ($newPaidAmount >= $totalAmount) {
                $lockedPayment->status = 'Lunas';
                $lockedPayment->paid_at = $validated['transaction_date'];
            } elseif ($newPaidAmount > 0) {
                $lockedPayment->status = 'Sebagian';
                $lockedPayment->paid_at = null;
            } else {
                $lockedPayment->status = 'Belum Bayar';
                $lockedPayment->paid_at = null;
            }

            $lockedPayment->save();
        }, 3);

        return redirect()
            ->route('payments.transactions.index', $payment)
            ->with('success', 'Pembayaran berhasil dicatat.');
    }
}
