<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class comments extends Model
{
    use HasFactory;

    protected $table = "comments";

    protected $fillable = [
        "user_id",
        "posts_id",
        "comment"
    ];


    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function post()
    {
        return $this->belongsTo(\App\Models\posts::class);
    }

    public function replies()
    {
        return $this->hasMany(\App\Models\reply::class);
    }


}
