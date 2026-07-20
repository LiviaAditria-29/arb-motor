<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Tambah duration_minutes jika belum ada
            if (!Schema::hasColumn('services', 'duration_minutes')) {
                $table->integer('duration_minutes')->default(60)->after('price');
            }
            if (!Schema::hasColumn('services', 'category')) {
                $table->string('category')->default('Umum')->after('duration_minutes');
            }
            if (!Schema::hasColumn('services', 'icon')) {
                $table->string('icon')->nullable()->after('category');
            }
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            foreach (['category', 'icon'] as $col) {
                if (Schema::hasColumn('services', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};