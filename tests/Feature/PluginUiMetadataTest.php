<?php

use Athwari\FilamentZktecoAdms\Filament\Pages\ZktecoDashboard;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoAttendanceLogs\Pages\ListZktecoAttendanceLogs;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoAttendanceLogs\Widgets\ZktecoAttendanceLogStats;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoAttendanceLogs\ZktecoAttendanceLogResource;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDeviceCommands\Pages\ListZktecoDeviceCommands;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDeviceCommands\Widgets\ZktecoDeviceCommandStats;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDeviceCommands\ZktecoDeviceCommandResource;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\Pages\ListZktecoDevices;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\RelationManagers\AttendanceLogsRelationManager as DeviceAttendanceLogsRelationManager;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\RelationManagers\DeviceCommandsRelationManager;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\Widgets\ZktecoDeviceStats;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\ZktecoDeviceResource;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\Pages\ListZktecoUsers;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\RelationManagers\AttendanceLogsRelationManager as UserAttendanceLogsRelationManager;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\Widgets\ZktecoUserStats;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\ZktecoUserResource;
use Athwari\FilamentZktecoAdms\Filament\Widgets\ZktecoOverviewStats;
use Athwari\FilamentZktecoAdms\Models\ZktecoDevice;
use Athwari\FilamentZktecoAdms\Tests\Fixtures\Models\TestTeam;
use Athwari\LaravelZktecoAdms\Enums\CommandStatus;
use Athwari\LaravelZktecoAdms\Enums\DeviceStatus;
use Athwari\LaravelZktecoAdms\Models\ZktecoAttendanceLog;
use Athwari\LaravelZktecoAdms\Models\ZktecoDeviceCommand;
use Filament\FilamentManager;
use Filament\Panel;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\Column;
use Filament\Tables\Table;

function fakeUiFilamentTenant(TestTeam $tenant): void
{
    $filament = Mockery::mock(FilamentManager::class);
    $filament->shouldReceive('getTenant')->andReturn($tenant);

    app()->instance('filament', $filament);
}

function fakeUiFilamentWithoutTenant(): void
{
    $filament = Mockery::mock(FilamentManager::class);
    $panel = Mockery::mock(Panel::class);

    $panel->shouldReceive('hasTenancy')->andReturn(false);

    $filament->shouldReceive('getTenant')->andReturn(null);
    $filament->shouldReceive('getCurrentOrDefaultPanel')->andReturn($panel);

    app()->instance('filament', $filament);
}

it('exposes dashboard and resource metadata', function () {
    $dashboard = new DashboardProxy();

    expect(ZktecoDashboard::getNavigationGroup())->toBe('ZKTeco ADMS')
        ->and(ZktecoDashboard::getNavigationLabel())->toBe('ZKTeco Dashboard')
        ->and($dashboard->getTitle())->toBe('ZKTeco Dashboard')
        ->and($dashboard->exposedHeaderWidgets())->toBe([ZktecoOverviewStats::class])
        ->and(ZktecoDeviceResource::getRelations())->toBe([
            DeviceAttendanceLogsRelationManager::class,
            DeviceCommandsRelationManager::class,
        ])
        ->and(ZktecoDeviceResource::getWidgets())->toBe([ZktecoDeviceStats::class])
        ->and(array_keys(ZktecoDeviceResource::getPages()))->toBe(['index', 'create', 'edit', 'view'])
        ->and(ZktecoUserResource::getWidgets())->toBe([ZktecoUserStats::class])
        ->and(ZktecoUserResource::getRelations())->toBe([UserAttendanceLogsRelationManager::class])
        ->and(array_keys(ZktecoUserResource::getPages()))->toBe(['index', 'create', 'view', 'edit'])
        ->and(ZktecoAttendanceLogResource::canCreate())->toBeFalse()
        ->and(ZktecoAttendanceLogResource::getWidgets())->toBe([ZktecoAttendanceLogStats::class])
        ->and(array_keys(ZktecoAttendanceLogResource::getPages()))->toBe(['index', 'view'])
        ->and(ZktecoDeviceCommandResource::canCreate())->toBeFalse()
        ->and(ZktecoDeviceCommandResource::getWidgets())->toBe([ZktecoDeviceCommandStats::class])
        ->and(array_keys(ZktecoDeviceCommandResource::getPages()))->toBe(['index', 'view']);
});

it('exposes resource labels and delegates form and table configuration', function () {
    $expectSectionSchema = function (Schema $schema): void {
        $schema->shouldReceive('components')
            ->once()
            ->withArgs(function (array $components): bool {
                expect($components)->toHaveCount(1)
                    ->and($components[0])->toBeInstanceOf(Section::class);

                return true;
            })
            ->andReturnSelf();
    };

    $expectColumns = function (array $columns, int $expectedCount): bool {
        expect($columns)->toHaveCount($expectedCount);

        foreach ($columns as $column) {
            expect($column)->toBeInstanceOf(Column::class);
        }

        return true;
    };

    expect(ZktecoDeviceResource::getNavigationGroup())->toBe('ZKTeco ADMS')
        ->and(ZktecoDeviceResource::getModelLabel())->toBe('Device')
        ->and(ZktecoDeviceResource::getPluralModelLabel())->toBe('Devices')
        ->and(ZktecoUserResource::getNavigationGroup())->toBe('ZKTeco ADMS')
        ->and(ZktecoUserResource::getModelLabel())->toBe('ZKTeco User')
        ->and(ZktecoUserResource::getPluralModelLabel())->toBe('ZKTeco Users');

    $deviceFormSchema = Mockery::mock(Schema::class);
    $expectSectionSchema($deviceFormSchema);
    expect(ZktecoDeviceResource::form($deviceFormSchema))->toBe($deviceFormSchema);

    $userFormSchema = Mockery::mock(Schema::class);
    $expectSectionSchema($userFormSchema);
    expect(ZktecoUserResource::form($userFormSchema))->toBe($userFormSchema);

    $deviceTable = Mockery::mock(Table::class);
    $deviceTable->shouldReceive('columns')->once()->withArgs(fn (array $columns): bool => $expectColumns($columns, 11))->andReturnSelf();
    $deviceTable->shouldReceive('filters')->once()->withArgs(fn (array $filters): bool => count($filters) === 1)->andReturnSelf();
    $deviceTable->shouldReceive('recordActions')->once()->withArgs(fn (array $actions): bool => count($actions) === 1)->andReturnSelf();
    $deviceTable->shouldReceive('groupedBulkActions')->once()->withArgs(fn (array $actions): bool => count($actions) === 1)->andReturnSelf();
    $deviceTable->shouldReceive('defaultSort')->once()->with('last_activity_at', 'desc')->andReturnSelf();
    expect(ZktecoDeviceResource::table($deviceTable))->toBe($deviceTable);

    $userTable = Mockery::mock(Table::class);
    $userTable->shouldReceive('columns')->once()->withArgs(fn (array $columns): bool => $expectColumns($columns, 7))->andReturnSelf();
    $userTable->shouldReceive('recordActions')->once()->withArgs(fn (array $actions): bool => count($actions) === 1)->andReturnSelf();
    $userTable->shouldReceive('groupedBulkActions')->once()->withArgs(fn (array $actions): bool => count($actions) === 1)->andReturnSelf();
    $userTable->shouldReceive('defaultSort')->once()->with('pin')->andReturnSelf();
    expect(ZktecoUserResource::table($userTable))->toBe($userTable);
});

it('includes soft deleted devices in the device resource query', function () {
    fakeUiFilamentWithoutTenant();

    $device = ZktecoDevice::query()->create([
        'serial_number' => 'SOFT-DELETE-DEVICE',
        'status' => DeviceStatus::Online,
    ]);

    $device->delete();

    expect(ZktecoDeviceResource::getEloquentQuery()->pluck('id')->all())
        ->toContain($device->id);
});

it('exposes widget page bindings and overview stats counts', function () {
    ZktecoDevice::query()->create([
        'serial_number' => 'STATS-1',
        'status' => DeviceStatus::Online,
    ]);

    $deviceTwo = ZktecoDevice::query()->create([
        'serial_number' => 'STATS-2',
        'status' => DeviceStatus::Offline,
    ]);

    ZktecoAttendanceLog::query()->create([
        'device_id' => $deviceTwo->id,
        'pin' => '1001',
        'recorded_at' => now(),
        'status' => 0,
        'verify_mode' => 1,
        'work_code' => '',
    ]);

    ZktecoDeviceCommand::query()->create([
        'device_id' => $deviceTwo->id,
        'command_id' => 1,
        'command_type' => 'INFO',
        'command_content' => 'INFO',
        'status' => CommandStatus::Pending,
    ]);

    $stats = (new OverviewStatsProxy())->stats();

    expect((new DeviceStatsProxy())->page())->toBe(ListZktecoDevices::class)
        ->and((new UserStatsProxy())->page())->toBe(ListZktecoUsers::class)
        ->and((new AttendanceLogStatsProxy())->page())->toBe(ListZktecoAttendanceLogs::class)
        ->and((new DeviceCommandStatsProxy())->page())->toBe(ListZktecoDeviceCommands::class)
        ->and($stats[0]->getValue())->toBe(2)
        ->and($stats[1]->getValue())->toBe(1)
        ->and($stats[2]->getValue())->toBe(1)
        ->and($stats[3]->getValue())->toBe(1);
});

it('filters overview stats by the active tenant when tenancy is enabled', function () {
    config()->set('filament-zkteco-adms.multi_tenancy.enabled', true);
    config()->set('filament-zkteco-adms.multi_tenancy.tenant_model', TestTeam::class);

    $tenantA = TestTeam::query()->create(['name' => 'Team A']);
    $tenantB = TestTeam::query()->create(['name' => 'Team B']);

    $deviceA = ZktecoDevice::query()->create([
        'serial_number' => 'TENANT-A',
        'status' => DeviceStatus::Online,
        'team_id' => $tenantA->id,
    ]);

    $deviceB = ZktecoDevice::query()->create([
        'serial_number' => 'TENANT-B',
        'status' => DeviceStatus::Offline,
        'team_id' => $tenantB->id,
    ]);

    ZktecoAttendanceLog::query()->create([
        'device_id' => $deviceA->id,
        'pin' => '1001',
        'recorded_at' => now(),
        'status' => 0,
        'verify_mode' => 1,
        'work_code' => '',
    ]);

    ZktecoAttendanceLog::query()->create([
        'device_id' => $deviceB->id,
        'pin' => '1002',
        'recorded_at' => now(),
        'status' => 0,
        'verify_mode' => 1,
        'work_code' => '',
    ]);

    ZktecoDeviceCommand::query()->create([
        'device_id' => $deviceA->id,
        'command_id' => 10,
        'command_type' => 'INFO',
        'command_content' => 'INFO',
        'status' => CommandStatus::Pending,
    ]);

    ZktecoDeviceCommand::query()->create([
        'device_id' => $deviceB->id,
        'command_id' => 11,
        'command_type' => 'INFO',
        'command_content' => 'INFO',
        'status' => CommandStatus::Pending,
    ]);

    fakeUiFilamentTenant($tenantA);

    $stats = (new OverviewStatsProxy())->stats();

    expect($stats[0]->getValue())->toBe(1)
        ->and($stats[1]->getValue())->toBe(1)
        ->and($stats[2]->getValue())->toBe(1)
        ->and($stats[3]->getValue())->toBe(1);
});

class DashboardProxy extends ZktecoDashboard
{
    public function exposedHeaderWidgets(): array
    {
        return $this->getHeaderWidgets();
    }
}

class OverviewStatsProxy extends ZktecoOverviewStats
{
    public function stats(): array
    {
        return $this->getStats();
    }
}

class DeviceStatsProxy extends ZktecoDeviceStats
{
    public function page(): string
    {
        return $this->getTablePage();
    }
}

class UserStatsProxy extends ZktecoUserStats
{
    public function page(): string
    {
        return $this->getTablePage();
    }
}

class AttendanceLogStatsProxy extends ZktecoAttendanceLogStats
{
    public function page(): string
    {
        return $this->getTablePage();
    }
}

class DeviceCommandStatsProxy extends ZktecoDeviceCommandStats
{
    public function page(): string
    {
        return $this->getTablePage();
    }
}
