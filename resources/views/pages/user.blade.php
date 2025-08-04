<x-layouts.app title="{{ __('Users') }}">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Users') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Manage the users of system') }}
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @livewire('component-user')
</x-layouts.app>