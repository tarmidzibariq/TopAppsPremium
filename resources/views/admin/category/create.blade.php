@extends('layouts.master')

@section('title', 'Tambah Kategori')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-6 gap-3">
            <div>
                <h4 class="mb-1">Tambah Kategori</h4>
                <p class="text-body-secondary mb-0">Isi form berikut untuk menambahkan kategori baru</p>
            </div>
            <a href="{{ route('category.index') }}" class="btn btn-label-secondary">
                <i class="bx bx-arrow-back me-1"></i> Kembali
            </a>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Informasi Kategori</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('category.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-4">
                                <label for="name_category" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                                <input type="text" id="name_category" name="name_category"
                                    class="form-control @error('name_category') is-invalid @enderror"
                                    value="{{ old('name_category') }}"
                                    placeholder="Contoh: Streaming"
                                    maxlength="30" required>
                                @error('name_category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-body-secondary">Maksimal 30 karakter.</small>
                            </div>

                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('category.index') }}" class="btn btn-label-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save me-1"></i>Simpan Kategori
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Tips --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0"><i class="bx bx-info-circle me-2 text-info"></i>Panduan</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0 small text-body-secondary">
                            <li class="mb-2"><i class="bx bx-check text-success me-1"></i>Masukkan nama kategori untuk pengelompokan layanan.</li>
                        </ul>
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
