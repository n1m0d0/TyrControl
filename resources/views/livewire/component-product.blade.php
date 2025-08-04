<div class="mt-2">
    <flux:button wire:click='showForm'>{{ __('New') }}</flux:button>

    <div class="mt-2 mb-2">
        <label for="search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
        <div class="relative">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 20 20">
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
                    <th scope="col" class="px-6 py-3">
                        {{ __('Image') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('Product') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('Brand') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('Code') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('Price') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('Minimum stock') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('Options') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <img src="{{ Storage::url($product->image) }}" alt="Current"
                                class="w-24 h-24 rounded object-cover border">
                        </th>
                        <td class="px-6 py-4 text-gray-900 dark:text-white">
                            {{ $product->name }}

                            <p class="text-gray-500 dark:text-gray-400">
                                {{ $product->description }}
                            </p>

                            {{ $product->sku }}
                        </td>
                        <td class="px-6 py-4 text-gray-900 dark:text-white">
                            {{ $product->brand->name }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-center flex flex-col bg-white text-black border-black border-2 p-2">
                                <img id="barcodeImage"
                                    src="data:image/png;base64,{{ DNS1D::getBarcodePNG($product->code, 'EAN13') }}"
                                    alt="Código de barras EAN13">

                                {{ $product->code }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            {{ $product->price }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $product->minimum_stock }}
                        </td>
                        <td class="px-6 py-4">
                            <ul>
                                <li>
                                    <a wire:click='showForm({{ $product->id }})'
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline cursor-pointer">
                                        {{ __('Edit') }}
                                    </a>
                                </li>

                                <li>
                                    <a wire:click='showDelete({{ $product->id }})'
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
            {{ $products->links() }}
        </div>
    </div>

    <flux:modal name="product-form" class="md:w-120">
        <div class="space-y-6">
            <div>
                @if ($activity == 'create')
                    <flux:heading size="lg">{{ __('Create product') }}</flux:heading>
                @else
                    <flux:heading size="lg">{{ __('Edit product') }}</flux:heading>
                @endif
            </div>

            <div class="w-full flex flex-auto justify-center">
                @if ($activity == 'create')
                    @if ($form->image)
                        <img src="{{ $form->image->temporaryUrl() }}" alt="Preview"
                            class="w-32 h-32 rounded object-cover border">
                    @else
                        <flux:icon.photo variant="solid" class="size-24" />
                    @endif
                @else
                    @if ($form->currentImage && $form->image == null)
                        <img src="{{ Storage::url($form->currentImage) }}" alt="Current"
                            class="w-32 h-32 rounded object-cover border">
                    @else
                        <img src="{{ $form->image->temporaryUrl() }}" alt="Preview"
                            class="w-32 h-32 rounded object-cover border">
                    @endif
                @endif
            </div>

            <flux:label>{{ __('Category') }}</flux:label>

            <flux:select class="mt-2" wire:model="form.category">
                <flux:select.option value="">{{ __('Select a category') }}</flux:select.option>
                @foreach ($categories as $category)
                    <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:error name="form.category" />

            <flux:label>{{ __('Brand') }}</flux:label>

            <flux:select class="mt-2" wire:model="form.brand">
                <flux:select.option value="">{{ __('Select a brand') }}</flux:select.option>
                @foreach ($brands as $brand)
                    <flux:select.option value="{{ $brand->id }}">{{ $brand->name }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:error name="form.brand" />

            <flux:input wire:model='form.name' label="{{ __('Name') }}"
                placeholder="{{ __('Example') }}: My product" />

            <flux:input wire:model='form.description' label="{{ __('Description') }}"
                placeholder="{{ __('Example') }}: product ingested orally" />

            <flux:input wire:model='form.sku' label="{{ __('SKU') }}"
                placeholder="{{ __('Example') }}: AMOX-500-1" />

            <flux:input wire:model='form.price' label="{{ __('Price') }}" type="number"
                placeholder="{{ __('Example') }}: 12.20" min="0" step="0.01" />

            <flux:input wire:model='form.minimum_stock' label="{{ __('Minimum stock') }}" type="number"
                placeholder="{{ __('Example') }}: 200" min="0" step="1" />

            <flux:input type="file" wire:model="form.image" label="{{ __('Image') }}" />

            <div class="flex">
                <flux:spacer />

                <flux:button wire:click='save' variant="primary">{{ __('Save') }}</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="product-delete" class="md:w-120">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Delete product') }}</flux:heading>
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
