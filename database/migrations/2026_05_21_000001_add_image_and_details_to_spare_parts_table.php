<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spare_parts', function (Blueprint $table) {
            // Cek kolom yang belum ada sebelum menambahkan
            // (defensive migration — aman dijalankan berkali-kali)

            if (!Schema::hasColumn('spare_parts', 'unit')) {
                $table->string('unit')->default('pcs')->after('stock');
            }
            if (!Schema::hasColumn('spare_parts', 'image')) {
                $table->string('image')->nullable()->after('unit');
            }
            if (!Schema::hasColumn('spare_parts', 'description')) {
                $table->text('description')->nullable()->after('image');
            }
            if (!Schema::hasColumn('spare_parts', 'brand')) {
                $table->string('brand')->nullable()->after('description');
            }
            if (!Schema::hasColumn('spare_parts', 'category')) {
                $table->string('category')->nullable()->after('brand');
            }
            if (!Schema::hasColumn('spare_parts', 'compatible_vehicles')) {
                $table->string('compatible_vehicles')->nullable()->after('category');
            }
        });
    }

    public function down(): void
    {
        Schema::table('spare_parts', function (Blueprint $table) {
            $cols = ['image', 'description', 'brand', 'category', 'compatible_vehicles'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('spare_parts', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};