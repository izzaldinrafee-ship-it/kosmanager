
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid px-0">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Dashboard</h2>
            <p class="text-secondary mb-0">
                Ringkasan dan kondisi terkini pengelolaan kos.
            </p>
        </div>

        <span class="badge rounded-pill text-bg-light border px-3 py-2">
            <i class="bi bi-calendar3 me-1"></i>
            {{ now()->translatedFormat('F Y') }}
        </span>
    </div>

    {{-- Statistik kamar dan penghuni --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-secondary small mb-2">Total Kamar</p>
                        <h3 class="fw-bold mb-1">{{ $totalRooms }}</h3>
                        <small class="text-secondary">Seluruh kamar kos</small>
                    </div>
                    <div class="stat-icon text-primary bg-primary bg-opacity-10">
                        <i class="bi bi-door-closed-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-secondary small mb-2">Kamar Kosong</p>
                        <h3 class="fw-bold mb-1">{{ $emptyRooms }}</h3>
                        <small class="text-secondary">Siap ditempati</small>
                    </div>
                    <div class="stat-icon text-success bg-success bg-opacity-10">
                        <i class="bi bi-house-check-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-secondary small mb-2">Kamar Terisi</p>
                        <h3 class="fw-bold mb-1">{{ $occupiedRooms }}</h3>
                        <small class="text-secondary">Sedang ditempati</small>
                    </div>
                    <div class="stat-icon text-info bg-info bg-opacity-10">
                        <i class="bi bi-house-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-secondary small mb-2">Penghuni Aktif</p>
                        <h3 class="fw-bold mb-1">{{ $activeTenants }}</h3>
                        <small class="text-secondary">Penghuni terdaftar aktif</small>
                    </div>
                    <div class="stat-icon text-warning bg-warning bg-opacity-10">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Ringkasan keuangan --}}
    <div class="mb-3">
        <h5 class="fw-bold mb-1">Ringkasan Keuangan</h5>
        <p class="text-secondary small mb-0">
            Tagihan berdasarkan periode bulan ini.
        </p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="content-card h-100">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon text-primary bg-primary bg-opacity-10">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div>
                        <div class="text-secondary small">Total Tagihan</div>
                        <div class="fw-bold fs-5 text-break">
                            Rp {{ number_format($totalMonthlyBills, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                <div class="text-secondary small">Total tagihan bulan ini</div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="content-card h-100">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon text-success bg-success bg-opacity-10">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div>
                        <div class="text-secondary small">Sudah Dibayar</div>
                        <div class="fw-bold fs-5 text-break">
                            Rp {{ number_format($totalMonthlyPaid, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                <div class="text-secondary small">Akumulasi pembayaran bulan ini</div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="content-card h-100">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon text-danger bg-danger bg-opacity-10">
                        <i class="bi bi-exclamation-circle-fill"></i>
                    </div>
                    <div>
                        <div class="text-secondary small">Sisa Tagihan</div>
                        <div class="fw-bold fs-5 text-break">
                            Rp {{ number_format($totalMonthlyOutstanding, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                <div class="text-secondary small">
                    {{ $unpaidBills }} tagihan belum lunas dari seluruh periode
                </div>
            </div>
        </div>
    </div>

    {{-- Tagihan terbaru --}}
    <div class="content-card">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Tagihan Terbaru</h5>
                <p class="text-secondary small mb-0">
                    Lima tagihan yang terakhir dibuat.
                </p>
            </div>

            <a href="{{ route('payments.index') }}"
               class="btn btn-outline-primary btn-sm">
                Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Penghuni</th>
                        <th>Kamar</th>
                        <th>Periode</th>
                        <th>Total Tagihan</th>
                        <th>Sudah Dibayar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentPayments as $payment)
                        <tr>
                            <td class="fw-semibold">
                                {{ $payment->tenant?->name ?? 'Penghuni tidak tersedia' }}
                            </td>
                            <td>
                                {{ $payment->tenant?->room?->room_number ?? '-' }}
                            </td>
                            <td>
                                {{ \Carbon\Carbon::createFromDate(
                                    (int) $payment->billing_year,
                                    (int) $payment->billing_month,
                                    1
                                )->translatedFormat('F Y') }}
                            </td>
                            <td>
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </td>
                            <td class="text-success">
                                Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}
                            </td>
                            <td>
                                @php
                                    $statusClass = match ($payment->status) {
                                        'Lunas' => 'success',
                                        'Sebagian' => 'warning',
                                        default => 'danger',
                                    };
                                @endphp

                                <span class="badge text-bg-{{ $statusClass }}">
                                    {{ $payment->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-secondary">
                                Belum ada data tagihan.
                                <a href="{{ route('payments.create') }}">Buat tagihan pertama</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
