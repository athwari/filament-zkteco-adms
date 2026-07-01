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

                TextColumn::make('occurred_at')
                    ->label('Occurred At')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('recorded_at')
                    ->label('Device-local Time')
                    ->dateTime(),

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

                Filter::make('occurred_at')
                    ->schema([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['from'], fn (Builder $q, $date) => $q->whereDate('occurred_at', '>=', $date))
                        ->when($data['until'], fn (Builder $q, $date) => $q->whereDate('occurred_at', '<=', $date))),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                ]),
            ])
            ->defaultSort('occurred_at', 'desc');
    }
}
