
@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    <div class="mb-4">
        <a href="{{ route('payments.index') }}" class="text-decoration-none">
            <i class="bi bi-arrow-left"></i> Kembali ke pembayaran
        </a>
        <h2 class="fw-bold mt-3 mb-1">Buat Tagihan</h2>
        <p class="text-secondary mb-0">Buat tagihan bulanan untuk penghuni aktif.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('payments.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="tenant_id" class="form-label">Penghuni</label>
                        <select name="tenant_id" id="tenant_id"
                                class="form-select @error('tenant_id') is-invalid @enderror"
                                required>
                            <option value="">Pilih penghuni</option>

                            @foreach ($tenants as $tenant)
                                <option value="{{ $tenant->id }}"
                                    data-price="{{ $tenant->room->price }}"
                                    @selected(old('tenant_id') == $tenant->id)>
                                    {{ $tenant->name }} — Kamar {{ $tenant->room->room_number }}
                                </option>
                            @endforeach
                        </select>
                        @error('tenant_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @if ($tenants->isEmpty())
                            <small class="text-danger">
                                Belum ada penghuni aktif. Tambahkan penghuni terlebih dahulu.
                            </small>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <label for="amount" class="form-label">Nominal Tagihan (Rp)</label>
                        <input type="number" name="amount" id="amount"
                               class="form-control @error('amount') is-invalid @enderror"
                               min="1" step="1" value="{{ old('amount') }}"
                               placeholder="Contoh: 750000" required>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-secondary">
                            Nominal bisa disesuaikan dengan kesepakatan kos.
                        </small>
                    </div>

                    <div class="col-md-6">
                        <label for="billing_month" class="form-label">Bulan Tagihan</label>
                        <select name="billing_month" id="billing_month" class="form-select" required>
                            <option value="">Pilih bulan</option>
                            @foreach ([
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                                4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                                10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ] as $number => $month)
                                <option value="{{ $number }}"
                                    @selected(old('billing_month', now()->month) == $number)>
                                    {{ $month }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="billing_year" class="form-label">Tahun Tagihan</label>
                        <input type="number" name="billing_year" id="billing_year"
                               class="form-control"
                               min="2020" max="2100"
                               value="{{ old('billing_year', now()->year) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="due_date" class="form-label">Tanggal Jatuh Tempo</label>
                        <input type="date" name="due_date" id="due_date"
                               class="form-control @error('due_date') is-invalid @enderror"
                               value="{{ old('due_date') }}" required>
                        @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="notes" class="form-label">Catatan (opsional)</label>
                        <textarea name="notes" id="notes" rows="3"
                                  class="form-control"
                                  placeholder="Contoh: Tagihan sewa kamar bulanan">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary"
                            @disabled($tenants->isEmpty())>
                        <i class="bi bi-check-lg me-1"></i> Simpan Tagihan
                    </button>

                    <a href="{{ route('payments.index') }}" class="btn btn-light">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tenantSelect = document.getElementById('tenant_id');
        const amountInput = document.getElementById('amount');

        tenantSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.dataset.price;

            if (price && !amountInput.value) {
                amountInput.value = price;
            }
        });
    });
</script>
@endsection
