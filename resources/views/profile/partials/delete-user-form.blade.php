<section>
    <div class="mb-3">
        <h5 class="fw-bold text-danger mb-1">Hapus Akun</h5>
        <p class="text-secondary small mb-0">
            Menghapus akun akan menghilangkan akses login akun ini ke KosManager.
            Pastikan Anda benar-benar ingin melanjutkan sebelum mengonfirmasi.
        </p>
    </div>

    <button
        type="button"
        class="btn btn-outline-danger"
        data-bs-toggle="modal"
        data-bs-target="#confirmUserDeletion"
    >
        <i class="bi bi-trash me-1"></i>
        Hapus Akun
    </button>

    <div
        class="modal fade"
        id="confirmUserDeletion"
        tabindex="-1"
        aria-labelledby="confirmUserDeletionLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-danger" id="confirmUserDeletionLabel">
                        Konfirmasi Penghapusan Akun
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup"
                    ></button>
                </div>

                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-body">
                        <div class="alert alert-warning small">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Tindakan ini akan menghapus akun login Anda. Pastikan masih ada cara untuk mengakses aplikasi sebelum melanjutkan.
                        </div>

                        <label for="delete_account_password" class="form-label fw-semibold">
                            Masukkan Password untuk Konfirmasi
                        </label>

                        <input
                            id="delete_account_password"
                            name="password"
                            type="password"
                            class="form-control @if ($errors->userDeletion->has('password')) is-invalid @endif"
                            placeholder="Password saat ini"
                            autocomplete="current-password"
                            required
                        >

                        @if ($errors->userDeletion->has('password'))
                            <div class="invalid-feedback">
                                {{ $errors->userDeletion->first('password') }}
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal"
                        >
                            Batal
                        </button>

                        <button type="submit" class="btn btn-danger">
                            Ya, Hapus Akun
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    @if ($errors->userDeletion->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modalElement = document.getElementById('confirmUserDeletion');

                if (modalElement && window.bootstrap) {
                    bootstrap.Modal.getOrCreateInstance(modalElement).show();
                }
            });
        </script>
    @endif
</section>