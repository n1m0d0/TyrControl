<div class="mt-2">
    <flux:button wire:click='showForm'>{{ __('New') }}</flux:button>

    <div class="mt-2 mb-2">
        <div class="flex flex-col md:flex-row justify-center gap-4 items-end">
            <div class="w-full flex-1">
                <flux:label>{{ __('Supplier') }}</flux:label>

                <flux:select class="mt-2" wire:model.live="supplier_id">
                    <flux:select.option value="">{{ __('Select a supplier') }}</flux:select.option>
                    @foreach ($suppliers as $supplier)
                        <flux:select.option value="{{ $supplier->id }}">
                            {{ $supplier->name }}
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
                    <th scope="col" class="px-6 py-3 w-40">
                        {{ __('Code') }}
                    </th>
                    <th scope="col" class="px-6 py-3 w-60">
                        {{ __('Quantity') }}
                    </th>
                    <th scope="col" class="px-6 py-3 w-40">
                        {{ __('Price') }}
                    </th>
                    <th scope="col" class="px-6 py-3 w-40">
                        {{ __('Expiration') }}
                    </th>
                    <th scope="col" class="px-6 py-3 w-40">
                        {{ __('Supplier') }}
                    </th><th scope="col" class="px-6 py-3 w-40">
                        {{ __('Options') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($batches as $batch)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <img src="{{ Storage::url($batch->product->image) }}" alt="Current"
                                class="w-24 h-24 rounded object-cover border">
                        </th>
                        <td class="px-6 py-4 text-gray-900 dark:text-white">
                            {{ $batch->product->name }}

                            <p class="text-gray-500 dark:text-gray-400">
                                {{ $batch->product->description }}
                            </p>

                            {{ $batch->product->sku }}

                            <p class="mt-2 uppercase font-semibold">
                                {{ $batch->product->brand->name }}
                            </p>
                        </td>
                        <td class="px-6 py-4 text-gray-900 dark:text-white">
                            {{ $batch->code }}
                        </td>
                        <td class="px-6 py-4 text-gray-900 dark:text-white">
                            {{ __('Packs') }}: {{ $batch->amount_of_packs }}

                            <p class="text-gray-500 dark:text-gray-400">
                                {{ __('Units per pack') }}: {{ $batch->amount_of_units_per_pack }}
                            </p>

                            {{ __('Total') }}: {{ $batch->total_units }}
                        </td>
                        <td class="px-6 py-4 text-gray-900 dark:text-white">
                            {{ __('Pack') }}: {{ $batch->price_per_pack }}

                            <p class="text-gray-500 dark:text-gray-400">
                                {{ __('Unit') }}: {{ $batch->price_per_unit }}
                            </p>
                        </td>
                        <td class="px-6 py-4 flex flex-col items-center justify-center">
                            @php
                                $now = \Carbon\Carbon::now();
                                $expiration =
                                    $batch->expiration_date instanceof Carbon
                                        ? $batch->expiration_date
                                        : \Carbon\Carbon::parse($batch->expiration_date);

                                $diffInDays = $now->diffInDays($expiration, false); // negativo si ya venció
                            @endphp

                            @if ($diffInDays < 0)
                                {{-- Vencido (rojo) --}}
                                <div class="flex items-center space-x-2 text-red-600">
                                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm7.707-3.707a1 1 0 0 0-1.414 1.414L10.586 12l-2.293 2.293a1 1 0 1 0 1.414 1.414L12 13.414l2.293 2.293a1 1 0 0 0 1.414-1.414L13.414 12l2.293-2.293a1 1 0 0 0-1.414-1.414L12 10.586 9.707 8.293Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @elseif ($diffInDays >= 0 && $diffInDays <= 29)
                                {{-- Amarillo (próximo a vencer) --}}
                                <div class="flex items-center space-x-2 text-yellow-600">
                                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v5a1 1 0 1 0 2 0V8Zm-1 7a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H12Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @else
                                {{-- Verde (ok, más de 30 días) --}}
                                <div class="flex items-center space-x-2 text-green-600">
                                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm13.707-1.293a1 1 0 0 0-1.414-1.414L11 12.586l-1.793-1.793a1 1 0 0 0-1.414 1.414l2.5 2.5a1 1 0 0 0 1.414 0l4-4Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @endif
                            {{ $batch->expiration_date->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 text-gray-900 dark:text-white">
                            {{ $batch->supplier->name }}
                        </td>
                        <td class="px-6 py-4">
                            <ul>
                                <li>
                                    <a wire:click='showForm({{ $batch->id }})'
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline cursor-pointer">
                                        {{ __('Edit') }}
                                    </a>
                                </li>

                                <li>
                                    <a wire:click='showDelete({{ $batch->id }})'
                                        class="font-medium text-red-600 dark:text-red-500 hover:underline cursor-pointer">
                                        {{ __('Delete') }}
                                    </a>
                                </li>
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-2">
            {{ $batches->links() }}
        </div>
    </div>

    <flux:modal name="batch-form" class="md:w-120">
        <div class="space-y-6">
            <div>
                @if ($activity == 'create')
                    <flux:heading size="lg">{{ __('Create batch') }}</flux:heading>
                @else
                    <flux:heading size="lg">{{ __('Edit batch') }}</flux:heading>
                @endif
            </div>

            <flux:label>{{ __('Supplier') }}</flux:label>

            <flux:select class="mt-2" wire:model="form.supplier">
                <flux:select.option value="">{{ __('Select a supplier') }}</flux:select.option>
                @foreach ($suppliers as $supplier)
                    <flux:select.option value="{{ $supplier->id }}">
                        {{ $supplier->name }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <flux:error name="form.supplier" />

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

            <flux:select class="mt-2" wire:model="form.product"
                wire:key="product-select-{{ $activity }}-{{ $selectedProduct ?? 'none' }}">
                <flux:select.option value="">{{ __('Select a product') }}</flux:select.option>
                @foreach ($products as $product)
                    <flux:select.option value="{{ $product->id }}">
                        {{ $product->name }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            @if ($activity == 'edit' && $selectedProduct)
                @php $form->product = $selectedProduct; @endphp
            @endif

            <flux:error name="form.product" />

            <flux:input wire:model='form.code' label="{{ __('Code') }}"
                placeholder="{{ __('Example') }}: BAMOX03062025" />

            <flux:input wire:model='form.amount_of_packs' label="{{ __('Amount of packs') }}" type="number"
                placeholder="{{ __('Example') }}: 200" min="1" step="1" />

            <flux:input wire:model='form.amount_of_units_per_pack' label="{{ __('Amount of units per pack') }}"
                type="number" placeholder="{{ __('Example') }}: 200" min="1" step="1" />

            <flux:input wire:model='form.price_per_pack' label="{{ __('Price per pack') }}" type="number"
                placeholder="{{ __('Example') }}: 12.20" min="0" step="0.01" />

            <flux:input wire:model='form.expiration_date' label="{{ __('Expiration') }}" type="date" />

            <div class="flex">
                <flux:spacer />

                <flux:button wire:click='save' variant="primary">{{ __('Save') }}</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="batch-delete" class="md:w-120">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Delete batch') }}</flux:heading>
                <flux:subheading>{{ __('Once the record is deleted it cannot be recovered.') }}</flux:subheading>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:button wire:click='closeDelete' variant="primary">{{ __('Cancel') }}</flux:button>
                <flux:button wire:click='delete' variant="danger">{{ __('Delete') }}</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
