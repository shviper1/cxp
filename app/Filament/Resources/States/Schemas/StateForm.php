<?php

namespace App\Filament\Resources\States\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('country_id')
                    ->relationship('country', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('name')
                    ->required(),
                Select::make('post_type')
                    ->required()
                    ->options([
                        'inherit' => 'Inherit from Country',
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
