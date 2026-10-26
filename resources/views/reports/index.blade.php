
@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Carbon;

    $namaBulan = Carbon::createFromDate($year, $month, 1)
        ->locale('id')
        ->translatedFormat('F Y');

    $rupiah = fn ($nominal) => 'Rp ' . number_format(
        $nominal,
        0,
        ',',
        '.'
    );
@endphp

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Laporan Keuangan</h2>
            <p class="text-secondary mb-0">
                Ringkasan tagihan, penerimaan uang, dan tunggakan kos.
            </p>
        </div>

        <button type="button" class="btn btn-outline-dark" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Cetak Laporan
        </button>
    </div>

    {{-- Filter Periode --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4 report-filter">
        <div class="card-body p-4">
            <form action="{{ route('reports.index') }}" method="GET"
                class="row g-3 align-items-end">

                <div class="col-md-5">
                    <label for="month" class="form-label fw-semibold">
                        Bulan
                    </label>
                    <select name="month" id="month" class="form-select">
                        @foreach ([
                            1 => 'Januari',
                            2 => 'Februari',
                            3 => 'Maret',
                            4 => 'April',
                            5 => 'Mei',
                            6 => 'Juni',
                            7 => 'Juli',
                            8 => 'Agustus',
                            9 => 'September',
                            10 => 'Oktober',
                            11 => 'November',
                            12 => 'Desember'
                        ] as $angka => $nama)
                            <option value="{{ $angka }}"
                                @selected($month == $angka)>
                                {{ $nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="year" class="form-label fw-semibold">
                        Tahun
                    </label>
                    <select name="year" id="year" class="form-select">
                        @for ($tahun = now()->year; $tahun >= 2020; $tahun--)
                            <option value="{{ $tahun }}"
                                @selected($year == $tahun)>
                                {{ $tahun }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Periode Laporan --}}
    <div class="mb-4">
        <h5 class="fw-bold mb-1">Laporan {{ $namaBulan }}</h5>
        <p class="text-secondary small mb-0">
            Ringkasan tagihan berdasarkan periode tagihan dan penerimaan
            berdasarkan tanggal transaksi.
        </p>
    </div>

    {{-- Ringkasan Tagihan --}}
    <h5 class="fw-bold mb-3">Ringkasan Tagihan</h5>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-secondary mb-2">Total Tagihan</p>
                            <h4 class="fw-bold mb-1">
                                {{ $rupiah($totalTagihan) }}
                            </h4>
                            <small class="text-secondary">
                                {{ $jumlahTagihan }} tagihan
                            </small>
                        </div>
                        <div class="rounded-3 bg-primary bg-opacity-10 p-3">
                            <i class="bi bi-receipt text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-secondary mb-2">Dibayar pada Tagihan</p>
                            <h4 class="fw-bold text-success mb-1">
                                {{ $rupiah($totalDibayar) }}
                            </h4>
                            <small class="text-secondary">
                                Akumulasi pembayaran tagihan periode ini
                            </small>
                        </div>
                        <div class="rounded-3 bg-success bg-opacity-10 p-3">
                            <i class="bi bi-check-circle text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-secondary mb-2">Total Tunggakan</p>
                            <h4 class="fw-bold text-danger mb-1">
                                {{ $rupiah($totalTunggakan) }}
                            </h4>
                            <small class="text-secondary">
                                Sisa tagihan yang belum dibayar
                            </small>
                        </div>
                        <div class="rounded-3 bg-danger bg-opacity-10 p-3">
                            <i class="bi bi-exclamation-circle text-danger fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Status Tagihan --}}
    <h5 class="fw-bold mb-3">Status Tagihan</h5>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <p class="text-secondary mb-1">Lunas</p>
                    <h3 class="fw-bold text-success mb-0">{{ $jumlahLunas }}</h3>
                    <small class="text-secondary">tagihan lunas</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <p class="text-secondary mb-1">Sebagian</p>
                    <h3 class="fw-bold text-warning mb-0">{{ $jumlahSebagian }}</h3>
                    <small class="text-secondary">tagihan dibayar sebagian</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <p class="text-secondary mb-1">Belum Bayar</p>
                    <h3 class="fw-bold text-danger mb-0">{{ $jumlahBelumBayar }}</h3>
                    <small class="text-secondary">tagihan belum dibayar</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Rincian Tagihan --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 px-4 pt-4">
            <h5 class="fw-bold mb-1">Rincian Tagihan</h5>
            <p class="text-secondary small mb-0">
                Daftar tagihan yang jatuh pada periode {{ $namaBulan }}.
            </p>
        </div>

        <div class="card-body p-0 mt-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No.</th>
                            <th>Nama Penghuni</th>
                            <th>Kamar</th>
                            <th>Total Tagihan</th>
                            <th>Dibayar</th>
                            <th>Tunggakan</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payments as $payment)
                            @php
                                $sisa = max(
                                    0,
                                    $payment->amount - $payment->paid_amount
                                );
                            @endphp

                            <tr>
                                <td class="ps-4">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="fw-semibold">
                                    {{ $payment->tenant->name ?? 'Penghuni tidak tersedia' }}
                                </td>
                                <td>
                                    {{ $payment->tenant->room->room_number ?? '-' }}
                                </td>
                                <td>{{ $rupiah($payment->amount) }}</td>
                                <td class="text-success">
                                    {{ $rupiah($payment->paid_amount) }}
                                </td>
                                <td class="{{ $sisa > 0 ? 'text-danger' : 'text-success' }}">
                                    {{ $rupiah($sisa) }}
                                </td>
                                <td>
                                    {{ $payment->due_date
                                        ? $payment->due_date->format('d/m/Y')
                                        : '-' }}
                                </td>
                                <td>
                                    @if ($payment->status === 'Lunas')
                                        <span class="badge bg-success-subtle text-success">
                                            Lunas
                                        </span>
                                    @elseif ($payment->status === 'Sebagian')
                                        <span class="badge bg-warning-subtle text-warning-emphasis">
                                            Sebagian
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger">
                                            Belum Bayar
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-secondary">
                                    Tidak ada tagihan pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    @if ($payments->isNotEmpty())
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="3" class="ps-4">Total</td>
                                <td>{{ $rupiah($totalTagihan) }}</td>
                                <td class="text-success">
                                    {{ $rupiah($totalDibayar) }}
                                </td>
                                <td class="text-danger">
                                    {{ $rupiah($totalTunggakan) }}
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-0 px-4 py-3">
            <small class="text-secondary">
                Total {{ $jumlahTagihan }} tagihan pada periode ini.
            </small>
        </div>
    </div>

    {{-- Ringkasan Penerimaan --}}
    <h5 class="fw-bold mb-3">Penerimaan Uang</h5>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <p class="text-secondary mb-2">
                        Total Uang Masuk
                    </p>
                    <h3 class="fw-bold text-success mb-2">
                        {{ $rupiah($totalPenerimaan) }}
                    </h3>
                    <small class="text-secondary">
                        Berdasarkan transaksi yang tercatat pada {{ $namaBulan }}.
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <p class="text-secondary mb-2">
                        Jumlah Transaksi
                    </p>
                    <h3 class="fw-bold mb-2">{{ $jumlahTransaksi }}</h3>
                    <small class="text-secondary">
                        Transaksi pembayaran yang tercatat pada periode ini.
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- Penerimaan per Metode --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 px-4 pt-4">
            <h5 class="fw-bold mb-1">Penerimaan Berdasarkan Metode</h5>
            <p class="text-secondary small mb-0">
                Perbandingan penerimaan menurut metode pembayaran.
            </p>
        </div>

        <div class="card-body p-0 mt-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No.</th>
                            <th>Metode Pembayaran</th>
                            <th>Jumlah Transaksi</th>
                            <th>Total Penerimaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penerimaanPerMetode as $metode => $rekap)
                            <tr>
                                <td class="ps-4">{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $metode }}</td>
                                <td>{{ $rekap['jumlah_transaksi'] }}</td>
                                <td class="fw-semibold text-success">
                                    {{ $rupiah($rekap['total']) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-secondary">
                                    Belum ada transaksi pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Riwayat Transaksi --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 px-4 pt-4">
            <h5 class="fw-bold mb-1">Riwayat Transaksi Pembayaran</h5>
            <p class="text-secondary small mb-0">
                Uang yang diterima selama {{ $namaBulan }},
                termasuk pembayaran untuk tagihan periode sebelumnya.
            </p>
        </div>

        <div class="card-body p-0 mt-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No.</th>
                            <th>Tanggal</th>
                            <th>Nama Penghuni</th>
                            <th>Kamar</th>
                            <th>Periode Tagihan</th>
                            <th>Metode</th>
                            <th>Nominal</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr>
                                <td class="ps-4">{{ $loop->iteration }}</td>
                                <td>
                                    {{ $transaction->transaction_date
                                        ? $transaction->transaction_date->format('d/m/Y')
                                        : '-' }}
                                </td>
                                <td class="fw-semibold">
                                    {{ $transaction->payment?->tenant?->name
                                        ?? 'Penghuni tidak tersedia' }}
                                </td>
                                <td>
                                    {{ $transaction->payment?->tenant?->room?->room_number
                                        ?? '-' }}
                                </td>
                                <td>
                                    @if ($transaction->payment)
                                        {{ Carbon::createFromDate(
                                            $transaction->payment->billing_year,
                                            $transaction->payment->billing_month,
                                            1
                                        )->locale('id')->translatedFormat('F Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $transaction->payment_method }}</td>
                                <td class="fw-semibold text-success">
                                    {{ $rupiah($transaction->amount) }}
                                </td>
                                <td>
                                    {{ $transaction->notes ?: '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-secondary">
                                    Tidak ada transaksi pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    @if ($transactions->isNotEmpty())
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="6" class="ps-4">Total Penerimaan</td>
                                <td class="text-success">
                                    {{ $rupiah($totalPenerimaan) }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-0 px-4 py-3">
            <small class="text-secondary">
                Total {{ $jumlahTransaksi }} transaksi pada periode ini.
            </small>
        </div>
    </div>

</div>

<style>
    @media print {
        body {
            background: #fff !important;
        }

        .sidebar,
        nav,
        .report-filter,
        button,
        .btn,
        .card-footer {
            display: none !important;
        }

        .container-fluid {
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
            break-inside: avoid;
        }

        .table-responsive {
            overflow: visible !important;
        }

        table {
            width: 100% !important;
            font-size: 10px !important;
        }

        th,
        td {
            padding: 5px !important;
        }

        h2 {
            font-size: 20px !important;
        }

        h5 {
            font-size: 14px !important;
        }

        .row {
            break-inside: avoid;
        }
    }
</style>
@endsection
