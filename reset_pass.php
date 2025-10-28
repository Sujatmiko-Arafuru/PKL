<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$akun = App\Models\OrmawaJurusan::find(4);
$akun->update(['password' => 'password123']);
echo "Password reset to password123\n";

