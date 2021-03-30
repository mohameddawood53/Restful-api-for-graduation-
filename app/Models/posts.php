<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class posts extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "body",
        "user_id",
        "desc"
    ];

    protected $primaryKey = "id";

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function image()
    {
        return $this->hasMany(\App\Models\postsImages::class);
    }

    public function comments()
    {
        return $this->hasMany(\App\Models\comments::class);
    }
}
