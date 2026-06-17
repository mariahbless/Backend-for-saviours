<x-layouts::app :title="__('Roles & Permissions')">
    <div class="space-y-6">
        <!-- Section 1: System Roles & Permissions -->
        <div class="p-6 bg-white dark:bg-zinc-900 rounded-xl shadow border border-zinc-200 dark:border-zinc-700">
            <div>
                <h1 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">System Roles & Permissions</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">Overview of all system roles and the functional permissions associated with each.</p>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                @foreach($roles as $role)
                    <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-lg">🛡️</span>
                            <h3 class="text-base font-bold text-zinc-950 dark:text-white uppercase">{{ $role->name }}</h3>
                        </div>
                        <div class="flex flex-wrap gap-1.5">
                            @forelse($role->permissions as $perm)
                                <span class="px-2 py-0.5 text-xs font-medium bg-indigo-50 text-indigo-700 dark:bg-indigo-950/30 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-900/50 rounded">
                                    {{ $perm->name }}
                                </span>
                            @empty
                                <span class="text-xs text-zinc-400">No permissions assigned.</span>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Section 2: User Role Assignments -->
        <div class="p-6 bg-white dark:bg-zinc-900 rounded-xl shadow border border-zinc-200 dark:border-zinc-700">
            <div class="mb-4">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-1">User Role Assignments</h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Review which roles are assigned to active system users.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-sm">
                    <thead>
                        <tr class="bg-zinc-50 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400 font-semibold uppercase text-xs">
                            <th class="px-6 py-3 border-b border-zinc-200 dark:border-zinc-700">Name</th>
                            <th class="px-6 py-3 border-b border-zinc-200 dark:border-zinc-700">Email</th>
                            <th class="px-6 py-3 border-b border-zinc-200 dark:border-zinc-700">Role</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700 text-zinc-700 dark:text-zinc-300">
                        @foreach($users as $user)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-zinc-900 dark:text-white">
                                    {{ $user->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-zinc-500 dark:text-zinc-400">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @forelse($user->roles as $role)
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @empty
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-zinc-100 text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                                            No Role
                                        </span>
                                    @endforelse
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts::app>
