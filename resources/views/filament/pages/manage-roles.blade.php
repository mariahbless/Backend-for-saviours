
<x-filament-panels::page>
    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">

        <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <thead>
                <tr style="background: #F9FAFB; color: #6B7280; font-size: 12px; text-transform: uppercase;">
                    <th style="padding: 12px 16px; text-align: left;">Name</th>
                    <th style="padding: 12px 16px; text-align: left;">Email</th>
                    <th style="padding: 12px 16px; text-align: left;">Current Role</th>
                    <th style="padding: 12px 16px; text-align: left;">Change Role</th>
                    <th style="padding: 12px 16px; text-align: left;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($this->getUsers() as $user)
                <tr style="border-top: 1px solid #F3F4F6;">
                    <td style="padding: 14px 16px; font-weight: 600; color: #1F2937;">
                        {{ $user->name }}
                    </td>
                    <td style="padding: 14px 16px; color: #6B7280;">
                        {{ $user->email }}
                    </td>
                    <td style="padding: 14px 16px;">
                        @if($user->roles->first())
                            <span style="background: #DBEAFE; color: #2563EB; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">
                                {{ ucfirst($user->roles->first()->name) }}
                            </span>
                        @else
                            <span style="background: #F3F4F6; color: #9CA3AF; padding: 4px 10px; border-radius: 20px; font-size: 11px;">
                                No Role
                            </span>
                        @endif
                    </td>
                    <td style="padding: 14px 16px;">
                        <select
                            wire:model="selectedRoles.{{ $user->id }}"
                            style="border: 1px solid #E5E7EB; border-radius: 8px; padding: 6px 12px; font-size: 13px; color: #1F2937; outline: none;">
                            <option value="">-- Select Role --</option>
                            @foreach($this->getRoles() as $role)
                                <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td style="padding: 14px 16px;">
                        <button
                            wire:click="updateRole({{ $user->id }})"
                            style="background: #1E40AF; color: white; padding: 6px 16px; border-radius: 8px; border: none; cursor: pointer; font-size: 13px; font-weight: 600;">
                            Update
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-filament-panels::page>