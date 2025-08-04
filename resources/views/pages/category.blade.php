<x-layouts.app title="{{ __('Categories') }}">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Categories') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Manage the categories of system') }}
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @livewire('component-category')
</x-layouts.app>