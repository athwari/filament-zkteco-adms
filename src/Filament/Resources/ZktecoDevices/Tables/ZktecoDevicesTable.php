<?php

namespace Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\Tables;

use Athwari\FilamentZktecoAdms\Models\ZktecoDevice;
use Athwari\LaravelZktecoAdms\Enums\DeviceStatus;
use Athwari\LaravelZktecoAdms\Services\CommandManager;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ZktecoDevicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('serial_number')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight(FontWeight::Medium),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('ip_address')
                    ->label('IP')
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('model')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('push_version')
                    ->label('Push Ver')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('language')
                    ->label('Language')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('last_activity_at')
                    ->label('Last Activity')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Never'),

                TextColumn::make('attendance_logs_count')
                    ->counts('attendanceLogs')
                    ->label('Logs')
                    ->sortable(),

                TextColumn::make('pending_commands_count')
                    ->counts('pendingCommands')
                    ->label('Pending')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(DeviceStatus::class),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),

                    Action::make('check_connection')
                        ->label(__('filament-zkteco-adms::default.actions.check_connection'))
                        ->icon(Heroicon::Signal)
                        ->color('success')
                        ->action(fn (ZktecoDevice $record) => app(CommandManager::class)->sendCheckCommand($record->serial_number)),

                    Action::make('get_info')
                        ->label(__('filament-zkteco-adms::default.actions.get_info'))
                        ->icon(Heroicon::InformationCircle)
                        ->color('info')
                        ->action(fn (ZktecoDevice $record) => app(CommandManager::class)->sendInfoCommand($record->serial_number)),

                    Action::make('sync_time')
                        ->label(__('filament-zkteco-adms::default.actions.sync_time'))
                        ->icon(Heroicon::Clock)
                        ->color('primary')
                        ->action(fn (ZktecoDevice $record) => app(CommandManager::class)->queueCommand($record->serial_number, 'SET OPTIONS DateTime='.now()->format('Y-m-d H:i:s'))),

                    Action::make('clear_logs')
                        ->label(__('filament-zkteco-adms::default.actions.clear_logs'))
                        ->icon(Heroicon::Trash)
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(fn (ZktecoDevice $record) => app(CommandManager::class)->sendClearLogsCommand($record->serial_number)),

                    Action::make('reboot')
                        ->label(__('filament-zkteco-adms::default.actions.reboot'))
                        ->icon(Heroicon::ArrowPath)
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn (ZktecoDevice $record) => app(CommandManager::class)->sendRebootCommand($record->serial_number)),

                    DeleteAction::make(),
                ]),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('last_activity_at', 'desc');
    }
}
