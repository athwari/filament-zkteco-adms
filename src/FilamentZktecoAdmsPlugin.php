<?php

namespace Athwari\FilamentZktecoAdms;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentZktecoAdmsPlugin implements Plugin
{
    public function getId(): string
    {
        return 'athwari-filament-zkteco-adms';
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function register(Panel $panel): void
    {
        $panel
            ->discoverResources(
                in: __DIR__.'/Filament/Resources',
                for: 'Athwari\\FilamentZktecoAdms\\Filament\\Resources'
            )
            ->discoverPages(
                in: __DIR__.'/Filament/Pages',
                for: 'Athwari\\FilamentZktecoAdms\\Filament\\Pages'
            )
            ->discoverWidgets(
                in: __DIR__.'/Filament/Widgets',
                for: 'Athwari\\FilamentZktecoAdms\\Filament\\Widgets'
            );
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
