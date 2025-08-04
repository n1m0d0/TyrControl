<x-layouts.app title="{{ __('Products') }}">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Products') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Manage the products of system') }}
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @livewire('component-product')
</x-layouts.app>
