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
        Schema::table('detail_peminjaman_ruangans', function (Blueprint $table) {
            // Remove jumlah columns since rooms are now booked as whole units
            $table->dropColumn(['jumlah', 'jumlah_dikembalikan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_peminjaman_ruangans', function (Blueprint $table) {
            // Add back jumlah columns
            $table->integer('jumlah')->default(1);
            $table->integer('jumlah_dikembalikan')->default(0);
        });
    }
};
