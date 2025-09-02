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
        Schema::table('ruangans', function (Blueprint $table) {
            // Remove kapasitas column since rooms are now booked as whole units
            $table->dropColumn('kapasitas');
            
            // Update status enum to include new statuses
            $table->enum('status', ['tersedia', 'maintenance', 'dipinjam'])->default('tersedia')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ruangans', function (Blueprint $table) {
            // Add back kapasitas column
            $table->integer('kapasitas')->default(0);
            
            // Revert status enum
            $table->enum('status', ['tersedia', 'tidak tersedia'])->default('tersedia')->change();
        });
    }
};
