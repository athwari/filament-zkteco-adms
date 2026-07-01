# Changelog

All notable changes to `filament-zkteco-adms` will be documented in this file.

## Unreleased

### Changed

* Use normalized `occurred_at` timestamps for attendance reporting, sorting, filtering, and dashboard statistics.
* Keep `recorded_at` visible as the original device-local timestamp.
* Replace automatic migration loading with the publishable `filament-zkteco-adms-migrations` migration stub.
* Generate the tenant migration timestamp when it is published.
* Support `.php.stub` migrations from both ZKTeco packages in the test bootstrap.
* Add a configurable, searchable device timezone selector with PHP runtime timezone fallback.
* Require `athwari/laravel-zkteco-adms` v1.0.1 or newer for normalized timestamps and migration stubs.

## v1.0.0 - 2026-06-21

### What's Changed

* Initial release

**Full Changelog**: https://github.com/athwari/filament-zkteco-adms/commits/v1.0.0
