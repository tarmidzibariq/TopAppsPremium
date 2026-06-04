@extends('layouts.master')

@section('title', 'Tambah Layanan')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-6 gap-3">
            <div>
                <h4 class="mb-1">Tambah Layanan</h4>
                <p class="text-body-secondary mb-0">Isi form berikut untuk menambahkan layanan baru</p>
            </div>
            <a href="{{ route('service.index') }}" class="btn btn-label-secondary">
                <i class="bx bx-arrow-back me-1"></i> Kembali
            </a>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Informasi Layanan</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('service.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-4">
                                <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select id="category_id" name="category_id"
                                    class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Pilih kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name_category }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="name_service" class="form-label">Nama Layanan <span class="text-danger">*</span></label>
                                <input type="text" id="name_service" name="name_service"
                                    class="form-control @error('name_service') is-invalid @enderror"
                                    value="{{ old('name_service') }}"
                                    placeholder="Contoh: Netflix Premium"
                                    maxlength="30" required>
                                @error('name_service')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-body-secondary">Maksimal 30 karakter.</small>
                            </div>

                            <div class="mb-4">
                                <label for="description_service" class="form-label">Deskripsi</label>
                                <textarea id="description_service" name="description_service" rows="3"
                                    class="form-control @error('description_service') is-invalid @enderror"
                                    placeholder="Deskripsi singkat layanan...">{{ old('description_service') }}</textarea>
                                @error('description_service')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-sm-6">
                                    <label for="price_service" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" id="price_service" name="price_service"
                                            class="form-control @error('price_service') is-invalid @enderror"
                                            value="{{ old('price_service', 0) }}"
                                            min="0" step="0.01" required>
                                        @error('price_service')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="stock_service" class="form-label">Stock Awal <span class="text-danger">*</span></label>
                                    <input type="number" id="stock_service" name="stock_service"
                                        class="form-control @error('stock_service') is-invalid @enderror"
                                        value="{{ old('stock_service', 0) }}"
                                        min="0" max="32767" required>
                                    @error('stock_service')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Upload Gambar --}}
                            <div class="mb-4">
                                <label class="form-label">Gambar Layanan</label>
                                <div class="d-flex align-items-start gap-4 flex-wrap">
                                    {{-- Preview --}}
                                    <div id="imagePreviewWrapper"
                                        class="border rounded d-flex align-items-center justify-content-center bg-label-secondary"
                                        style="width: 120px; height: 120px; flex-shrink: 0; overflow: hidden; cursor: pointer;"
                                        onclick="document.getElementById('image_service').click()">
                                        <img id="imagePreview" src="" alt="Preview"
                                            style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                        <div id="imagePlaceholder" class="text-center text-body-secondary p-2">
                                            <i class="bx bx-image-add bx-lg d-block mb-1"></i>
                                            <small>Klik untuk pilih</small>
                                        </div>
                                    </div>
                                    {{-- Input --}}
                                    <div class="flex-grow-1">
                                        <input type="file" id="image_service" name="image_service"
                                            class="form-control @error('image_service') is-invalid @enderror"
                                            accept="image/jpg,image/jpeg,image/png,image/webp">
                                        @error('image_service')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-body-secondary d-block mt-1">
                                            Format: JPG, JPEG, PNG, WEBP. Maks. 2MB.<br>
                                            Gambar akan ditampilkan sebagai thumbnail layanan.
                                        </small>
                                        <button type="button" id="btnRemoveImage"
                                            class="btn btn-sm btn-label-danger mt-2" style="display: none;"
                                            onclick="removeImage()">
                                            <i class="bx bx-x me-1"></i>Hapus Gambar
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('service.index') }}" class="btn btn-label-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save me-1"></i>Simpan Layanan
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
                            <li class="mb-2"><i class="bx bx-check text-success me-1"></i>Pilih kategori yang sesuai untuk memudahkan pengelompokan.</li>
                            <li class="mb-2"><i class="bx bx-check text-success me-1"></i>Nama layanan maksimal 30 karakter.</li>
                            <li class="mb-2"><i class="bx bx-check text-success me-1"></i>Harga dalam satuan Rupiah.</li>
                            <li class="mb-2"><i class="bx bx-check text-success me-1"></i>Stock awal bisa diisi 0 jika belum ada stok.</li>
                            <li class="mb-0"><i class="bx bx-check text-success me-1"></i>Gambar bersifat opsional, disarankan rasio 1:1.</li>
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

@push('page-scripts')
<script>
    const imageInput     = document.getElementById('image_service');
    const imagePreview   = document.getElementById('imagePreview');
    const imagePlaceholder = document.getElementById('imagePlaceholder');
    const btnRemove      = document.getElementById('btnRemoveImage');

    imageInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';
            imagePlaceholder.style.display = 'none';
            btnRemove.style.display = 'inline-flex';
        };
        reader.readAsDataURL(file);
    });

    function removeImage() {
        imageInput.value = '';
        imagePreview.src = '';
        imagePreview.style.display = 'none';
        imagePlaceholder.style.display = 'block';
        btnRemove.style.display = 'none';
    }
</script>
@endpush