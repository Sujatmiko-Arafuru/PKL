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
            $table->boolean('sudah_dikembalikan')->default(false)->after('ruangan_id');
            $table->timestamp('tanggal_dikembalikan')->nullable()->after('sudah_dikembalikan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_peminjaman_ruangans', function (Blueprint $table) {
            $table->dropColumn(['sudah_dikembalikan', 'tanggal_dikembalikan']);
        });
    }
};
