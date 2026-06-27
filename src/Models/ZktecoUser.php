<?php

declare(strict_types=1);

namespace Athwari\FilamentZktecoAdms\Models;

use Athwari\FilamentZktecoAdms\Traits\BelongsToTenant;
use Athwari\LaravelZktecoAdms\Models\ZktecoUser as BaseZktecoUser;

class ZktecoUser extends BaseZktecoUser
{
    use BelongsToTenant;
}
