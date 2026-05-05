<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'first_name',
        'middle_name',
        'last_name',
        'contact_number',
        'email',
        'address',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    protected $appends = ['full_name'];

    // Relationship with transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'customer_id');
    }

    // Accessor for full name
    public function getFullNameAttribute()
    {
        $nameParts = [$this->first_name];
        
        if ($this->middle_name) {
            $nameParts[] = $this->middle_name;
        }
        
        $nameParts[] = $this->last_name;
        
        return implode(' ', $nameParts);
    }

    // Accessor for full name with middle initial
    public function getFullNameWithInitialAttribute()
    {
        $middleInitial = $this->middle_name ? strtoupper(substr($this->middle_name, 0, 1)) . '.' : '';
        return trim($this->first_name . ' ' . $middleInitial . ' ' . $this->last_name);
    }

    // Scope for searching customers
    public function scopeSearch($query, $search)
    {
        return $query->where('first_name', 'like', "%{$search}%")
                     ->orWhere('last_name', 'like', "%{$search}%")
                     ->orWhere('contact_number', 'like', "%{$search}%")
                     ->orWhere('id', 'like', "%{$search}%");
    }
}