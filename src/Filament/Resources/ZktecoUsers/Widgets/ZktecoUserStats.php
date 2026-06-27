<?php

namespace Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\Widgets;

use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\Pages\ListZktecoUsers;
use Athwari\LaravelZktecoAdms\Enums\UserPrivilege;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ZktecoUserStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListZktecoUsers::class;
    }

    protected function getStats(): array
    {
        $query = $this->getPageTableQuery();

        $totalUsers = $query->count();
        $enabledUsers = (clone $query)->where('is_enabled', true)->count();
        $disabledUsers = (clone $query)->where('is_enabled', false)->count();
        $admins = (clone $query)->where('privilege', UserPrivilege::Admin)->count();

        return [
            Stat::make(__('filament-zkteco-adms::default.models.user.plural_label'), $totalUsers),
            Stat::make('Enabled Users', $enabledUsers)
                ->color('success'),
            Stat::make('Disabled Users', $disabledUsers)
                ->color('danger'),
            Stat::make('Administrators', $admins)
                ->color('warning'),
        ];
    }
}
