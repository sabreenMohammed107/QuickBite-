<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['restaurant_id', 'country_code', 'address_text', 'label', 'lat', 'lng', 'is_active', 'opens_at', 'closes_at', 'accept_orders', 'delivery_radius'])]
class RestaurantBranch extends Model
{
    protected $connection = 'mysql_core';

    protected function casts(): array
    {
        return [
            'lat' => 'float',
            'lng' => 'float',
            'is_active' => 'boolean',
            'accept_orders' => 'boolean',
        ];
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function productDetails(): HasMany
    {
        return $this->hasMany(ProductBranchDetail::class, 'branch_id');
    }
}
