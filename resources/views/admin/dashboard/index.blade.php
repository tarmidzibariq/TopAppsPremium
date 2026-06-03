@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
@php
    $fmtRp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.');
@endphp
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-6 gap-3">
            <div>
                <h4 class="mb-1">Dashboard</h4>
                <p class="text-body-secondary mb-0">Ringkasan stock, pesanan, dan pemasukan Top Apps Premium</p>
            </div>
            <span class="badge bg-label-primary">{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>

        {{-- Stat cards --}}
        <div class="row g-6 mb-6">
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="bx bx-package bx-lg"></i>
                                </span>
                            </div>
                        </div>
                        <h4 class="mb-1 mt-4">{{ number_format($totalStock) }}</h4>
                        <p class="mb-0 text-body-secondary">Total Stock Tersedia</p>
                        <small class="text-muted">{{ $totalServices }} layanan aktif</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="bx bx-cart bx-lg"></i>
                                </span>
                            </div>
                        </div>
                        <h4 class="mb-1 mt-4">{{ number_format($stockPesan) }}</h4>
                        <p class="mb-0 text-body-secondary">Stock Pesan (Order)</p>
                        <small class="text-muted">Transaksi keluar (type: out)</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="bx bx-import bx-lg"></i>
                                </span>
                            </div>
                        </div>
                        <h4 class="mb-1 mt-4">{{ number_format($stockMasuk) }}</h4>
                        <p class="mb-0 text-body-secondary">Stock Masuk</p>
                        <small class="text-muted">Transaksi masuk (type: in)</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-info">
                                    <i class="bx bx-wallet bx-lg"></i>
                                </span>
                            </div>
                        </div>
                        <h4 class="mb-1 mt-4">{{ $fmtRp($totalPemasukan) }}</h4>
                        <p class="mb-0 text-body-secondary">Total Pemasukan</p>
                        <small class="text-muted">Dari pesanan stock keluar</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts row 1 --}}
        <div class="row g-6 mb-6">
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">Stock per Kategori</h5>
                        <span class="badge bg-label-secondary">{{ $totalCategories }} kategori</span>
                    </div>
                    <div class="card-body">
                        <div id="chartStockCategory"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Distribusi Stock</h5>
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <div id="chartCategoryDonut" class="w-100"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts row 2 --}}
        <div class="row g-6 mb-6">
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Pergerakan Stock (6 Bulan Terakhir)</h5>
                    </div>
                    <div class="card-body">
                        <div id="chartStockMovement"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Total Pemasukan</h5>
                    </div>
                    <div class="card-body">
                        <div id="chartRevenue"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Additional widgets --}}
        <div class="row g-6 mb-6">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Ringkasan Sistem</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="d-flex justify-content-between align-items-center mb-4">
                                <span class="text-body-secondary"><i class="bx bx-category me-2"></i>Kategori</span>
                                <strong>{{ $totalCategories }}</strong>
                            </li>
                            <li class="d-flex justify-content-between align-items-center mb-4">
                                <span class="text-body-secondary"><i class="bx bx-grid-alt me-2"></i>Layanan</span>
                                <strong>{{ $totalServices }}</strong>
                            </li>
                            <li class="d-flex justify-content-between align-items-center mb-4">
                                <span class="text-body-secondary"><i class="bx bx-user me-2"></i>Pengguna</span>
                                <strong>{{ $totalUsers }}</strong>
                            </li>
                            <li class="d-flex justify-content-between align-items-center">
                                <span class="text-body-secondary"><i class="bx bx-transfer me-2"></i>Transaksi Stock</span>
                                <strong>{{ $totalTransactions }}</strong>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Stock Terbanyak</h5>
                    </div>
                    <div class="card-body">
                        @forelse ($topServices as $service)
                            <div class="d-flex justify-content-between align-items-center {{ !$loop->last ? 'mb-4' : '' }}">
                                <div class="me-2">
                                    <h6 class="mb-0 text-truncate" style="max-width: 180px;">{{ $service->name_service }}</h6>
                                    <small class="text-body-secondary">{{ $service->category?->name_category ?? '-' }}</small>
                                </div>
                                <span class="badge bg-label-primary">{{ $service->stock_service }}</span>
                            </div>
                        @empty
                            <p class="text-body-secondary text-center mb-0 py-3">Belum ada layanan.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border border-warning">
                    <div class="card-header">
                        <h5 class="card-title mb-0 text-warning">
                            <i class="bx bx-error-circle me-1"></i>Stock Menipis
                        </h5>
                    </div>
                    <div class="card-body">
                        @forelse ($lowStockServices as $service)
                            <div class="d-flex justify-content-between align-items-center {{ !$loop->last ? 'mb-4' : '' }}">
                                <div class="me-2">
                                    <h6 class="mb-0 text-truncate" style="max-width: 180px;">{{ $service->name_service }}</h6>
                                    <small class="text-body-secondary">{{ $service->category?->name_category ?? '-' }}</small>
                                </div>
                                <span class="badge bg-warning">{{ $service->stock_service }} unit</span>
                            </div>
                        @empty
                            <p class="text-body-secondary text-center mb-0 py-3">Semua stock aman (&gt; 5 unit).</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Category table + recent movements --}}
        <div class="row g-6">
            <div class="col-lg-5">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Detail Stock per Kategori</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th class="text-end">Jumlah Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stockByCategory as $row)
                                    <tr>
                                        <td>{{ $row->name_category }}</td>
                                        <td class="text-end fw-medium">{{ number_format($row->total_stock) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-body-secondary py-4">Belum ada kategori.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if ($stockByCategory->isNotEmpty())
                                <tfoot class="table-light">
                                    <tr>
                                        <th>Total</th>
                                        <th class="text-end">{{ number_format($totalStock) }}</th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Transaksi Stock Terbaru</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Layanan</th>
                                    <th>User</th>
                                    <th>Jumlah</th>
                                    <th>Tipe</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentMovements as $movement)
                                    <tr>
                                        <td>
                                            <span class="fw-medium">{{ $movement->service?->name_service ?? '-' }}</span>
                                            <br>
                                            <small class="text-body-secondary">{{ $movement->service?->category?->name_category ?? '' }}</small>
                                        </td>
                                        <td>{{ $movement->user?->name ?? '-' }}</td>
                                        <td>{{ $movement->quantity }}</td>
                                        <td>
                                            @if ($movement->type === 'out')
                                                <span class="badge bg-label-warning">Pesan</span>
                                            @else
                                                <span class="badge bg-label-success">Masuk</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $movement->created_at->translatedFormat('d M Y H:i') }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-body-secondary py-4">Belum ada transaksi stock.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
    window.dashboardData = {
        categoryLabels: @json($stockByCategory->pluck('name_category')),
        categoryStock: @json($stockByCategory->pluck('total_stock')->map(fn ($v) => (int) $v)),
        monthLabels: @json($monthLabels),
        revenueSeries: @json($revenueSeries),
        pesanSeries: @json($pesanSeries),
        masukSeries: @json($masukSeries),
        totalStock: {{ $totalStock }},
    };
</script>
<script src="{{ asset('assets-adminTemplate/js/dashboard-topapps.js') }}"></script>
@endpush
