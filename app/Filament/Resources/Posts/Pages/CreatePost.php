<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected static ?string $title = 'Create New Post';

    protected static ?string $breadcrumb = 'Create Post';

    public function getTitle(): string
    {
        return '📝 Create New Post';
    }

    public function getSubheading(): ?string
    {
        return 'Follow the steps below to create an attractive post that will get maximum visibility.';
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function getCreateFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateFormAction()
            ->label('🚀 Publish Post')
            ->icon('heroicon-o-paper-airplane')
            ->color('success');
    }

    protected function getCancelFormAction(): \Filament\Actions\Action
    {
        return parent::getCancelFormAction()
            ->label('Cancel')
            ->color('gray');
    }
}
