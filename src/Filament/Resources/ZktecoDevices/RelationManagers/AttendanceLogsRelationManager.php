<?php

declare(strict_types=1);

namespace Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttendanceLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'attendanceLogs';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
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
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('occurred_at', 'desc');
    }
}
