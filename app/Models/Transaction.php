<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'customer_id', 'service_type_id', 'staff_id', 'status_id',
        'number_of_loads', 'weight', 'total_amount', 'payment_status', 'payment_type',
        'remarks', 'transaction_date'
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
        'transaction_date' => 'date',
    ];

    protected $appends = ['extra_items_formatted', 'customer_name', 'service_name', 'staff_name', 'status_name'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'transaction_id');
    }

    public function extraItems()
    {
        return $this->belongsToMany(ExtraItem::class, 'transaction_extra_items', 'transaction_id', 'extra_item_id')
                    ->withPivot('id', 'quantity', 'subtotal')
                    ->withTimestamps();
    }
    
    public function getExtraItemsFormattedAttribute()
    {
        return $this->extraItems->map(function($item) {
            return [
                'id' => $item->id,
                'item_name' => $item->item_name,
                'price' => $item->price,
                'quantity' => $item->pivot->quantity,
                'subtotal' => $item->pivot->subtotal
            ];
        });
    }

    public function getCustomerNameAttribute()
    {
        return $this->customer ? $this->customer->first_name . ' ' . $this->customer->last_name : 'N/A';
    }

    public function getServiceNameAttribute()
    {
        return $this->serviceType ? $this->serviceType->name : 'N/A';
    }

    public function getStaffNameAttribute()
    {
        return $this->staff ? $this->staff->first_name . ' ' . $this->staff->last_name : 'N/A';
    }

    public function getStatusNameAttribute()
    {
        return $this->status ? $this->status->status_name : 'N/A';
    }
    
    public function getTotalPaidAttribute()
    {
        return $this->payments()->where('payment_status', 'Paid')->sum('payment_amount');
    }
    
    public function updatePaymentStatus()
    {
        $totalPaid = $this->total_paid;
        if ($totalPaid >= $this->total_amount) {
            $this->payment_status = 'Paid';
        } elseif ($totalPaid > 0) {
            $this->payment_status = 'Partial';
        } else {
            $this->payment_status = 'Pending';
        }
        $this->save();
    }
    
    public function getExtraItemsTotalAttribute()
    {
        return $this->extraItems->sum(function($item) {
            return $item->pivot->subtotal;
        });
    }
}