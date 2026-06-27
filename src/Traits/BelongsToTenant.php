<?php

namespace Athwari\FilamentZktecoAdms\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static void creating(\Closure|string $callback)
 *
 * @mixin Model
 */
trait BelongsToTenant
{
    /**
     * Boot the trait to hook into the model lifecycle.
     */
    public static function bootBelongsToTenant(): void
    {
        if (config('filament-zkteco-adms.multi_tenancy.enabled', false)) {
            static::creating(function ($model) {
                $tenantColumn = config('filament-zkteco-adms.multi_tenancy.tenant_column', 'team_id');

                if (auth()->guard()->check() && ! $model->getAttribute($tenantColumn)) {
                    $tenant = filament()->getTenant();

                    if ($tenant instanceof Model) {
                        $model->setAttribute($tenantColumn, $tenant->getKey());
                    }
                }
            });
        }
    }

    /**
     * Generic relationship to the tenant.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(
            config('filament-zkteco-adms.multi_tenancy.tenant_model', 'App\Models\Team'),
            config('filament-zkteco-adms.multi_tenancy.tenant_column', 'team_id')
        );
    }

    /**
     * Dynamically resolve relation calls to support custom tenant relationship names.
     *
     * @param  string  $method
     * @param  array<int, mixed>  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $relationshipName = config('filament-zkteco-adms.multi_tenancy.tenant_relationship', 'team');

        if ($method === $relationshipName) {
            return $this->tenant();
        }

        return parent::__call($method, $parameters);
    }
}
