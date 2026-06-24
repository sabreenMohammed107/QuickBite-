<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_core';

    public function up(): void
    {
        Schema::connection($this->connection)->create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('status', ['active', 'inactive', 'pending_review'])->default('pending_review');
            $table->string('logo_url')->nullable();
            $table->char('primary_country', 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('restaurants');
    }
};
