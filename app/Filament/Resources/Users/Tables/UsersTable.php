<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

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
                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->color('primary')
                    ->listWithLineBreaks()
                    ->limitList(2)
                    ->expandableLimitedList(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'suspended' => 'danger',
                        default => 'gray',
                    })
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
                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload(),
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
                    ->color('info')
                    ->visible(fn ($record) => $record->id_document_path || $record->selfie_path)
                    ->modalHeading('User Verification Documents')
                    ->modalContent(function ($record) {
                        $content = '<div class="space-y-4">';

                        if ($record->id_document_path) {
                            $content .= '<div>';
                            $content .= '<h4 class="font-semibold text-gray-800 mb-2">ID Document:</h4>';
                            $content .= '<img src="'.Storage::url($record->id_document_path).'" alt="ID Document" class="max-w-full h-auto rounded-lg border" style="max-height: 400px;">';
                            $content .= '</div>';
                        }

                        if ($record->selfie_path) {
                            $content .= '<div>';
                            $content .= '<h4 class="font-semibold text-gray-800 mb-2">Selfie:</h4>';
                            $content .= '<img src="'.Storage::url($record->selfie_path).'" alt="Selfie" class="max-w-full h-auto rounded-lg border" style="max-height: 400px;">';
                            $content .= '</div>';
                        }

                        if ($record->verification_notes) {
                            $content .= '<div>';
                            $content .= '<h4 class="font-semibold text-gray-800 mb-2">Verification Notes:</h4>';
                            $content .= '<p class="text-gray-600">'.htmlspecialchars($record->verification_notes).'</p>';
                            $content .= '</div>';
                        }

                        $content .= '</div>';

                        return new HtmlString($content);
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),
                Action::make('view_details')
                    ->label('View Details')
                    ->icon('heroicon-o-information-circle')
                    ->color('gray')
                    ->modalHeading('User Details & Activity')
                    ->modalContent(function ($record) {
                        $posts = $record->posts()->latest()->take(5)->get();
                        $totalPosts = $record->posts()->count();

                        $content = '<div class="space-y-6">';

                        // User Info
                        $content .= '<div>';
                        $content .= '<h3 class="text-lg font-semibold text-gray-800 mb-3">User Information</h3>';
                        $content .= '<div class="grid grid-cols-2 gap-4 text-sm">';
                        $content .= '<div><strong>Name:</strong> '.htmlspecialchars($record->name).'</div>';
                        $content .= '<div><strong>Email:</strong> '.htmlspecialchars($record->email).'</div>';
                        $content .= '<div><strong>Status:</strong> '.ucfirst($record->status).'</div>';
                        $content .= '<div><strong>Verification:</strong> '.ucfirst($record->verification_status).'</div>';
                        $content .= '<div><strong>Joined:</strong> '.$record->created_at->format('M j, Y').'</div>';
                        $content .= '<div><strong>Last Updated:</strong> '.$record->updated_at->format('M j, Y').'</div>';
                        $content .= '</div>';
                        $content .= '</div>';

                        // Activity Stats
                        $content .= '<div>';
                        $content .= '<h3 class="text-lg font-semibold text-gray-800 mb-3">Activity Statistics</h3>';
                        $content .= '<div class="grid grid-cols-3 gap-4">';
                        $content .= '<div class="text-center p-3 bg-blue-50 rounded-lg">';
                        $content .= '<div class="text-2xl font-bold text-blue-600">'.$totalPosts.'</div>';
                        $content .= '<div class="text-sm text-gray-600">Total Posts</div>';
                        $content .= '</div>';

                        $approvedPosts = $record->posts()->where('status', 'approved')->count();
                        $content .= '<div class="text-center p-3 bg-green-50 rounded-lg">';
                        $content .= '<div class="text-2xl font-bold text-green-600">'.$approvedPosts.'</div>';
                        $content .= '<div class="text-sm text-gray-600">Approved Posts</div>';
                        $content .= '</div>';

                        $pendingPosts = $record->posts()->where('status', 'pending')->count();
                        $content .= '<div class="text-center p-3 bg-yellow-50 rounded-lg">';
                        $content .= '<div class="text-2xl font-bold text-yellow-600">'.$pendingPosts.'</div>';
                        $content .= '<div class="text-sm text-gray-600">Pending Posts</div>';
                        $content .= '</div>';
                        $content .= '</div>';
                        $content .= '</div>';

                        // Recent Posts
                        if ($posts->count() > 0) {
                            $content .= '<div>';
                            $content .= '<h3 class="text-lg font-semibold text-gray-800 mb-3">Recent Posts</h3>';
                            $content .= '<div class="space-y-2">';
                            foreach ($posts as $post) {
                                $statusColor = match ($post->status) {
                                    'approved' => 'text-green-600',
                                    'pending' => 'text-yellow-600',
                                    'rejected' => 'text-red-600',
                                    default => 'text-gray-600'
                                };
                                $content .= '<div class="flex justify-between items-center p-2 bg-gray-50 rounded">';
                                $content .= '<div>';
                                $content .= '<div class="font-medium">'.htmlspecialchars(Str::limit($post->title, 50)).'</div>';
                                $content .= '<div class="text-sm text-gray-500">'.$post->created_at->format('M j, Y').'</div>';
                                $content .= '</div>';
                                $content .= '<span class="'.$statusColor.' text-sm font-medium capitalize">'.$post->status.'</span>';
                                $content .= '</div>';
                            }
                            $content .= '</div>';
                            $content .= '</div>';
                        }

                        $content .= '</div>';

                        return new HtmlString($content);
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),
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
