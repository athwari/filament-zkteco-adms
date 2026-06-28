<?php

declare(strict_types=1);

namespace Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\Pages;

use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\ZktecoDeviceResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewZktecoDevice extends ViewRecord
{
    protected static string $resource = ZktecoDeviceResource::class;

    protected function getActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}
