<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['restaurant_id', 'name', 'description', 'image_url'])]
class Product extends Model
{
    use HasTranslations;

    protected $connection = 'mysql_core';

    protected function casts(): array
    {
        return [
            'name'        => 'array',
            'description' => 'array',
        ];
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function branchDetails(): HasMany
    {
        return $this->hasMany(ProductBranchDetail::class);
    }
}
