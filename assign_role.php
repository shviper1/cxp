<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = \App\Models\User::first();
if ($user) {
    $user->assignRole('super_admin');
    echo 'Assigned super_admin role to ' . $user->email . "\n";

    // Check if user has permissions
    echo 'User has ViewAny:Post permission: ' . ($user->can('ViewAny:Post') ? 'YES' : 'NO') . "\n";
    echo 'User has Create:Post permission: ' . ($user->can('Create:Post') ? 'YES' : 'NO') . "\n";
} else {
    echo 'No user found' . "\n";
}
