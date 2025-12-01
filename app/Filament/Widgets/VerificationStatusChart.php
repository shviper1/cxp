<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class VerificationStatusChart extends ChartWidget
{
    protected ?string $heading = 'User Verification Status';

    protected function getData(): array
    {
        $statuses = [
            'unverified' => User::where('verification_status', 'unverified')->count(),
            'pending' => User::where('verification_status', 'pending')->count(),
            'verified' => User::where('verification_status', 'verified')->count(),
            'rejected' => User::where('verification_status', 'rejected')->count(),
        ];

        return [
            'datasets' => [
                [
                    'data' => array_values($statuses),
                    'backgroundColor' => [
                        'rgb(156, 163, 175)', // unverified - gray
                        'rgb(245, 158, 11)',  // pending - amber
                        'rgb(34, 197, 94)',   // verified - green
                        'rgb(239, 68, 68)',   // rejected - red
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => [
                'Unverified',
                'Pending Review',
                'Verified',
                'Rejected',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
