<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'status', 'logo_url', 'primary_country'])]
class Restaurant extends Model
{
    use HasTranslations;

    protected $connection = 'mysql_core';

    protected function casts(): array
    {
        return [
            'name' => 'array',
        ];
    }

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
