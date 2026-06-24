<?php

namespace App\Models;

use App\Domains\Restaurant\Enums\MemberRole;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'restaurant_id', 'role', 'status'])]
class RestaurantMember extends Model
{
    protected $connection = 'mysql_core';

    protected function casts(): array
    {
        return [
            'role' => MemberRole::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}
