<x-layouts.app title="{{ __('Branches') }}">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Branches') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Manage the branches of system') }}
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @livewire('component-branch')
</x-layouts.app>