<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderHeader extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $cascadeDeletes = ['orderDetails', 'trackers'];

    protected $dates = ['deleted_at'];

    protected $with = ['orderDetails', 'trackers', 'status', 'store'];

    public function paymentGroup()
    {
        return $this->belongsTo(PaymentGroup::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function trackers()
    {
        return $this->hasMany(Tracker::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function transaction() {
        return $this->belongsTo(Transaction::class);
    }

    public function scopeStatusCheck($query, array $filters)
    {
        $query->when($filters['user_id'] ?? false, function ($query, $user_id) {
            return $query->whereHas('paymentGroup', function (Builder $query) use ($user_id) {
                $query->where('user_id', $user_id);
            });
        });

        $query->when($filters['store_id'] ?? false, function ($query, $storeId) {
            return $query->where('store_id', $storeId);
        });

        $query->when($filters['date'] ?? false, function ($query, $date) {
            return $query->whereDate('created_at', '=', $date);
        });

        $query->when($filters['status'] ?? false, function ($query, $status) {
            return $query->whereIn('status_id', $status);
        });

        $query->when($filters['order'] ?? false, function ($query, $order) {
            $query->orderBy('id', $order);
        });
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['user_id'] ?? false, function ($query, $user_id) {
            return $query->whereHas('paymentGroup', function (Builder $query) use ($user_id) {
                $query->where('user_id', $user_id);
            });
        });

        $query->when($filters['store_id'] ?? false, function ($query, $storeId) {
            return $query->where('store_id', $storeId);
        });

        $query->when($filters['order_code'] ?? false, function ($query, $orderCode) {
            return $query->where('order_code', $orderCode);
        });

        $query->when($filters['status_success'] ?? false, function ($query, $statusId) {
            return $query->where('status_id', $statusId);
        });

        $query->when($filters['status_failed'] ?? false, function ($query, $statusId) {
            $query->whereHas('trackers', function (Builder $query) use ($statusId) {
                return $query->where(function ($query) use ($statusId) {
                    $query->where('status_id',  $statusId[0])->when($statusId[1] ?? false, function ($query, $statusId){
                        $query->orWhere('status_id', $statusId);
                    });
                });
            });
        });

        $query->when($filters['start_date'] ?? false, function ($query, $startDate) {
            return $query->whereDate('created_at', '>=', $startDate);
        });

        $query->when($filters['end_date'] ?? false, function ($query, $endDate) {
            return $query->whereDate('created_at', '<=', $endDate);
        });

        $query->when($filters['order'] ?? false, function ($query, $order) {
            $query->orderBy('id', $order);
        });
    }
}
