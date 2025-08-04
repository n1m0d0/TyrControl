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
                        {{ __('Name') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('Document type') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('Document identifier') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('Options') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clients as $client)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $client->name }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $client->document_type->label() }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $client->document_identifier }}
                        </td>
                        <td class="px-6 py-4">
                            <ul>
                                <li>
                                    <a wire:click='showForm({{ $client->id }})'
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline cursor-pointer">
                                        {{ __('Edit') }}
                                    </a>
                                </li>

                                <li>
                                    <a wire:click='showDelete({{ $client->id }})'
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
            {{ $clients->links() }}
        </div>
    </div>

    <flux:modal name="client-form" class="md:w-120">
        <div class="space-y-6">
            <div>
                @if ($activity == 'create')
                    <flux:heading size="lg">{{ __('Create client') }}</flux:heading>
                @else
                    <flux:heading size="lg">{{ __('Edit client') }}</flux:heading>
                @endif
            </div>

            <flux:input wire:model='form.name' label="{{ __('Name') }}"
                placeholder="{{ __('Example') }}: John Doe" />

            <flux:label>{{ __('Document type') }}</flux:label>

            <flux:select class="mt-2" wire:model="form.document_type">
                <flux:select.option value="">{{ __('Select a document type') }}</flux:select.option>
                @foreach ($document_types as $document_type)
                    <flux:select.option value="{{ $document_type->value }}">{{ $document_type->label() }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <flux:error name="form.document_type" />

            <flux:input wire:model='form.document_identifier' label="{{ __('Document identifier') }}"
                placeholder="{{ __('Example') }}: 6745689" />

            <div class="flex">
                <flux:spacer />

                <flux:button wire:click='save' variant="primary">{{ __('Save') }}</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="client-delete" class="md:w-120">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Delete client') }}</flux:heading>
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
