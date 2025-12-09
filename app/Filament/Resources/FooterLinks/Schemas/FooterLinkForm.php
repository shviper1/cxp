<?php

namespace App\Filament\Resources\FooterLinks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\FileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Toggle;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;

class FooterLinkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Link Details')
                        ->description('Label, target, and grouping')
                        ->icon('heroicon-o-pencil-square')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    TextInput::make('label')
                                        ->label('Display Label')
                                        ->required()
                                        ->maxLength(100),
                                    TextInput::make('url')
                                        ->label('Destination URL')
                                        ->url()
                                        ->maxLength(255)
                                        ->nullable(),
                                ]),
                            Grid::make(2)
                                ->schema([
                                    Select::make('type')
                                        ->label('Link Group')
                                        ->options([
                                            'primary' => 'Primary Navigation',
                                            'support' => 'Support Links',
                                            'social' => 'Social Media',
                                            'legal' => 'Legal / Policies',
                                            'payment' => 'Supported Payments',
                                        ])
                                        ->required()
                                        ->reactive(),
                                    TextInput::make('display_order')
                                        ->numeric()
                                        ->label('Display Order')
                                        ->default(0),
                                ]),
                            Toggle::make('is_active')
                                ->label('Visible in footer')
                                ->inline(false)
                                ->default(true),
                        ]),
                    Step::make('Media & Notes')
                        ->description('Optional icon or helper text')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            FileUpload::make('icon_path')
                                ->label('Icon / Image')
                                ->disk('public')
                                ->directory('footer-links')
                                ->visibility('public')
                                ->image()
                                ->imagePreviewHeight('150')
                                ->downloadable()
                                ->openable()
                                ->helperText('Recommended for payment or social links.')
                                ->hidden(fn (callable $get) => $get('type') !== 'payment'),
                        ]),
                ])
                    ->skippable()
                    ->persistStepInQueryString()
                    ->columnSpanFull(),
            ]);
    }
}
