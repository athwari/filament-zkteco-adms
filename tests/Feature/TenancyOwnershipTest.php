<?php

use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoAttendanceLogs\ZktecoAttendanceLogResource;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDeviceCommands\ZktecoDeviceCommandResource;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\ZktecoDeviceResource;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\ZktecoUserResource;
use Athwari\FilamentZktecoAdms\Models\ZktecoDevice;
use Athwari\FilamentZktecoAdms\Models\ZktecoUser;
use Athwari\FilamentZktecoAdms\Tests\Fixtures\Models\TestTeam;
use Athwari\FilamentZktecoAdms\Tests\Fixtures\Models\TestUser;
use Athwari\FilamentZktecoAdms\Traits\BelongsToTenant;
use Athwari\LaravelZktecoAdms\Enums\CommandStatus;
use Athwari\LaravelZktecoAdms\Enums\DeviceStatus;
use Athwari\LaravelZktecoAdms\Models\ZktecoAttendanceLog;
use Athwari\LaravelZktecoAdms\Models\ZktecoDeviceCommand;
use Filament\FilamentManager;
use Illuminate\Database\Eloquent\Model;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

function fakeFilamentTenant(TestTeam $tenant): void
{
    $filament = Mockery::mock(FilamentManager::class);
    $filament->shouldReceive('getTenant')->andReturn($tenant);

    app()->instance('filament', $filament);
}

function fakeFilamentWithoutTenant(): void
{
    $filament = Mockery::mock(FilamentManager::class);
    $filament->shouldReceive('getTenant')->andReturn(null);

    app()->instance('filament', $filament);
}

it('moves tenancy ownership to the filament plugin configuration', function () {
    expect(config('zkteco-adms.multi_tenancy'))->toBeNull()
        ->and(config('filament-zkteco-adms.multi_tenancy.enabled'))->toBeFalse()
        ->and(config('filament-zkteco-adms.multi_tenancy.tenant_relationship'))->toBe('team');
});

it('overrides the core tenant-aware models with plugin models', function () {
    expect(config('zkteco-adms.models.device'))->toBe(ZktecoDevice::class)
        ->and(config('zkteco-adms.models.user'))->toBe(ZktecoUser::class);
});

it('reads resource tenancy flags from the filament plugin config', function () {
    config()->set('filament-zkteco-adms.multi_tenancy.enabled', true);
    config()->set('filament-zkteco-adms.multi_tenancy.tenant_relationship', 'company');

    expect(ZktecoDeviceResource::isScopedToTenant())->toBeTrue()
        ->and(ZktecoUserResource::isScopedToTenant())->toBeTrue()
        ->and(ZktecoDeviceResource::getTenantRelationshipName())->toBe('company')
        ->and(ZktecoUserResource::getTenantRelationshipName())->toBe('company');
});

it('uses non-tenant defaults when plugin tenancy is disabled', function () {
    config()->set('filament-zkteco-adms.multi_tenancy.enabled', false);

    $multiTenancyConfig = config('filament-zkteco-adms.multi_tenancy');
    unset($multiTenancyConfig['tenant_relationship']);
    config()->set('filament-zkteco-adms.multi_tenancy', $multiTenancyConfig);

    expect(ZktecoDeviceResource::isScopedToTenant())->toBeFalse()
        ->and(ZktecoUserResource::isScopedToTenant())->toBeFalse()
        ->and(ZktecoDeviceResource::getTenantRelationshipName())->toBe('team')
        ->and(ZktecoUserResource::getTenantRelationshipName())->toBe('team');
});

it('assigns the active filament tenant to plugin-owned device and user models', function () {
    config()->set('filament-zkteco-adms.multi_tenancy.enabled', true);
    config()->set('filament-zkteco-adms.multi_tenancy.tenant_model', TestTeam::class);

    $tenant = TestTeam::query()->create(['name' => 'Team A']);
    $user = TestUser::query()->create(['name' => 'Admin']);

    fakeFilamentTenant($tenant);
    actingAs($user);

    $device = ZktecoDevice::query()->create([
        'serial_number' => 'TENANT-DEVICE-001',
    ]);

    $zktecoUser = ZktecoUser::query()->create([
        'pin' => '1001',
    ]);

    expect($device->team_id)->toBe($tenant->id)
        ->and($zktecoUser->team_id)->toBe($tenant->id);

    assertDatabaseHas($device->getTable(), [
        'id' => $device->id,
        'team_id' => $tenant->id,
    ]);

    assertDatabaseHas($zktecoUser->getTable(), [
        'id' => $zktecoUser->id,
        'team_id' => $tenant->id,
    ]);
});

it('does not override an explicitly provided tenant id when creating models', function () {
    config()->set('filament-zkteco-adms.multi_tenancy.enabled', true);
    config()->set('filament-zkteco-adms.multi_tenancy.tenant_model', TestTeam::class);

    $tenantA = TestTeam::query()->create(['name' => 'Team A']);
    $tenantB = TestTeam::query()->create(['name' => 'Team B']);
    $user = TestUser::query()->create(['name' => 'Admin']);

    fakeFilamentTenant($tenantA);
    actingAs($user);

    $device = ZktecoDevice::query()->create([
        'serial_number' => 'TENANT-DEVICE-EXPLICIT',
        'team_id' => $tenantB->id,
    ]);

    expect($device->team_id)->toBe($tenantB->id);
});

it('does not assign a tenant when unauthenticated even if filament has an active tenant', function () {
    config()->set('filament-zkteco-adms.multi_tenancy.enabled', true);
    config()->set('filament-zkteco-adms.multi_tenancy.tenant_model', TestTeam::class);

    $tenant = TestTeam::query()->create(['name' => 'Team A']);

    fakeFilamentTenant($tenant);

    $device = ZktecoDevice::query()->create([
        'serial_number' => 'TENANT-DEVICE-GUEST',
    ]);

    expect($device->team_id)->toBeNull();
});

it('scopes attendance log and command resources through the owning device tenant', function () {
    config()->set('filament-zkteco-adms.multi_tenancy.enabled', true);
    config()->set('filament-zkteco-adms.multi_tenancy.tenant_model', TestTeam::class);

    $tenantA = TestTeam::query()->create(['name' => 'Team A']);
    $tenantB = TestTeam::query()->create(['name' => 'Team B']);

    $deviceA = ZktecoDevice::query()->create([
        'serial_number' => 'TEAM-A-DEVICE',
        'team_id' => $tenantA->id,
    ]);

    $deviceB = ZktecoDevice::query()->create([
        'serial_number' => 'TEAM-B-DEVICE',
        'team_id' => $tenantB->id,
    ]);

    $teamALog = ZktecoAttendanceLog::query()->create([
        'device_id' => $deviceA->id,
        'pin' => '1001',
        'recorded_at' => now(),
        'status' => 0,
        'verify_mode' => 1,
        'work_code' => '',
    ]);

    ZktecoAttendanceLog::query()->create([
        'device_id' => $deviceB->id,
        'pin' => '2001',
        'recorded_at' => now(),
        'status' => 0,
        'verify_mode' => 1,
        'work_code' => '',
    ]);

    $teamACommand = ZktecoDeviceCommand::query()->create([
        'device_id' => $deviceA->id,
        'command_id' => 1,
        'command_type' => 'INFO',
        'command_content' => 'INFO',
        'status' => CommandStatus::Pending,
    ]);

    ZktecoDeviceCommand::query()->create([
        'device_id' => $deviceB->id,
        'command_id' => 2,
        'command_type' => 'INFO',
        'command_content' => 'INFO',
        'status' => CommandStatus::Pending,
    ]);

    fakeFilamentTenant($tenantA);

    expect(
        ZktecoAttendanceLogResource::getEloquentQuery()->pluck('id')->all()
    )->toBe([$teamALog->id]);

    expect(
        ZktecoDeviceCommandResource::getEloquentQuery()->pluck('id')->all()
    )->toBe([$teamACommand->id]);
});

it('returns unscoped attendance log and command queries when tenancy is disabled', function () {
    config()->set('filament-zkteco-adms.multi_tenancy.enabled', false);

    $deviceOne = ZktecoDevice::query()->create([
        'serial_number' => 'UNSCOPED-DEVICE-1',
    ]);

    $deviceTwo = ZktecoDevice::query()->create([
        'serial_number' => 'UNSCOPED-DEVICE-2',
    ]);

    $logOne = ZktecoAttendanceLog::query()->create([
        'device_id' => $deviceOne->id,
        'pin' => '1001',
        'recorded_at' => now(),
        'status' => 0,
        'verify_mode' => 1,
        'work_code' => '',
    ]);

    $logTwo = ZktecoAttendanceLog::query()->create([
        'device_id' => $deviceTwo->id,
        'pin' => '1002',
        'recorded_at' => now(),
        'status' => 0,
        'verify_mode' => 1,
        'work_code' => '',
    ]);

    $commandOne = ZktecoDeviceCommand::query()->create([
        'device_id' => $deviceOne->id,
        'command_id' => 101,
        'command_type' => 'INFO',
        'command_content' => 'INFO',
        'status' => CommandStatus::Pending,
    ]);

    $commandTwo = ZktecoDeviceCommand::query()->create([
        'device_id' => $deviceTwo->id,
        'command_id' => 102,
        'command_type' => 'INFO',
        'command_content' => 'INFO',
        'status' => CommandStatus::Pending,
    ]);

    expect(ZktecoAttendanceLogResource::getEloquentQuery()->pluck('id')->sort()->values()->all())
        ->toBe([$logOne->id, $logTwo->id]);

    expect(ZktecoDeviceCommandResource::getEloquentQuery()->pluck('id')->sort()->values()->all())
        ->toBe([$commandOne->id, $commandTwo->id]);
});

it('returns unscoped attendance log and command queries when tenancy is enabled but tenant is missing', function () {
    config()->set('filament-zkteco-adms.multi_tenancy.enabled', true);

    $deviceOne = ZktecoDevice::query()->create([
        'serial_number' => 'NULL-TENANT-DEVICE-1',
    ]);

    $deviceTwo = ZktecoDevice::query()->create([
        'serial_number' => 'NULL-TENANT-DEVICE-2',
    ]);

    $logOne = ZktecoAttendanceLog::query()->create([
        'device_id' => $deviceOne->id,
        'pin' => '1003',
        'recorded_at' => now(),
        'status' => 0,
        'verify_mode' => 1,
        'work_code' => '',
    ]);

    $logTwo = ZktecoAttendanceLog::query()->create([
        'device_id' => $deviceTwo->id,
        'pin' => '1004',
        'recorded_at' => now(),
        'status' => 0,
        'verify_mode' => 1,
        'work_code' => '',
    ]);

    $commandOne = ZktecoDeviceCommand::query()->create([
        'device_id' => $deviceOne->id,
        'command_id' => 103,
        'command_type' => 'INFO',
        'command_content' => 'INFO',
        'status' => CommandStatus::Pending,
    ]);

    $commandTwo = ZktecoDeviceCommand::query()->create([
        'device_id' => $deviceTwo->id,
        'command_id' => 104,
        'command_type' => 'INFO',
        'command_content' => 'INFO',
        'status' => CommandStatus::Pending,
    ]);

    fakeFilamentWithoutTenant();

    expect(ZktecoAttendanceLogResource::getEloquentQuery()->pluck('id')->sort()->values()->all())
        ->toBe([$logOne->id, $logTwo->id]);

    expect(ZktecoDeviceCommandResource::getEloquentQuery()->pluck('id')->sort()->values()->all())
        ->toBe([$commandOne->id, $commandTwo->id]);
});

it('allows recreating soft deleted devices with the same unique identifier', function () {
    $deviceSerialNumber = 'REUSE-DEVICE-001';
    $device = ZktecoDevice::query()->create([
        'serial_number' => $deviceSerialNumber,
        'status' => DeviceStatus::Online,
    ]);

    $device->delete();

    $replacementDevice = ZktecoDevice::query()->create([
        'serial_number' => $deviceSerialNumber,
        'status' => DeviceStatus::Offline,
    ]);

    expect($replacementDevice->id)->not->toBe($device->id)
        ->and(ZktecoDevice::withTrashed()->whereKey($device->id)->value('serial_number'))->not->toBe($deviceSerialNumber)
        ->and(ZktecoDevice::query()->where('serial_number', $deviceSerialNumber)->count())->toBe(1);
});

it('resolves tenant relation dynamically from plugin configuration', function () {
    config()->set('filament-zkteco-adms.multi_tenancy.enabled', false);
    config()->set('filament-zkteco-adms.multi_tenancy.tenant_model', TestTeam::class);
    config()->set('filament-zkteco-adms.multi_tenancy.tenant_relationship', 'company');

    $model = new class() extends Model
    {
        use BelongsToTenant;

        protected $table = 'zkteco_devices';
    };

    expect($model->company()->getForeignKeyName())->toBe('team_id')
        ->and($model->company()->getRelated()::class)->toBe(TestTeam::class)
        ->and($model->tenant()->getRelated()::class)->toBe(TestTeam::class);
});

it('falls back to parent model call for non-tenant dynamic methods', function () {
    config()->set('filament-zkteco-adms.multi_tenancy.tenant_relationship', 'company');

    $model = new class() extends Model
    {
        use BelongsToTenant;

        protected $table = 'zkteco_devices';
    };

    expect(fn () => $model->notATenantRelation())->toThrow(BadMethodCallException::class);
});
