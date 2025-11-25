<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Checking User with ID 5:\n";
$user = \App\Models\User::where('iduser', 5)->first();

if($user) {
    echo "Found!\n";
    echo "ID: " . $user->iduser . "\n";
    echo "Nama: " . $user->nama . "\n";
    echo "Email: " . $user->email . "\n";
} else {
    echo "User ID 5 not found!\n\n";
    echo "All users in database:\n";
    $users = \App\Models\User::all();
    foreach($users as $u) {
        echo "ID: " . $u->iduser . " - Nama: " . $u->nama . " - Email: " . $u->email . "\n";
    }
}

echo "\n\nChecking role_user table:\n";
$roleUsers = \App\Models\RoleUser::all();
foreach($roleUsers as $ru) {
    echo "ID: " . $ru->idrole_user . " - User ID: " . $ru->iduser . " - Role ID: " . $ru->idrole . "\n";
    $user = \App\Models\User::find($ru->iduser);
    if($user) {
        echo "  User: " . $user->nama . "\n";
    }
}

echo "\n\nChecking rekam_medis:\n";
$rekamMedis = \App\Models\RekamMedis::where('idrekam_medis', 1)->first();
if($rekamMedis) {
    echo "Rekam Medis ID 1:\n";
    echo "dokter_pemeriksa (role_user ID): " . $rekamMedis->dokter_pemeriksa . "\n";
    
    $roleUser = \App\Models\RoleUser::find($rekamMedis->dokter_pemeriksa);
    if($roleUser) {
        echo "RoleUser found - User ID: " . $roleUser->iduser . "\n";
        $dokter = \App\Models\User::find($roleUser->iduser);
        if($dokter) {
            echo "Dokter nama: " . $dokter->nama . "\n";
        }
    } else {
        echo "RoleUser ID 5 not found!\n";
    }
}
