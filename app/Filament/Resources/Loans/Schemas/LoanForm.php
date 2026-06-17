<?php

namespace App\Filament\Resources\Loans\Schemas;

use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LoanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General Information')
                    ->schema([
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
                                'Business Loan' => 'Business Loan',
                                'Personal Loan' => 'Personal Loan',
                                'Land Title Loan' => 'Land Title Loan',
                            ])
                            ->required(),

                        Select::make('collateral')
                            ->label('Collateral')
                            ->options([
                                'Land' => 'Land',
                                'Vehicle Logbook' => 'Vehicle Logbook',
                                'Business Assets' => 'Business Assets',
                            ])
                            ->required(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->default('pending')
                            ->required(),
                    ]),

                Section::make('Applicant Details')
                    ->schema([
                        TextInput::make('name')
                            ->label('Applicant Name')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required(),
                        TextInput::make('contact')
                            ->label('Contact Number')
                            ->required(),
                        TextInput::make('other_contact')
                            ->label('Other Contact'),
                        TextInput::make('gender')
                            ->label('Gender'),
                        TextInput::make('location')
                            ->label('Location'),
                        TextInput::make('current_address')
                            ->label('Current Address'),
                        TextInput::make('occupation')
                            ->label('Occupation'),
                        TextInput::make('monthly_income')
                            ->label('Monthly Income (UGX)')
                            ->numeric(),
                    ]),

                Section::make('Next of Kin')
                    ->schema([
                        TextInput::make('next_of_kin_name')
                            ->label('Next of Kin Name')
                            ->required(),
                        TextInput::make('next_of_kin_contact')
                            ->label('Next of Kin Contact')
                            ->required(),
                    ]),

                Section::make('Identity Documents')
                    ->schema([
                        FileUpload::make('id_image_front')
                            ->label('ID Image Front')
                            ->image()
                            ->directory('id_images')
                            ->disk('public'),
                        FileUpload::make('id_image_back')
                            ->label('ID Image Back')
                            ->image()
                            ->directory('id_images')
                            ->disk('public'),
                    ]),
            ]);
    }
}
