@extends('layouts.master')

@section('title', 'Detail Layanan')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-6 gap-3">
            <div>
                <h4 class="mb-1">Detail Layanan</h4>
                <p class="text-body-secondary mb-0">Informasi lengkap layanan <strong>{{ $service->name_service }}</strong></p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('service.index') }}" class="btn btn-label-secondary">
                    <i class="bx bx-arrow-back me-1"></i> Kembali
                </a>
                <a href="{{ route('service.edit', $service) }}" class="btn btn-primary">
                    <i class="bx bx-edit-alt me-1"></i> Edit
                </a>
                <button type="button" class="btn btn-danger"
                    data-bs-toggle="modal" data-bs-target="#modalDelete">
                    <i class="bx bx-trash me-1"></i> Hapus
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">

            {{-- Info Dasar --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Informasi Layanan</h6>
                    </div>
                    <div class="card-body text-center pb-4">
                        @if ($service->image_service)
                            <img src="{{ asset('storage/' . $service->image_service) }}"
                                alt="{{ $service->name_service }}"
                                class="rounded mb-4 mt-2"
                                style="width: 140px; height: 140px; object-fit: cover;">
                        @else
                            <div class="d-inline-flex align-items-center justify-content-center rounded bg-label-secondary mb-4 mt-2"
                                style="width: 140px; height: 140px;">
                                <i class="bx bx-image-alt" style="font-size: 2.5rem;"></i>
                            </div>
                        @endif

                        <h5 class="mb-1">{{ $service->name_service }}</h5>
                        <span class="badge bg-label-info mb-3">
                            {{ $service->category?->name_category ?? 'Tanpa Kategori' }}
                        </span>

                        @if ($service->description_service)
                            <p class="text-body-secondary small mb-0">{{ $service->description_service }}</p>
                        @endif
                    </div>
                    <div class="card-body border-top pt-3">
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-body-secondary small">Harga</span>
                            <span class="fw-semibold text-primary">Rp {{ number_format($service->price_service, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-body-secondary small">Stock Saat Ini</span>
                            @if ($service->stock_service > 10)
                                <span class="badge bg-label-success">{{ $service->stock_service }} unit</span>
                            @elseif ($service->stock_service > 0)
                                <span class="badge bg-label-warning">{{ $service->stock_service }} unit</span>
                            @else
                                <span class="badge bg-label-danger">Habis</span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-body-secondary small">Dibuat</span>
                            <span class="small">{{ $service->created_at->translatedFormat('d M Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-2">
                            <span class="text-body-secondary small">Diperbarui</span>
                            <span class="small">{{ $service->updated_at->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Riwayat Stock --}}
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h6 class="card-title mb-0">Riwayat Stock</h6>
                        <div class="d-flex gap-2">
                            <span class="badge bg-label-success">Masuk: {{ $totalMasuk }} unit</span>
                            <span class="badge bg-label-danger">Keluar: {{ $totalKeluar }} unit</span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tipe</th>
                                    <th>Jumlah</th>
                                    <th>Oleh</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stockHistories as $history)
                                    <tr>
                                        <td>{{ $stockHistories->firstItem() + $loop->index }}</td>
                                        <td>
                                            @if ($history->type === 'in')
                                                <span class="badge bg-label-success">
                                                    <i class="bx bx-plus me-1"></i>Masuk
                                                </span>
                                            @else
                                                <span class="badge bg-label-danger">
                                                    <i class="bx bx-minus me-1"></i>Keluar
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-medium {{ $history->type === 'in' ? 'text-success' : 'text-danger' }}">
                                                {{ $history->type === 'in' ? '+' : '-' }}{{ $history->quantity }}
                                            </span>
                                        </td>
                                        <td>{{ $history->user?->name ?? '-' }}</td>
                                        <td>
                                            <small>{{ $history->created_at->translatedFormat('d M Y H:i') }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-body-secondary py-5">
                                            Belum ada riwayat stock.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($stockHistories->hasPages())
                        <div class="card-footer">
                            {{ $stockHistories->links() }}
                        </div>
                    @endif
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

{{-- Modal Hapus --}}
<div class="modal fade" id="modalDelete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Layanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bx bx-trash bx-lg text-danger mb-3 d-block"></i>
                <p class="mb-1">Yakin ingin menghapus layanan:</p>
                <strong>{{ $service->name_service }}</strong>
                <p class="text-body-secondary small mt-2 mb-0">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                <form method="POST" action="{{ route('service.destroy', $service) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection