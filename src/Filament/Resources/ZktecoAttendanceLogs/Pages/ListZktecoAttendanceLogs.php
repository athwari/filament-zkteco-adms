<?php

declare(strict_types=1);

namespace Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoAttendanceLogs\Pages;

use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoAttendanceLogs\ZktecoAttendanceLogResource;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListZktecoAttendanceLogs extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = ZktecoAttendanceLogResource::class;

    protected function getHeaderWidgets(): array
    {
        return ZktecoAttendanceLogResource::getWidgets();
    }
}
