<?php

declare(strict_types=1);

namespace Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\Pages;

use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\ZktecoUserResource;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListZktecoUsers extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = ZktecoUserResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return ZktecoUserResource::getWidgets();
    }
}
