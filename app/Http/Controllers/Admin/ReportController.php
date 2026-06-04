<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use App\Models\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year  = $request->input('year', now()->year);
        $years = range(now()->year, now()->year - 4);

        // Stat cards
        $totalStockMasuk  = (int) StockService::where('type', 'in')
            ->whereYear('created_at', $year)->whereMonth('created_at', $month)->sum('quantity');

        $totalStockKeluar = (int) StockService::where('type', 'out')
            ->whereYear('created_at', $year)->whereMonth('created_at', $month)->sum('quantity');

        $totalTransaksi   = (int) StockService::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)->count();

        // Grafik 12 bulan
        $chartMonths = [];
        $chartIn     = [];
        $chartOut    = [];
        for ($m = 1; $m <= 12; $m++) {
            $chartMonths[] = \Carbon\Carbon::create($year, $m, 1)->translatedFormat('M');
            $chartIn[]     = (int) StockService::where('type', 'in')
                ->whereYear('created_at', $year)->whereMonth('created_at', $m)->sum('quantity');
            $chartOut[]    = (int) StockService::where('type', 'out')
                ->whereYear('created_at', $year)->whereMonth('created_at', $m)->sum('quantity');
        }

        // Top 5 tertinggi masuk bulan ini
        $topMasuk = StockService::with('service.category')
            ->where('type', 'in')
            ->whereYear('created_at', $year)->whereMonth('created_at', $month)
            ->select('service_id', DB::raw('SUM(quantity) as total'))
            ->groupBy('service_id')
            ->orderByDesc('total')
            ->limit(5)->get();

        // Top 5 tertinggi keluar bulan ini
        $topKeluar = StockService::with('service.category')
            ->where('type', 'out')
            ->whereYear('created_at', $year)->whereMonth('created_at', $month)
            ->select('service_id', DB::raw('SUM(quantity) as total'))
            ->groupBy('service_id')
            ->orderByDesc('total')
            ->limit(5)->get();

        // Stok kritis
        $kritisServices = Service::with('category')
            ->where('stock_service', '<=', 5)
            ->orderBy('stock_service')->get();

        return view('admin.report.index', compact(
            'month', 'year', 'years',
            'totalStockMasuk', 'totalStockKeluar', 'totalTransaksi',
            'chartMonths', 'chartIn', 'chartOut',
            'topMasuk', 'topKeluar', 'kritisServices'
        ));
    }
}