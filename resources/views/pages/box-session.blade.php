<x-layouts.app title="{{ __('Box sessions') }}">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Box sessions') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Manage the box sessions of system') }}
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @livewire('component-box-session')
</x-layouts.app>
