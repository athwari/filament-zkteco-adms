<?php

declare(strict_types=1);

namespace Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoAttendanceLogs\Schemas;

use Athwari\LaravelZktecoAdms\Enums\AttendanceStatus;
use Athwari\LaravelZktecoAdms\Enums\VerifyMode;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ZktecoAttendanceLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('filament-zkteco-adms::default.models.attendance_log.label'))
                ->columns(2)
                ->columnSpanFull()
                ->schema([
                    Select::make('device_id')
                        ->relationship('device', 'name')
                        ->required(),

                    TextInput::make('pin')
                        ->required()
                        ->maxLength(255),

                    DateTimePicker::make('occurred_at')
                        ->label('Occurred At'),

                    DateTimePicker::make('recorded_at')
                        ->label('Device-local Time')
                        ->required(),

                    Select::make('status')
                        ->options(AttendanceStatus::class)
                        ->required(),

                    Select::make('verify_mode')
                        ->options(VerifyMode::class)
                        ->required(),

                    TextInput::make('work_code')
                        ->maxLength(32),

                    TextInput::make('reserved_1')
                        ->maxLength(255),

                    TextInput::make('reserved_2')
                        ->maxLength(255),

                    KeyValue::make('raw_data')
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
