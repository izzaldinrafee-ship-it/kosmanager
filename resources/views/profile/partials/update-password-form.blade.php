<section>
    <div class="mb-4">
        <h5 class="fw-bold text-dark mb-1">Keamanan Akun</h5>
        <p class="text-secondary small mb-0">
            Gunakan password yang kuat dan tidak mudah ditebak untuk melindungi akun KosManager Anda.
        </p>
    </div>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="update_password_current_password" class="form-label fw-semibold">
                Password Saat Ini
            </label>

            <input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="form-control @if ($errors->updatePassword->has('current_password')) is-invalid @endif"
                autocomplete="current-password"
                required
            >

            @if ($errors->updatePassword->has('current_password'))
                <div class="invalid-feedback">
                    {{ $errors->updatePassword->first('current_password') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="update_password_password" class="form-label fw-semibold">
                Password Baru
            </label>

            <input
                id="update_password_password"
                name="password"
                type="password"
                class="form-control @if ($errors->updatePassword->has('password')) is-invalid @endif"
                autocomplete="new-password"
                required
            >

            @if ($errors->updatePassword->has('password'))
                <div class="invalid-feedback">
                    {{ $errors->updatePassword->first('password') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="update_password_password_confirmation" class="form-label fw-semibold">
                Konfirmasi Password Baru
            </label>

            <input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="form-control"
                autocomplete="new-password"
                required
            >
        </div>

        <div class="d-flex align-items-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-shield-lock me-1"></i>
                Perbarui Password
            </button>

            @if (session('status') === 'password-updated')
                <span class="text-success small">
                    <i class="bi bi-check-circle me-1"></i>
                    Password berhasil diperbarui.
                </span>
            @endif
        </div>
    </form>
</section>