<?php

namespace App\Models;

use App\Models\Seller;
use App\Models\Teller;
use App\Models\Student;
use App\Models\Teacher;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasFactory, SoftDeletes, CascadeSoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'student_id',
        'teacher_id',
        'seller_id',
        'cashier_id',
        'email',
        'phone_number',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $cascadeDeletes = ['paymentGroups'];

    protected $dates = ['deleted_at'];

    protected $with = ['student', 'teacher', 'seller', 'teller'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function teller()
    {
        return $this->belongsTo(Teller::class);
    }

    public function administrator()
    {
        return $this->belongsTo(Administrator::class);
    }

    public function scopeAuth($query, array $auth_creds)
    {
        $query->when($auth_creds['user_auth'] ?? false, function ($query, $user_auth) {
            return $query->where('email', $user_auth)->orWhere('phone_number', $user_auth)->orWhereHas('student', function (Builder $query) use ($user_auth) {
                $query->where('nisn', $user_auth)->orWhere('nis', $user_auth);
            })->orWhereHas('teacher', function (Builder $query) use ($user_auth) {
                $query->where('nuptk', $user_auth)->orWhere('nip', $user_auth);
            });
        });
        $query->when($auth_creds['admin_auth'] ?? false, function ($query, $admin_auth) {
            return $query->where(function ($query) use ($admin_auth) {
                $query->where('email', $admin_auth)->orWhere('phone_number', $admin_auth);
            })->whereNotNull('administrator_id');
        });
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['keyword'] ?? false, function ($query, $keyword) {
            return $query->where('email', 'like', "%{$keyword}%")->orWhere('phone_number', 'like', "%{$keyword}%")->orWhereHas('student', function (Builder $query) use ($keyword) {
                $query->where('nisn', 'like', "%{$keyword}%")->orWhere('nis', 'like', "%{$keyword}%")->orWhere('name', 'like', "%{$keyword}%")->orWhere('gender', 'like', "%{$keyword}%")->orWhere('birth_place', 'like', "%{$keyword}%")->orWhere('birth_date', 'like', "%{$keyword}%")->orWhere('address', 'like', "%{$keyword}%");
            })->orWhereHas('teacher', function (Builder $query) use ($keyword) {
                $query->where('nuptk', 'like', "%{$keyword}%")->orWhere('nip', 'like', "%{$keyword}%")->orWhere('name', 'like', "%{$keyword}%")->orWhere('gender', 'like', "%{$keyword}%")->orWhere('birth_place', 'like', "%{$keyword}%")->orWhere('birth_date', 'like', "%{$keyword}%")->orWhere('address', 'like', "%{$keyword}%");
            })->orWhereHas('seller', function (Builder $query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%")->orWhere('gender', 'like', "%{$keyword}%")->orWhere('birth_place', 'like', "%{$keyword}%")->orWhere('birth_date', 'like', "%{$keyword}%")->orWhere('address', 'like', "%{$keyword}%")->orWhereHas('store', function (Builder $query) use ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%")->orWhere('initials', 'like', "%{$keyword}%")->orWhere('location', 'like', "%{$keyword}%");
                });
            })->orWhereHas('teller', function (Builder $query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%")->orWhere('gender', 'like', "%{$keyword}%")->orWhere('birth_place', 'like', "%{$keyword}%")->orWhere('birth_date', 'like', "%{$keyword}%")->orWhere('address', 'like', "%{$keyword}%")->orWhereHas('cashier', function (Builder $query) use ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%")->orWhere('initials', 'like', "%{$keyword}%")->orWhere('location', 'like', "%{$keyword}%");
                });
            })->orWhereHas('administrator', function (Builder $query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%")->orWhere('gender', 'like', "%{$keyword}%")->orWhere('birth_place', 'like', "%{$keyword}%")->orWhere('birth_date', 'like', "%{$keyword}%")->orWhere('address', 'like', "%{$keyword}%");
            });
        });
    }

    public function paymentGroups()
    {
        return $this->hasMany(PaymentGroup::class);
    }
}
