<?php
// ============================================================
// FILE: app/Http/Controllers/ServiceController.php
// ============================================================

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $search   = $request->search;
        $category = $request->category;

        $services = Service::when($search, fn($q) => $q->where('name', 'like', "%$search%")
                                                        ->orWhere('description', 'like', "%$search%"))
                           ->when($category, fn($q) => $q->where('category', $category))
                           ->get();

        $categories = Service::distinct()->pluck('category')->filter()->values();

        return view('services.index', compact('services', 'categories', 'search', 'category'));
    }

    public function show($id)
    {
        $service = Service::findOrFail($id);
        $related = Service::where('category', $service->category)
                          ->where('id', '!=', $id)
                          ->take(3)
                          ->get();

        return view('services.show', compact('service', 'related'));
    }
}
