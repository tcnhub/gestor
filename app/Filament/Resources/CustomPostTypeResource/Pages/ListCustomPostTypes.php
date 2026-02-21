<?php

namespace App\Filament\Resources\CustomPostTypeResource\Pages;

use App\Filament\Resources\CustomPostTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomPostTypes extends ListRecords
{
    protected static string $resource = CustomPostTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
