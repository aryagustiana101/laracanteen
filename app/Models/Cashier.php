<?php

namespace App\Models;

use App\Models\Teller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cashier extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $dates = ['deleted_at'];

    public function teller()
    {
        return $this->hasOne(Teller::class);
    }

    public function paymentGroups()
    {
        return $this->hasMany(PaymentGroup::class);
    }
}
