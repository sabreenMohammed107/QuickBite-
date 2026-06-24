<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_core';

    public function up(): void
    {
        Schema::connection($this->connection)->create('restaurant_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['owner', 'manager', 'cashier', 'staff']);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->json('permissions')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'restaurant_id']);
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('restaurant_members');
    }
};
