<?php

namespace App\Filament\Resources\Loans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use App\Models\User;

class LoanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('User')
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                TextInput::make('amount')
                    ->label('Loan Amount (UGX)')
                    ->required()
                    ->numeric()
                    ->minValue(1),

                Select::make('description')
                    ->label('Loan Type')
                    ->options([
                        'School Fees Loan' => 'School Fees Loan',
                        'Business Loan'    => 'Business Loan',
                        'Personal Loan'    => 'Personal Loan',
                        'Land Title Loan'  => 'Land Title Loan',
                    ])
                    ->required(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending'  => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }
}