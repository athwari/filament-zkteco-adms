<?php

declare(strict_types=1);

namespace Athwari\FilamentZktecoAdms;

use Athwari\FilamentZktecoAdms\Models\ZktecoDevice;
use Athwari\FilamentZktecoAdms\Models\ZktecoUser;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentZktecoAdmsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-zkteco-adms')
            ->hasConfigFile('filament-zkteco-adms')
            ->hasTranslations()
            ->hasViews();
    }

    public function packageBooted(): void
    {
        parent::packageBooted();

        if ($this->app->runningInConsole()) {
            $migration = 'add_tenant_columns_to_zkteco_tables.php';
            // The core package reserves the current timestamp plus five seconds for its six migrations.
            $timestamp = time() + 6;

            $this->publishes([
                __DIR__.'/../database/migrations/'.$migration.'.stub' => database_path(
                    'migrations/'.date('Y_m_d_His', $timestamp).'_'.str_replace('.php', '', $migration).'.php'
                ),
            ], 'filament-zkteco-adms-migrations');
        }

        config()->set('zkteco-adms.models.device', ZktecoDevice::class);
        config()->set('zkteco-adms.models.user', ZktecoUser::class);
    }
}
