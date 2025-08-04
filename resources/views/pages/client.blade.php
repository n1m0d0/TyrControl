<x-layouts.app title="{{ __('Clients') }}">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Clients') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Manage the clients of system') }}
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @livewire('component-client')
</x-layouts.app>
