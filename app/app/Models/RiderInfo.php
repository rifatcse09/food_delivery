<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiderInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','lat','long','capture_time'
    ];
}
