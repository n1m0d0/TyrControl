<x-layouts.app title="{{ __('Warehouses') }}">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Warehouses') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Manage the warehouses of system') }}
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @livewire('component-warehouse')
</x-layouts.app>