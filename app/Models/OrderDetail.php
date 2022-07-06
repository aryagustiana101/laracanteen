<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $dates = ['deleted_at'];

    protected $with = ['product'];

    public function orderHeader()
    {
        return $this->belongsTo(OrderHeader::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
