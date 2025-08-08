<div class="mt-2 grid grid-cols-1 md:grid-cols-12 gap-2">
    <div class="col-span-1 md:col-span-7">
        <flux:select class="mt-2" wire:model.live="warehouse_id">
            <flux:select.option value="">{{ __('Select a warehouse') }}</flux:select.option>
            @foreach ($warehouses as $warehouse)
                <flux:select.option value="{{ $warehouse->id }}">
                    {{ $warehouse->name }}
                </flux:select.option>
            @endforeach
        </flux:select>

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

        <div class="my-4 grid grid-cols-2 md:grid-cols-4 gap-2">
            @foreach ($inventories as $inventory)
                <div
                    class="w-full max-w-xs bg-white rounded-2xl shadow-md border border-gray-200 dark:bg-gray-900 dark:border-gray-800 overflow-hidden">
                    <img src="{{ Storage::url($inventory->product->image) }}" alt="Imagen del producto"
                        class="w-full h-48 object-cover">

                    <div class="p-4 flex flex-col gap-2 items-center text-center">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $inventory->product->name }}
                        </h4>

                        <h5 class="text-sm text-gray-600 dark:text-gray-300 font-medium">
                            {{ $inventory->product->brand->name }}
                        </h5>

                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            SKU: {{ $inventory->product->sku }}
                        </p>

                        <div class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                            {{ __('Stock') }}: {{ $inventory->stock }}
                        </div>

                        <div class="text-base font-bold text-blue-600 dark:text-blue-400">
                            {{ __('Price') }}: {{ $inventory->product->price }} Bs.
                        </div>

                        <button wire:click='showAddForm({{ $inventory->product->id }}, {{ $inventory->stock }})'
                            class="mt-3 px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-200">
                            {{ __('Add') }}
                        </button>
                    </div>
                </div>
            @endforeach
            <div class="mt-2">
                {{ $inventories->links() }}
            </div>
        </div>
    </div>

    @if ($items->isNotEmpty())
        <div
            class="col-span-1 md:col-span-5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl p-4 shadow-md">
            <flux:heading size="xl" class="text-center mb-4 text-gray-800 dark:text-white">
                {{ __('Sale') }}
            </flux:heading>

            <div class="grid grid-cols-2 gap-2">
                <flux:button variant="primary" color="green" class="col-span-1 w-full" wire:click='showClientForm'>
                    {{ __('Client') }}</flux:button>

                <flux:button class="col-span-1 w-full" wire:click='verify'>{{ __('Save') }}</flux:button>
            </div>

            <div class="mt-2 bg-blue-800 py-4 px-6 mb-4 rounded-2xl text-center shadow-lg">
                <h2 class="text-2xl font-bold text-white tracking-wide">
                    {{ __('Total') }}:
                    <span class="text-yellow-300">{{ number_format($total, 2, '.', '') }} Bs.</span>
                </h2>
            </div>

            @if ($client)
                <div
                    class="mt-4 px-4 py-3 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl border border-indigo-100 dark:border-indigo-900 text-sm text-gray-700 dark:text-gray-300">
                    <span class="font-medium">{{ __('Client') }}:</span>
                    <span class="text-indigo-600 dark:text-indigo-300">{{ $client->name }}</span>
                    <span class="mx-1">•</span>
                    <span class="font-medium">{{ $client->document_type->label() }}:</span>
                    <span class="text-indigo-600 dark:text-indigo-300">{{ $client->document_identifier }}</span>
                </div>
            @endif

            <div class="relative overflow-x-auto rounded-xl shadow-sm mt-2">
                <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                    <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3 rounded-tl-xl">{{ __('Name') }}</th>
                            <th class="px-4 py-3">{{ __('Quantity') }}</th>
                            <th class="px-4 py-3">{{ __('Price') }}</th>
                            <th class="px-4 py-3 rounded-tr-xl"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($items as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                    {{ $item['name'] }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $item['quantity'] }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ number_format($item['price'], 2) }} Bs.
                                </td>
                                <td class="px-4 py-3">
                                    <a wire:click="showDeleteItem('{{ $item['id'] }}')"
                                        class="font-medium text-red-600 dark:text-red-500 hover:underline cursor-pointer">
                                        <flux:icon.trash />
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <flux:modal name="add-form" class="md:w-120">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Add Item') }}</flux:heading>
            </div>

            @if ($product)
                <h4 class="mb-2 text-xl text-center font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ $product->name }}</h4>

                <div class="w-full flex flex-auto justify-center">
                    <img src="{{ Storage::url($product->image) }}" alt="Current"
                        class="w-32 h-32 rounded object-cover border">

                </div>
            @endif

            <flux:input wire:model='quantity' label="{{ __('Quantity') }}" type="number"
                placeholder="{{ __('Example') }}: 200" min="1" step="1" />

            <div class="flex">
                <flux:spacer />

                <flux:button wire:click='addItem' variant="primary">{{ __('Save') }}</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="item-delete" class="md:w-120">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Delete item') }}</flux:heading>
                <flux:subheading>{{ __('Once the record is deleted it cannot be recovered.') }}</flux:subheading>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:button wire:click='closeDeleteItem' variant="primary">{{ __('Cancel') }}</flux:button>
                <flux:button wire:click='removeItem' variant="danger">{{ __('Delete') }}</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="client-form" class="md:w-120">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Create client') }}</flux:heading>
            </div>

            <flux:input wire:model='clientForm.name' label="{{ __('Name') }}"
                placeholder="{{ __('Example') }}: John Doe" />

            <flux:label>{{ __('Document type') }}</flux:label>

            <flux:select class="mt-2" wire:model="clientForm.document_type">
                <flux:select.option value="">{{ __('Select a document type') }}</flux:select.option>
                @foreach ($document_types as $document_type)
                    <flux:select.option value="{{ $document_type->value }}">{{ $document_type->label() }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <flux:error name="clientForm.document_type" />

            <flux:input wire:model='clientForm.document_identifier' label="{{ __('Document identifier') }}"
                placeholder="{{ __('Example') }}: 6745689" />

            <div class="flex">
                <flux:spacer />

                <flux:button wire:click='saveClient' variant="primary">{{ __('Save') }}</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="sale-alert" class="md:w-120">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Alert') }}</flux:heading>
                <flux:subheading>{{ __('The sale does not have an assigned customer.') }}</flux:subheading>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:button wire:click='closeSaleAlert' variant="danger">{{ __('Cancel') }}</flux:button>
                <flux:button wire:click='openConfirmed' variant="primary">{{ __('Accept') }}</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="sale-confirmed" class="md:w-120">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Confirmation') }}</flux:heading>
            </div>

            <flux:label>{{ __('Payment method') }}</flux:label>

            <flux:select class="mt-2" wire:model="payment_method">
                <flux:select.option value="">{{ __('Select a payment method') }}</flux:select.option>
                @foreach ($methods as $method)
                    <flux:select.option value="{{ $method->value }}">{{ $method->label() }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:error name="payment_method" />

            <div class="flex gap-2">
                <flux:spacer />

                <flux:button wire:click='closeSaleAlert' variant="danger">{{ __('Cancel') }}</flux:button>
                <flux:button wire:click='saveSale' variant="primary">{{ __('Save') }}</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
