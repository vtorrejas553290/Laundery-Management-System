<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Staff extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'staff';
    
    protected $fillable = [
        'id',
        'first_name',
        'middle_name',
        'last_name',
        'birthday',
        'age',
        'contact',
        'address',
        'email',
        'password',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
        'birthday' => 'date',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    
    public function getAuthPassword()
    {
        return $this->password;
    }
    
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }
}