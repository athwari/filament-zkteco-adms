<?php

namespace Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoAttendanceLogs\Tables;

use Athwari\LaravelZktecoAdms\Enums\AttendanceStatus;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ZktecoAttendanceLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('device.serial_number')
                    ->label('Device')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('pin')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('zktecoUser.name')
                    ->label('User')
                    ->placeholder('—'),

                TextColumn::make('recorded_at')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('verify_mode')
                    ->badge()
                    ->sortable(),

                TextColumn::make('work_code')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(AttendanceStatus::class),

                SelectFilter::make('device')
                    ->relationship('device', 'name'),

                SelectFilter::make('user')
                    ->relationship('zktecoUser', 'pin'),

                Filter::make('recorded_at')
                    ->schema([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['from'], fn (Builder $q, $date) => $q->whereDate('recorded_at', '>=', $date))
                        ->when($data['until'], fn (Builder $q, $date) => $q->whereDate('recorded_at', '<=', $date))),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                ]),
            ])
            ->defaultSort('recorded_at', 'desc');
    }
}
