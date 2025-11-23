<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
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
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                // TextColumn::make('email_verified_at')
                //     ->dateTime()
                //     ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'suspended' => 'danger',
                        'pending' => 'warning',
                    }),
                TextColumn::make('roles.name')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'suspended' => 'Suspended',
                        'pending' => 'Pending',
                    ]),
                SelectFilter::make('roles')
                    ->relationship('roles', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('suspend')
                    ->label('Suspend')
                    ->color('danger')
                    ->icon('heroicon-o-no-symbol')
                    ->visible(fn (User $record) => $record->status === 'active')
                    ->form([
                        DateTimePicker::make('suspended_until')
                            ->required()
                            ->minDate(now())
                            ->label('Suspend Until'),
                    ])
                    ->action(function (User $record, array $data): void {
                        $record->update([
                            'status' => 'suspended',
                            'suspended_until' => $data['suspended_until'],
                        ]);
                    }),
                Action::make('activate')
                    ->label('Activate')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn (User $record) => $record->status !== 'active')
                    ->action(function (User $record): void {
                        $record->update([
                            'status' => 'active',
                            'suspended_until' => null,
                        ]);
                    }),
                DeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([

                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
