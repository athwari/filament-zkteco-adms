# Filament ZKteco ADMS

[![Latest Version on Packagist](https://img.shields.io/packagist/v/athwari/filament-zkteco-adms.svg?style=flat-square)](https://packagist.org/packages/athwari/filament-zkteco-adms)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/athwari/filament-zkteco-adms/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/athwari/filament-zkteco-adms/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/athwari/filament-zkteco-adms.svg?style=flat-square)](https://packagist.org/packages/athwari/filament-zkteco-adms)

This Filament v5 plugin provides the admin UI for ZKTeco ADMS and owns tenancy behavior for the ZKTeco domain.

It is built on top of athwari/laravel-zkteco-adms (core backend logic) and adds:

- Filament resources, pages, and widgets
- Tenant-aware model behavior for devices and users
- Tenant column migration ownership for zkteco devices and users tables
- Tenant-scoped resource queries for attendance logs and device commands

## Features

- Device, user, attendance log, and device command resources
- Dashboard and stats widgets
- Plugin-owned multi-tenancy settings via filament-zkteco-adms.multi_tenancy
- Plugin model overrides for zkteco-adms.models.device and zkteco-adms.models.user
- Automatic migration loading for tenant columns on zkteco devices and users tables

## Installation

Install the core package and this plugin:

```bash
composer require athwari/laravel-zkteco-adms athwari/filament-zkteco-adms
```

Register the plugin in your Filament panel provider:

```php
use Athwari\FilamentZktecoAdms\FilamentZktecoAdmsPlugin;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugin(FilamentZktecoAdmsPlugin::make());
}
```

## Configuration

Publish the plugin configuration file:

```bash
php artisan vendor:publish --tag="filament-zkteco-adms-config"
```

Main config keys in config/filament-zkteco-adms.php:

- multi_tenancy.enabled
- multi_tenancy.tenant_model
- multi_tenancy.tenant_column
- multi_tenancy.tenant_relationship
- filament.navigation_group
- filament.navigation_sort

Migrations are loaded automatically by the plugin service provider.

## Usage

After registration, resources and pages are discovered automatically. By default, resource slugs are:

- zkteco/devices
- zkteco/users
- zkteco/attendance-logs
- zkteco/device-commands

The final URL depends on your panel path prefix.

### Tenancy Ownership

Tenancy for the ZKTeco domain is owned by this plugin.

When multi_tenancy.enabled is true:

- ZktecoDevice and ZktecoUser models are auto-assigned to the active Filament tenant on create
- Attendance log and device command resources are scoped through each record's related device tenant
- Core config keys zkteco-adms.models.device and zkteco-adms.models.user are overridden to plugin models

## Testing

Run the test suite with:

```bash
composer test
```

Additional useful scripts:

```bash
composer test-coverage
composer analyse
composer format
```

## Architecture Boundary

- athwari/laravel-zkteco-adms: protocol, parsing, command and persistence core
- athwari/filament-zkteco-adms: Filament UI and tenancy ownership

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

Please review [SECURITY](SECURITY.md) if you discover a vulnerability in this package.

## Credits

- [Athwari](https://github.com/athwari)
- [All Contributors](https://github.com/athwari/filament-zkteco-adms/graphs/contributors)

## License

The MIT License (MIT). Please see [LICENSE.md](LICENSE.md) for more information.
