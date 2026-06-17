<?php

namespace App\Filament\Resources\Loans\Pages;

use App\Filament\Resources\Loans\LoanResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateLoan extends CreateRecord
{
    protected static string $resource = LoanResource::class;

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }
}
