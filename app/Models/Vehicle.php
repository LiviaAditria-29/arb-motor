<?php
// app/Models/Vehicle.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id','plate_number','brand','model','year'];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function bookings() { return $this->hasMany(Booking::class); }

    public function getFullNameAttribute(): string
    {
        return trim($this->brand.' '.$this->model.' ('.$this->year.')');
    }
}
