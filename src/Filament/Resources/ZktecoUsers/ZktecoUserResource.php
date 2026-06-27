<?php

namespace Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers;

use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\Pages\CreateZktecoUser;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\Pages\EditZktecoUser;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\Pages\ListZktecoUsers;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\Pages\ViewZktecoUser;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\RelationManagers\AttendanceLogsRelationManager;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\Schemas\ZktecoUserForm;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\Tables\ZktecoUsersTable;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\Widgets\ZktecoUserStats;
use Athwari\FilamentZktecoAdms\Models\ZktecoUser;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

/**
 * @extends resource<ZktecoUser>
 */
class ZktecoUserResource extends Resource
{
    protected static ?string $model = ZktecoUser::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'zkteco/users';

    public static function getNavigationGroup(): ?string
    {
        return __('filament-zkteco-adms::default.navigation.group');
    }

    public static function getModelLabel(): string
    {
        return __('filament-zkteco-adms::default.models.user.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-zkteco-adms::default.models.user.plural_label');
    }

    public static function form(Schema $schema): Schema
    {
        return ZktecoUserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ZktecoUsersTable::configure($table);
    }

    public static function getWidgets(): array
    {
        return [
            ZktecoUserStats::class,
        ];
    }

    public static function getRelations(): array
    {
        return [
            AttendanceLogsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListZktecoUsers::route('/'),
            'create' => CreateZktecoUser::route('/create'),
            'view' => ViewZktecoUser::route('/{record}'),
            'edit' => EditZktecoUser::route('/{record}/edit'),
        ];
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
