<?php
// app/Models/Service.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = ['name','description','price','duration_minutes','category','icon'];

    public function bookings() { return $this->hasMany(Booking::class); }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getIconEmojiAttribute(): string
    {
        return match($this->category ?? '') {
            'Mesin'    => '⚙️',
            'Rem'      => '🛑',
            'Listrik'  => '⚡',
            'AC'       => '❄️',
            'Suspensi' => '🔩',
            default    => '🔧',
        };
    }
}
