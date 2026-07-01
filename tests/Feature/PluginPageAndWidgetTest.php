<?php

use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoAttendanceLogs\Pages\ListZktecoAttendanceLogs;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoAttendanceLogs\Pages\ViewZktecoAttendanceLog;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoAttendanceLogs\Widgets\ZktecoAttendanceLogStats;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoAttendanceLogs\ZktecoAttendanceLogResource;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDeviceCommands\Pages\ListZktecoDeviceCommands;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDeviceCommands\Pages\ViewZktecoDeviceCommand;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDeviceCommands\Widgets\ZktecoDeviceCommandStats;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDeviceCommands\ZktecoDeviceCommandResource;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\Pages\CreateZktecoDevice;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\Pages\EditZktecoDevice;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\Pages\ListZktecoDevices;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\Pages\ViewZktecoDevice;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\Widgets\ZktecoDeviceStats;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\ZktecoDeviceResource;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\Pages\CreateZktecoUser;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\Pages\EditZktecoUser;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\Pages\ListZktecoUsers;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\Pages\ViewZktecoUser;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\Widgets\ZktecoUserStats;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\ZktecoUserResource;
use Athwari\FilamentZktecoAdms\Models\ZktecoDevice;
use Athwari\FilamentZktecoAdms\Models\ZktecoUser;
use Athwari\LaravelZktecoAdms\Enums\AttendanceStatus;
use Athwari\LaravelZktecoAdms\Enums\CommandStatus;
use Athwari\LaravelZktecoAdms\Enums\DeviceStatus;
use Athwari\LaravelZktecoAdms\Enums\UserPrivilege;
use Athwari\LaravelZktecoAdms\Models\ZktecoAttendanceLog;
use Athwari\LaravelZktecoAdms\Models\ZktecoDeviceCommand;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;

it('binds pages to the expected resources and actions', function () {
    $createDevicePage = new CreateZktecoDeviceProxy();
    $editDevicePage = new EditZktecoDeviceProxy();
    $listDevicePage = new ListZktecoDevicesProxy();
    $viewDevicePage = new ViewZktecoDeviceProxy();
    $createUserPage = new CreateZktecoUserProxy();
    $editUserPage = new EditZktecoUserProxy();
    $listUserPage = new ListZktecoUsersProxy();
    $viewUserPage = new ViewZktecoUserProxy();
    $listAttendanceLogPage = new ListZktecoAttendanceLogsProxy();
    $viewAttendanceLogPage = new ViewZktecoAttendanceLogProxy();
    $listDeviceCommandPage = new ListZktecoDeviceCommandsProxy();
    $viewDeviceCommandPage = new ViewZktecoDeviceCommandProxy();

    expect($createDevicePage::resourceClass())->toBe(ZktecoDeviceResource::class)
        ->and($editDevicePage::resourceClass())->toBe(ZktecoDeviceResource::class)
        ->and($viewDevicePage::resourceClass())->toBe(ZktecoDeviceResource::class)
        ->and($createUserPage::resourceClass())->toBe(ZktecoUserResource::class)
        ->and($editUserPage::resourceClass())->toBe(ZktecoUserResource::class)
        ->and($viewUserPage::resourceClass())->toBe(ZktecoUserResource::class)
        ->and($listAttendanceLogPage::resourceClass())->toBe(ZktecoAttendanceLogResource::class)
        ->and($viewAttendanceLogPage::resourceClass())->toBe(ZktecoAttendanceLogResource::class)
        ->and($listDeviceCommandPage::resourceClass())->toBe(ZktecoDeviceCommandResource::class)
        ->and($viewDeviceCommandPage::resourceClass())->toBe(ZktecoDeviceCommandResource::class)
        ->and($listDevicePage->actions()[0])->toBeInstanceOf(CreateAction::class)
        ->and($listDevicePage->exposedHeaderWidgets())->toBe([ZktecoDeviceStats::class])
        ->and($viewDevicePage->actions()[0])->toBeInstanceOf(EditAction::class)
        ->and($viewDevicePage->headerActions()[1])->toBeInstanceOf(DeleteAction::class)
        ->and($editDevicePage->headerActions()[0])->toBeInstanceOf(DeleteAction::class)
        ->and($listUserPage->actions()[0])->toBeInstanceOf(CreateAction::class)
        ->and($listUserPage->exposedHeaderWidgets())->toBe([ZktecoUserStats::class])
        ->and($listAttendanceLogPage->exposedHeaderWidgets())->toBe([ZktecoAttendanceLogStats::class])
        ->and($listDeviceCommandPage->exposedHeaderWidgets())->toBe([ZktecoDeviceCommandStats::class])
        ->and($editUserPage->headerActions()[0])->toBeInstanceOf(DeleteAction::class)
        ->and($viewDeviceCommandPage->headerActions()[0])->toBeInstanceOf(DeleteAction::class);
});

it('computes page table widget stats from their queries', function () {
    $deviceOne = ZktecoDevice::query()->create([
        'serial_number' => 'PAGE-STATS-1',
        'status' => DeviceStatus::Online,
    ]);

    $deviceTwo = ZktecoDevice::query()->create([
        'serial_number' => 'PAGE-STATS-2',
        'status' => DeviceStatus::Offline,
    ]);

    ZktecoUser::query()->create([
        'pin' => '1001',
        'name' => 'Enabled Admin',
        'privilege' => UserPrivilege::Admin,
        'is_enabled' => true,
    ]);

    ZktecoUser::query()->create([
        'pin' => '1002',
        'name' => 'Disabled User',
        'privilege' => UserPrivilege::User,
        'is_enabled' => false,
    ]);

    ZktecoAttendanceLog::query()->create([
        'device_id' => $deviceOne->id,
        'pin' => '1001',
        'recorded_at' => now()->subDay(),
        'occurred_at' => now(),
        'status' => AttendanceStatus::CheckIn,
        'verify_mode' => 1,
        'work_code' => '',
    ]);

    ZktecoAttendanceLog::query()->create([
        'device_id' => $deviceTwo->id,
        'pin' => '1002',
        'recorded_at' => now()->subDay(),
        'occurred_at' => now(),
        'status' => AttendanceStatus::CheckOut,
        'verify_mode' => 1,
        'work_code' => '',
    ]);

    ZktecoDeviceCommand::query()->create([
        'device_id' => $deviceOne->id,
        'command_id' => 20,
        'command_type' => 'INFO',
        'command_content' => 'INFO',
        'status' => CommandStatus::Pending,
    ]);

    ZktecoDeviceCommand::query()->create([
        'device_id' => $deviceTwo->id,
        'command_id' => 21,
        'command_type' => 'INFO',
        'command_content' => 'INFO',
        'status' => CommandStatus::Acknowledged,
    ]);

    ZktecoDeviceCommand::query()->create([
        'device_id' => $deviceTwo->id,
        'command_id' => 22,
        'command_type' => 'INFO',
        'command_content' => 'INFO',
        'status' => CommandStatus::Failed,
    ]);

    DeviceStatsQueryProxy::$query = ZktecoDevice::query();
    UserStatsQueryProxy::$query = ZktecoUser::query();
    AttendanceLogStatsQueryProxy::$query = ZktecoAttendanceLog::query();
    DeviceCommandStatsQueryProxy::$query = ZktecoDeviceCommand::query();

    $deviceStats = (new DeviceStatsQueryProxy())->stats();
    $userStats = (new UserStatsQueryProxy())->stats();
    $attendanceStats = (new AttendanceLogStatsQueryProxy())->stats();
    $commandStats = (new DeviceCommandStatsQueryProxy())->stats();

    expect($deviceStats[0]->getValue())->toBe(2)
        ->and($deviceStats[1]->getValue())->toBe(1)
        ->and($deviceStats[2]->getValue())->toBe(1)
        ->and($deviceStats[3]->getValue())->toBe(1)
        ->and($userStats[0]->getValue())->toBe(2)
        ->and($userStats[1]->getValue())->toBe(1)
        ->and($userStats[2]->getValue())->toBe(1)
        ->and($userStats[3]->getValue())->toBe(1)
        ->and($attendanceStats[0]->getValue())->toBe(2)
        ->and($attendanceStats[1]->getValue())->toBe(2)
        ->and($attendanceStats[2]->getValue())->toBe(1)
        ->and($attendanceStats[3]->getValue())->toBe(1)
        ->and($commandStats[0]->getValue())->toBe(3)
        ->and($commandStats[1]->getValue())->toBe(1)
        ->and($commandStats[2]->getValue())->toBe(1)
        ->and($commandStats[3]->getValue())->toBe(1);
});

class CreateZktecoDeviceProxy extends CreateZktecoDevice
{
    public static function resourceClass(): string
    {
        return static::$resource;
    }
}

class EditZktecoDeviceProxy extends EditZktecoDevice
{
    public static function resourceClass(): string
    {
        return static::$resource;
    }

    public function headerActions(): array
    {
        return $this->getHeaderActions();
    }
}

class ListZktecoDevicesProxy extends ListZktecoDevices
{
    public function actions(): array
    {
        return $this->getActions();
    }

    public function exposedHeaderWidgets(): array
    {
        return $this->getHeaderWidgets();
    }
}

class ViewZktecoDeviceProxy extends ViewZktecoDevice
{
    public static function resourceClass(): string
    {
        return static::$resource;
    }

    public function actions(): array
    {
        return $this->getActions();
    }

    public function headerActions(): array
    {
        return $this->getHeaderActions();
    }
}

class CreateZktecoUserProxy extends CreateZktecoUser
{
    public static function resourceClass(): string
    {
        return static::$resource;
    }
}

class EditZktecoUserProxy extends EditZktecoUser
{
    public static function resourceClass(): string
    {
        return static::$resource;
    }

    public function headerActions(): array
    {
        return $this->getHeaderActions();
    }
}

class ListZktecoUsersProxy extends ListZktecoUsers
{
    public function actions(): array
    {
        return $this->getActions();
    }

    public function exposedHeaderWidgets(): array
    {
        return $this->getHeaderWidgets();
    }
}

class ViewZktecoUserProxy extends ViewZktecoUser
{
    public static function resourceClass(): string
    {
        return static::$resource;
    }
}

class ViewZktecoAttendanceLogProxy extends ViewZktecoAttendanceLog
{
    public static function resourceClass(): string
    {
        return static::$resource;
    }
}

class ListZktecoAttendanceLogsProxy extends ListZktecoAttendanceLogs
{
    public static function resourceClass(): string
    {
        return static::$resource;
    }

    public function exposedHeaderWidgets(): array
    {
        return $this->getHeaderWidgets();
    }
}

class ViewZktecoDeviceCommandProxy extends ViewZktecoDeviceCommand
{
    public static function resourceClass(): string
    {
        return static::$resource;
    }

    public function headerActions(): array
    {
        return $this->getHeaderActions();
    }
}

class ListZktecoDeviceCommandsProxy extends ListZktecoDeviceCommands
{
    public static function resourceClass(): string
    {
        return static::$resource;
    }

    public function exposedHeaderWidgets(): array
    {
        return $this->getHeaderWidgets();
    }
}

class DeviceStatsQueryProxy extends ZktecoDeviceStats
{
    public static Builder $query;

    protected function getPageTableQuery(): Builder
    {
        return clone static::$query;
    }

    public function stats(): array
    {
        return $this->getStats();
    }
}

class UserStatsQueryProxy extends ZktecoUserStats
{
    public static Builder $query;

    protected function getPageTableQuery(): Builder
    {
        return clone static::$query;
    }

    public function stats(): array
    {
        return $this->getStats();
    }
}

class AttendanceLogStatsQueryProxy extends ZktecoAttendanceLogStats
{
    public static Builder $query;

    protected function getPageTableQuery(): Builder
    {
        return clone static::$query;
    }

    public function stats(): array
    {
        return $this->getStats();
    }
}

class DeviceCommandStatsQueryProxy extends ZktecoDeviceCommandStats
{
    public static Builder $query;

    protected function getPageTableQuery(): Builder
    {
        return clone static::$query;
    }

    public function stats(): array
    {
        return $this->getStats();
    }
}
