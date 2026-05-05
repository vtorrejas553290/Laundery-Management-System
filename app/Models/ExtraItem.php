<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'item_name',
        'price',
    ];

    public $incrementing = false;
    protected $keyType = 'string';
}