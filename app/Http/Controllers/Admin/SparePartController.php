<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SparePart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SparePartController extends Controller
{
    public function index(Request $request)
    {
        $query = SparePart::latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('brand', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $spareParts = $query->paginate(15)->withQueryString();
        $categories = SparePart::distinct()
            ->pluck('category')
            ->filter()
            ->values();

        return view('admin.spare-parts.index', compact(
            'spareParts',
            'categories'
        ));
    }

    public function create()
    {
        $categories = SparePart::distinct()
            ->pluck('category')
            ->filter()
            ->values();

        $units = [
            'pcs',
            'liter',
            'set',
            'pasang',
            'roll',
            'meter',
            'botol',
            'kaleng'
        ];

        return view('admin.spare-parts.create', compact(
            'categories',
            'units'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                => 'required|string|max:255',
            'price'               => 'required|numeric|min:0',
            'stock'               => 'required|integer|min:0',
            'unit'                => 'required|string|max:50',
            'description'         => 'nullable|string',
            'brand'               => 'nullable|string|max:100',
            'category'            => 'nullable|string|max:100',
            'compatible_vehicles' => 'nullable|string|max:255',
            'image'               => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('spare-parts', 'public');
        }

        SparePart::create($data);

        return redirect()
            ->route('admin.spare-parts.index')
            ->with('success', 'Spare part berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $sparePart = SparePart::findOrFail($id);

        $categories = SparePart::distinct()
            ->pluck('category')
            ->filter()
            ->values();

        $units = [
            'pcs',
            'liter',
            'set',
            'pasang',
            'roll',
            'meter',
            'botol',
            'kaleng'
        ];

        return view('admin.spare-parts.edit', compact(
            'sparePart',
            'categories',
            'units'
        ));
    }

    public function update(Request $request, $id)
    {
        $sparePart = SparePart::findOrFail($id);

        $data = $request->validate([
            'name'                => 'required|string|max:255',
            'price'               => 'required|numeric|min:0',
            'stock'               => 'required|integer|min:0',
            'unit'                => 'required|string|max:50',
            'description'         => 'nullable|string',
            'brand'               => 'nullable|string|max:100',
            'category'            => 'nullable|string|max:100',
            'compatible_vehicles' => 'nullable|string|max:255',
            'image'               => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {

            $newImage = $request->file('image')
                ->store('spare-parts', 'public');

            if (
                $sparePart->image &&
                !filter_var($sparePart->image, FILTER_VALIDATE_URL)
            ) {
                Storage::disk('public')->delete($sparePart->image);
            }

            $data['image'] = $newImage;
        }

        $sparePart->update($data);

        return redirect()
            ->route('admin.spare-parts.index')
            ->with('success', 'Spare part berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $sparePart = SparePart::findOrFail($id);

        if (
            $sparePart->image &&
            !filter_var($sparePart->image, FILTER_VALIDATE_URL)
        ) {
            Storage::disk('public')->delete($sparePart->image);
        }

        $sparePart->delete();

        return redirect()
            ->route('admin.spare-parts.index')
            ->with('success', 'Spare part berhasil dihapus!');
    }
}