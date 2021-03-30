<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mixtuers extends Model
{
    use HasFactory;

    protected $table = "mixtuers";

    protected $fillable = [
        "user_id",
        "mx_date",
        "mx_time"
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
