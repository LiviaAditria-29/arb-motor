<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            $table->string('customer_name')->nullable()->after('updated_at');
            $table->string('customer_phone', 20)->nullable()->after('customer_name');
            $table->string('vehicle_name')->nullable()->after('customer_phone');
            $table->string('service_name')->nullable()->after('vehicle_name');

            $table->integer('actual_cost')->nullable()->after('service_name');
            $table->string('technician_name')->nullable()->after('actual_cost');
            $table->timestamp('completed_at')->nullable()->after('technician_name');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'customer_name',
                'customer_phone',
                'vehicle_name',
                'service_name',
                'actual_cost',
                'technician_name',
                'completed_at',
            ]);
        });
    }
};