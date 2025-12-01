<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\User;
use Filament\Widgets\Widget;

class RecentActivityWidget extends Widget
{
    protected string $view = 'filament-widgets.recent-activity-widget';

    protected int|string|array $columnSpan = 'full';

    public function getRecentUsers()
    {
        return User::latest()
            ->take(5)
            ->get(['id', 'name', 'email', 'created_at', 'verification_status']);
    }

    public function getRecentPosts()
    {
        return Post::with(['user:id,name'])
            ->latest()
            ->take(5)
            ->get(['id', 'title', 'status', 'created_at', 'user_id']);
    }
}
