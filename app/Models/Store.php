<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $dates = ['deleted_at'];

//    protected  $with = ['seller'];

    public function seller()
    {
        return $this->hasOne(Seller::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orderHeaders()
    {
        return $this->hasMany(OrderHeader::class);
    }

    public function scopeProductFilter($query, array $filters)
    {
        $query->when($filters['store_id'] ?? false, function ($query, $store_id) {
            return $query->whereHas('products', function (Builder $query) use ($store_id) {
                $query->where('store_id', $store_id);
            });
        });

        $query->when($filters ?? false, function ($query, $filters) {
            return $query->with(['products' => function ($query) use ($filters) {
                $query->when($filters['keyword'] ?? false, function (Builder $query) use ($filters) {
                    return $query->where(function ($query) use ($filters) {
                        $query->where('name', 'like', '%' . $filters['keyword'] . '%')->orWhere('description', 'like', '%' . $filters['keyword'] . '%');
                    });
                });
                $query->when($filters['store_id'] ?? false, function (Builder $query) use ($filters) {
                    return $query->where(function ($query) use ($filters) {
                        $query->where('store_id', $filters['store_id']);
                    });
                });
                $query->when($filters['category_id'] ?? false, function (Builder $query) use ($filters) {
                    return $query->where(function ($query) use ($filters) {
                        $query->where('category_id', $filters['category_id']);
                    });
                });
                $query->when($filters['price'] ?? false, function (Builder $query) use ($filters) {
                    return $query->orderByRaw("CAST(price AS int) " . $filters['price']);
                });
            }, 'seller']);
        });

    }

}
