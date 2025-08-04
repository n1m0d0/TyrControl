<x-layouts.app title="{{ __('Movements') }}">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Movements') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Manage the movements of system') }}
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @livewire('component-movement')
</x-layouts.app>
