<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('belt'); 
            $table->string('phone'); 
            $table->string('picture')->nullable(); 
            $table->year('year_of_registration'); 
            $table->string('status')->default('active');
            $table->text('notes')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->json('attendance')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('belt');
            $table->dropColumn('phone');
            $table->dropColumn('picture');
            $table->dropColumn('year_of_registration');
            $table->dropColumn('date_of_birth');
            $table->dropColumn('status');
            $table->dropColumn('notes');
            $table->dropColumn('attendance');
        });
    }
};
