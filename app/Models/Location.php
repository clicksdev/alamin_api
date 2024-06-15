<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $fillable = [
        "title",
        "sub_title",
        "thumbnail_path",
        "url",
        'title_ar',
        'sub_title_ar',
    ];

    public $table = "locations";

    /**
     * The roles that belong to the Location
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function events()
    {
        return $this->hasMany(Event::class, "location_id");
    }
    public function restaurants()
    {
        return $this->hasMany(Restaurant::class, "location_id");
    }
}
