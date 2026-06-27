<?php

declare(strict_types=1);

namespace Athwari\FilamentZktecoAdms\Filament\Resources\ZktecoUsers\Schemas;

use Athwari\FilamentZktecoAdms\Models\ZktecoUser;
use Athwari\LaravelZktecoAdms\Enums\UserPrivilege;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ZktecoUserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('filament-zkteco-adms::default.models.user.label'))
                ->columns(2)
                ->columnSpanFull()
                ->schema([
                    TextInput::make('pin')
                        ->required()
                        ->unique(ZktecoUser::class, 'pin', ignoreRecord: true),

                    TextInput::make('name')
                        ->maxLength(255),

                    TextInput::make('card_number')
                        ->maxLength(255),

                    Select::make('privilege')
                        ->options(UserPrivilege::class)
                        ->default(UserPrivilege::User),

                    TextInput::make('group')
                        ->maxLength(255),

                    Toggle::make('is_enabled')
                        ->default(true),
                ]),
        ]);
    }
}
