<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description'];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($cat) {
            if (empty($cat->slug)) {
                $cat->slug = Str::slug($cat->name);
            }
        });
        static::updating(function ($cat) {
            $cat->slug = Str::slug($cat->name);
        });
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}