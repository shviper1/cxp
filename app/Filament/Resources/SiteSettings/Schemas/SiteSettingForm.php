<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Setting Key')
                        ->description('Identify how this setting is referenced in code')
                        ->icon('heroicon-o-hashtag')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    TextInput::make('key')
                                        ->label('Setting Key')
                                        ->required()
                                        ->maxLength(150)
                                        ->unique(ignoreRecord: true)
                                        ->disabled(fn ($record) => filled($record)),
                                    TextInput::make('group')
                                        ->label('Group')
                                        ->default('general')
                                        ->datalist([
                                            'general',
                                            'branding',
                                            'seo',
                                            'contact',
                                            'social',
                                            'footer',
                                        ])
                                        ->maxLength(100)
                                        ->required(),
                                ]),
                            Select::make('type')
                                ->label('Value Type')
                                ->options([
                                    'text' => 'Text',
                                    'textarea' => 'Long Text',
                                    'image' => 'Image',
                                    'email' => 'Email',
                                    'url' => 'URL',
                                ])
                                ->default('text')
                                ->required()
                                ->reactive(),
                        ]),

                    Step::make('Setting Value')
                        ->description('Add or update the saved value')
                        ->icon('heroicon-o-adjustments-horizontal')
                        ->schema([
                            Textarea::make('value')
                                ->label('Value')
                                ->rows(6)
                                ->columnSpanFull()
                                ->dehydrated(fn (callable $get) => $get('type') !== 'image')
                                ->visible(fn (callable $get) => $get('type') !== 'image')
                                ->helperText('Supports Markdown for long-form content.'),
                            FileUpload::make('media_upload')
                                ->label('Upload Asset')
                                ->disk('public')
                                ->directory(fn (Get $get) => 'site-settings/' . now()->format('Y/m') . '/' . Str::slug($get('group') ?: 'general'))
                                ->visibility('public')
                                ->preserveFilenames()
                                ->image()
                                ->maxSize(2048)
                                ->imagePreviewHeight('200')
                                ->downloadable()
                                ->openable()
                                ->dehydrated(false)
                                ->hidden(fn (callable $get) => $get('type') !== 'image')
                                ->afterStateHydrated(function ($state, callable $set, callable $get) {
                                    if (! $state && filled($get('value'))) {
                                        $set('media_upload', $get('value'));
                                    }
                                })
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $set('value', $state);
                                })
                                ->helperText('Upload PNG, JPG, or WEBP assets up to 2MB. Files are organized by setting group automatically.'),
                        ]),
                ])
                    ->skippable()
                    ->persistStepInQueryString()
                    ->columnSpanFull(),
            ]);
    }
}
