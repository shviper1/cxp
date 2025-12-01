<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('section_id')
                    ->relationship('section', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
            ]);
    }
}
