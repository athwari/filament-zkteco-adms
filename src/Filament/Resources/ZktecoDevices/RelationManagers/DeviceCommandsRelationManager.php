<?php

namespace Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\RelationManagers;

use Athwari\LaravelZktecoAdms\Enums\CommandStatus;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DeviceCommandsRelationManager extends RelationManager
{
    protected static string $relationship = 'deviceCommands';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('command_type')
                    ->badge()
                    ->sortable(),

                TextColumn::make('command_content')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->command_content),

                TextColumn::make('status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('sent_at')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('acknowledged_at')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('retry_count')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(CommandStatus::class),
            ])
            ->actions([
                Action::make('retry')
                    ->label(__('filament-zkteco-adms::default.actions.retry'))
                    ->action(fn ($record) => $record->retry())
                    ->visible(fn ($record) => in_array($record->status, [CommandStatus::Sent, CommandStatus::Failed]))
                    ->requiresConfirmation(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
