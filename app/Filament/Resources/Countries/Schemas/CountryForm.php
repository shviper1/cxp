<?php

namespace App\Filament\Resources\Countries\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CountryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Select::make('post_type')
                    ->required()
                    ->options([
                        'free' => 'Free',
                        'paid' => 'Paid',
                    ])
                    ->default('free')
                    ->label('Post Type')
                    ->live(),
                TextInput::make('currency_symbol')
                    ->maxLength(10)
                    ->label('Currency Symbol')
                    ->visible(fn ($get) => $get('post_type') === 'paid'),
                TextInput::make('amount')
                    ->numeric()
                    ->label('Amount (for Paid posts)')
                    ->visible(fn ($get) => $get('post_type') === 'paid')
                    ->required(fn ($get) => $get('post_type') === 'paid'),
                TextInput::make('order')
                    ->numeric()
                    ->default(0)
                    ->label('Display Order')
                    ->helperText('Lower numbers appear first'),
            ]);
    }
}
