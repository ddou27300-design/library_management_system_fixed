<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'author', 'isbn', 'category_id', 'faculty',
        'published_year', 'publisher', 'description',
        'total_copies', 'available_copies', 'cover_image', 'status',
    ];

    protected $casts = [
        'total_copies'     => 'integer',
        'available_copies' => 'integer',
        'published_year'   => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }

    public function isAvailable(): bool
    {
        return $this->available_copies > 0 && $this->status === 'available';
    }

    public function getBorrowedCopiesAttribute(): int
    {
        return $this->total_copies - $this->available_copies;
    }

    public function scopeAvailable($query)
    {
        return $query->where('available_copies', '>', 0)->where('status', 'available');
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('author', 'like', "%{$term}%")
              ->orWhere('isbn', 'like', "%{$term}%");
        });
    }
}