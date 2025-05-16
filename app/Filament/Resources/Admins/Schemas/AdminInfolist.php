<?php

namespace App\Filament\Resources\Admins\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AdminInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make()
                    ->schema([
                        TextEntry::make('id'),
                        IconEntry::make('status')
                            ->boolean(),
                        TextEntry::make('name'),
                        TextEntry::make('email')
                            ->copyable()
                            ->copyMessage('Email copiado com sucesso!')
                            ->copyMessageDuration(1500),
                    ]),
                Section::make('INFORMAÇÕES ADICIONAIS')
                    ->description('Informações da data de cadastro/alteração e referência.')
                    ->schema([
                        TextEntry::make('created_at')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->dateTime(),
                    ]),
            ]);
    }
}
