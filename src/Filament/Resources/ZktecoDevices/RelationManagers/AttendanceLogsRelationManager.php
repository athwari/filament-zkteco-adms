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
            ->defaultSort('recorded_at', 'desc');
    }
}
