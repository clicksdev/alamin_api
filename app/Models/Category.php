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
        "svg_icon",
        "cover_path",
        "thumbnail_path",
        'title_ar',
        'description_ar',
    ];

    public $table = "categories";

    /**
     * Get all of the events for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_category', 'category_id', 'event_id');
    }

}
