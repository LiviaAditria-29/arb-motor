<?php
// ============================================================
// FILE 1: database/migrations/xxxx_add_image_and_details_to_spare_parts_table.php
// Jalankan: php artisan make:migration add_image_and_details_to_spare_parts_table
// Lalu isi dengan kode di bawah ini:
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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
            $table->dropColumn(['image', 'description', 'brand', 'category', 'compatible_vehicles']);
        });
    }
};


// ============================================================
// FILE 2: database/migrations/xxxx_add_category_icon_to_services_table.php
// Jalankan: php artisan make:migration add_category_icon_to_services_table
// ============================================================

/*
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('category')->default('Umum')->after('duration_minutes');
            $table->string('icon')->nullable()->after('category');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['category', 'icon']);
        });
    }
};
*/


// ============================================================
// FILE 3: database/migrations/xxxx_create_testimonials_table.php
// Jalankan: php artisan make:migration create_testimonials_table
// ============================================================

/*
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
*/
