<x-layouts.app title="{{ __('Companies') }}">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Companies') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Manage the companies of system') }}
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @livewire('component-company')
</x-layouts.app>
