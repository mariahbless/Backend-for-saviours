<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'User Management';

    // --- Permissions (Admin Only) ---

    // Only admin can see Users in the sidebar
    public static function canViewAny(): bool
{
    return auth()->check() && auth()->user()->hasRole('admin');
}

    // Only admin can view a single user
    public static function canView($record): bool
    {
        return auth()->user()->hasRole('admin');
    }

    // Only admin can create users
    public static function canCreate(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    // Only admin can edit users
    public static function canEdit($record): bool
    {
        return auth()->user()->hasRole('admin');
    }

    // Only admin can delete users
    public static function canDelete($record): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
// namespace App\Filament\Resources\Users;

// use App\Filament\Resources\Users\Pages\CreateUser;
// use App\Filament\Resources\Users\Pages\EditUser;
// use App\Filament\Resources\Users\Pages\ListUsers;
// use App\Filament\Resources\Users\Schemas\UserForm;
// use App\Filament\Resources\Users\Tables\UsersTable;
// use App\Models\User;
// use BackedEnum;
// use Filament\Resources\Resource;
// use Filament\Schemas\Schema;
// use Filament\Support\Icons\Heroicon;
// use Filament\Tables\Table;

// class UserResource extends Resource
// {
//     protected static ?string $model = User::class;

//     protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

//     protected static ?string $recordTitleAttribute = 'User Management';

//     public static function form(Schema $schema): Schema
//     {
//         return UserForm::configure($schema);
//     }

//     public static function table(Table $table): Table
//     {
//         return UsersTable::configure($table);
//     }

//     public static function getRelations(): array
//     {
//         return [
//             //
//         ];
//     }

//     public static function getPages(): array
//     {
//         return [
//             'index' => ListUsers::route('/'),
//             'create' => CreateUser::route('/create'),
//             'edit' => EditUser::route('/{record}/edit'),
//         ];
//     }
// }
