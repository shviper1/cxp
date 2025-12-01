<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function schema(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Text::make('name')
                                    ->label('Full Name'),
                                Text::make('email')
                                    ->label('Email Address'),
                            ]),
                        Grid::make(3)
                            ->schema([
                                Text::make('phone')
                                    ->label('Phone Number'),
                                Text::make('date_of_birth')
                                    ->label('Date of Birth')
                                    ->formatStateUsing(fn ($state) => $state ? $state->format('M d, Y') : null),
                                Text::make('gender')
                                    ->label('Gender')
                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                        'male' => 'Male',
                                        'female' => 'Female',
                                        'other' => 'Other',
                                        'prefer_not_to_say' => 'Prefer not to say',
                                        default => $state,
                                    }),
                            ]),
                        Text::make('occupation')
                            ->label('Occupation')
                            ->formatStateUsing(fn ($state) => $state ?: 'Not specified'),
                    ]),

                Section::make('Account Status')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Text::make('status')
                                    ->label('Account Status')
                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                        'active' => 'Active',
                                        'suspended' => 'Suspended',
                                        default => $state,
                                    })
                                    ->color(fn (string $state): string => match ($state) {
                                        'active' => 'success',
                                        'suspended' => 'danger',
                                        default => 'gray',
                                    }),
                                Text::make('suspended_until')
                                    ->label('Suspended Until')
                                    ->formatStateUsing(fn ($state) => $state ? $state->format('M d, Y H:i') : 'Not suspended'),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Text::make('email_verified_at')
                                    ->label('Email Verified At')
                                    ->formatStateUsing(fn ($state) => $state ? $state->format('M d, Y H:i') : 'Not verified'),
                                Text::make('created_at')
                                    ->label('Account Created')
                                    ->formatStateUsing(fn ($state) => $state->format('M d, Y H:i')),
                            ]),
                    ]),

                Section::make('Profile Information')
                    ->schema([
                        Text::make('bio')
                            ->label('Biography')
                            ->formatStateUsing(fn ($state) => $state ?: 'No biography provided'),
                        Grid::make(2)
                            ->schema([
                                Text::make('address')
                                    ->label('Address'),
                                Text::make('city')
                                    ->label('City'),
                            ]),
                        Grid::make(3)
                            ->schema([
                                Text::make('state')
                                    ->label('State'),
                                Text::make('zip_code')
                                    ->label('ZIP Code'),
                                Text::make('country')
                                    ->label('Country'),
                            ]),
                    ]),

                Section::make('Verification Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Text::make('verification_status')
                                    ->label('Verification Status')
                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                        'unverified' => 'Unverified',
                                        'pending' => 'Pending Review',
                                        'verified' => 'Verified',
                                        'rejected' => 'Rejected',
                                        default => $state,
                                    })
                                    ->color(fn (string $state): string => match ($state) {
                                        'verified' => 'success',
                                        'pending' => 'warning',
                                        'rejected' => 'danger',
                                        'unverified' => 'gray',
                                        default => 'gray',
                                    }),
                                Text::make('verified_at')
                                    ->label('Verified At')
                                    ->formatStateUsing(fn ($state) => $state ? $state->format('M d, Y H:i') : 'Not verified'),
                            ]),
                        Text::make('verification_notes')
                            ->label('Verification Notes')
                            ->formatStateUsing(fn ($state) => $state ?: 'No verification notes'),
                        Fieldset::make('Verification Documents')
                            ->schema([
                                Text::make('id_document_path')
                                    ->label('ID Document')
                                    ->formatStateUsing(fn (?string $state): string => $state ? 'Document uploaded' : 'No document uploaded')
                                    ->color(fn (?string $state): string => $state ? 'success' : 'gray'),
                                Text::make('selfie_path')
                                    ->label('Selfie')
                                    ->formatStateUsing(fn (?string $state): string => $state ? 'Selfie uploaded' : 'No selfie uploaded')
                                    ->color(fn (?string $state): string => $state ? 'success' : 'gray'),
                            ])
                            ->columns(2),
                    ]),

                Section::make('Activity & Statistics')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Text::make('posts_count')
                                    ->label('Total Posts')
                                    ->formatStateUsing(fn ($record) => $record->posts()->count())
                                    ->color('primary'),
                                Text::make('approved_posts_count')
                                    ->label('Approved Posts')
                                    ->formatStateUsing(fn ($record) => $record->posts()->where('status', 'approved')->count())
                                    ->color('success'),
                                Text::make('pending_posts_count')
                                    ->label('Pending Posts')
                                    ->formatStateUsing(fn ($record) => $record->posts()->where('status', 'pending')->count())
                                    ->color('warning'),
                            ]),
                    ]),
            ]);
    }
}
