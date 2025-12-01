<?php

$resources = [
    'Cities/CityResource.php',
    'Countries/CountryResource.php',
    'Sections/SectionResource.php',
    'SiteSettings/SiteSettingResource.php',
    'States/StateResource.php',
    'Users/UserResource.php',
];

foreach ($resources as $resourcePath) {
    $fullPath = __DIR__ . '/app/Filament/Resources/' . $resourcePath;

    if (!file_exists($fullPath)) {
        echo "File not found: $fullPath\n";
        continue;
    }

    $content = file_get_contents($fullPath);

    // Check if trait is already added
    if (strpos($content, 'HasPageShield') !== false) {
        echo "Trait already added to $resourcePath\n";
        continue;
    }

    // Add import
    $content = str_replace(
        'use Filament\Resources\Resource;',
        'use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Resources\Resource;',
        $content
    );

    // Add trait to class
    $content = preg_replace(
        '/(class \w+ extends Resource\n\{)/',
        '$1
    use HasPageShield;',
        $content
    );

    file_put_contents($fullPath, $content);
    echo "Added HasPageShield trait to $resourcePath\n";
}

echo "Done!\n";
