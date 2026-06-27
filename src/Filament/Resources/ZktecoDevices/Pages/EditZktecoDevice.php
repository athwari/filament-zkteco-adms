<?php

declare(strict_types=1);

namespace Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\Pages;

use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\ZktecoDeviceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditZktecoDevice extends EditRecord
{
    protected static string $resource = ZktecoDeviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
