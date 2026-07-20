<?php
// app/Models/Testimonial.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;
    protected $fillable = ['customer_name','vehicle','rating','comment','is_active'];
    protected $casts    = ['is_active' => 'boolean'];
}
