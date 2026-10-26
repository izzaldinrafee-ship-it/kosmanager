
@extends('layouts.app')

@section('title', 'Tambah Kamar')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="mb-4">
        <a href="{{ route('rooms.index') }}"
           class="text-decoration-none d-inline-flex align-items-center gap-2 mb-3">
            <i class="bi bi-arrow-left"></i>
            Kembali ke Data Kamar
        </a>

        <h2 class="fw-bold mb-1">Tambah Kamar</h2>
        <p class="text-secondary mb-0">
            Masukkan informasi kamar baru untuk kos Anda.
        </p>
    </div>

    {{-- Pesan Error Validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="fw-semibold mb-2">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Periksa kembali data yang kamu masukkan.
            </div>

            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Tutup"></button>
        </div>
    @endif

    {{-- Form Tambah Kamar --}}
    <div class="content-card" style="max-width: 850px;">

        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="stat-icon bg-primary-subtle text-primary">
                <i class="bi bi-door-open"></i>
            </div>

            <div>
                <h5 class="fw-bold mb-1">Informasi Kamar</h5>
                <p class="text-secondary small mb-0">
                    Lengkapi semua kolom wajib di bawah ini.
                </p>
            </div>
        </div>

        <form action="{{ route('rooms.store') }}" method="POST">
            @csrf

            {{-- Nomor Kamar --}}
            <div class="mb-3">
                <label for="room_number" class="form-label fw-semibold">
                    Nomor Kamar <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    name="room_number"
                    id="room_number"
                    class="form-control @error('room_number') is-invalid @enderror"
                    placeholder="Contoh: A-01"
                    value="{{ old('room_number') }}"
                    maxlength="50"
                    required
                    autofocus
                >

                @error('room_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tipe Kamar --}}
            <div class="mb-3">
                <label for="room_type" class="form-label fw-semibold">
                    Tipe Kamar <span class="text-danger">*</span>
                </label>

                <select
                    name="room_type"
                    id="room_type"
                    class="form-select @error('room_type') is-invalid @enderror"
                    required
                >
                    <option value="">-- Pilih tipe kamar --</option>

                    <option value="Standar"
                        @selected(old('room_type', 'Standar') === 'Standar')>
                        Standar
                    </option>

                    <option value="Premium"
                        @selected(old('room_type') === 'Premium')>
                        Premium
                    </option>

                    <option value="VIP"
                        @selected(old('room_type') === 'VIP')>
                        VIP
                    </option>
                </select>

                @error('room_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Harga Sewa --}}
            <div class="mb-3">
                <label for="price" class="form-label fw-semibold">
                    Harga Sewa per Bulan (Rp) <span class="text-danger">*</span>
                </label>

                <div class="input-group">
                    <span class="input-group-text">Rp</span>

                    <input
                        type="number"
                        name="price"
                        id="price"
                        class="form-control @error('price') is-invalid @enderror"
                        placeholder="Contoh: 750000"
                        value="{{ old('price') }}"
                        min="1"
                        step="1"
                        required
                    >

                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-text">
                    Masukkan harga sewa bulanan dalam angka, tanpa titik atau koma.
                </div>
            </div>

            {{-- Status Kamar --}}
            <div class="mb-3">
                <label for="status" class="form-label fw-semibold">
                    Status Kamar <span class="text-danger">*</span>
                </label>

                <select
                    name="status"
                    id="status"
                    class="form-select @error('status') is-invalid @enderror"
                    required
                >
                    <option value="Kosong"
                        @selected(old('status', 'Kosong') === 'Kosong')>
                        Kosong
                    </option>

                    <option value="Terisi"
                        @selected(old('status') === 'Terisi')>
                        Terisi
                    </option>
                </select>

                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div class="form-text">
                    Pilih Kosong jika belum ditempati penghuni.
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="mb-4">
                <label for="description" class="form-label fw-semibold">
                    Deskripsi <span class="text-secondary fw-normal">(Opsional)</span>
                </label>

                <textarea
                    name="description"
                    id="description"
                    class="form-control @error('description') is-invalid @enderror"
                    rows="4"
                    maxlength="1000"
                    placeholder="Contoh: Kamar dekat kamar mandi, dilengkapi jendela."
                >{{ old('description') }}</textarea>

                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <hr class="my-4">

            {{-- Tombol Aksi --}}
            <div class="d-flex flex-wrap justify-content-end gap-2">
                <a href="{{ route('rooms.index') }}"
                   class="btn btn-light border">
                    <i class="bi bi-x-lg me-1"></i>
                    Batal
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>
                    Simpan Kamar
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
