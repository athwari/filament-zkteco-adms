<?php

declare(strict_types=1);

use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\Schemas\ZktecoDeviceForm;
use Athwari\FilamentZktecoAdms\Support\TimezoneOptions;
use Filament\Forms\Components\Select;

it('uses all PHP runtime timezones when configuration is empty', function () {
    config()->set('filament-zkteco-adms.timezone_options', []);

    $options = TimezoneOptions::configured();

    expect($options)
        ->toBe(TimezoneOptions::all())
        ->toHaveKeys(['UTC', 'Asia/Aden', 'America/New_York', 'US/Eastern']);
});

it('normalizes indexed and associative timezone options', function () {
    config()->set('filament-zkteco-adms.timezone_options', [
        'UTC',
        'Asia/Aden' => 'Yemen',
        'Mars/Phobos',
        'Invalid/Zone' => 'Invalid',
    ]);

    expect(TimezoneOptions::configured())->toBe([
        'UTC' => 'UTC',
        'Asia/Aden' => 'Yemen',
    ]);
});

it('falls back to runtime timezones when no configured option is valid', function () {
    config()->set('filament-zkteco-adms.timezone_options', ['Mars/Phobos']);

    expect(TimezoneOptions::configured())->toBe(TimezoneOptions::all());
});

it('chooses the configured default then UTC then the first available option', function () {
    config()->set('filament-zkteco-adms.timezone_options', [
        'UTC' => 'Universal',
        'Asia/Aden' => 'Yemen',
    ]);
    config()->set('zkteco-adms.default_timezone', 'Asia/Aden');

    expect(TimezoneOptions::default())->toBe('Asia/Aden');

    config()->set('zkteco-adms.default_timezone', 'Europe/London');

    expect(TimezoneOptions::default())->toBe('UTC');

    config()->set('filament-zkteco-adms.timezone_options', [
        'Asia/Aden' => 'Yemen',
        'Europe/Istanbul' => 'Türkiye',
    ]);

    expect(TimezoneOptions::default())->toBe('Asia/Aden');
});

it('configures a required searchable preloaded timezone select', function () {
    config()->set('filament-zkteco-adms.timezone_options', ['UTC', 'Asia/Aden']);
    config()->set('zkteco-adms.default_timezone', 'Asia/Aden');

    $method = new ReflectionMethod(ZktecoDeviceForm::class, 'timezoneSelect');
    $timezone = $method->invoke(null);

    expect($timezone)->toBeInstanceOf(Select::class)
        ->and($timezone->getOptions())->toBe([
            'UTC' => 'UTC',
            'Asia/Aden' => 'Asia/Aden',
        ])
        ->and($timezone->getDefaultState())->toBe('Asia/Aden')
        ->and($timezone->isSearchable())->toBeTrue()
        ->and($timezone->isPreloaded())->toBeTrue()
        ->and($timezone->isRequired())->toBeTrue();
});
