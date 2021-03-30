<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class donationImages extends Model
{
    use HasFactory;
    protected $table = "donation_images";
    protected $fillable = [
        "image",
        "donation_id"
    ];




    public function donation()
    {
        return $this->belongsTo(\App\Models\donation::class);
    }
}
