<?php

use Athwari\FilamentZktecoAdms\FilamentZktecoAdmsPlugin;
use Filament\Panel;

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
