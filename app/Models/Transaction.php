<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'book_id', 'party_id', 'date', 'due_date', 'description',
        'amount', 'payment_status', 'payment_method', 'paid_amount', 'created_by', 'proof_image',
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Sisa hutang
    public function getRemainingDebtAttribute()
    {
        return $this->amount - $this->paid_amount;
    }

    // Status warna untuk tampilan
    public function getStatusBadgeAttribute()
    {
        return match($this->payment_status) {
            'lunas' => 'success',
            'cicil' => 'warning',
            'hutang' => 'danger',
            default => 'secondary'
        };
    }
}