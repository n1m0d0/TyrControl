<x-layouts.app title="{{ __('Boxes') }}">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Boxes') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Manage the boxes of system') }}
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @livewire('component-box')
</x-layouts.app>