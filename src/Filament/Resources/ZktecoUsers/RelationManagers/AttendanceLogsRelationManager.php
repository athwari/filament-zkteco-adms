<?php

namespace Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\RelationManagers;

use Athwari\LaravelZktecoAdms\Enums\AttendanceStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AttendanceLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'attendanceLogs';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('device.serial_number')
                    ->label('Device')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('recorded_at')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('verify_mode')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(AttendanceStatus::class),

                SelectFilter::make('device')
                    ->relationship('device', 'name'),

                Filter::make('recorded_at')
                    ->schema([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['from'], fn (Builder $q, $date) => $q->whereDate('recorded_at', '>=', $date))
                        ->when($data['until'], fn (Builder $q, $date) => $q->whereDate('recorded_at', '<=', $date))),
            ])
            ->defaultSort('recorded_at', 'desc');
    }
}
