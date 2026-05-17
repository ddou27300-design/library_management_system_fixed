<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id', 'name', 'email', 'phone',
        'class', 'major', 'address', 'status',
    ];

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }

    public function activeBorrows()
    {
        return $this->hasMany(Borrow::class)->whereIn('status', ['borrowed', 'overdue']);
    }

    public function getTotalFinesAttribute(): float
    {
        return (float) $this->borrows()->sum('fine_amount');
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('student_id', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%");
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}