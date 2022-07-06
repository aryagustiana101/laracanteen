<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $dates = ['deleted_at'];

    protected $with = ['category'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['id'] ?? false, function ($query, $id) {
            return $query->where('id', $id);
        });

        $query->when($filters['store_id'] ?? false, function ($query, $store_id) {
            return $query->where('store_id', $store_id);
        });

        $query->when($filters['category_id'] ?? false, function ($query, $category_id) {
            return $query->where('category_id', $category_id);
        });

        $query->when($filters['image'] ?? false, function ($query, $image) {
            return $query->where('image', $image);
        });

        $query->when($filters['keyword'] ?? false, function ($query, $keyword) {
            return $query->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')->orWhere('description', 'like', '%' . $keyword . '%');
            });
        });
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
