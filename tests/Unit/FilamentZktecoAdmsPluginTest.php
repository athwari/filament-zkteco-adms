<?php

use Athwari\FilamentZktecoAdms\FilamentZktecoAdmsPlugin;
use Athwari\FilamentZktecoAdms\FilamentZktecoAdmsServiceProvider;
use Filament\Panel;
use Illuminate\Support\ServiceProvider;

it('exposes the plugin id and resolves make from the container', function () {
    $plugin = new FilamentZktecoAdmsPlugin();

    app()->instance(FilamentZktecoAdmsPlugin::class, $plugin);

    expect($plugin->getId())->toBe('athwari-filament-zkteco-adms')
        ->and(FilamentZktecoAdmsPlugin::make())->toBe($plugin);
});

it('registers resources pages and widgets on the panel', function () {
    $panel = Mockery::mock(Panel::class);

    $panel->shouldReceive('discoverResources')
        ->once()
        ->withArgs(fn (string $in, string $for): bool => str_ends_with($in, '/src/Filament/Resources') && $for === 'Athwari\\FilamentZktecoAdms\\Filament\\Resources')
        ->andReturnSelf();

    $panel->shouldReceive('discoverPages')
        ->once()
        ->withArgs(fn (string $in, string $for): bool => str_ends_with($in, '/src/Filament/Pages') && $for === 'Athwari\\FilamentZktecoAdms\\Filament\\Pages')
        ->andReturnSelf();

    $panel->shouldReceive('discoverWidgets')
        ->once()
        ->withArgs(fn (string $in, string $for): bool => str_ends_with($in, '/src/Filament/Widgets') && $for === 'Athwari\\FilamentZktecoAdms\\Filament\\Widgets')
        ->andReturnSelf();

    $plugin = new FilamentZktecoAdmsPlugin();
    $plugin->register($panel);
    $plugin->boot($panel);

    expect(true)->toBeTrue();
});

it('publishes a timestamped tenant migration stub', function () {
    $paths = ServiceProvider::pathsToPublish(
        FilamentZktecoAdmsServiceProvider::class,
        'filament-zkteco-adms-migrations',
    );

    expect($paths)->toHaveCount(1)
        ->and(array_key_first($paths))->toEndWith('database/migrations/add_tenant_columns_to_zkteco_tables.php.stub')
        ->and(array_values($paths)[0])->toMatch('/\/database\/migrations\/\d{4}_\d{2}_\d{2}_\d{6}_add_tenant_columns_to_zkteco_tables\.php$/');
});
