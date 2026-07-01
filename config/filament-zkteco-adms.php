<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Device Timezones
    |--------------------------------------------------------------------------
    |
    | Restrict the timezones available in the device form. Use an indexed list
    | to display identifiers as-is, or an associative array for custom labels.
    | Leave this empty to use every timezone supported by the PHP runtime.
    |
    */
    'timezone_options' => [],

    /*
    |--------------------------------------------------------------------------
    | Multi-Tenancy
    |--------------------------------------------------------------------------
    |
    | Filament-specific tenant ownership for the ZKTeco admin plugin.
    |
    */
    'multi_tenancy' => [
        'enabled' => false,
        'tenant_model' => 'App\Models\Team',
        'tenant_column' => 'team_id',
        'tenant_relationship' => 'team',
    ],

    /*
    |--------------------------------------------------------------------------
    | Filament Navigation
    |--------------------------------------------------------------------------
    */
    'filament' => [
        'navigation_group' => 'ZKTeco ADMS',
        'navigation_sort' => 50,
    ],
];
