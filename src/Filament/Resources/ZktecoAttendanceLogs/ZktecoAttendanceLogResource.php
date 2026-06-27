<?php

namespace Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoAttendanceLogs;

use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoAttendanceLogs\Pages\ListZktecoAttendanceLogs;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoAttendanceLogs\Pages\ViewZktecoAttendanceLog;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoAttendanceLogs\Schemas\ZktecoAttendanceLogForm;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoAttendanceLogs\Tables\ZktecoAttendanceLogsTable;
use Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoAttendanceLogs\Widgets\ZktecoAttendanceLogStats;
use Athwari\LaravelZktecoAdms\Models\ZktecoAttendanceLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends resource<ZktecoAttendanceLog>
 */
class ZktecoAttendanceLogResource extends Resource
{
    protected static ?string $model = ZktecoAttendanceLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'zkteco/attendance-logs';

    public static function getNavigationGroup(): ?string
    {
        return __('filament-zkteco-adms::default.navigation.group');
    }

    public static function getModelLabel(): string
    {
        return __('filament-zkteco-adms::default.models.attendance_log.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-zkteco-adms::default.models.attendance_log.plural_label');
    }

    public static function form(Schema $schema): Schema
    {
        return ZktecoAttendanceLogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ZktecoAttendanceLogsTable::configure($table);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    /** @return Builder<ZktecoAttendanceLog> */
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
            ZktecoAttendanceLogStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListZktecoAttendanceLogs::route('/'),
            'view' => ViewZktecoAttendanceLog::route('/{record}'),
        ];
    }
}
