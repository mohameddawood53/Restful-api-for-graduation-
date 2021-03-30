<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\App;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        "job",
        "address",
        "signed_in",
        "phone",
        "sick",
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function mixtures()
    {
        return $this->hasMany(\App\Models\mixtuers::class);
    }

    public function posts()
    {
        return $this->hasMany(\App\Models\posts::class);
    }

    public function donations()
    {
        return $this->hasMany(\App\Models\donation::class);
    }

    public function donation()
    {
        return $this->hasMany(\App\Models\donation::class);
    }

    public function comments()
    {
        return $this->hasMany(\App\Models\comments::class);
    }

    public function replies()
    {
        return $this->hasMany(\App\Models\reply::class);
    }
}
