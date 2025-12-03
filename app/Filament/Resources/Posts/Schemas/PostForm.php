<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

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
                                        ->required()
                                        ->maxLength(30)
                                        ->placeholder('+1 (555) 123-4567')
                                        ->helperText('Include country code for international numbers')
                                        ->rule('string'),
                                ]),

                            Grid::make(2)
                                ->schema([
                                    TextInput::make('age')
                                        ->label('Age (if applicable)')
                                        ->numeric()
                                        ->minValue(1)
                                        ->nullable()
                                        ->helperText('Only share if relevant to your listing'),

                                    Select::make('user_id')
                                        ->label('Assign to User')
                                        ->relationship('user', 'name')
                                        ->placeholder('Select user')
                                        ->searchable()
                                        ->preload()
                                        ->helperText('Attach this post to an existing user account if needed')
                                        ->nullable(),
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

                            Repeater::make('media')
                                ->label('Photos & Videos')
                                ->relationship('media')
                                ->minItems(1)
                                ->maxItems(10)
                                ->columns(1)
                                ->helperText('Upload images or videos (max 10 files, up to 10MB each). Existing files remain unless you remove them.')
                                ->schema([
                                    FileUpload::make('file_path')
                                        ->label('File')
                                        ->disk('public')
                                        ->directory('posts')
                                        ->visibility('public')
                                        ->preserveFileNames()
                                        ->acceptedFileTypes([
                                            'image/*',
                                            'video/*',
                                        ])
                                        ->maxSize(10240)
                                        ->downloadable()
                                        ->openable()
                                        ->columnSpanFull()
                                        ->required()
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            $storedPath = is_array($state) ? ($state[0] ?? null) : $state;

                                            if (! filled($storedPath)) {
                                                return;
                                            }

                                            $extension = Str::lower(pathinfo($storedPath, PATHINFO_EXTENSION));
                                            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                                            $set('type', in_array($extension, $imageExtensions, true) ? 'image' : 'video');
                                        }),

                                    Hidden::make('type')
                                        ->default('image')
                                        ->dehydrated()
                                        ->reactive(),
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
