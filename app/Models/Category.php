<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        "title",
        "description",
        "cover_path",
        "thumbnail_path",
    ];

    public $table = "categories";

}
