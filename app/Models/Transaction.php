<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected  $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $dates = ['deleted_at'];

    public function orderHeaders(){
        return $this->hasMany(OrderHeader::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['store_id'] ?? false, function ($query, $storeId) {
            return $query->where('store_id', $storeId);
        });

        $query->when($filters['transaction_code'] ?? false, function ($query, $transactionCode) {
            return $query->where('transaction_code', $transactionCode);
        });

        $query->when($filters['withdraw_status'] ?? false, function ($query, $withdrawStatus) {
            return $query->where('withdraw_status', $withdrawStatus);
        });

        $query->when($filters['start_date'] ?? false, function ($query, $startDate) {
            return $query->whereDate('created_at', '>=', $startDate);
        });

        $query->when($filters['end_date'] ?? false, function ($query, $endDate) {
            return $query->whereDate('created_at', '<=', $endDate);
        });
    }

}
