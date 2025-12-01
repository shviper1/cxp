<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $suspendedUsers = User::where('status', 'suspended')->count();
        $verifiedUsers = User::where('verification_status', 'verified')->count();
        $pendingVerification = User::where('verification_status', 'pending')->count();

        return [
            Stat::make('Total Users', $totalUsers)
                ->description('All registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Active Users', $activeUsers)
                ->description('Currently active accounts')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Suspended Users', $suspendedUsers)
                ->description('Temporarily suspended')
                ->descriptionIcon('heroicon-m-user-minus')
                ->color('danger'),

            Stat::make('Verified Users', $verifiedUsers)
                ->description('Identity verified')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('success'),

            Stat::make('Pending Verification', $pendingVerification)
                ->description('Awaiting review')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('New This Month', User::whereMonth('created_at', now()->month)->count())
                ->description('Registered this month')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
        ];
    }
}
