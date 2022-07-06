<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class PaymentGroup extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $cascadeDeletes = ['orderHeaders'];

    protected $dates = ['deleted_at'];

     protected $with = ['orderHeaders', 'user', 'cashier'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cashier()
    {
        return $this->belongsTo(Cashier::class);
    }

    public function orderHeaders()
    {
        return $this->hasMany(OrderHeader::class);
    }

    public function scopeFilter($query, array $filters)
    {

        $query->when($filters['id'] ?? false, function ($query, $id) {
            return $query->where('id', $id);
        });

        $query->when($filters['payment_code'] ?? false, function ($query, $payment_code) {
            return $query->where('payment_code', $payment_code);
        });

        $query->when($filters['user_id'] ?? false, function ($query, $user_id) {
            return $query->where('user_id', $user_id);
        });
        $query->when($filters['user_id'] ?? false, function ($query, $user_id) {
            return $query->where('user_id', $user_id);
        });
        $query->when($filters['store_id'] ?? false, function ($query, $store_id) {
            return $query->whereHas('orderHeaders', function (Builder $query) use ($store_id) {
                $query->where('store_id', $store_id);
            });
        });
        $query->when($filters['image'] ?? false, function ($query, $image) {
            return $query->where('image', $image);
        });
    }
}
