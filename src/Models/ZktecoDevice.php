<?php

declare(strict_types=1);

namespace Athwari\FilamentZktecoAdms\Models;

use Athwari\FilamentZktecoAdms\Traits\BelongsToTenant;
use Athwari\LaravelZktecoAdms\Models\ZktecoDevice as BaseZktecoDevice;

class ZktecoDevice extends BaseZktecoDevice
{
    use BelongsToTenant;

    public function delete()
    {
        if ($this->isForceDeleting()) {
            return parent::delete();
        }

        $replacementValue = sprintf('deleted-%s', $this->getKey());

        $this->newQueryWithoutScopes()
            ->whereKey($this->getKey())
            ->update(['serial_number' => $replacementValue]);

        $this->setAttribute('serial_number', $replacementValue);

        return parent::delete();
    }
}
