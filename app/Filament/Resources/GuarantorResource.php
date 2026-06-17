<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Guarantors\Pages\CreateGuarantor;
use App\Filament\Resources\Guarantors\Pages\EditGuarantor;
use App\Filament\Resources\Guarantors\Pages\ListGuarantors;
use App\Filament\Resources\Guarantors\Schemas\GuarantorForm;
use App\Filament\Resources\Guarantors\Tables\GuarantorsTable;
use App\Models\Guarantor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class GuarantorResource extends Resource
{
    protected static ?string $model = Guarantor::class;

    // Sidebar Settings
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-users';

    protected static UnitEnum|string|null $navigationGroup = 'Loan Management';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    // --- Permissions ---

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'loan officer', 'viewer']);
    }

    public static function canView($record): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'loan officer', 'viewer']);
    }

    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'loan officer']);
    }

    public static function canEdit($record): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'loan officer']);
    }

    public static function canDelete($record): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    public static function form(Schema $schema): Schema
    {
        return GuarantorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GuarantorsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGuarantors::route('/'),
            'create' => CreateGuarantor::route('/create'),
            'edit' => EditGuarantor::route('/{record}/edit'),
        ];
    }
}
