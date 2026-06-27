<?php

declare(strict_types=1);

namespace Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoDevices\Schemas;

use Athwari\FilamentZktecoAdms\Models\ZktecoDevice;
use Athwari\LaravelZktecoAdms\Enums\DeviceStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ZktecoDeviceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('filament-zkteco-adms::default.models.device.label'))
                ->columns(2)
                ->columnSpanFull()
                ->schema([
                    TextInput::make('serial_number')
                        ->required()
                        ->maxLength(64)
                        ->unique(ZktecoDevice::class, 'serial_number', ignoreRecord: true),

                    TextInput::make('name')
                        ->maxLength(255),

                    TextInput::make('ip_address')
                        ->label('IP Address')
                        ->maxLength(45),

                    Select::make('status')
                        ->options(DeviceStatus::class)
                        ->default(DeviceStatus::Unknown),

                    TextInput::make('model')
                        ->maxLength(255),

                    TextInput::make('device_type')
                        ->maxLength(255),

                    TextInput::make('firmware_version')
                        ->maxLength(255),

                    TextInput::make('push_version')
                        ->maxLength(255),

                    TextInput::make('language')
                        ->maxLength(255),

                    TextInput::make('timezone')
                        ->maxLength(64),
                ]),
        ]);
    }
}
