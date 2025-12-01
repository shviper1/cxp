<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('country_id')
                    ->relationship('country', 'name')
                    ->required(),
                Select::make('state_id')
                    ->relationship('state', 'name')
                    ->required(),
                Select::make('city_id')
                    ->relationship('city', 'name')
                    ->required(),
                Select::make('section_id')
                    ->relationship('section', 'name')
                    ->required(),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                TextInput::make('title')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->required()
                    ->rows(5)
                    ->columnSpanFull(),
                TextInput::make('age')
                    ->required()
                    ->numeric(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                
                // Media Upload (Photos & Videos)
                FileUpload::make('media')
                    ->label('Photos & Videos')
                    ->multiple()
                    ->image()
                    ->maxFiles(10)
                    ->directory('posts/media')
                    ->helperText('Upload up to 10 images or videos')
                    ->columnSpanFull(),
                
                Select::make('status')
                    ->required()
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending'),
                Select::make('payment_status')
                    ->required()
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'free' => 'Free',
                    ])
                    ->default('pending'),
            ]);
    }
}
