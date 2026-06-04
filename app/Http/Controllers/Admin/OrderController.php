<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use App\Models\StockService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
     public function index(Request $request)
    {
        $query = StockService::with(['service.category', 'user'])
            ->where('type', 'out');

        // Filter kategori
        if ($request->filled('category_id')) {
            $query->whereHas('service', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // Filter layanan
        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        // Filter range tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();
        $totalKeluar = $query->sum('quantity'); // ikut filter

        $services = Service::with('category')->get();
        $categories = Category::all(); 

        return view('admin.order.index', compact('orders', 'totalKeluar', 'services', 'categories'));
    }   

}
