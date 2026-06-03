@extends('layouts.master')

@section('title', 'Stock Masuk')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-6 gap-3">
            <div>
                <h4 class="mb-1">Stock Masuk</h4>
                <p class="text-body-secondary mb-0">Daftar transaksi penambahan stock (type: in)</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-label-success">Total masuk: {{ number_format($totalMasuk) }} unit</span>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreateStock">
                    <i class="bx bx-plus me-1"></i> Tambah Stock
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Riwayat Stock Masuk</h5>
                <span class="badge bg-label-secondary">{{ $stocks->total() }} transaksi</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Layanan</th>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                            <th>Stock Saat Ini</th>
                            <th>Oleh</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stocks as $stock)
                            <tr>
                                <td>{{ $stocks->firstItem() + $loop->index }}</td>
                                <td>
                                    <span class="fw-medium">{{ $stock->service?->name_service ?? '-' }}</span>
                                </td>
                                <td>{{ $stock->service?->category?->name_category ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-label-success">+{{ $stock->quantity }}</span>
                                </td>
                                <td>{{ $stock->service?->stock_service ?? '-' }} unit</td>
                                <td>{{ $stock->user?->name ?? '-' }}</td>
                                <td>
                                    <small>{{ $stock->created_at->translatedFormat('d M Y H:i') }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-body-secondary py-5">
                                    Belum ada transaksi stock masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($stocks->hasPages())
                <div class="card-footer">
                    {{ $stocks->links() }}
                </div>
            @endif
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

{{-- Modal create stock --}}
<div class="modal fade" id="modalCreateStock" tabindex="-1" aria-labelledby="modalCreateStockLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('stock.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreateStockLabel">Tambah Stock Masuk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label for="service_id" class="form-label">Layanan</label>
                        <select id="service_id" name="service_id"
                            class="form-select @error('service_id') is-invalid @enderror" required>
                            <option value="" disabled {{ old('service_id') ? '' : 'selected' }}>Pilih layanan</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" {{ (string) old('service_id') === (string) $service->id ? 'selected' : '' }}>
                                    {{ $service->name_service }}
                                    ({{ $service->category?->name_category ?? 'Tanpa kategori' }} — stock: {{ $service->stock_service }})
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if ($services->isEmpty())
                            <small class="text-danger">Belum ada layanan. Tambahkan layanan di menu Managemen terlebih dahulu.</small>
                        @endif
                    </div>
                    <div class="mb-0">
                        <label for="quantity" class="form-label">Jumlah Stock Masuk</label>
                        <input type="number" id="quantity" name="quantity" min="1" max="255"
                            class="form-control @error('quantity') is-invalid @enderror"
                            value="{{ old('quantity', 1) }}" required />
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-body-secondary">Maksimal 255 unit per transaksi.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" {{ $services->isEmpty() ? 'disabled' : '' }}>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('page-scripts')
@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('modalCreateStock');
        if (modal) {
            bootstrap.Modal.getOrCreateInstance(modal).show();
        }
    });
</script>
@endif
@endpush
