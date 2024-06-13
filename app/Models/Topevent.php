<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topevent extends Model
{
    use HasFactory;
    protected $fillable = [
        "item_id",
        "sort",
        "type"
    ];

    public $timestamps = false;
}
