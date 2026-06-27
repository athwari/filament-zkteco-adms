<?php

declare(strict_types=1);

namespace Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDeviceCommands\Pages;

use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDeviceCommands\ZktecoDeviceCommandResource;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListZktecoDeviceCommands extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = ZktecoDeviceCommandResource::class;

    protected function getHeaderWidgets(): array
    {
        return ZktecoDeviceCommandResource::getWidgets();
    }
}
