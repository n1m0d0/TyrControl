<x-layouts.app title="{{ __('Inventories') }}">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Inventories') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Manage the inventories of system') }}
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @livewire('component-inventory')
</x-layouts.app>
