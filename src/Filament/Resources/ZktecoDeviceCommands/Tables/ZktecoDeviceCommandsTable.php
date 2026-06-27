<?php

namespace Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDeviceCommands\Tables;

use Athwari\LaravelZktecoAdms\Enums\CommandStatus;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ZktecoDeviceCommandsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('device.serial_number')
                    ->label('Device')
                    ->searchable()
                    ->sortable(),

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
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(CommandStatus::class),

                SelectFilter::make('device')
                    ->relationship('device', 'serial_number'),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    Action::make('retry')
                        ->label(__('filament-zkteco-adms::default.actions.retry'))
                        ->action(fn ($record) => $record->retry())
                        ->visible(fn ($record) => in_array($record->status, [CommandStatus::Sent, CommandStatus::Failed]))
                        ->requiresConfirmation(),
                    DeleteAction::make(),
                ]),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
