<x-layouts.app title="{{ __('Sales') }}">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Sales') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Manage the sales of system') }}
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @livewire('component-detail', ['id' => $id])
</x-layouts.app>
