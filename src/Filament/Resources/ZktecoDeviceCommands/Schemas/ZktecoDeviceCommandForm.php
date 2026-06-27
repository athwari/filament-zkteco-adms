<?php

declare(strict_types=1);

namespace Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDeviceCommands\Schemas;

use Athwari\LaravelZktecoAdms\Enums\CommandStatus;
use Athwari\LaravelZktecoAdms\Enums\CommandType;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ZktecoDeviceCommandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('filament-zkteco-adms::default.models.device_command.label'))
                ->columns(2)
                ->columnSpanFull()
                ->schema([
                    Select::make('device_id')
                        ->relationship('device', 'name')
                        ->required(),

                    TextInput::make('command_id')
                        ->numeric(),

                    Select::make('command_type')
                        ->options(CommandType::class)
                        ->required(),

                    Select::make('status')
                        ->options(CommandStatus::class)
                        ->required(),

                    TextInput::make('return_code')
                        ->numeric(),

                    TextInput::make('retry_count')
                        ->numeric()
                        ->default(0)
                        ->required(),

                    DateTimePicker::make('queued_at'),

                    DateTimePicker::make('sent_at'),

                    DateTimePicker::make('acknowledged_at'),

                    Textarea::make('command_content')
                        ->required()
                        ->columnSpanFull(),

                    Textarea::make('response')
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
