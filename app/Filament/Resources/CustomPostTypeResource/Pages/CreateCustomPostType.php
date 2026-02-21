<?php

namespace App\Filament\Resources\CustomPostTypeResource\Pages;

use App\Filament\Resources\CustomPostTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomPostType extends CreateRecord
{
    protected static string $resource = CustomPostTypeResource::class;
}
