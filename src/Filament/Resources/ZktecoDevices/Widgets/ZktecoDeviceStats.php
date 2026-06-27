<?php

namespace Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\Widgets;

use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\Pages\ListZktecoDevices;
use Athwari\LaravelZktecoAdms\Enums\CommandStatus;
use Athwari\LaravelZktecoAdms\Enums\DeviceStatus;
use Athwari\LaravelZktecoAdms\Models\ZktecoDeviceCommand;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ZktecoDeviceStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListZktecoDevices::class;
    }

    protected function getStats(): array
    {
        $query = $this->getPageTableQuery();

        $totalDevices = $query->count();
        $onlineDevices = (clone $query)->where('status', DeviceStatus::Online)->count();
        $offlineDevices = (clone $query)->where('status', DeviceStatus::Offline)->count();

        $commandModel = config('zkteco-adms.models.device_command', ZktecoDeviceCommand::class);
        $pendingCommands = $commandModel::query()
            ->whereIn('device_id', (clone $query)->select('id'))
            ->where('status', CommandStatus::Pending)
            ->count();

        return [
            Stat::make(__('filament-zkteco-adms::default.widgets.overview.total_devices'), $totalDevices),
            Stat::make(__('filament-zkteco-adms::default.widgets.overview.online_devices'), $onlineDevices)
                ->color('success'),
            Stat::make(__('filament-zkteco-adms::default.enums.device_status.offline'), $offlineDevices)
                ->color('danger'),
            Stat::make(__('filament-zkteco-adms::default.widgets.overview.pending_commands'), $pendingCommands)
                ->color($pendingCommands > 0 ? 'warning' : 'success'),
        ];
    }
}
