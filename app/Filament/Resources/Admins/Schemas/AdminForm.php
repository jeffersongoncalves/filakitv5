<?php

namespace App\Filament\Resources\Admins\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

use function filled;

class AdminForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make()
                    ->columns()
                    ->schema([
                        Toggle::make('status')
                            ->required()
                            ->autofocus(),
                        TextInput::make('name')
                            ->required()
                            ->string()
                            ->autofocus(),
                        TextInput::make('email')
                            ->required()
                            ->string()
                            ->unique('admins', 'email', ignoreRecord: true)
                            ->email(),
                        TextInput::make('password')
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->dehydrated(fn ($state) => filled($state))
                            ->minLength(6),
                    ]),
            ]);
    }
}
