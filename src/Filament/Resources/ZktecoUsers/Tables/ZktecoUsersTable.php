<?php

declare(strict_types=1);

namespace Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ZktecoUsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pin')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('card_number')
                    ->label('Card')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('privilege')
                    ->badge()
                    ->sortable(),

                IconColumn::make('is_enabled')
                    ->boolean()
                    ->label('Enabled'),

                TextColumn::make('attendance_logs_count')
                    ->counts('attendanceLogs')
                    ->label('Logs')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('pin');
    }
}
