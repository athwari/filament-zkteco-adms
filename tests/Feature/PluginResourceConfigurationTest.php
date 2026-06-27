<?php

declare(strict_types=1);

use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoAttendanceLogs\Schemas\ZktecoAttendanceLogForm;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoAttendanceLogs\Tables\ZktecoAttendanceLogsTable;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDeviceCommands\Schemas\ZktecoDeviceCommandForm;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDeviceCommands\Tables\ZktecoDeviceCommandsTable;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\RelationManagers\AttendanceLogsRelationManager as DeviceAttendanceLogsRelationManager;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\RelationManagers\DeviceCommandsRelationManager;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\Schemas\ZktecoDeviceForm;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\Tables\ZktecoDevicesTable;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\RelationManagers\AttendanceLogsRelationManager as UserAttendanceLogsRelationManager;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\Schemas\ZktecoUserForm;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\Tables\ZktecoUsersTable;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\Column;
use Filament\Tables\Table;

it('configures all resource forms with schema components', function () {
    $configureForm = function (string $formClass): void {
        $schema = Mockery::mock(Schema::class);
        $schema->shouldReceive('components')
            ->once()
            ->withArgs(function (array $components): bool {
                expect($components)->toHaveCount(1)
                    ->and($components[0])->toBeInstanceOf(Section::class);

                return true;
            })
            ->andReturnSelf();

        expect($formClass::configure($schema))->toBe($schema);
    };

    $configureForm(ZktecoDeviceForm::class);
    $configureForm(ZktecoUserForm::class);
    $configureForm(ZktecoAttendanceLogForm::class);
    $configureForm(ZktecoDeviceCommandForm::class);
});

it('configures each resource table with expected columns and sort order', function () {
    $expectColumns = function (array $columns, int $expectedCount): bool {
        expect($columns)->toHaveCount($expectedCount);

        foreach ($columns as $column) {
            expect($column)->toBeInstanceOf(Column::class);
        }

        return true;
    };

    $devicesTable = Mockery::mock(Table::class);
    $devicesTable->shouldReceive('columns')->once()->withArgs(fn (array $columns): bool => $expectColumns($columns, 11))->andReturnSelf();
    $devicesTable->shouldReceive('filters')->once()->withArgs(fn (array $filters): bool => count($filters) === 1)->andReturnSelf();
    $devicesTable->shouldReceive('recordActions')->once()->withArgs(fn (array $actions): bool => count($actions) === 1)->andReturnSelf();
    $devicesTable->shouldReceive('groupedBulkActions')->once()->withArgs(fn (array $actions): bool => count($actions) === 1)->andReturnSelf();
    $devicesTable->shouldReceive('defaultSort')->once()->with('last_activity_at', 'desc')->andReturnSelf();

    expect(ZktecoDevicesTable::configure($devicesTable))->toBe($devicesTable);

    $usersTable = Mockery::mock(Table::class);
    $usersTable->shouldReceive('columns')->once()->withArgs(fn (array $columns): bool => $expectColumns($columns, 7))->andReturnSelf();
    $usersTable->shouldReceive('recordActions')->once()->withArgs(fn (array $actions): bool => count($actions) === 1)->andReturnSelf();
    $usersTable->shouldReceive('groupedBulkActions')->once()->withArgs(fn (array $actions): bool => count($actions) === 1)->andReturnSelf();
    $usersTable->shouldReceive('defaultSort')->once()->with('pin')->andReturnSelf();

    expect(ZktecoUsersTable::configure($usersTable))->toBe($usersTable);

    $attendanceLogsTable = Mockery::mock(Table::class);
    $attendanceLogsTable->shouldReceive('columns')->once()->withArgs(fn (array $columns): bool => $expectColumns($columns, 7))->andReturnSelf();
    $attendanceLogsTable->shouldReceive('filters')->once()->withArgs(fn (array $filters): bool => count($filters) === 4)->andReturnSelf();
    $attendanceLogsTable->shouldReceive('recordActions')->once()->withArgs(fn (array $actions): bool => count($actions) === 1)->andReturnSelf();
    $attendanceLogsTable->shouldReceive('defaultSort')->once()->with('recorded_at', 'desc')->andReturnSelf();

    expect(ZktecoAttendanceLogsTable::configure($attendanceLogsTable))->toBe($attendanceLogsTable);

    $deviceCommandsTable = Mockery::mock(Table::class);
    $deviceCommandsTable->shouldReceive('columns')->once()->withArgs(fn (array $columns): bool => $expectColumns($columns, 7))->andReturnSelf();
    $deviceCommandsTable->shouldReceive('filters')->once()->withArgs(fn (array $filters): bool => count($filters) === 2)->andReturnSelf();
    $deviceCommandsTable->shouldReceive('recordActions')->once()->withArgs(fn (array $actions): bool => count($actions) === 1)->andReturnSelf();
    $deviceCommandsTable->shouldReceive('groupedBulkActions')->once()->withArgs(fn (array $actions): bool => count($actions) === 1)->andReturnSelf();
    $deviceCommandsTable->shouldReceive('defaultSort')->once()->with('created_at', 'desc')->andReturnSelf();

    expect(ZktecoDeviceCommandsTable::configure($deviceCommandsTable))->toBe($deviceCommandsTable);
});

it('configures relation manager tables for devices and users', function () {
    $newRelationManager = (fn (string $class): RelationManager => (new ReflectionClass($class))->newInstanceWithoutConstructor());

    $deviceAttendanceTable = Mockery::mock(Table::class);
    $deviceAttendanceTable->shouldReceive('columns')->once()->withArgs(fn (array $columns): bool => count($columns) === 5)->andReturnSelf();
    $deviceAttendanceTable->shouldReceive('defaultSort')->once()->with('recorded_at', 'desc')->andReturnSelf();

    expect($newRelationManager(DeviceAttendanceLogsRelationManager::class)->table($deviceAttendanceTable))->toBe($deviceAttendanceTable);

    $deviceCommandsTable = Mockery::mock(Table::class);
    $deviceCommandsTable->shouldReceive('columns')->once()->withArgs(fn (array $columns): bool => count($columns) === 6)->andReturnSelf();
    $deviceCommandsTable->shouldReceive('filters')->once()->withArgs(fn (array $filters): bool => count($filters) === 1)->andReturnSelf();
    $deviceCommandsTable->shouldReceive('actions')->once()->withArgs(fn (array $actions): bool => count($actions) === 1)->andReturnSelf();
    $deviceCommandsTable->shouldReceive('defaultSort')->once()->with('created_at', 'desc')->andReturnSelf();

    expect($newRelationManager(DeviceCommandsRelationManager::class)->table($deviceCommandsTable))->toBe($deviceCommandsTable);

    $userAttendanceTable = Mockery::mock(Table::class);
    $userAttendanceTable->shouldReceive('columns')->once()->withArgs(fn (array $columns): bool => count($columns) === 4)->andReturnSelf();
    $userAttendanceTable->shouldReceive('filters')->once()->withArgs(fn (array $filters): bool => count($filters) === 3)->andReturnSelf();
    $userAttendanceTable->shouldReceive('defaultSort')->once()->with('recorded_at', 'desc')->andReturnSelf();

    expect($newRelationManager(UserAttendanceLogsRelationManager::class)->table($userAttendanceTable))->toBe($userAttendanceTable);
});
