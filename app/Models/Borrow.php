<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use HasFactory;

    const FINE_PER_DAY = 0.25;
    const LOAN_DAYS    = 14;

    protected $fillable = [
        'borrow_code', 'student_id', 'book_id',
        'borrow_date', 'due_date', 'return_date',
        'status', 'fine_amount', 'notes',
        'issued_by', 'returned_to',
    ];

    protected $casts = [
        'borrow_date'  => 'date',
        'due_date'     => 'date',
        'return_date'  => 'date',
        'fine_amount'  => 'decimal:2',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($b) {
            if (empty($b->borrow_code)) {
                $b->borrow_code = 'BRW-' . strtoupper(substr(uniqid(), -6));
            }
        });
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function returnedTo()
    {
        return $this->belongsTo(User::class, 'returned_to');
    }

    public function isOverdue(): bool
    {
        return in_array($this->status, ['borrowed', 'overdue'])
            && $this->due_date->isPast();
    }

    public function calculateFine(): float
    {
        $checkDate = $this->return_date ?? Carbon::today();
        if ($checkDate->lte($this->due_date)) {
            return 0;
        }
        return round($this->due_date->diffInDays($checkDate) * self::FINE_PER_DAY, 2);
    }

    public function getDaysRemainingAttribute(): int
    {
        if (in_array($this->status, ['returned', 'lost'])) return 0;
        return (int) Carbon::today()->diffInDays($this->due_date, false);
    }

    public function scopeBorrowed($query)
    {
        return $query->where('status', 'borrowed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
                     ->orWhere(function ($q) {
                         $q->where('status', 'borrowed')
                           ->where('due_date', '<', now()->toDateString());
                     });
    }
}