<?php

declare(strict_types=1);

namespace Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\Pages;

use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\ZktecoUserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateZktecoUser extends CreateRecord
{
    protected static string $resource = ZktecoUserResource::class;
}
