<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionExtraItem extends Model
{
    use HasFactory;

    protected $table = 'transaction_extra_items';
    
    protected $fillable = ['id', 'transaction_id', 'extra_item_id', 'quantity', 'subtotal'];
    
    public $incrementing = false;
    protected $keyType = 'string';

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function extraItem()
    {
        return $this->belongsTo(ExtraItem::class);
    }
}