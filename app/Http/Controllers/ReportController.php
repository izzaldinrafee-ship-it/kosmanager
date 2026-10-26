<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Menampilkan laporan tagihan dan penerimaan.
     */
    public function index(Request $request)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $month = $request->input('month', $currentMonth);
        $year = $request->input('year', $currentYear);

        // Validasi bulan laporan.
        if (
            !is_numeric($month)
            || (int) $month < 1
            || (int) $month > 12
        ) {
            $month = $currentMonth;
        }

        // Validasi tahun laporan.
        if (
            !is_numeric($year)
            || (int) $year < 2020
            || (int) $year > 2100
        ) {
            $year = $currentYear;
        }

        $month = (int) $month;
        $year = (int) $year;

        /*
         * BAGIAN 1:
         * Ringkasan tagihan berdasarkan bulan dan tahun tagihan.
         */
        $payments = Payment::with('tenant.room')
            ->where('billing_month', $month)
            ->where('billing_year', $year)
            ->latest()
            ->get();

        $totalTagihan = (int) $payments->sum('amount');
        $totalDibayar = (int) $payments->sum('paid_amount');

        $totalTunggakan = (int) $payments->sum(
            fn ($payment) => max(
                0,
                (int) $payment->amount - (int) $payment->paid_amount
            )
        );

        $jumlahTagihan = $payments->count();

        $jumlahLunas = $payments
            ->where('status', 'Lunas')
            ->count();

        $jumlahSebagian = $payments
            ->where('status', 'Sebagian')
            ->count();

        $jumlahBelumBayar = $payments
            ->where('status', 'Belum Bayar')
            ->count();

        /*
         * BAGIAN 2:
         * Penerimaan berdasarkan tanggal transaksi pembayaran.
         *
         * Contoh:
         * Tagihan bulan September yang dibayar Oktober
         * masuk ke laporan penerimaan bulan Oktober.
         */
        $transactions = PaymentTransaction::with('payment.tenant.room')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->latest('transaction_date')
            ->latest('id')
            ->get();

        $totalPenerimaan = (int) $transactions->sum('amount');
        $jumlahTransaksi = $transactions->count();

        /*
         * BAGIAN 3:
         * Rekap penerimaan berdasarkan metode pembayaran.
         */
        $penerimaanPerMetode = $transactions
            ->groupBy('payment_method')
            ->map(fn ($items) => [
                'jumlah_transaksi' => $items->count(),
                'total' => (int) $items->sum('amount'),
            ])
            ->sortKeys();

        return view('reports.index', compact(
            'month',
            'year',
            'payments',
            'totalTagihan',
            'totalDibayar',
            'totalTunggakan',
            'jumlahTagihan',
            'jumlahLunas',
            'jumlahSebagian',
            'jumlahBelumBayar',
            'transactions',
            'totalPenerimaan',
            'jumlahTransaksi',
            'penerimaanPerMetode'
        ));
    }
}
