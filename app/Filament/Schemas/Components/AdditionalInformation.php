<?php

namespace App\Filament\Schemas\Components;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;

class AdditionalInformation
{
    public static function make(array $dates = ['created_at', 'updated_at'])
    {
        $dates = collect($dates)->map(fn ($date) => TextEntry::make($date)->dateTime())->toArray();

        return Section::make('ADDITIONAL INFORMATION')
            ->description('Information on the date of registration and date of modification.')
            ->columns()
            ->schema($dates);
    }
}
