<?php

namespace App\Filament\Resources\Guarantors\Schemas;

use App\Models\Loan;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GuarantorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Guarantor Details')
                    ->schema([
                        Select::make('loan_id')
                            ->label('Loan / Borrower')
                            ->options(Loan::all()->mapWithKeys(function ($loan) {
                                return [$loan->id => "Loan #{$loan->id} - {$loan->name} (UGX ".number_format($loan->amount).')'];
                            }))
                            ->searchable()
                            ->required(),

                        TextInput::make('name')
                            ->label('Guarantor Name')
                            ->required(),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->email(),

                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->required(),

                        TextInput::make('relationship')
                            ->label('Relationship to Borrower')
                            ->placeholder('e.g. Spouse, Business Partner, Parent')
                            ->required(),

                        TextInput::make('location')
                            ->label('Location / Address'),

                        TextInput::make('occupation')
                            ->label('Occupation'),

                        TextInput::make('id_number')
                            ->label('ID Number'),
                    ]),

                Section::make('Guarantor Identity Documents')
                    ->schema([
                        FileUpload::make('id_image_front')
                            ->label('ID Front Image')
                            ->image()
                            ->directory('guarantor_id_images')
                            ->disk('public'),

                        FileUpload::make('id_image_back')
                            ->label('ID Back Image')
                            ->image()
                            ->directory('guarantor_id_images')
                            ->disk('public'),
                    ]),
            ]);
    }
}
