<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use App\Models\StockService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stockByCategory = Category::query()
            ->leftJoin('services', 'services.category_id', '=', 'categories.id')
            ->select('categories.name_category', DB::raw('COALESCE(SUM(services.stock_service), 0) as total_stock'))
            ->groupBy('categories.id', 'categories.name_category')
            ->orderBy('categories.name_category')
            ->get();

        $totalStock = (int) Service::sum('stock_service');
        $stockPesan = (int) StockService::where('type', 'out')->sum('quantity');
        $stockMasuk = (int) StockService::where('type', 'in')->sum('quantity');

        $totalPemasukan = (float) StockService::query()
            ->where('stock_services.type', 'out')
            ->join('services', 'services.id', '=', 'stock_services.service_id')
            ->selectRaw('COALESCE(SUM(stock_services.quantity * services.price_service), 0) as total')
            ->value('total');

        $months = $this->lastMonths(6);
        $monthLabels = collect($months)->map(fn ($m) => Carbon::createFromFormat('Y-m', $m)->translatedFormat('M Y'))->values();

        $monthlyRevenue = StockService::query()
            ->where('stock_services.type', 'out')
            ->join('services', 'services.id', '=', 'stock_services.service_id')
            ->where('stock_services.created_at', '>=', Carbon::parse($months[0])->startOfMonth())
            ->selectRaw("DATE_FORMAT(stock_services.created_at, '%Y-%m') as month")
            ->selectRaw('SUM(stock_services.quantity * services.price_service) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $monthlyPesan = StockService::query()
            ->where('type', 'out')
            ->where('created_at', '>=', Carbon::parse($months[0])->startOfMonth())
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month")
            ->selectRaw('SUM(quantity) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $monthlyMasuk = StockService::query()
            ->where('type', 'in')
            ->where('created_at', '>=', Carbon::parse($months[0])->startOfMonth())
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month")
            ->selectRaw('SUM(quantity) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $revenueSeries = collect($months)->map(fn ($m) => (float) ($monthlyRevenue[$m] ?? 0))->values();
        $pesanSeries = collect($months)->map(fn ($m) => (int) ($monthlyPesan[$m] ?? 0))->values();
        $masukSeries = collect($months)->map(fn ($m) => (int) ($monthlyMasuk[$m] ?? 0))->values();

        $recentMovements = StockService::with(['service.category', 'user'])
            ->latest()
            ->limit(8)
            ->get();

        $lowStockServices = Service::with('category')
            ->where('stock_service', '<=', 5)
            ->orderBy('stock_service')
            ->limit(6)
            ->get();

        $topServices = Service::with('category')
            ->orderByDesc('stock_service')
            ->limit(5)
            ->get();

        $totalCategories = Category::count();
        $totalServices = Service::count();
        $totalUsers = DB::table('users')->count();
        $totalTransactions = StockService::count();

        return view('admin.dashboard.index', compact(
            'stockByCategory',
            'totalStock',
            'stockPesan',
            'stockMasuk',
            'totalPemasukan',
            'monthLabels',
            'revenueSeries',
            'pesanSeries',
            'masukSeries',
            'recentMovements',
            'lowStockServices',
            'topServices',
            'totalCategories',
            'totalServices',
            'totalUsers',
            'totalTransactions',
        ));
    }

    /**
     * @return list<string>
     */
    private function lastMonths(int $count): array
    {
        $months = [];
        for ($i = $count - 1; $i >= 0; $i--) {
            $months[] = now()->subMonths($i)->format('Y-m');
        }

        return $months;
    }
}
