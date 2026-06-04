@extends('layouts.master')

@section('title', 'Detail Kategori')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-6 gap-3">
            <div>
                <h4 class="mb-1">Detail Kategori</h4>
                <p class="text-body-secondary mb-0">Informasi lengkap kategori <strong>{{ $category->name_category }}</strong></p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('category.index') }}" class="btn btn-label-secondary">
                    <i class="bx bx-arrow-back me-1"></i> Kembali
                </a>
                <a href="{{ route('category.edit', $category) }}" class="btn btn-primary">
                    <i class="bx bx-edit-alt me-1"></i> Edit
                </a>
            </div>
        </div>

        <div class="row">
            {{-- Info utama --}}
            <div class="col-md-7">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">Informasi</h5>
                        <span class="badge bg-label-primary">{{ $category->services_count }} service</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="mb-3">
                                    <div class="text-body-secondary small">Nama Kategori</div>
                                    <div class="fs-5 fw-semibold">{{ $category->name_category }}</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="text-body-secondary small">Dibuat</div>
                                    <div class="fw-medium">{{ optional($category->created_at)->translatedFormat('d M Y H:i') }}</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="text-body-secondary small">Terakhir diperbarui</div>
                                    <div class="fw-medium">{{ optional($category->updated_at)->translatedFormat('d M Y H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Daftar service --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Service dalam kategori</h5>
                    </div>
                    <div class="card-body">
                        @if($category->services->isEmpty())
                            <div class="text-center text-body-secondary py-4">
                                Belum ada service pada kategori ini.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Service</th>
                                            <th>Harga</th>
                                            <th>Deskripsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($category->services as $service)
                                            <tr>
                                                <td>{{ $service->id }}</td>
                                                <td>
                                                    {{ $service->name_service ?? '-' }}
                                                </td>
                                                <td>
                                                    {{ isset($service->price_service) ? 'Rp ' . number_format($service->price_service, 0, ',', '.') : '-' }}
                                                </td>
                                                <td style="max-width: 320px;">
                                                    <span class="text-truncate d-block" title="{{ $service->description_service ?? '' }}">
                                                        {{ $service->description_service ?? '-' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Side info --}}
            <div class="col-md-5">
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0"><i class="bx bx-info-circle me-2 text-info"></i>Ringkasan</h6>
                    </div>
                    <div class="card-body small text-body-secondary">
                        <div class="mb-2">
                            <span class="fw-medium text-body">Total service:</span><br>
                            {{ $category->services_count }}
                        </div>
                        <div class="mb-2">
                            <span class="fw-medium text-body">Kategori:</span><br>
                            {{ $category->name_category }}
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

