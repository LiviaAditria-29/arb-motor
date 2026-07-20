# ARB Motor – Panduan Implementasi Lengkap
## Redesign UI/UX & Upgrade Fitur

> **Stack**: Laravel 10, Tailwind CSS, Alpine.js (sudah include via Vite), Chart.js, SweetAlert2
> **Urutan implementasi**: Ikuti tahap 1–7 secara berurutan.

---

## TAHAP 1 — Database & Migration

### 1.1 Tambah kolom ke tabel `spare_parts`

```bash
php artisan make:migration add_image_and_details_to_spare_parts_table
```

```php
// database/migrations/xxxx_add_image_and_details_to_spare_parts_table.php
public function up(): void
{
    Schema::table('spare_parts', function (Blueprint $table) {
        $table->string('image')->nullable()->after('unit');
        $table->text('description')->nullable()->after('image');
        $table->string('brand')->nullable()->after('description');
        $table->string('category')->nullable()->after('brand');
        $table->string('compatible_vehicles')->nullable()->after('category');
    });
}

public function down(): void
{
    Schema::table('spare_parts', function (Blueprint $table) {
        $table->dropColumn(['image','description','brand','category','compatible_vehicles']);
    });
}
```

### 1.2 Tambah kolom `category` ke tabel `services`

```bash
php artisan make:migration add_category_to_services_table
```

```php
public function up(): void
{
    Schema::table('services', function (Blueprint $table) {
        $table->string('category')->default('Umum')->after('duration_minutes');
        $table->string('icon')->nullable()->after('category');
    });
}
```

### 1.3 Buat tabel `testimonials`

```bash
php artisan make:migration create_testimonials_table
```

```php
public function up(): void
{
    Schema::create('testimonials', function (Blueprint $table) {
        $table->id();
        $table->string('customer_name');
        $table->string('vehicle')->nullable();
        $table->tinyInteger('rating')->default(5);
        $table->text('comment');
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}
```

```bash
php artisan migrate
```

---

## TAHAP 2 — Models

### `app/Models/SparePart.php`
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparePart extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'price', 'stock', 'unit',
        'image', 'description', 'brand',
        'category', 'compatible_vehicles',
    ];

    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('images/spare-part-placeholder.png');
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%$search%")
                     ->orWhere('brand', 'like', "%$search%");
    }

    public function scopeByCategory($query, $category)
    {
        return $category ? $query->where('category', $category) : $query;
    }
}
```

### `app/Models/Service.php`
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name','description','price','duration_minutes','category','icon'];

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getIconEmojiAttribute(): string
    {
        return match($this->category) {
            'Mesin'       => '⚙️',
            'Rem'         => '🛑',
            'Listrik'     => '⚡',
            'AC'          => '❄️',
            'Eksterior'   => '🚗',
            default       => '🔧',
        };
    }
}
```

### `app/Models/Booking.php`
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_name','customer_name','customer_phone',
        'booking_date','time_slot','status','estimated_cost','notes',
    ];

    protected $casts = ['booking_date' => 'date'];

    public function getStatusLabelAttribute(): array
    {
        return match($this->status) {
            'pending'     => ['label' => 'Menunggu',    'color' => 'yellow'],
            'confirmed'   => ['label' => 'Dikonfirmasi','color' => 'blue'],
            'in_progress' => ['label' => 'Diproses',    'color' => 'indigo'],
            'completed'   => ['label' => 'Selesai',     'color' => 'green'],
            'cancelled'   => ['label' => 'Dibatalkan',  'color' => 'red'],
            default       => ['label' => $this->status, 'color' => 'gray'],
        };
    }
}
```

### `app/Models/Testimonial.php`
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;
    protected $fillable = ['customer_name','vehicle','rating','comment','is_active'];

    public function getStarsAttribute(): string
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }
}
```

---

## TAHAP 3 — Controllers

### `app/Http/Controllers/HomeController.php`
```php
<?php
namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\SparePart;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $services      = Service::take(3)->get();
        $testimonials  = Testimonial::where('is_active', true)->latest()->take(6)->get();
        $stats = [
            'total_services'    => Service::count(),
            'total_spare_parts' => SparePart::count(),
            'total_bookings'    => Booking::count(),
            'total_customers'   => Customer::count(),
        ];
        return view('home', compact('services','testimonials','stats'));
    }
}
```

### `app/Http/Controllers/ServiceController.php`
```php
<?php
namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $search   = $request->search;
        $category = $request->category;

        $services = Service::when($search, fn($q) => $q->where('name','like',"%$search%")
                                                        ->orWhere('description','like',"%$search%"))
                           ->when($category, fn($q) => $q->where('category', $category))
                           ->get();

        $categories = Service::distinct()->pluck('category')->filter()->values();

        return view('services.index', compact('services','categories','search','category'));
    }

    public function show($id)
    {
        $service  = Service::findOrFail($id);
        $related  = Service::where('category', $service->category)
                           ->where('id','!=',$id)->take(3)->get();
        return view('services.show', compact('service','related'));
    }
}
```

### `app/Http/Controllers/SparePartController.php`
```php
<?php
namespace App\Http\Controllers;

use App\Models\SparePart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SparePartController extends Controller
{
    /* ─── PUBLIC ────────────────────────────────── */

    public function index(Request $request)
    {
        $search   = $request->search;
        $category = $request->category;
        $sort     = $request->sort ?? 'latest';

        $query = SparePart::search($search)->byCategory($category);

        $query = match($sort) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'name'       => $query->orderBy('name'),
            default      => $query->latest(),
        };

        $spareParts = $query->paginate(12)->withQueryString();
        $categories = SparePart::distinct()->pluck('category')->filter()->values();

        return view('spareparts.index', compact('spareParts','categories','search','category','sort'));
    }

    public function show($id)
    {
        $sparePart = SparePart::findOrFail($id);
        $related   = SparePart::where('category', $sparePart->category)
                              ->where('id','!=',$id)->take(4)->get();
        return view('spareparts.show', compact('sparePart','related'));
    }

    /* ─── ADMIN ──────────────────────────────────── */

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
        return view('admin.spareparts.edit', compact('sparePart','categories'));
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
            if ($sparePart->image) Storage::disk('public')->delete($sparePart->image);
            $validated['image'] = $request->file('image')->store('spare-parts','public');
        }

        $sparePart->update($validated);

        return redirect()->route('admin.spare-parts.index')
                         ->with('success', 'Spare part berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $sparePart = SparePart::findOrFail($id);
        if ($sparePart->image) Storage::disk('public')->delete($sparePart->image);
        $sparePart->delete();

        return redirect()->route('admin.spare-parts.index')
                         ->with('success', 'Spare part berhasil dihapus!');
    }
}
```

### `app/Http/Controllers/Admin/DashboardController.php`
```php
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Booking, Customer, Service, SparePart};
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_spare_parts' => SparePart::count(),
            'total_services'    => Service::count(),
            'total_bookings'    => Booking::count(),
            'total_customers'   => Customer::count(),
        ];

        // Booking per bulan (12 bulan terakhir)
        $monthlyBookings = Booking::select(
                DB::raw('MONTH(booking_date) as month'),
                DB::raw('YEAR(booking_date) as year'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('booking_date', now()->year)
            ->groupBy('year','month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $chartLabels = [];
        $chartData   = [];
        for ($m = 1; $m <= 12; $m++) {
            $chartLabels[] = date('M', mktime(0,0,0,$m,1));
            $chartData[]   = $monthlyBookings[$m]->total ?? 0;
        }

        $recentBookings = Booking::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats','chartLabels','chartData','recentBookings'));
    }
}
```

---

## TAHAP 4 — Routes (`routes/web.php`)

```php
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SparePartController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

/* ─── PUBLIC ──────────────────────────────── */
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services',       [ServiceController::class,  'index'])->name('services.index');
Route::get('/services/{id}',  [ServiceController::class,  'show'])->name('services.show');
Route::get('/spare-parts',    [SparePartController::class, 'index'])->name('spare-parts.index');
Route::get('/spare-parts/{id}', [SparePartController::class, 'show'])->name('spare-parts.show');

/* ─── ADMIN ───────────────────────────────── */
Route::middleware(['auth','verified'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('spare-parts', SparePartController::class)->except(['index','show']);
    Route::get('/spare-parts', [SparePartController::class, 'adminIndex'])->name('spare-parts.index');
});

/* ─── AUTH ─────────────────────────────────── */
Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
```

> **Tambahkan method `adminIndex`** di SparePartController untuk admin view:
```php
public function adminIndex()
{
    $spareParts = SparePart::latest()->paginate(15);
    return view('admin.spareparts.index', compact('spareParts'));
}
```

---

## TAHAP 5 — Storage Setup

```bash
php artisan storage:link
```

Buat folder placeholder:
```bash
mkdir -p public/images
# Taruh file spare-part-placeholder.png di public/images/
```

---

## TAHAP 6 — Install Dependencies

```bash
# Pastikan Tailwind sudah ada (Laravel Breeze sudah include)
npm install

# Install SweetAlert2 & Chart.js
npm install sweetalert2 chart.js

# Tambahkan ke resources/js/app.js:
# import Swal from 'sweetalert2';
# import Chart from 'chart.js/auto';
# window.Swal  = Swal;
# window.Chart = Chart;

npm run build
```

---

## TAHAP 7 — Struktur Folder View yang Direkomendasikan

```
resources/views/
├── layouts/
│   ├── app.blade.php          ← Layout utama (Navbar + Footer)
│   ├── admin.blade.php        ← Layout admin sidebar
│   └── guest.blade.php
├── components/
│   ├── navbar.blade.php
│   ├── footer.blade.php
│   ├── toast.blade.php
│   └── skeleton-card.blade.php
├── home.blade.php
├── services/
│   ├── index.blade.php
│   └── show.blade.php
├── spareparts/
│   ├── index.blade.php
│   └── show.blade.php
└── admin/
    ├── dashboard.blade.php
    └── spareparts/
        ├── index.blade.php
        ├── create.blade.php
        └── edit.blade.php
```

---

## CATATAN PENTING

1. **Seeder Testimonial** – jalankan setelah migrasi:
```bash
php artisan make:seeder TestimonialSeeder
# Isi dengan data dummy, lalu:
php artisan db:seed --class=TestimonialSeeder
```

2. **Update Service category** – setelah migrasi, update data existing:
```sql
UPDATE services SET category = 'Umum' WHERE category IS NULL;
```

3. **Update SparePart category** – set default:
```sql
UPDATE spare_parts SET category = 'Umum' WHERE category IS NULL;
```

Lihat file-file kode view di dokumen terpisah (VIEWS_*.php).
