<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class postsImages extends Model
{
    use HasFactory;

    protected $fillable = [
        "image",
        "posts_id"
    ];

    public function post()
    {
        return $this->belongsTo(\App\Models\posts::class);
    }
}
