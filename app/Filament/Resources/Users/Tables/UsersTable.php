<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable()
                    ->sortable(),
                BadgeColumn::make('verification_status')
                    ->label('Verification')
                    ->colors([
                        'success' => 'verified',
                        'warning' => 'pending',
                        'danger' => 'rejected',
                        'gray' => 'unverified',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'verified',
                        'heroicon-o-clock' => 'pending',
                        'heroicon-o-x-circle' => 'rejected',
                        'heroicon-o-question-mark-circle' => 'unverified',
                    ])
                    ->sortable(),
                TextColumn::make('posts_count')
                    ->label('Posts')
                    ->counts('posts')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'suspended' => 'danger',
                        default => 'gray',
                    })
                    ->toggleable(),
                TextColumn::make('city')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('country')
                    ->searchable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('verification_status')
                    ->options([
                        'unverified' => 'Unverified',
                        'pending' => 'Pending Review',
                        'verified' => 'Verified',
                        'rejected' => 'Rejected',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'suspended' => 'Suspended',
                    ]),
                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('verify')
                    ->label('Verify')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->verification_status === 'pending')
                    ->action(function ($record) {
                        $record->update([
                            'verification_status' => 'verified',
                            'verified_at' => now(),
                        ]);
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->verification_status === 'pending')
                    ->action(function ($record) {
                        $record->update(['verification_status' => 'rejected']);
                    }),
                Action::make('view_documents')
                    ->label('View Documents')
                    ->icon('heroicon-o-eye')
                    ->visible(fn ($record) => $record->id_document_path || $record->selfie_path)
                    ->action(function ($record) {
                        // This would open a modal to view documents
                        return redirect()->back();
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
