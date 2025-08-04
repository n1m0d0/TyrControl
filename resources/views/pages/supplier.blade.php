<x-layouts.app title="{{ __('Suppliers') }}">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Suppliers') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Manage the suppliers of system') }}
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @livewire('component-supplier')
</x-layouts.app>