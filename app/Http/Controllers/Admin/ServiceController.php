<?php
// ============================================================
// app/Http/Controllers/Admin/ServiceController.php
// ============================================================
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::latest()->paginate(15);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $categories = ['Umum','Mesin','Rem','Listrik','AC','Suspensi','Eksterior'];
        return view('admin.services.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'required|string',
            'price'            => 'required|integer|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'category'         => 'required|string|max:100',
        ]);
        Service::create($v);
        return redirect()->route('admin.services.index')->with('success','Layanan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $service    = Service::findOrFail($id);
        $categories = ['Umum','Mesin','Rem','Listrik','AC','Suspensi','Eksterior'];
        return view('admin.services.edit', compact('service','categories'));
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $v = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'required|string',
            'price'            => 'required|integer|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'category'         => 'required|string|max:100',
        ]);
        $service->update($v);
        return redirect()->route('admin.services.index')->with('success','Layanan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Service::findOrFail($id)->delete();
        return redirect()->route('admin.services.index')->with('success','Layanan berhasil dihapus!');
    }
}
