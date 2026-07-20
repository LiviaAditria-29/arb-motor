<?php
// ============================================================
// FILE: app/Http/Controllers/SparePartController.php
// ============================================================

namespace App\Http\Controllers;

use App\Models\SparePart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SparePartController extends Controller
{
    // ── PUBLIC ─────────────────────────────────────────────

    public function index(Request $request)
    {
        $search   = $request->search;
        $category = $request->category;
        $sort     = $request->sort ?? 'latest';

        $query = SparePart::search($search)->byCategory($category);

        $query = match ($sort) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'name'       => $query->orderBy('name'),
            default      => $query->latest(),
        };

        $spareParts = $query->paginate(12)->withQueryString();
        $categories = SparePart::distinct()->pluck('category')->filter()->values();

        return view('spareparts.index', compact('spareParts', 'categories', 'search', 'category', 'sort'));
    }

    public function show($id)
    {
        $sparePart = SparePart::findOrFail($id);
        $related   = SparePart::where('category', $sparePart->category)
                              ->where('id', '!=', $id)
                              ->take(4)
                              ->get();

        return view('spareparts.show', compact('sparePart', 'related'));
    }

    // ── ADMIN ───────────────────────────────────────────────

    public function adminIndex()
    {
        $spareParts = SparePart::latest()->paginate(15);
        return view('admin.spareparts.index', compact('spareParts'));
    }

    public function create()
    {
        $categories = SparePart::distinct()->pluck('category')->filter()->values();
        return view('admin.spareparts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:255',
            'price'               => 'required|integer|min:0',
            'stock'               => 'required|integer|min:0',
            'unit'                => 'required|string|max:50',
            'description'         => 'nullable|string',
            'brand'               => 'nullable|string|max:100',
            'category'            => 'nullable|string|max:100',
            'compatible_vehicles' => 'nullable|string|max:255',
            'image'               => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                                          ->store('spare-parts', 'public');
        }

        SparePart::create($validated);

        return redirect()->route('admin.spare-parts.index')
                         ->with('success', 'Spare part berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $sparePart  = SparePart::findOrFail($id);
        $categories = SparePart::distinct()->pluck('category')->filter()->values();
        return view('admin.spareparts.edit', compact('sparePart', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $sparePart = SparePart::findOrFail($id);

        $validated = $request->validate([
            'name'                => 'required|string|max:255',
            'price'               => 'required|integer|min:0',
            'stock'               => 'required|integer|min:0',
            'unit'                => 'required|string|max:50',
            'description'         => 'nullable|string',
            'brand'               => 'nullable|string|max:100',
            'category'            => 'nullable|string|max:100',
            'compatible_vehicles' => 'nullable|string|max:255',
            'image'               => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($sparePart->image) {
                Storage::disk('public')->delete($sparePart->image);
            }
            $validated['image'] = $request->file('image')->store('spare-parts', 'public');
        }

        $sparePart->update($validated);

        return redirect()->route('admin.spare-parts.index')
                         ->with('success', 'Spare part berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $sparePart = SparePart::findOrFail($id);

        if ($sparePart->image) {
            Storage::disk('public')->delete($sparePart->image);
        }

        $sparePart->delete();

        return redirect()->route('admin.spare-parts.index')
                         ->with('success', 'Spare part berhasil dihapus!');
    }
}
