<?php

declare(strict_types=1);

namespace Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\Pages;

use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\ZktecoUserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditZktecoUser extends EditRecord
{
    protected static string $resource = ZktecoUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
