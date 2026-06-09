@extends('layouts.master')

@section('title', 'Laporan')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-6 gap-3">
            <div>
                <h4 class="mb-1">Laporan</h4>
                <p class="text-body-secondary mb-0">Ringkasan data transaksi dan stock layanan</p>
            </div>
        </div>

        {{-- Filter --}}
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0"><i class="bx bx-filter-alt me-2"></i>Filter Periode</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('report.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Bulan</label>
                            <select name="month" class="form-select">
                                @foreach(range(1,12) as $m)
                                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tahun</label>
                            <select name="year" class="form-select">
                                @foreach($years as $y)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bx bx-search me-1"></i>Tampilkan
                            </button>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('report.index') }}" class="btn btn-label-secondary w-100">
                                <i class="bx bx-reset me-1"></i>Bulan Ini
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Periode aktif --}}
        <div class="alert alert-primary d-flex align-items-center mb-4 py-2" role="alert">
            <i class="bx bx-calendar me-2"></i>
            Menampilkan data periode:
            <strong class="ms-1">
                {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}
            </strong>
        </div>

        {{-- Stat Cards --}}
        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="avatar avatar-md flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="bx bx-trending-up bx-md"></i>
                            </span>
                        </div>
                        <div>
                            <small class="text-body-secondary d-block">Stock Masuk</small>
                            <h4 class="mb-0 text-success">{{ number_format($totalStockMasuk) }}</h4>
                            <small class="text-body-secondary">unit bulan ini</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="avatar avatar-md flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class="bx bx-trending-down bx-md"></i>
                            </span>
                        </div>
                        <div>
                            <small class="text-body-secondary d-block">Stock Keluar</small>
                            <h4 class="mb-0 text-danger">{{ number_format($totalStockKeluar) }}</h4>
                            <small class="text-body-secondary">unit bulan ini</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="avatar avatar-md flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="bx bx-transfer bx-md"></i>
                            </span>
                        </div>
                        <div>
                            <small class="text-body-secondary d-block">Total Transaksi</small>
                            <h4 class="mb-0 text-primary">{{ number_format($totalTransaksi) }}</h4>
                            <small class="text-body-secondary">bulan ini</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="avatar avatar-md flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="bx bx-package bx-md"></i>
                            </span>
                        </div>
                        <div>
                            <small class="text-body-secondary d-block">Stok Kritis</small>
                            <h4 class="mb-0 text-warning">{{ $kritisServices->count() }}</h4>
                            <small class="text-body-secondary">layanan ≤ 5 unit</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Grafik --}}
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="card-title mb-0">Grafik Stock Masuk & Keluar</h6>
                <span class="badge bg-label-secondary">Tahun {{ $year }}</span>
            </div>
            <div class="card-body">
                <canvas id="chartStockTahunan" height="80"></canvas>
            </div>
        </div>

        {{-- Top Layanan & Stok Kritis --}}
        <div class="row g-4 mb-4">

            {{-- Top Masuk --}}
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h6 class="card-title mb-0">
                            <i class="bx bx-trending-up text-success me-1"></i>Tertinggi Masuk
                        </h6>
                        <small class="text-body-secondary">
                            {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}
                        </small>
                    </div>
                    <div class="card-body">
                        @forelse ($topMasuk as $i => $item)
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-label-success rounded-circle"
                                        style="width:24px;height:24px;display:inline-flex;align-items:center;justify-content:center;font-size:11px;">
                                        {{ $i + 1 }}
                                    </span>
                                    <div>
                                        <div class="fw-medium small">{{ $item->service?->name_service ?? '-' }}</div>
                                        <div class="text-body-secondary" style="font-size:11px;">
                                            {{ $item->service?->category?->name_category ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                                <span class="badge bg-label-success">+{{ number_format($item->total) }}</span>
                            </div>
                        @empty
                            <p class="text-body-secondary small text-center mb-0">Belum ada transaksi.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Top Keluar --}}
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h6 class="card-title mb-0">
                            <i class="bx bx-trending-down text-danger me-1"></i>Tertinggi Keluar
                        </h6>
                        <small class="text-body-secondary">
                            {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}
                        </small>
                    </div>
                    <div class="card-body">
                        @forelse ($topKeluar as $i => $item)
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-label-danger rounded-circle"
                                        style="width:24px;height:24px;display:inline-flex;align-items:center;justify-content:center;font-size:11px;">
                                        {{ $i + 1 }}
                                    </span>
                                    <div>
                                        <div class="fw-medium small">{{ $item->service?->name_service ?? '-' }}</div>
                                        <div class="text-body-secondary" style="font-size:11px;">
                                            {{ $item->service?->category?->name_category ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                                <span class="badge bg-label-danger">-{{ number_format($item->total) }}</span>
                            </div>
                        @empty
                            <p class="text-body-secondary small text-center mb-0">Belum ada transaksi.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Stok Kritis --}}
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h6 class="card-title mb-0">
                            <i class="bx bx-error-circle text-warning me-1"></i>Stok Kritis
                        </h6>
                        <span class="badge bg-label-warning">≤ 5 unit</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <tbody>
                                    @forelse ($kritisServices as $kritis)
                                        <tr>
                                            <td class="ps-3">
                                                <div class="fw-medium small">{{ $kritis->name_service }}</div>
                                                <div class="text-body-secondary" style="font-size:11px;">
                                                    {{ $kritis->category?->name_category ?? '-' }}
                                                </div>
                                            </td>
                                            <td class="pe-3 text-end">
                                                @if ($kritis->stock_service == 0)
                                                    <span class="badge bg-label-danger">Habis</span>
                                                @else
                                                    <span class="badge bg-label-warning">{{ $kritis->stock_service }} unit</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-body-secondary py-4">
                                                <i class="bx bx-check-circle text-success d-block mb-1" style="font-size:1.5rem;"></i>
                                                Semua stok aman.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        {{-- Laporan Pesanan --}}
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Log Mutasi Stock</h5>
                
                <span class="badge bg-label-secondary"> {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Layanan</th>
                            <th>Kategori</th>
                            <th>Mutasi</th>
                            <th>Stok Awal</th>
                            <th>Stok Akhir</th>
                            <th>Oleh</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporanPesanan as $stock)
                            <tr>
                                <td>{{ $stock->id }}</td>
                                <td>
                                    <span class="fw-medium">{{ $stock->service?->name_service ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-label-info">
                                        {{ $stock->service?->category?->name_category ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    @if ($stock->type === 'in')
                                        <span class="text-success fw-semibold">
                                            +{{ number_format($stock->quantity) }}
                                        </span>
                                        <span class="badge bg-label-success ms-1">Masuk</span>
                                    @else
                                        <span class="text-danger fw-semibold">
                                            -{{ number_format($stock->quantity) }}
                                        </span>
                                        <span class="badge bg-label-danger ms-1">Keluar</span>
                                    @endif
                                </td>
                                <td class="text-body-secondary">
                                    {{ number_format($stock->stock_before ?? 0) }} unit
                                </td>
                                <td>
                                    <span class="fw-medium">{{ number_format($stock->stock_after ?? 0) }} unit</span>
                                </td>
                                <td>{{ $stock->user?->name ?? '-' }}</td>
                                <td>
                                    <small>{{ $stock->created_at->translatedFormat('d M Y H:i') }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-body-secondary py-5">
                                    Belum ada transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- {{ $laporanPesanan->links('pagination::bootstrap-5') }} --}}

            {{-- @if ($stocks->hasPages())
                <div class="card-footer">
                    {{ $stocks->links() }}
                </div>
            @endif --}}
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('chartStockTahunan').getContext('2d'), {
        type: 'bar',
        data: {
            labels: @json($chartMonths),
            datasets: [
                {
                    label: 'Masuk',
                    data: @json($chartIn),
                    backgroundColor: 'rgba(113, 221, 55, 0.85)',
                    borderRadius: 4,
                },
                {
                    label: 'Keluar',
                    data: @json($chartOut),
                    backgroundColor: 'rgba(255, 62, 29, 0.75)',
                    borderRadius: 4,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
</script>
@endpush