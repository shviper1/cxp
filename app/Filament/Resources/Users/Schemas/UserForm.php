<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Basic Information')
                        ->description('Primary contact details')
                        ->icon('heroicon-o-user')
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
                                ->placeholder('+1 (991) 683-3455')
                                ->maxLength(30)
                                ->rule('string')
                                ->helperText('Formats with spaces, dashes, or parentheses are allowed')
                                ->nullable(),
                            Grid::make(2)
                                ->schema([
                                    DatePicker::make('date_of_birth')
                                        ->label('Date of Birth')
                                        ->maxDate(now()),
                                    Select::make('gender')
                                        ->options([
                                            'male' => 'Male',
                                            'female' => 'Female',
                                            'other' => 'Other',
                                            'prefer_not_to_say' => 'Prefer not to say',
                                        ])
                                        ->placeholder('Select gender'),
                                ]),
                        ]),

                    Step::make('Account Security')
                        ->description('Credentials & status controls')
                        ->icon('heroicon-o-shield-check')
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
                            Grid::make(2)
                                ->schema([
                                    DateTimePicker::make('email_verified_at')
                                        ->label('Email Verified At'),
                                    DateTimePicker::make('suspended_until')
                                        ->label('Suspended Until'),
                                ]),
                        ]),

                    Step::make('Profile Details')
                        ->description('Optional background information')
                        ->icon('heroicon-o-clipboard-document')
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

                    Step::make('Verification & Roles')
                        ->description('Trust signals and permissions')
                        ->icon('heroicon-o-identification')
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
                            Select::make('roles')
                                ->label('Roles')
                                ->multiple()
                                ->preload()
                                ->relationship('roles', 'name')
                                ->helperText('Select one or more roles for this user'),
                        ]),
                ])
                    ->skippable()
                    ->persistStepInQueryString()
                    ->columnSpanFull(),
            ]);
    }
}
