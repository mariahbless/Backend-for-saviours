<?php

namespace App\Filament\Resources\Loans\Pages;

use App\Filament\Resources\Loans\LoanResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditLoan extends EditRecord
{
    protected static string $resource = LoanResource::class;

    public function getMaxContentWidth(): Width | string | null
    {
        return Width::Full;
    }

    protected function getHeaderActions(): array
    {
        return [

            DeleteAction::make()
                ->label('Delete Loan')
                ->color('danger')
                ->icon('heroicon-o-trash')

                // Confirmation Modal
                ->modalHeading('Delete Loan Record')
                ->modalDescription('Are you sure you want to delete this loan? This action cannot be undone.')
                ->modalSubmitActionLabel('Yes, Delete Loan')

                // Custom Delete Notification
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Loan Deleted Successfully')
                        ->body('The loan record has been removed from the system.')
                        ->icon('heroicon-o-trash')
                        ->duration(5000)
                ),
        ];
    }

    // Custom Save Notification
    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Loan Updated Successfully')
            ->body('The loan information has been updated successfully.')
            ->icon('heroicon-o-check-circle')
            ->duration(5000);
    }
}