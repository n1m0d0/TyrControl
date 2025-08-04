<x-layouts.app title="{{ __('Batches') }}">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Batches') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Manage the batches of system') }}
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @livewire('component-batch')
</x-layouts.app>