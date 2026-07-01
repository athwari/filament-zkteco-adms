<?php

namespace Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices;

use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\Pages\CreateZktecoDevice;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\Pages\EditZktecoDevice;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\Pages\ListZktecoDevices;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\Pages\ViewZktecoDevice;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\RelationManagers\AttendanceLogsRelationManager;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\RelationManagers\DeviceCommandsRelationManager;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\Schemas\ZktecoDeviceForm;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\Tables\ZktecoDevicesTable;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\Widgets\ZktecoDeviceStats;
use Athwari\FilamentZktecoAdms\Models\ZktecoDevice;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * @extends resource<ZktecoDevice>
 */
class ZktecoDeviceResource extends Resource
{
    protected static ?string $model = ZktecoDevice::class;

    protected static ?string $recordTitleAttribute = 'serial_number';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFingerPrint;

    protected static ?int $navigationSort = 0;

    protected static ?string $slug = 'zkteco/devices';

    public static function getNavigationGroup(): ?string
    {
        return __('filament-zkteco-adms::default.navigation.group');
    }

    public static function getModelLabel(): string
    {
        return __('filament-zkteco-adms::default.models.device.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-zkteco-adms::default.models.device.plural_label');
    }

    public static function form(Schema $schema): Schema
    {
        return ZktecoDeviceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ZktecoDevicesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            AttendanceLogsRelationManager::class,
            DeviceCommandsRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [
            ZktecoDeviceStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListZktecoDevices::route('/'),
            'create' => CreateZktecoDevice::route('/create'),
            'edit' => EditZktecoDevice::route('/{record}/edit'),
            'view' => ViewZktecoDevice::route('/{record}'),
        ];
    }

    /** @return Builder<ZktecoDevice> */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function isScopedToTenant(): bool
    {
        return config('filament-zkteco-adms.multi_tenancy.enabled', false);
    }

    public static function getTenantRelationshipName(): string
    {
        return config('filament-zkteco-adms.multi_tenancy.tenant_relationship', 'team');
    }
}
