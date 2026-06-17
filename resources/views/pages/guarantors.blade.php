<x-layouts::app :title="__('Guarantors')">
    <div class="p-6 bg-white dark:bg-zinc-900 rounded-xl shadow border border-zinc-200 dark:border-zinc-700">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-zinc-900 dark:text-white">Loan Guarantors</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">List of all loan guarantors and their associated applications.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead>
                    <tr class="bg-zinc-50 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400 font-semibold uppercase text-xs">
                        <th class="px-6 py-3 border-b border-zinc-200 dark:border-zinc-700">Name</th>
                        <th class="px-6 py-3 border-b border-zinc-200 dark:border-zinc-700">Relationship</th>
                        <th class="px-6 py-3 border-b border-zinc-200 dark:border-zinc-700">Phone</th>
                        <th class="px-6 py-3 border-b border-zinc-200 dark:border-zinc-700">Associated Borrower</th>
                        <th class="px-6 py-3 border-b border-zinc-200 dark:border-zinc-700">Loan Amount</th>
                        <th class="px-6 py-3 border-b border-zinc-200 dark:border-zinc-700">Occupation</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700 text-zinc-700 dark:text-zinc-300">
                    @forelse($guarantors as $guarantor)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-zinc-900 dark:text-white">
                                {{ $guarantor->name }}
                                @if($guarantor->email)
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $guarantor->email }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 text-xs font-semibold rounded bg-purple-50 text-purple-700 dark:bg-purple-950/30 dark:text-purple-300 border border-purple-100 dark:border-purple-900/50">
                                    {{ $guarantor->relationship }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $guarantor->phone }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-zinc-900 dark:text-white">
                                {{ $guarantor->loan->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-zinc-600 dark:text-zinc-300">
                                UGX {{ number_format($guarantor->loan->amount) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-zinc-500 dark:text-zinc-400">
                                {{ $guarantor->occupation ?? 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-zinc-500 dark:text-zinc-400">
                                No guarantors registered.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($guarantors->hasPages())
            <div class="mt-4">
                {{ $guarantors->links() }}
            </div>
        @endif
    </div>
</x-layouts::app>
