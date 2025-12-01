<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Basic Information')
                        ->description('Tell us about your post')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Grid::make(1)
                                ->schema([
                                    TextInput::make('title')
                                        ->label('Post Title')
                                        ->required()
                                        ->placeholder('Enter an attractive title for your post')
                                        ->maxLength(255)
                                        ->helperText('Make it descriptive and engaging'),

                                    Textarea::make('description')
                                        ->label('Description')
                                        ->required()
                                        ->rows(6)
                                        ->placeholder('Provide detailed information about what you\'re offering...')
                                        ->maxLength(2000)
                                        ->helperText('Be specific about features, condition, and any important details'),
                                ]),
                        ]),

                    Step::make('Location Details')
                        ->description('Where is this located?')
                        ->icon('heroicon-o-map-pin')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    Select::make('country_id')
                                        ->label('Country')
                                        ->relationship('country', 'name')
                                        ->required()
                                        ->placeholder('Select country')
                                        ->preload()
                                        ->searchable(),

                                    Select::make('state_id')
                                        ->label('State/Province')
                                        ->relationship('state', 'name')
                                        ->required()
                                        ->placeholder('Select state/province')
                                        ->preload()
                                        ->searchable(),
                                ]),

                            Grid::make(1)
                                ->schema([
                                    Select::make('city_id')
                                        ->label('City')
                                        ->relationship('city', 'name')
                                        ->required()
                                        ->placeholder('Select city')
                                        ->preload()
                                        ->searchable(),
                                ]),
                        ]),

                    Step::make('Contact Information')
                        ->description('How can people reach you?')
                        ->icon('heroicon-o-phone')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    TextInput::make('email')
                                        ->label('Email Address')
                                        ->email()
                                        ->required()
                                        ->placeholder('your@email.com')
                                        ->helperText('We\'ll use this to send you important updates'),

                                    TextInput::make('phone')
                                        ->label('Phone Number')
                                        ->tel()
                                        ->required()
                                        ->placeholder('+1 (555) 123-4567')
                                        ->helperText('Include country code for international numbers'),
                                ]),

                            Grid::make(2)
                                ->schema([
                                    TextInput::make('age')
                                        ->label('Age (if applicable)')
                                        ->numeric()
                                        ->minValue(1)
                                        ->maxValue(120)
                                        ->placeholder('Enter age')
                                        ->helperText('Leave empty if not applicable'),

                                    Select::make('user_id')
                                        ->label('Posted By')
                                        ->relationship('user', 'name')
                                        ->required()
                                        ->placeholder('Select user')
                                        ->preload()
                                        ->searchable()
                                        ->helperText('Who is posting this?'),
                                ]),
                        ]),

                    Step::make('Category & Media')
                        ->description('Categorize and add photos')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    Select::make('section_id')
                                        ->label('Section')
                                        ->relationship('section', 'name')
                                        ->required()
                                        ->placeholder('Select section')
                                        ->preload()
                                        ->searchable()
                                        ->helperText('Main category section'),

                                    Select::make('category_id')
                                        ->label('Category')
                                        ->relationship('category', 'name')
                                        ->required()
                                        ->placeholder('Select category')
                                        ->preload()
                                        ->searchable()
                                        ->helperText('Specific subcategory'),
                                ]),

                            Grid::make(1)
                                ->schema([
                                    FileUpload::make('media')
                                        ->label('Photos & Videos')
                                        ->multiple()
                                        ->image()
                                        ->maxFiles(10)
                                        ->directory('posts/media')
                                        ->visibility('public')
                                        ->imageResizeMode('cover')
                                        ->imageCropAspectRatio('16:9')
                                        ->imageResizeTargetWidth('1200')
                                        ->imageResizeTargetHeight('675')
                                        ->panelLayout('grid')
                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                                        ->helperText('Upload high-quality photos (max 10 files, JPG/PNG/GIF/WebP only)')
                                        ->required()
                                        ->minFiles(1),
                                ]),
                        ]),

                    Step::make('Review & Publish')
                        ->description('Final review and publishing options')
                        ->icon('heroicon-o-check-circle')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    Select::make('status')
                                        ->label('Post Status')
                                        ->required()
                                        ->options([
                                            'pending' => 'Pending Review',
                                            'approved' => 'Publish Immediately',
                                        ])
                                        ->default('pending')
                                        ->helperText('Choose how to publish your post'),

                                    Select::make('payment_status')
                                        ->label('Payment Status')
                                        ->required()
                                        ->options([
                                            'free' => 'Free Post',
                                            'pending' => 'Payment Pending',
                                            'paid' => 'Paid Post',
                                        ])
                                        ->default('free')
                                        ->helperText('Is this a paid or free post?'),
                                ]),

                            // Summary section
                            \Filament\Forms\Components\Placeholder::make('summary')
                                ->label('Post Summary')
                                ->content(function ($get) {
                                    $title = $get('title');
                                    $description = $get('description');
                                    $country = $get('country_id') ? \App\Models\Country::find($get('country_id'))?->name : 'Not selected';
                                    $category = $get('category_id') ? \App\Models\Category::find($get('category_id'))?->name : 'Not selected';
                                    $mediaCount = is_array($get('media')) ? count($get('media')) : 0;

                                    return view('filament.components.post-summary', compact(
                                        'title', 'description', 'country', 'category', 'mediaCount'
                                    ));
                                }),
                        ]),
                ])
                    ->skippable()
                    ->persistStepInQueryString()
                    ->columnSpanFull(),
            ]);
    }
}
