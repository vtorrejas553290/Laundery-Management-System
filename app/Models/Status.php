<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'status_name'];
    public $incrementing = false;
    protected $keyType = 'string';

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'status_id');
    }
}