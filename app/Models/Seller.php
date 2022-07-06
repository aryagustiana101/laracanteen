<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seller extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $dates = ['deleted_at'];

    protected $with = ['store'];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
