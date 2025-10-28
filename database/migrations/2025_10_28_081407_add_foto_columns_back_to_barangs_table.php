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
        Schema::table('barangs', function (Blueprint $table) {
            // Add foto1, foto2, foto3 columns if they don't exist
            if (!Schema::hasColumn('barangs', 'foto1')) {
                $table->string('foto1')->nullable()->after('kondisi');
            }
            if (!Schema::hasColumn('barangs', 'foto2')) {
                $table->string('foto2')->nullable()->after('foto1');
            }
            if (!Schema::hasColumn('barangs', 'foto3')) {
                $table->string('foto3')->nullable()->after('foto2');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['foto1', 'foto2', 'foto3']);
        });
    }
};
