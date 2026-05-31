<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-x-3">
                <div class="flex flex-col">
                    <h2 class="text-xl font-bold tracking-tight sm:text-2xl">
                        Welcome back, {{ filament()->auth()->user()->name }}
                    </h2>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ now()->format('l, jS F Y') }} &bull; Logged in as {{ filament()->auth()->user()->name }}
                    </p>
                </div>
            </div>
            
            <div class="flex shrink-0">
                <x-filament::button
                    tag="a"
                    href="{{ \App\Filament\Resources\LoanResource::getUrl('create') }}"
                    color="primary"
                >
                    + New Loan
                </x-filament::button>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
