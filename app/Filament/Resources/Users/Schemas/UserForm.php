<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                    ->required(fn ($context) => $context === 'create'),
                TextInput::make('status')
                    ->required()
                    ->default('active'),
                DateTimePicker::make('suspended_until'),
                DatePicker::make('date_of_birth'),
                Select::make('gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                        'other' => 'Other',
                        'prefer_not_to_say' => 'Prefer not to say',
                    ]),
                TextInput::make('occupation'),
                Textarea::make('bio')
                    ->columnSpanFull(),
                TextInput::make('address'),
                TextInput::make('city'),
                TextInput::make('state'),
                TextInput::make('zip_code'),
                TextInput::make('country'),
                Select::make('verification_status')
                    ->options([
                        'unverified' => 'Unverified',
                        'pending' => 'Pending Review',
                        'verified' => 'Verified',
                        'rejected' => 'Rejected',
                    ])
                    ->default('unverified')
                    ->required(),
                TextInput::make('id_document_path')
                    ->label('ID Document Path')
                    ->disabled(),
                TextInput::make('selfie_path')
                    ->label('Selfie Path')
                    ->disabled(),
                Textarea::make('verification_notes')
                    ->columnSpanFull()
                    ->placeholder('Add notes about verification status...'),
                DateTimePicker::make('verified_at')
                    ->label('Verified At'),
            ]);
    }
}
