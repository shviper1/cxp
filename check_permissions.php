<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$permissions = \Spatie\Permission\Models\Permission::all();

echo "Total permissions: " . $permissions->count() . "\n\n";

echo "First 20 permissions:\n";
foreach ($permissions->take(20) as $permission) {
    echo "- " . $permission->name . "\n";
}

$roles = \Spatie\Permission\Models\Role::all();
echo "\nRoles:\n";
foreach ($roles as $role) {
    echo "- " . $role->name . "\n";
}
