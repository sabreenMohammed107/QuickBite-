<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'status', 'logo_url', 'primary_country'])]
class Restaurant extends Model
{
    protected $connection = 'mysql_core';

    public function branches(): HasMany
    {
        return $this->hasMany(RestaurantBranch::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(RestaurantMember::class);
    }
}
