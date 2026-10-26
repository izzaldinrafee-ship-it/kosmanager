<section>
    <div class="mb-4">
        <h5 class="fw-bold text-dark mb-1">Informasi Profil</h5>
        <p class="text-secondary small mb-0">
            Perbarui nama dan alamat email akun KosManager Anda.
        </p>
    </div>

    <form
        id="send-verification"
        method="post"
        action="{{ route('verification.send') }}"
    >
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">
                Nama Lengkap
            </label>

            <input
                id="name"
                name="name"
                type="text"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $user->name) }}"
                required
                autofocus
                autocomplete="name"
            >

            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">
                Alamat Email
            </label>

            <input
                id="email"
                name="email"
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="username"
            >

            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="alert alert-warning mt-3 mb-0 small">
                    <p class="mb-2">
                        Alamat email Anda belum diverifikasi.
                    </p>

                    <button
                        form="send-verification"
                        type="submit"
                        class="btn btn-sm btn-outline-dark"
                    >
                        Kirim Ulang Email Verifikasi
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="text-success mt-2 mb-0">
                            Tautan verifikasi baru telah dikirim.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <span class="text-success small">
                    <i class="bi bi-check-circle me-1"></i>
                    Profil berhasil disimpan.
                </span>
            @endif
        </div>
    </form>
</section>