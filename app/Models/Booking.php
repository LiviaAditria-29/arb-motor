<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'service_id',
        'name', 'phone', 'email',
        'vehicle_type', 'vehicle_number',
        'booking_date', 'booking_time', 'time_slot',
        'status', 'notes',
        // kolom baru
        'customer_name', 'customer_phone', 'vehicle_name', 'service_name',
        'actual_cost', 'technician_name', 'completed_at',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function service()  { return $this->belongsTo(Service::class); }
    public function user()     { return $this->belongsTo(User::class); }

    // ── Accessor — ambil nama dari kolom manapun yang tersedia ──
    public function getDisplayNameAttribute(): string
    {
        return $this->customer_name
            ?? $this->name
            ?? $this->user?->name
            ?? '-';
    }

    public function getDisplayPhoneAttribute(): string
    {
        return $this->customer_phone ?? $this->phone ?? '-';
    }

    public function getDisplayVehicleAttribute(): string
    {
        return $this->vehicle_name
            ?? $this->vehicle_type
            ?? '-';
    }

    public function getDisplayServiceAttribute(): string
    {
        return $this->service_name ?? $this->service?->name ?? '-';
    }

    public function getDisplayCostAttribute(): string
    {
        $cost = $this->actual_cost ?? 0;
        return 'Rp ' . number_format($cost, 0, ',', '.');
    }

    public function getDisplayTimeAttribute(): string
    {
        return substr($this->time_slot ?? $this->booking_time ?? '00:00:00', 0, 5);
    }

    public function getStatusLabelAttribute(): array
    {
        return match($this->status) {
            'pending'     => ['label' => 'Menunggu',     'color' => 'yellow'],
            'confirmed'   => ['label' => 'Dikonfirmasi', 'color' => 'blue'],
            'in_progress' => ['label' => 'Diproses',     'color' => 'indigo'],
            'completed'   => ['label' => 'Selesai',      'color' => 'green'],
            'cancelled'   => ['label' => 'Dibatalkan',   'color' => 'red'],
            default       => ['label' => $this->status,  'color' => 'gray'],
        };
    }

    public function scopeMonth($q, $month, $year)
    {
        return $q->whereMonth('booking_date', $month)
                 ->whereYear('booking_date', $year);
    }
}