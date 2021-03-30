<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class reply extends Model
{
    use HasFactory;
    protected $table = "replies";

    protected $fillable = [
        "comments_id",
        "user_id",
        "comment"
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function comment()
    {
        return $this->belongsTo(\App\Models\comments::class);
    }
}
