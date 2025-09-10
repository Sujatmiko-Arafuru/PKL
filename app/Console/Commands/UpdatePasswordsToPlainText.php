<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\OrmawaJurusan;

class UpdatePasswordsToPlainText extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'passwords:update-to-plain-text';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing ormawa and jurusan passwords to plain text';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating ormawa and jurusan passwords to plain text...');
        
        // Get all ormawa and jurusan accounts
        $accounts = OrmawaJurusan::whereIn('tipe', ['ormawa', 'jurusan'])->get();
        
        $updated = 0;
        foreach ($accounts as $account) {
            // Update password to plain text
            DB::table('ormawa_jurusan')
                ->where('id', $account->id)
                ->update(['password' => 'password123']);
            
            $updated++;
            $this->line("Updated: {$account->nama} ({$account->tipe})");
        }
        
        $this->info("Successfully updated {$updated} accounts to use plain text passwords.");
        $this->info('All ormawa and jurusan accounts now use password: password123');
    }
}
