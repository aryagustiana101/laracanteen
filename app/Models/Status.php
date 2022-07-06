<?php

namespace App\Models;

use App\Models\Tracker;
use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Status extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $dates = ['deleted_at'];

    public function trackers()
    {
        return $this->hasMany(Tracker::class);
    }

    public function orderHeaders()
    {
        return $this->hasMany(OrderHeader::class);
    }
}
