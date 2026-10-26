<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Room;
use App\Models\Tenant;

class DashboardController extends Controller
{
    /**
     * Menampilkan ringkasan utama KosManager.
     */
    public function index()
    {
        // Ringkasan kamar.
        $totalRooms = Room::count();

        $emptyRooms = Room::where('status', 'Kosong')
            ->count();

        $occupiedRooms = Room::where('status', 'Terisi')
            ->count();

        // Ringkasan penghuni aktif.
        $activeTenants = Tenant::where('status', 'Aktif')
            ->count();

        // Periode tagihan bulan berjalan.
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $monthlyPayments = Payment::where('billing_month', $currentMonth)
            ->where('billing_year', $currentYear);

        $totalMonthlyBills = (int) (clone $monthlyPayments)
            ->sum('amount');

        $totalMonthlyPaid = (int) (clone $monthlyPayments)
            ->sum('paid_amount');

        $totalMonthlyOutstanding = (int) (clone $monthlyPayments)
            ->selectRaw(
                'COALESCE(SUM(GREATEST(amount - paid_amount, 0)), 0) AS total'
            )
            ->value('total');

        // Jumlah tagihan yang belum lunas dari seluruh periode.
        $unpaidBills = Payment::whereIn('status', [
            'Belum Bayar',
            'Sebagian',
        ])->count();

        // Lima tagihan terbaru.
        $recentPayments = Payment::with('tenant.room')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalRooms',
            'emptyRooms',
            'occupiedRooms',
            'activeTenants',
            'totalMonthlyBills',
            'totalMonthlyPaid',
            'totalMonthlyOutstanding',
            'unpaidBills',
            'recentPayments'
        ));
    }
}
