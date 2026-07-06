<x-filament-panels::page.simple>
    <div class="mb-8 text-center">
        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-primary-500 text-xl font-bold text-white shadow-lg shadow-primary-500/30">
            SP
        </div>

        <h1 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
            {{ $this->getHeading() }}
        </h1>

        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            {{ $this->getSubheading() }}
        </p>
    </div>

    <x-filament-panels::form wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>
</x-filament-panels::page.simple>
