<?php

namespace App\Filament\Pages;

use App\Models\User;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Spatie\Permission\Models\Role;
use UnitEnum;

class ManageRoles extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationLabel = 'Manage Roles';

    protected static UnitEnum|string|null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Manage User Roles';

    protected string $view = 'filament.pages.manage-roles';

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    public array $selectedRoles = [];

    public function mount(): void
    {

        foreach (User::with('roles')->get() as $user) {
            $this->selectedRoles[$user->id] = $user->roles->first()?->name ?? '';
        }
    }

    public function updateRole(int $userId): void
    {
        $user = User::findOrFail($userId);
        $role = $this->selectedRoles[$userId];

        if ($role) {
            $user->syncRoles([$role]);

            \App\Services\ActivityLogger::log(
                'Updated Role',
                "Assigned role '{$role}' to user {$user->name}."
            );

            Notification::make()
                ->title('Role Updated')
                ->body("Role for {$user->name} changed to {$role}.")
                ->success()
                ->send();
        }
    }

    public function getRoles(): array
    {
        return Role::pluck('name')->toArray();
    }

    public function getUsers()
    {
        return User::with('roles')->get();
    }
}
