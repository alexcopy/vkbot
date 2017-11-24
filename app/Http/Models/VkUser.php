<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VkUser extends Model
{

    protected $fillable = [
        "first_name",
        "last_name",
        "screen_name",
        "sex",
        "bdate",
        "home_town",
        "country_id",
        "country_title",
        "city_id",
        "city_title"
    ];
}
