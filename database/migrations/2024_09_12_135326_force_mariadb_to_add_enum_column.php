<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `users` MODIFY `belt` ENUM('blanche', 'grise', 'jaune', 'orange', 'verte', 'bleu', 'violette', 'marron', 'noire') DEFAULT 'blanche';");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `users` MODIFY `belt` VARCHAR(255) DEFAULT 'blanche';");
    }
};
