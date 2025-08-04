<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    @persist('toast')
        <livewire:toasts />
    @endpersist

    <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
            <x-app-logo />
        </a>

        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Platform')" class="grid">
                <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>

        @role('administrator')
            <flux:navlist.group :heading="__('Administration')" class="grid">
                <flux:navlist.item icon="user" :href="route('admin.user')" :current="request()->routeIs('admin.user')"
                    wire:navigate>{{ __('Users') }}
                </flux:navlist.item>

                <flux:navlist.item icon="building-office-2" :href="route('admin.company')"
                    :current="request()->routeIs('admin.company')" wire:navigate>{{ __('Companies') }}
                </flux:navlist.item>

                <flux:navlist.item icon="building-storefront" :href="route('admin.branch')"
                    :current="request()->routeIs('admin.branch')" wire:navigate>{{ __('Branches') }}
                </flux:navlist.item>

                <flux:navlist.item icon="shopping-bag" :href="route('admin.box')"
                    :current="request()->routeIs('admin.box')" wire:navigate>{{ __('Boxes') }}
                </flux:navlist.item>

                <flux:navlist.item icon="wallet" :href="route('admin.warehouse')"
                    :current="request()->routeIs('admin.warehouse')" wire:navigate>{{ __('Warehouses') }}
                </flux:navlist.item>

                <flux:navlist.item icon="briefcase" :href="route('admin.brand')"
                    :current="request()->routeIs('admin.brand')" wire:navigate>{{ __('Brands') }}
                </flux:navlist.item>

                <flux:navlist.item icon="square-3-stack-3d" :href="route('admin.category')"
                    :current="request()->routeIs('admin.category')" wire:navigate>{{ __('Categories') }}
                </flux:navlist.item>

                <flux:navlist.item icon="swatch" :href="route('admin.product')"
                    :current="request()->routeIs('admin.product')" wire:navigate>{{ __('Products') }}
                </flux:navlist.item>
            </flux:navlist.group>
        @endrole


        <flux:navlist.group :heading="__('Inventory')" class="grid">
            @role('administrator|supervisor')
                <flux:navlist.item icon="identification" :href="route('super.supplier')"
                    :current="request()->routeIs('super.supplier')" wire:navigate>{{ __('Suppliers') }}
                </flux:navlist.item>

                <flux:navlist.item icon="inbox-stack" :href="route('super.batch')"
                    :current="request()->routeIs('super.batch')" wire:navigate>{{ __('Batches') }}
                </flux:navlist.item>

                <flux:navlist.item icon="arrows-right-left" :href="route('super.movement')"
                    :current="request()->routeIs('super.movement')" wire:navigate>{{ __('Movements') }}
                </flux:navlist.item>
            @endrole

            @role('administrator|supervisor|seller')
                <flux:navlist.item icon="table-cells" :href="route('super.inventory')"
                    :current="request()->routeIs('super.inventory')" wire:navigate>{{ __('Inventories') }}
                </flux:navlist.item>
            @endrole
        </flux:navlist.group>

        @role('administrator|seller')
            <flux:navlist.group :heading="__('Sale')" class="grid">
                <flux:navlist.item icon="user-group" :href="route('seller.client')"
                    :current="request()->routeIs('seller.client')" wire:navigate>{{ __('Clients') }}
                </flux:navlist.item>

                <flux:navlist.item icon="computer-desktop" :href="route('seller.box-session')"
                    :current="request()->routeIs('seller.box-session')" wire:navigate>{{ __('Box sessions') }}
                </flux:navlist.item>

                <flux:navlist.item icon="shopping-bag" :href="route('seller.sale')"
                    :current="request()->routeIs('seller.sale')" wire:navigate>{{ __('Sales') }}
                </flux:navlist.item>
            </flux:navlist.group>
        @endrole

        <flux:spacer />

        <!-- Desktop User Menu -->
        <flux:dropdown position="bottom" align="start">
            <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
                icon-trailing="chevrons-up-down" />

            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>
                        {{ __('Settings') }}</flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>
                        {{ __('Settings') }}</flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @fluxScripts
</body>

</html>
