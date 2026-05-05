<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'transaction_id', 'payment_amount', 'payment_method', 'payment_status', 'paid_at'
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}