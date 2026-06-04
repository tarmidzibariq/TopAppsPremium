<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $query->where('name_service', 'like', '%' . $request->search . '%');
        }

        $services   = $query->orderBy('name_service')->paginate(15)->withQueryString();
        $categories = Category::orderBy('name_category')->get();

        return view('admin.service.index', compact('services', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name_category')->get();
        return view('admin.service.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'         => 'required|exists:categories,id',
            'name_service'        => 'required|string|max:30',
            'description_service' => 'nullable|string',
            'image_service'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'stock_service'       => 'required|integer|min:0|max:32767',
            'price_service'       => 'required|numeric|min:0',
        ]);

        if ($request->hasFile('image_service')) {
            $validated['image_service'] = $request->file('image_service')
                ->store('services', 'public');
        }

        Service::create($validated);

        return redirect()->route('service.index')
            ->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Service $service)
    {
        $categories = Category::orderBy('name_category')->get();
        return view('admin.service.edit', compact('service', 'categories'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'category_id'         => 'required|exists:categories,id',
            'name_service'        => 'required|string|max:30',
            'description_service' => 'nullable|string',
            'image_service'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'stock_service'       => 'required|integer|min:0|max:32767',
            'price_service'       => 'required|numeric|min:0',
        ]);

        if ($request->hasFile('image_service')) {
            // Hapus gambar lama
            if ($service->image_service) {
                Storage::disk('public')->delete($service->image_service);
            }
            $validated['image_service'] = $request->file('image_service')
                ->store('services', 'public');
        } else {
            unset($validated['image_service']);
        }

        $service->update($validated);

        return redirect()->route('service.index')
            ->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Service $service)
    {
        if ($service->image_service) {
            Storage::disk('public')->delete($service->image_service);
        }

        $service->delete();

        return redirect()->route('service.index')
            ->with('success', 'Layanan berhasil dihapus.');
    }
}