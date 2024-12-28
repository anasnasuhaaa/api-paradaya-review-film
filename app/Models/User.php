<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'
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
        'password' => 'hashed',
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            $model->generate_otp();
        });
    }
    public function generate_otp()
    {
        do {
            $random_number = mt_rand(100000, 999999);
            $check = otpCode::where('otp', $random_number)->first();
        } while ($check);

        $now = Carbon::now();

        $otp_code = otpCode::updateOrCreate([
            'user_id' => $this->id
        ], [
            'otp' => $random_number,
            'valid_until' => $now->addMinutes(5)
        ]);
    }
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }
    public function review()
    {
        return $this->hasMany(Profile::class, 'user_id');
    }
    public function otpData()
    {
        return $this->hasOne(otpCode::class, 'user_id');
    }
}
