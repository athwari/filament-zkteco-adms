<?php

return [
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
