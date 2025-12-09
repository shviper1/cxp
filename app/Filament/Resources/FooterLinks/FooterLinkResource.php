<?php

namespace App\Filament\Resources\FooterLinks;

use App\Filament\Resources\FooterLinks\Pages\CreateFooterLink;
use App\Filament\Resources\FooterLinks\Pages\EditFooterLink;
use App\Filament\Resources\FooterLinks\Pages\ListFooterLinks;
use App\Filament\Resources\FooterLinks\Schemas\FooterLinkForm;
use App\Filament\Resources\FooterLinks\Tables\FooterLinksTable;
use App\Models\FooterLink;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class FooterLinkResource extends Resource
{
    protected static ?string $model = FooterLink::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-link';

    protected static ?int $navigationSort = 8;

    protected static UnitEnum|string|null $navigationGroup = 'Site Settings';

    public static function form(Schema $schema): Schema
    {
        return FooterLinkForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FooterLinksTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFooterLinks::route('/'),
            'create' => CreateFooterLink::route('/create'),
            'edit' => EditFooterLink::route('/{record}/edit'),
        ];
    }
}
