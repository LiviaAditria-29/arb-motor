<?php
// ============================================================
// app/Http/Controllers/Admin/CustomerController.php
// ============================================================
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::withCount('bookings')->with('vehicles')->latest();
        if ($request->filled('search'))
            $query->where('name','like','%'.$request->search.'%')
                  ->orWhere('phone','like','%'.$request->search.'%');

        $customers = $query->paginate(20)->withQueryString();
        return view('admin.customers.index', compact('customers'));
    }

    public function show($id)
    {
        $customer = Customer::with(['vehicles','bookings' => fn($q) => $q->latest()->take(10)])
                            ->findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }
}
