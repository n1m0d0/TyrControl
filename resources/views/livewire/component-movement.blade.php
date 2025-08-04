<div class="mt-2">
    <flux:button wire:click='showForm'>{{ __('New') }}</flux:button>

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
                    @foreach ($searchBranches as $branch)
                        <flux:select.option value="{{ $branch->id }}">
                            {{ $branch->name }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <flux:label>{{ __('Warehouse') }}</flux:label>

                <flux:select class="mt-2" wire:model.live="warehouse_id">
                    <flux:select.option value="">{{ __('Select a warehouse') }}</flux:select.option>
                    @foreach ($searchWarehouses as $warehouse)
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
                    @foreach ($searchProducts as $product)
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
                    <th scope="col" class="px-6 py-3 w-20">
                        {{ __('Type') }}
                    </th>
                    <th scope="col" class="px-6 py-3 w-20">
                        {{ __('Quantity') }}
                    </th>
                    <th scope="col" class="px-6 py-3 w-70">
                        {{ __('Reason') }}
                    </th>
                    <th scope="col" class="px-6 py-3 w-50">
                        {{ __('Warehouse') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($movements as $movement)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <img src="{{ Storage::url($movement->product->image) }}" alt="Current"
                                class="w-24 h-24 rounded object-cover border">
                        </th>
                        <td class="px-6 py-4 text-gray-900 dark:text-white">
                            {{ $movement->product->name }}

                            <p class="text-gray-500 dark:text-gray-400">
                                {{ $movement->product->description }}
                            </p>

                            {{ $movement->product->sku }}

                            <p class="mt-2 uppercase font-semibold">
                                {{ $movement->product->brand->name }}
                            </p>
                        </td>
                        <td class="px-6 py-4 text-gray-900 dark:text-white uppercase">
                            {{ $movement->movement_type->label() }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $movement->quantity }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $movement->reason }}
                        </td>
                        <td class="px-6 py-4 text-gray-900 dark:text-white">
                            {{ $movement->warehouse->branch->name }}

                            <p class="text-gray-500 dark:text-gray-400">
                                {{ $movement->warehouse->name }}
                            </p>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-2">
            {{ $movements->links() }}
        </div>
    </div>

    <flux:modal name="movement-form" class="md:w-120">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Create movement') }}</flux:heading>
            </div>

            <flux:label>{{ __('Type') }}</flux:label>

            <flux:select class="mt-2" wire:model="form.movement_type">
                <flux:select.option value="">{{ __('Select a type') }}</flux:select.option>
                @foreach ($types as $type)
                    <flux:select.option value="{{ $type->value }}">
                        {{ $type->label() }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <flux:error name="form.movement_type" />

            <flux:label>{{ __('Company') }}</flux:label>

            <flux:select class="mt-2" wire:model.live="company">
                <flux:select.option value="">{{ __('Select a company') }}</flux:select.option>
                @foreach ($companies as $company)
                    <flux:select.option value="{{ $company->id }}">
                        {{ $company->name }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <flux:label>{{ __('Branch') }}</flux:label>

            <flux:select class="mt-2" wire:model.live="branch">
                <flux:select.option value="">{{ __('Select a branch') }}</flux:select.option>
                @foreach ($branches as $branch)
                    <flux:select.option value="{{ $branch->id }}">
                        {{ $branch->name }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <flux:label>{{ __('Warehouse') }}</flux:label>

            <flux:select class="mt-2" wire:model="form.warehouse">
                <flux:select.option value="">{{ __('Select a warehouse') }}</flux:select.option>
                @foreach ($warehouses as $warehouse)
                    <flux:select.option value="{{ $warehouse->id }}">
                        {{ $warehouse->name }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <flux:error name="form.warehouse" />

            <flux:label>{{ __('Brand') }}</flux:label>

            <flux:select class="mt-2" wire:model.live="brand">
                <flux:select.option value="">{{ __('Select a brand') }}</flux:select.option>
                @foreach ($brands as $brand)
                    <flux:select.option value="{{ $brand->id }}">
                        {{ $brand->name }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <flux:label>{{ __('Product') }}</flux:label>

            <flux:select class="mt-2" wire:model="form.product">
                <flux:select.option value="">{{ __('Select a product') }}</flux:select.option>
                @foreach ($products as $product)
                    <flux:select.option value="{{ $product->id }}">
                        {{ $product->name }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <flux:error name="form.product" />

            <flux:input wire:model='form.quantity' label="{{ __('Quantity') }}" type="number"
                placeholder="{{ __('Example') }}: 200" min="1" step="1" />

            <flux:input wire:model='form.reason' label="{{ __('Reason') }}"
                placeholder="{{ __('Example') }}: Purchase of medicines" />

            <div class="flex">
                <flux:spacer />

                <flux:button wire:click='save' variant="primary">{{ __('Save') }}</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
