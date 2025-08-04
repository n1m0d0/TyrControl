<x-layouts.app title="{{ __('Brands') }}">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Brands') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Manage the brands of system') }}
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @livewire('component-brand')
</x-layouts.app>
