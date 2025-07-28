<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Repair extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'customer_name',
        'device_type',
        'problem_description',
        'spare_part_id',
        'status',
        'repair_cost',
        'total',
    ];

    // العلاقة مع العميل (إن وجد)
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // العلاقة مع قطعة الغيار
    public function sparePart()
    {
        return $this->belongsTo(Product::class, 'spare_part_id');
    }
}
