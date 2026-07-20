<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparePart extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'stock',
        'unit',
        'image',
        'description',
        'brand',
        'category',
        'compatible_vehicles',
    ];

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return asset('images/spare-part-placeholder.png');
        }

        // Jika image berupa URL
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        // Jika image berupa file upload lokal
        return asset('storage/' . $this->image);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function scopeSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('brand', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('category', 'like', "%{$search}%");
        });
    }

    public function scopeByCategory($query, $category)
    {
        if (!$category) {
            return $query;
        }

        return $query->where('category', $category);
    }
}