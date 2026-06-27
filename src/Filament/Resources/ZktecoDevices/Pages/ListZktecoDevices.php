<?php

declare(strict_types=1);

namespace Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\Pages;

use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\ZktecoDeviceResource;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListZktecoDevices extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = ZktecoDeviceResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return ZktecoDeviceResource::getWidgets();
    }
}
