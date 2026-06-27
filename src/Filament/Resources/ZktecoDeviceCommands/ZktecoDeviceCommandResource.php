<?php

namespace Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDeviceCommands;

use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDeviceCommands\Pages\ListZktecoDeviceCommands;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDeviceCommands\Pages\ViewZktecoDeviceCommand;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDeviceCommands\Schemas\ZktecoDeviceCommandForm;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDeviceCommands\Tables\ZktecoDeviceCommandsTable;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDeviceCommands\Widgets\ZktecoDeviceCommandStats;
use Athwari\LaravelZktecoAdms\Models\ZktecoDeviceCommand;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends resource<ZktecoDeviceCommand>
 */
class ZktecoDeviceCommandResource extends Resource
{
    protected static ?string $model = ZktecoDeviceCommand::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCommandLine;

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'zkteco/device-commands';

    public static function getNavigationGroup(): ?string
    {
        return __('filament-zkteco-adms::default.navigation.group');
    }

    public static function getModelLabel(): string
    {
        return __('filament-zkteco-adms::default.models.device_command.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-zkteco-adms::default.models.device_command.plural_label');
    }

    public static function form(Schema $schema): Schema
    {
        return ZktecoDeviceCommandForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ZktecoDeviceCommandsTable::configure($table);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    /** @return Builder<ZktecoDeviceCommand> */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (! config('filament-zkteco-adms.multi_tenancy.enabled', false)) {
            return $query;
        }

        $tenant = filament()->getTenant();

        if (! $tenant instanceof Model) {
            return $query;
        }

        return $query->whereHas('device', function (Builder $deviceQuery) use ($tenant): void {
            $deviceQuery->whereBelongsTo(
                $tenant,
                config('filament-zkteco-adms.multi_tenancy.tenant_relationship', 'team')
            );
        });
    }

    public static function getWidgets(): array
    {
        return [
            ZktecoDeviceCommandStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListZktecoDeviceCommands::route('/'),
            'view' => ViewZktecoDeviceCommand::route('/{record}'),
        ];
    }
}
