<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->label('Email address')
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                            ]),
                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->maxLength(20),
                        DatePicker::make('date_of_birth')
                            ->label('Date of Birth'),
                        Select::make('gender')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                                'other' => 'Other',
                                'prefer_not_to_say' => 'Prefer not to say',
                            ])
                            ->placeholder('Select gender'),
                    ]),

                Section::make('Account Security')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('password')
                                    ->password()
                                    ->dehydrateStateUsing(function ($state) {
                                        if (filled($state)) {
                                            return bcrypt($state);
                                        }

                                        return null; // Don't update password if empty
                                    })
                                    ->dehydrated(fn ($state): bool => filled($state))
                                    ->required(fn ($context) => $context === 'create')
                                    ->minLength(8)
                                    ->helperText('Leave blank to keep current password'),
                                Select::make('status')
                                    ->options([
                                        'active' => 'Active',
                                        'suspended' => 'Suspended',
                                    ])
                                    ->default('active')
                                    ->required(),
                            ]),
                        DateTimePicker::make('email_verified_at')
                            ->label('Email Verified At'),
                        DateTimePicker::make('suspended_until')
                            ->label('Suspended Until'),
                    ]),

                Section::make('Profile Information')
                    ->schema([
                        TextInput::make('occupation')
                            ->maxLength(255),
                        Textarea::make('bio')
                            ->maxLength(1000)
                            ->rows(3)
                            ->columnSpanFull(),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('address')
                                    ->maxLength(255),
                                TextInput::make('city')
                                    ->maxLength(255),
                            ]),
                        Grid::make(3)
                            ->schema([
                                TextInput::make('state')
                                    ->maxLength(255),
                                TextInput::make('zip_code')
                                    ->maxLength(20),
                                TextInput::make('country')
                                    ->maxLength(255),
                            ]),
                    ]),

                Section::make('Verification Status')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('verification_status')
                                    ->options([
                                        'unverified' => 'Unverified',
                                        'pending' => 'Pending Review',
                                        'verified' => 'Verified',
                                        'rejected' => 'Rejected',
                                    ])
                                    ->default('unverified')
                                    ->required(),
                                DateTimePicker::make('verified_at')
                                    ->label('Verified At'),
                            ]),
                        Textarea::make('verification_notes')
                            ->maxLength(1000)
                            ->rows(3)
                            ->columnSpanFull()
                            ->placeholder('Add notes about verification status...'),
                        Fieldset::make('Verification Documents')
                            ->schema([
                                TextInput::make('id_document_path')
                                    ->label('ID Document Path')
                                    ->disabled()
                                    ->helperText('Uploaded document path (read-only)'),
                                TextInput::make('selfie_path')
                                    ->label('Selfie Path')
                                    ->disabled()
                                    ->helperText('Uploaded selfie path (read-only)'),
                            ])
                            ->columns(1),
                    ]),
            ]);
    }
}
