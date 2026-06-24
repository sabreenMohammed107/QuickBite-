<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_core';

    public function up(): void
    {
        // Expand the role enum to include 'staff'
        DB::connection($this->connection)->statement(
            "ALTER TABLE restaurant_members MODIFY COLUMN role ENUM('owner','manager','cashier','staff') NOT NULL"
        );

        Schema::connection($this->connection)->table('restaurant_members', function (Blueprint $table) {
            $table->json('permissions')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->table('restaurant_members', function (Blueprint $table) {
            $table->dropColumn('permissions');
        });

        DB::connection($this->connection)->statement(
            "ALTER TABLE restaurant_members MODIFY COLUMN role ENUM('owner','manager','cashier') NOT NULL"
        );
    }
};
