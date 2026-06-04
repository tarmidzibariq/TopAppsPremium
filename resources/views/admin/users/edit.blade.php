@extends('layouts.master')

@section('title', 'Edit Pengguna')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-6 gap-3">
            <div>
                <h4 class="mb-1">Edit Pengguna</h4>
                <p class="text-body-secondary mb-0">Perbarui informasi <strong>{{ $user->name }}</strong></p>
            </div>
            <a href="{{ route('users.index') }}" class="btn btn-label-secondary">
                <i class="bx bx-arrow-back me-1"></i> Kembali
            </a>
        </div>

        <div class="row">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Informasi Pengguna</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('users.update', $user) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" id="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4">
                            <p class="text-body-secondary small mb-3">
                                <i class="bx bx-lock me-1"></i>
                                Kosongkan field password jika tidak ingin mengubah password.
                            </p>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password Baru</label>
                                <div class="input-group">
                                    <input type="password" id="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Minimal 8 karakter">
                                    <button class="btn btn-icon btn-label-secondary" type="button" id="togglePassword">
                                        <i class="bx bx-hide" id="togglePasswordIcon"></i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="form-control" placeholder="Ulangi password baru">
                                    <button class="btn btn-icon btn-label-secondary" type="button" id="togglePasswordConfirm">
                                        <i class="bx bx-hide" id="togglePasswordConfirmIcon"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('users.index') }}" class="btn btn-label-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save me-1"></i>Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0"><i class="bx bx-time me-2 text-warning"></i>Info Akun</h6>
                    </div>
                    <div class="card-body small">
                        <div class="mb-3">
                            <span class="text-body-secondary">Status Verifikasi</span><br>
                            @if ($user->email_verified_at)
                                <span class="badge bg-label-success mt-1">
                                    <i class="bx bx-check me-1"></i>Terverifikasi
                                </span>
                                <div class="text-body-secondary mt-1">
                                    {{ $user->email_verified_at->translatedFormat('d M Y H:i') }}
                                </div>
                            @else
                                <span class="badge bg-label-warning mt-1">Belum Terverifikasi</span>
                            @endif
                        </div>
                        <div class="mb-2">
                            <span class="text-body-secondary">Dibuat</span><br>
                            <span>{{ $user->created_at->translatedFormat('d M Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-body-secondary">Terakhir diperbarui</span><br>
                            <span>{{ $user->updated_at->translatedFormat('d M Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <footer class="content-footer footer bg-footer-theme">
        <div class="container-xxl">
            <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                    © <script>document.write(new Date().getFullYear());</script> Top Apps Premium
                </div>
            </div>
        </div>
    </footer>
    <div class="content-backdrop fade"></div>
</div>
@endsection

@push('page-scripts')
<script>
    function toggleVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bx-hide', 'bx-show');
        } else {
            input.type = 'password';
            icon.classList.replace('bx-show', 'bx-hide');
        }
    }

    document.getElementById('togglePassword').addEventListener('click', () =>
        toggleVisibility('password', 'togglePasswordIcon'));

    document.getElementById('togglePasswordConfirm').addEventListener('click', () =>
        toggleVisibility('password_confirmation', 'togglePasswordConfirmIcon'));
</script>
@endpush