<?php

namespace App\Filament\Resources\Guarantors\Pages;

use App\Filament\Resources\GuarantorResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateGuarantor extends CreateRecord
{
    protected static string $resource = GuarantorResource::class;

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }
}
