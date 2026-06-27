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

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        config()->set('zkteco-adms.models.device', ZktecoDevice::class);
        config()->set('zkteco-adms.models.user', ZktecoUser::class);
    }
}
