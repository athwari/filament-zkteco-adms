<?php

declare(strict_types=1);

namespace Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDeviceCommands\Pages;

use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDeviceCommands\ZktecoDeviceCommandResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;

class ViewZktecoDeviceCommand extends ViewRecord
{
    protected static string $resource = ZktecoDeviceCommandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
