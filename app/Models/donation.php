<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class donation extends Model
{
    use HasFactory;

    protected $table = "donations";

    protected $fillable = [
        "amount",
        "title",
        "users_id",
        "desc"

    ];

    public function images()
    {
        return $this->hasMany(\App\Models\donationImages::class);
    }
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

}
