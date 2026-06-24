<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_core';

    public function up(): void
    {
        Schema::connection($this->connection)->create('restaurant_branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->char('country_code', 2);
            $table->string('address_text');
            $table->string('label')->nullable();
            $table->decimal('lat', 10, 8);
            $table->decimal('lng', 11, 8);
            $table->boolean('is_active')->default(true);
            $table->time('opens_at');
            $table->time('closes_at');
            $table->boolean('accept_orders')->default(true);
            $table->unsignedSmallInteger('delivery_radius');
            $table->timestamps();

            $table->index(['lat', 'lng']);
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('restaurant_branches');
    }
};
