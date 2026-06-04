<?php

namespace App\Filament\Resources\Loans\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class LoansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc') 
               ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Applicant Name')
                    ->searchable(),

                TextColumn::make('amount')
                    ->money('UGX', true)
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Loan Type')
                    ->searchable(),

                TextColumn::make('collateral')
                    ->label('Collateral')
                     ->searchable(),

                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ]);
    }
}