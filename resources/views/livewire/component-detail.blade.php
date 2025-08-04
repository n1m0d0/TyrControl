<div class="mt-2">
    <div class="mt-2 mb-2">
        <label for="search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
        <div class="relative">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewsale="0 0 20 20">
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
                        {{ __('Date') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('Client') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('Total') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('Options') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sales as $sale)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $sale->sale_date->format('Y-m-d H:i:s') }}
                        </th>
                        <td class="px-6 py-4">
                            @if ($sale->client)
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        {{ $sale->client->name }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $sale->client->document_type->label() }}:
                                        {{ $sale->client->document_identifier }}
                                    </div>
                                </div>
                            @else
                                <div class="font-medium text-gray-900 dark:text-white">
                                    S/N
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            {{ number_format($sale->total, 2) }} Bs.
                        </td>
                        <td class="px-6 py-4">
                            <ul>
                                <li>
                                    <a wire:click='exportPdf({{ $sale->id }})'
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline cursor-pointer">
                                        {{ __('View') }}
                                    </a>
                                </li>
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-2">
            {{ $sales->links() }}
        </div>
    </div>
</div>
