<x-layouts::app :title="__('Activity Logs')">
    <div class="p-6 bg-white dark:bg-zinc-900 rounded-xl shadow border border-zinc-200 dark:border-zinc-700">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-zinc-900 dark:text-white">Activity Logs</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">A real-time list of system-wide user actions and events.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead>
                    <tr class="bg-zinc-50 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400 font-semibold uppercase text-xs">
                        <th class="px-6 py-3 border-b border-zinc-200 dark:border-zinc-700">Timestamp</th>
                        <th class="px-6 py-3 border-b border-zinc-200 dark:border-zinc-700">User</th>
                        <th class="px-6 py-3 border-b border-zinc-200 dark:border-zinc-700">Action</th>
                        <th class="px-6 py-3 border-b border-zinc-200 dark:border-zinc-700">Description</th>
                        <th class="px-6 py-3 border-b border-zinc-200 dark:border-zinc-700">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700 text-zinc-700 dark:text-zinc-300">
                    @forelse($logs as $log)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-zinc-500 dark:text-zinc-400">
                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-zinc-900 dark:text-white">
                                {{ $log->user_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full 
                                    @if($log->action === 'Login') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                    @elseif($log->action === 'Logout') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                    @elseif(str_contains($log->action, 'Created')) bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                    @elseif(str_contains($log->action, 'Approved')) bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300
                                    @elseif(str_contains($log->action, 'Rejected')) bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                    @else bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-300 @endif">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ $log->description }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-zinc-500 dark:text-zinc-400">
                                {{ $log->ip_address }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-zinc-500 dark:text-zinc-400">
                                No activity logs found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="mt-4">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</x-layouts::app>
