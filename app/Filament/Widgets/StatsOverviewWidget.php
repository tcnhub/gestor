<?php

namespace App\Filament\Widgets;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Posts', Post::where('type', 'post')->count())
                ->description('Published: ' . Post::where('type', 'post')->where('status', 'publish')->count())
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),

            Stat::make('Total Pages', Post::where('type', 'page')->count())
                ->description('Published: ' . Post::where('type', 'page')->where('status', 'publish')->count())
                ->descriptionIcon('heroicon-m-document')
                ->color('info'),

            Stat::make('Users', User::count())
                ->description('Active members')
                ->descriptionIcon('heroicon-m-users')
                ->color('warning'),

            Stat::make('Comments', Comment::count())
                ->description('Pending: ' . Comment::where('status', 'pending')->count())
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('primary'),

            Stat::make('Total Views', Post::sum('views'))
                ->description('All-time post views')
                ->descriptionIcon('heroicon-m-eye')
                ->color('success'),

            Stat::make('Media Files', \Spatie\MediaLibrary\MediaCollections\Models\Media::count())
                ->description('Uploaded files')
                ->descriptionIcon('heroicon-m-photo')
                ->color('gray'),
        ];
    }
}
