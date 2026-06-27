<?php

namespace Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoAttendanceLogs\Widgets;

use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoAttendanceLogs\Pages\ListZktecoAttendanceLogs;
use Athwari\LaravelZktecoAdms\Enums\AttendanceStatus;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ZktecoAttendanceLogStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListZktecoAttendanceLogs::class;
    }

    protected function getStats(): array
    {
        $query = $this->getPageTableQuery();

        $totalLogs = $query->count();
        $punchesToday = (clone $query)->whereDate('recorded_at', today())->count();
        $checkIns = (clone $query)->where('status', AttendanceStatus::CheckIn)->count();
        $checkOuts = (clone $query)->where('status', AttendanceStatus::CheckOut)->count();

        return [
            Stat::make(__('filament-zkteco-adms::default.models.attendance_log.plural_label'), $totalLogs),
            Stat::make(__('filament-zkteco-adms::default.widgets.overview.today_attendance'), $punchesToday)
                ->color('info'),
            Stat::make(__('filament-zkteco-adms::default.enums.attendance_status.check_in'), $checkIns)
                ->color('success'),
            Stat::make(__('filament-zkteco-adms::default.enums.attendance_status.check_out'), $checkOuts)
                ->color('danger'),
        ];
    }
}
