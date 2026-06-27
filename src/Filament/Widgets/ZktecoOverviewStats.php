<?php

namespace Athwari\FilamentZktecoAdms\Filament\Widgets;

use Athwari\FilamentZktecoAdms\Models\ZktecoDevice;
use Athwari\LaravelZktecoAdms\Enums\CommandStatus;
use Athwari\LaravelZktecoAdms\Enums\DeviceStatus;
use Athwari\LaravelZktecoAdms\Models\ZktecoAttendanceLog;
use Athwari\LaravelZktecoAdms\Models\ZktecoDeviceCommand;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ZktecoOverviewStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $deviceModel = config('zkteco-adms.models.device', ZktecoDevice::class);
        $attendanceModel = config('zkteco-adms.models.attendance_log', ZktecoAttendanceLog::class);
        $commandModel = config('zkteco-adms.models.device_command', ZktecoDeviceCommand::class);

        $deviceQuery = $deviceModel::query();
        $attendanceQuery = $attendanceModel::query();
        $commandQuery = $commandModel::query();

        if (config('filament-zkteco-adms.multi_tenancy.enabled', false) && filament()->getTenant()) {
            $tenant = filament()->getTenant();
            $relationship = config('filament-zkteco-adms.multi_tenancy.tenant_relationship', 'team');

            $deviceQuery->whereBelongsTo($tenant, $relationship);
            $attendanceQuery->whereHas('device', fn ($query) => $query->whereBelongsTo($tenant, $relationship));
            $commandQuery->whereHas('device', fn ($query) => $query->whereBelongsTo($tenant, $relationship));
        }

        $totalDevices = $deviceQuery->count();
        $onlineDevices = (clone $deviceQuery)->where('status', DeviceStatus::Online)->count();
        $todayAttendance = $attendanceQuery->whereDate('recorded_at', today())->count();
        $pendingCommands = $commandQuery->where('status', CommandStatus::Pending)->count();

        return [
            Stat::make(__('filament-zkteco-adms::default.widgets.overview.total_devices'), $totalDevices)
                ->description($onlineDevices.' '.__('filament-zkteco-adms::default.widgets.overview.online_devices'))
                ->color('primary'),

            Stat::make(__('filament-zkteco-adms::default.widgets.overview.online_devices'), $onlineDevices)
                ->color($onlineDevices > 0 ? 'success' : 'danger'),

            Stat::make(__('filament-zkteco-adms::default.widgets.overview.today_attendance'), $todayAttendance)
                ->color('info'),

            Stat::make(__('filament-zkteco-adms::default.widgets.overview.pending_commands'), $pendingCommands)
                ->color($pendingCommands > 0 ? 'warning' : 'success'),
        ];
    }
}
