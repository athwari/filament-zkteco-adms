<?php

declare(strict_types=1);

namespace Athwari\FilamentZktecoAdms\Models;

use Athwari\FilamentZktecoAdms\Traits\BelongsToTenant;
use Athwari\LaravelZktecoAdms\Models\ZktecoDevice as BaseZktecoDevice;

class ZktecoDevice extends BaseZktecoDevice
{
    use BelongsToTenant;
}
