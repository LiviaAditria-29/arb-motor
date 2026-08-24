# ARB Motor – Panduan Implementasi Lengkap


---

```
arb_motor/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── HomeController.php          
│   │       ├── ServiceController.php       
│   │       ├── SparePartController.php     
│   │       └── Admin/
│   │           └── DashboardController.php 
│   └── Models/
│       ├── Booking.php                     
│       ├── Service.php                     
│       ├── SparePart.php                   
│       └── Testimonial.php                 
├── database/
│   ├── migrations/
│   │   ├── xxxx_add_image_to_spare_parts.php  
│   │   ├── xxxx_add_category_to_services.php  
│   │   └── xxxx_create_testimonials.php       
│   └── seeders/
│       └── TestimonialSeeder.php          
├── resources/
│   ├── css/app.css                        
│   ├── js/app.js                          
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php              
│       │   └── admin.blade.php            
│       ├── home.blade.php                 
│       ├── services/
│       │   ├── index.blade.php            
│       │   └── show.blade.php             
│       ├── spareparts/
│       │   ├── index.blade.php            
│       │   └── show.blade.php             
│       └── admin/
│           ├── dashboard.blade.php        
│           └── spareparts/
│               ├── index.blade.php        
│               ├── create.blade.php       
│               └── edit.blade.php        
└── routes/
    └── web.php                            
```

---

## LANGKAH IMPLEMENTASI (Jalankan Berurutan)

### LANGKAH 1: Install Dependencies
```bash
cd arb_motor

# Install npm packages baru
npm install sweetalert2 chart.js

# Pastikan Alpine.js tersedia (sudah include di Laravel Breeze)
# Jika belum: npm install alpinejs
```

### LANGKAH 2: Buat Migrations
```bash
php artisan make:migration add_image_and_details_to_spare_parts_table
php artisan make:migration add_category_icon_to_services_table
php artisan make:migration create_testimonials_table
```

Salin isi masing-masing dari file `database/migrations/MIGRATIONS.php` di paket ini,
lalu jalankan:
```bash
php artisan migrate
```

### LANGKAH 3: Update Data Lama di Database
```sql
-- Jalankan di phpMyAdmin atau MySQL client:
UPDATE spare_parts SET category = 'Umum'  WHERE category IS NULL;
UPDATE services     SET category = 'Umum' WHERE category IS NULL;
```

### LANGKAH 4: Buat Seeder Testimonial
```bash
php artisan make:seeder TestimonialSeeder
# Salin isi dari database/seeders/TestimonialSeeder.php di paket ini
php artisan db:seed --class=TestimonialSeeder
```

### LANGKAH 5: Setup Storage
```bash
php artisan storage:link
```

Buat placeholder image untuk spare part:
```bash
# Download atau buat file spare-part-placeholder.png
# Taruh di: public/images/spare-part-placeholder.png
```

### LANGKAH 6: Salin Semua File
Salin file-file dari paket ini ke posisi yang sesuai di project Laravel kamu.
(Lihat struktur folder di atas)

Perhatian khusus:
- Buat folder `app/Http/Controllers/Admin/` jika belum ada
- Buat folder `resources/views/admin/spareparts/` jika belum ada

### LANGKAH 7: Build Assets
```bash
npm run build
# atau untuk development:
npm run dev
```

### LANGKAH 8: Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### LANGKAH 9: Verifikasi
Buka browser dan cek:
- `http://localhost/`             → Halaman Beranda (Hero + Stats + Layanan + Testimonial)
- `http://localhost/services`     → Halaman Layanan (Search + Filter)
- `http://localhost/spare-parts`  → Halaman Spare Part (Marketplace)
- `http://localhost/admin/dashboard` → Dashboard Admin (Login dulu)
- `http://localhost/admin/spare-parts` → Kelola Spare Part

---

## FITUR-FITUR YANG SUDAH DIIMPLEMENTASI

### Beranda ✅
- Hero section dengan gradient + grid pattern
- Counter animasi statistik bengkel
- 3 layanan featured dengan card hover
- Section "Mengapa Pilih ARB Motor?" dengan 4 keunggulan
- Carousel/grid testimoni pelanggan
- CTA section sebelum footer
- Footer lengkap 4 kolom

### Layanan ✅
- Page hero dengan breadcrumb
- Search real-time (debounce 500ms)
- Filter kategori (pills/chips)
- Card modern dengan checklist, harga, estimasi waktu
- Empty state jika tidak ditemukan
- Halaman detail layanan dengan sidebar sticky

### Spare Part ✅
- Layout marketplace 4 kolom (responsive)
- Search + filter kategori + sort (harga/nama/terbaru)
- Stock badge (Tersedia/Terbatas/Habis)
- Pagination
- Halaman detail dengan gambar besar, spesifikasi, related products

### Manajemen Gambar ✅
- Upload via Laravel Storage (disk: public)
- Validasi: JPG, PNG, WEBP, maks 2MB
- Preview sebelum upload (FileReader API)
- Drag & drop support
- Hapus gambar lama otomatis saat update

### Dashboard Admin ✅
- Sidebar responsive (mobile-friendly)
- 4 stat cards (Spare Part, Layanan, Booking, Pelanggan)
- Grafik booking bulanan (Chart.js bar chart)
- Tabel booking terbaru (desktop) + card (mobile)
- Quick stats dan shortcut

### Admin Spare Part CRUD ✅
- Tabel dengan foto, nama, kategori, harga, stok
- SweetAlert konfirmasi hapus
- Form create/edit dengan image preview
- Responsive: tabel → card di mobile

### UI Enhancement ✅
- Toast notification (global)
- SweetAlert2 konfirmasi hapus
- Empty state
- Hover animation pada cards
- Scroll reveal animation
- Counter animation
- Mobile hamburger menu

---

## KONFIGURASI TAMBAHAN (Opsional)

### Tailwind Config (tailwind.config.js)
```js
/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Plus Jakarta Sans', 'sans-serif'],
                display: ['Space Grotesk', 'sans-serif'],
            },
            colors: {
                navy: {
                    DEFAULT: '#0F172A',
                    800: '#1E293B',
                    700: '#334155',
                },
                brand: {
                    orange: '#F97316',
                }
            }
        },
    },
    plugins: [],
}
```

### Pagination View
Untuk custom pagination Tailwind, publish vendor view:
```bash
php artisan vendor:publish --tag=laravel-pagination
```
Kemudian gunakan di model/paginate view: `vendor.pagination.tailwind`

---

## TROUBLESHOOTING

| Problem | Solusi |
|---------|--------|
| Gambar tidak muncul | Pastikan `php artisan storage:link` sudah dijalankan |
| Counter tidak bergerak | Pastikan Alpine.js ter-load (`npm run dev`) |
| Admin tidak bisa akses | Login dulu di `/login`, pastikan middleware `auth,verified` |
| Category kosong | Jalankan SQL update category di langkah 3 |
| Chart tidak muncul | Pastikan Chart.js ter-install (`npm install chart.js`) |
| Tailwind class tidak styling | Jalankan `npm run build` ulang |

---

## CATATAN PENTING

1. **Stack tidak berubah**: Laravel + Blade + Tailwind CSS + Alpine.js (sudah include di Breeze)
2. **Chatbot**: Kode Flowise chatbot dari hero asli bisa diintegrasikan kembali di `home.blade.php`
3. **Auth**: Dashboard admin menggunakan auth bawaan Laravel Breeze (middleware `auth`)
4. **Bootstrap dihapus**: Layout baru full Tailwind CSS (tidak ada Bootstrap)
5. **Booking form**: Saat ini booking diarahkan ke WhatsApp, sistem booking penuh bisa dikembangkan terpisah

---

*Dibuat untuk ARB Motor | Laravel 10 + Tailwind CSS + Alpine.js*
