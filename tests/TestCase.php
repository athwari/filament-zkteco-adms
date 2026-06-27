<?php

declare(strict_types=1);

namespace Athwari\FilamentZktecoAdms\Tests;

use Athwari\FilamentZktecoAdms\FilamentZktecoAdmsServiceProvider;
use Athwari\FilamentZktecoAdms\Tests\Fixtures\Models\TestUser;
use Athwari\LaravelZktecoAdms\LaravelZktecoAdmsServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Schema;
use Illuminate\Testing\TestResponse;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected static ?TestResponse $latestResponse = null;

    protected function setUp(): void
    {
        parent::setUp();

        $corePackageMigrationsPath = __DIR__.'/../../laravel-zkteco-adms/database/migrations';

        // In standalone package CI, the core package exists under vendor instead of as a sibling directory.
        if (! is_dir($corePackageMigrationsPath)) {
            $corePackageMigrationsPath = __DIR__.'/../vendor/athwari/laravel-zkteco-adms/database/migrations';
        }

        $this->loadMigrationsFrom($corePackageMigrationsPath);
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        Schema::create('teams', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
        });
    }

    /**
     * @param  Application  $app
     */
    protected function getPackageProviders($app): array
    {
        return [
            LaravelZktecoAdmsServiceProvider::class,
            FilamentZktecoAdmsServiceProvider::class,
        ];
    }

    /**
     * @param  Application  $app
     */
    public function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('filament-zkteco-adms.multi_tenancy.enabled', false);
        $app['config']->set('filament-zkteco-adms.multi_tenancy.tenant_model', 'App\\Models\\Team');
        $app['config']->set('filament-zkteco-adms.multi_tenancy.tenant_column', 'team_id');
        $app['config']->set('filament-zkteco-adms.multi_tenancy.tenant_relationship', 'team');
        $app['config']->set('zkteco-adms.user_model', TestUser::class);
        $app['config']->set('zkteco-adms.events.dispatch_device_event', false);
    }
}
