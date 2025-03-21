<?php

use App\Enums\PhosphorIconWeightsEnum;
use Filament\Enums\ThemeMode;

return [
    'icon_style' => PhosphorIconWeightsEnum::Regular,
    'theme_mode' => ThemeMode::Light,
    'phosphoricon_enabled' => true,
    'public_panel_enabled' => true,
    'admin_panel_enabled' => true,
    'app_panel_enabled' => true,
    'favicon' => [
        'enabled' => true,
        'manifest' => [
            'name' => 'FilaKit',
            'icons' => [
                '36' => '0.75',
                '48' => '1.0',
                '72' => '1.5',
                '96' => '2.0',
                '144' => '3.0',
                '192' => '4.0',
            ],
        ],
        'logo' => 'resources/images/logo-filakit.png',
        'favicon' => 'resources/favicon/favicon.ico',
    ],
];
