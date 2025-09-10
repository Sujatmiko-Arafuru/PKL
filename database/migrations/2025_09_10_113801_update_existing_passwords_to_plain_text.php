<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing ormawa and jurusan accounts to use plain text passwords
        // Reset all ormawa and jurusan passwords to a default value
        DB::table('ormawa_jurusan')
            ->whereIn('tipe', ['ormawa', 'jurusan'])
            ->update(['password' => 'password123']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hash the passwords back (though this is not recommended)
        // This is just for rollback purposes
        $accounts = DB::table('ormawa_jurusan')
            ->whereIn('tipe', ['ormawa', 'jurusan'])
            ->get();

        foreach ($accounts as $account) {
            DB::table('ormawa_jurusan')
                ->where('id', $account->id)
                ->update(['password' => bcrypt('password123')]);
        }
    }
};
