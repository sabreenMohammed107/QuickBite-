<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected string $connection = 'mysql_core';

    public function up(): void
    {
        $db = DB::connection($this->connection);

        // ── restaurants.name ──────────────────────────────────────────────
        $db->statement("ALTER TABLE restaurants ADD COLUMN name_i18n JSON NULL");
        $db->statement("UPDATE restaurants SET name_i18n = JSON_OBJECT('en', `name`, 'ar', `name`)");
        $db->statement("ALTER TABLE restaurants DROP COLUMN `name`");
        $db->statement("ALTER TABLE restaurants CHANGE COLUMN `name_i18n` `name` JSON NOT NULL");

        // ── products.name ─────────────────────────────────────────────────
        $db->statement("ALTER TABLE products ADD COLUMN name_i18n JSON NULL");
        $db->statement("UPDATE products SET name_i18n = JSON_OBJECT('en', `name`, 'ar', `name`)");
        $db->statement("ALTER TABLE products DROP COLUMN `name`");
        $db->statement("ALTER TABLE products CHANGE COLUMN `name_i18n` `name` JSON NOT NULL");

        // ── products.description ──────────────────────────────────────────
        $db->statement("ALTER TABLE products ADD COLUMN description_i18n JSON NULL");
        $db->statement("UPDATE products SET description_i18n = JSON_OBJECT('en', IFNULL(`description`, ''), 'ar', IFNULL(`description`, '')) WHERE `description` IS NOT NULL");
        $db->statement("ALTER TABLE products DROP COLUMN `description`");
        $db->statement("ALTER TABLE products CHANGE COLUMN `description_i18n` `description` JSON NULL");

        // ── restaurant_branches.label ─────────────────────────────────────
        $db->statement("ALTER TABLE restaurant_branches ADD COLUMN label_i18n JSON NULL");
        $db->statement("UPDATE restaurant_branches SET label_i18n = JSON_OBJECT('en', IFNULL(`label`, ''), 'ar', IFNULL(`label`, '')) WHERE `label` IS NOT NULL");
        $db->statement("ALTER TABLE restaurant_branches CHANGE COLUMN `label_i18n` `label` JSON NULL");

        // ── restaurant_branches.address_text ──────────────────────────────
        $db->statement("ALTER TABLE restaurant_branches ADD COLUMN address_i18n JSON NULL");
        $db->statement("UPDATE restaurant_branches SET address_i18n = JSON_OBJECT('en', `address_text`, 'ar', `address_text`)");
        $db->statement("ALTER TABLE restaurant_branches DROP COLUMN `address_text`");
        $db->statement("ALTER TABLE restaurant_branches CHANGE COLUMN `address_i18n` `address_text` JSON NOT NULL");
    }

    public function down(): void
    {
        $db = DB::connection($this->connection);

        // Restore restaurants.name
        $db->statement("ALTER TABLE restaurants ADD COLUMN name_bak VARCHAR(255) NULL");
        $db->statement("UPDATE restaurants SET name_bak = IFNULL(JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.en')), '')");
        $db->statement("ALTER TABLE restaurants DROP COLUMN `name`");
        $db->statement("ALTER TABLE restaurants CHANGE COLUMN `name_bak` `name` VARCHAR(255) NOT NULL DEFAULT ''");

        // Restore products.name
        $db->statement("ALTER TABLE products ADD COLUMN name_bak VARCHAR(255) NULL");
        $db->statement("UPDATE products SET name_bak = IFNULL(JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.en')), '')");
        $db->statement("ALTER TABLE products DROP COLUMN `name`");
        $db->statement("ALTER TABLE products CHANGE COLUMN `name_bak` `name` VARCHAR(255) NOT NULL DEFAULT ''");

        // Restore products.description
        $db->statement("ALTER TABLE products ADD COLUMN description_bak TEXT NULL");
        $db->statement("UPDATE products SET description_bak = JSON_UNQUOTE(JSON_EXTRACT(`description`, '$.en')) WHERE `description` IS NOT NULL");
        $db->statement("ALTER TABLE products DROP COLUMN `description`");
        $db->statement("ALTER TABLE products CHANGE COLUMN `description_bak` `description` TEXT NULL");

        // Restore restaurant_branches.label
        $db->statement("ALTER TABLE restaurant_branches ADD COLUMN label_bak VARCHAR(255) NULL");
        $db->statement("UPDATE restaurant_branches SET label_bak = JSON_UNQUOTE(JSON_EXTRACT(`label`, '$.en')) WHERE `label` IS NOT NULL");
        $db->statement("ALTER TABLE restaurant_branches DROP COLUMN `label`");
        $db->statement("ALTER TABLE restaurant_branches CHANGE COLUMN `label_bak` `label` VARCHAR(255) NULL");

        // Restore restaurant_branches.address_text
        $db->statement("ALTER TABLE restaurant_branches ADD COLUMN address_bak VARCHAR(500) NULL");
        $db->statement("UPDATE restaurant_branches SET address_bak = IFNULL(JSON_UNQUOTE(JSON_EXTRACT(`address_text`, '$.en')), '')");
        $db->statement("ALTER TABLE restaurant_branches DROP COLUMN `address_text`");
        $db->statement("ALTER TABLE restaurant_branches CHANGE COLUMN `address_bak` `address_text` VARCHAR(500) NOT NULL DEFAULT ''");
    }
};
