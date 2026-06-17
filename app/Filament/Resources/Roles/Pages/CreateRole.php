<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }
}
