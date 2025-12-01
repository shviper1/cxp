<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PostStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalPosts = Post::count();
        $approvedPosts = Post::where('status', 'approved')->count();
        $pendingPosts = Post::where('status', 'pending')->count();
        $rejectedPosts = Post::where('status', 'rejected')->count();
        $paidPosts = Post::where('payment_status', 'paid')->count();
        $freePosts = Post::where('payment_status', 'free')->count();

        return [
            Stat::make('Total Posts', $totalPosts)
                ->description('All submitted posts')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

            Stat::make('Approved Posts', $approvedPosts)
                ->description('Published and visible')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Pending Posts', $pendingPosts)
                ->description('Awaiting approval')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Rejected Posts', $rejectedPosts)
                ->description('Not approved')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),

            Stat::make('Paid Posts', $paidPosts)
                ->description('Premium listings')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),

            Stat::make('Free Posts', $freePosts)
                ->description('Free listings')
                ->descriptionIcon('heroicon-m-gift')
                ->color('info'),
        ];
    }
}
