<?php

namespace App\Filament\Resources\Cities\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('state_id')
                    ->relationship('state', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('name')
                    ->required(),
                Select::make('post_type')
                    ->required()
                    ->options([
                        'inherit' => 'Inherit from State',
                        'free' => 'Free',
                        'paid' => 'Paid',
                    ])
                    ->default('inherit')
                    ->label('Post Type'),
                TextInput::make('order')
                    ->numeric()
                    ->default(0)
                    ->label('Display Order'),
            ]);
    }
}
