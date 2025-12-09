<?php

namespace App\Filament\Resources\FooterLinks\Tables;

use App\Models\FooterLink;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FooterLinksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')
                    ->label('Label')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(function (string $state) {
                        return match ($state) {
                            'primary' => 'Primary',
                            'support' => 'Support',
                            'social' => 'Social',
                            'legal' => 'Legal',
                            'payment' => 'Payment',
                            default => ucfirst($state),
                        };
                    }),
                TextColumn::make('url')
                    ->label('URL')
                    ->limit(40)
                    ->toggleable()
                    ->url(fn (FooterLink $record) => $record->url, true),
                ImageColumn::make('icon_path')
                    ->label('Icon')
                    ->disk('public')
                    ->square()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('display_order')
                    ->sortable()
                    ->label('Order'),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Link Group')
                    ->options([
                        'primary' => 'Primary Navigation',
                        'support' => 'Support Links',
                        'social' => 'Social Media',
                        'legal' => 'Legal',
                        'payment' => 'Payment Methods',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
