<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    use HasFactory;

    protected $table = 'service_categories';
    protected $fillable = [
        'id',
        'category_name',
        'description',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function serviceTypes()
    {
        return $this->hasMany(ServiceType::class, 'category_id');
    }
}