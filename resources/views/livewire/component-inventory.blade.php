<div class="mt-2">
    <div class="mt-2 mb-2">
        <div class="flex flex-col md:flex-row justify-center gap-4 items-end">
            <div class="w-full flex-1">
                <flux:label>{{ __('Company') }}</flux:label>

                <flux:select class="mt-2" wire:model.live="company_id">
                    <flux:select.option value="">{{ __('Select a company') }}</flux:select.option>
                    @foreach ($companies as $company)
                        <flux:select.option value="{{ $company->id }}">
                            {{ $company->name }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <flux:label>{{ __('Branch') }}</flux:label>

                <flux:select class="mt-2" wire:model.live="branch_id">
                    <flux:select.option value="">{{ __('Select a branch') }}</flux:select.option>
                    @foreach ($branches as $branch)
                        <flux:select.option value="{{ $branch->id }}">
                            {{ $branch->name }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <flux:label>{{ __('Warehouse') }}</flux:label>
                
                <flux:select class="mt-2" wire:model.live="warehouse_id">
                    <flux:select.option value="">{{ __('Select a warehouse') }}</flux:select.option>
                    @foreach ($warehouses as $warehouse)
                        <flux:select.option value="{{ $warehouse->id }}">
                            {{ $warehouse->name }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            <div class="w-full flex-1">
                <flux:label>{{ __('Brand') }}</flux:label>

                <flux:select class="mt-2" wire:model.live="brand_id">
                    <flux:select.option value="">{{ __('Select a brand') }}</flux:select.option>
                    @foreach ($brands as $brand)
                        <flux:select.option value="{{ $brand->id }}">
                            {{ $brand->name }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <flux:label>{{ __('Product') }}</flux:label>

                <flux:select class="mt-2" wire:model.live="product_id">
                    <flux:select.option value="">{{ __('Select a product') }}</flux:select.option>
                    @foreach ($products as $product)
                        <flux:select.option value="{{ $product->id }}">
                            {{ $product->name }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </div>
        </div>
    </div>

    <div class="mt-2 mb-2">
        <label for="search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
        <div class="relative">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
            </div>
            <input wire:model.live='search' type="search" id="search"
                class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="{{ __('Search') }}" required />
        </div>
    </div>

    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3 w-50">
                        {{ __('Image') }}
                    </th>
                    <th scope="col" class="px-6 py-3 w-80">
                        {{ __('Product') }}
                    </th>
                    <th scope="col" class="px-6 py-3 w-40">
                        {{ __('Minimum stock') }}
                    </th>
                    <th scope="col" class="px-6 py-3 w-40">
                        {{ __('Stock') }}
                    </th>
                    <th scope="col" class="px-6 py-3 w-50">
                        {{ __('Warehouse') }}
                    </th>
                    <th scope="col" class="px-6 py-3 w-40">
                        {{ __('State') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inventories as $inventory)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <img src="{{ Storage::url($inventory->product->image) }}" alt="Current"
                                class="w-24 h-24 rounded object-cover border">
                        </th>
                        <td class="px-6 py-4 text-gray-900 dark:text-white">
                            {{ $inventory->product->name }}

                            <p class="text-gray-500 dark:text-gray-400">
                                {{ $inventory->product->description }}
                            </p>

                            {{ $inventory->product->sku }}

                            <p class="mt-2 uppercase font-semibold">
                                {{ $inventory->product->brand->name }}
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            {{ $inventory->product->minimum_stock }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $inventory->stock }}
                        </td>
                        <td class="px-6 py-4 text-gray-900 dark:text-white">
                            {{ $inventory->warehouse->branch->name }}

                            <p class="text-gray-500 dark:text-gray-400">
                                {{ $inventory->warehouse->name }}
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            @if ($inventory->product->minimum_stock > $inventory->stock)
                                <svg class="w-10 h-10 text-red-600 dark:text-red-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path fill-rule="evenodd"
                                        d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm7.707-3.707a1 1 0 0 0-1.414 1.414L10.586 12l-2.293 2.293a1 1 0 1 0 1.414 1.414L12 13.414l2.293 2.293a1 1 0 0 0 1.414-1.414L13.414 12l2.293-2.293a1 1 0 0 0-1.414-1.414L12 10.586 9.707 8.293Z"
                                        clip-rule="evenodd" />
                                </svg>
                            @endif

                            @if (
                                $inventory->stock >= $inventory->product->minimum_stock &&
                                    $inventory->stock <= $inventory->product->minimum_stock + 10)
                                <svg class="w-10 h-10 text-yellow-600 dark:text-yellow-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path fill-rule="evenodd"
                                        d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v5a1 1 0 1 0 2 0V8Zm-1 7a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H12Z"
                                        clip-rule="evenodd" />
                                </svg>
                            @endif

                            @if ($inventory->product->minimum_stock + 10 < $inventory->stock)
                                <svg class="w-10 h-10 text-green-600 dark:text-green-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path fill-rule="evenodd"
                                        d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm13.707-1.293a1 1 0 0 0-1.414-1.414L11 12.586l-1.793-1.793a1 1 0 0 0-1.414 1.414l2.5 2.5a1 1 0 0 0 1.414 0l4-4Z"
                                        clip-rule="evenodd" />
                                </svg>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-2">
            {{ $inventories->links() }}
        </div>
    </div>
</div>
