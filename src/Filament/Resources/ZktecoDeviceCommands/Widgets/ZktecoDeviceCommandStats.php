<?php

namespace Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDeviceCommands\Widgets;

use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDeviceCommands\Pages\ListZktecoDeviceCommands;
use Athwari\LaravelZktecoAdms\Enums\CommandStatus;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ZktecoDeviceCommandStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListZktecoDeviceCommands::class;
    }

    protected function getStats(): array
    {
        $query = $this->getPageTableQuery();

        $totalCommands = $query->count();
        $pending = (clone $query)->where('status', CommandStatus::Pending)->count();
        $acknowledged = (clone $query)->where('status', CommandStatus::Acknowledged)->count();
        $failed = (clone $query)->where('status', CommandStatus::Failed)->count();

        return [
            Stat::make(__('filament-zkteco-adms::default.models.device_command.plural_label'), $totalCommands),
            Stat::make(__('filament-zkteco-adms::default.enums.command_status.pending'), $pending)
                ->color($pending > 0 ? 'warning' : 'success'),
            Stat::make(__('filament-zkteco-adms::default.enums.command_status.acknowledged'), $acknowledged)
                ->color('success'),
            Stat::make(__('filament-zkteco-adms::default.enums.command_status.failed'), $failed)
                ->color($failed > 0 ? 'danger' : 'gray'),
        ];
    }
}
