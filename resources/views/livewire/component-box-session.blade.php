<div class="mt-2">
    <flux:button wire:click='showForm'>{{ __('New') }}</flux:button>

    @role('administrator')
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

                    <flux:label>{{ __('Box') }}</flux:label>

                    <flux:select class="mt-2" wire:model.live="box_id">
                        <flux:select.option value="">{{ __('Select a box') }}</flux:select.option>
                        @foreach ($searchBoxes as $box)
                            <flux:select.option value="{{ $box->id }}">
                                {{ $box->name }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                <div class="w-full flex-1">
                    <flux:label>{{ __('Status') }}</flux:label>

                    <flux:select class="mt-2" wire:model.live="selectedStatus">
                        <flux:select.option value="">{{ __('Select a status') }}</flux:select.option>
                        @foreach ($statuses as $status)
                            <flux:select.option value="{{ $status->value }}">{{ $status->label() }}</flux:select.option>
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
    @endrole

    <div class="mt-2 relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3 w-80">
                        {{ __('Box') }}
                    </th>
                    @role('administrator')
                        <th scope="col" class="px-6 py-3 w-40">
                            {{ __('User') }}
                        </th>
                    @endrole
                    <th scope="col" class="px-6 py-3 w-100">
                        {{ __('Opening') }}
                    </th>
                    <th scope="col" class="px-6 py-3 w-100">
                        {{ __('Closing') }}
                    </th>
                    <th scope="col" class="px-6 py-3 w-40">
                        {{ __('Expected amount') }}
                    </th>
                    <th scope="col" class="px-6 py-3 w-40">
                        {{ __('Difference') }}
                    </th>
                    <th scope="col" class="px-6 py-3 w-40">
                        {{ __('Notes') }}
                    </th>
                    <th scope="col" class="px-6 py-3 w-40">
                        {{ __('Status') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('Options') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($boxSessions as $boxSession)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            <div class="font-semibold text-gray-900 dark:text-white">
                                {{ $boxSession->box->branch->company->name }}
                            </div>

                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                {{ $boxSession->box->branch->name }}
                            </div>

                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ $boxSession->box->name }}
                            </div>
                        </th>
                        @role('administrator')
                            <td class="px-6 py-4 text-gray-900 dark:text-white">
                                {{ $boxSession->user->name }}
                            </td>
                        @endrole
                        <td class="px-6 py-4 text-gray-900 dark:text-white">
                            {{ __('Opening amount') }}:
                            <small class="text-gray-500 dark:text-gray-400 text-xl">
                                {{ $boxSession->opening_amount }}
                            </small>
                            <br>
                            {{ $boxSession->opened_at->format('Y-m-d H:i:s') }}
                        </td>
                        <td class="px-6 py-4 text-gray-900 dark:text-white">
                            @if ($boxSession->status == \App\Enums\BoxSessionStatusEnum::CLOSED)
                                {{ __('Closing amount') }}:
                                <small class="text-gray-500 dark:text-gray-400 text-xl">
                                    {{ $boxSession->closing_amount }}
                                </small>
                                <br>
                                {{ $boxSession->closed_at->format('Y-m-d H:i:s') }}
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-900 dark:text-white">
                            @if ($boxSession->status == \App\Enums\BoxSessionStatusEnum::CLOSED)
                                {{ $boxSession->expected_amount }}
                            @endif
                        </td>
                        <td class="px-6 py-4 flex flex-col justify-center items-center text-gray-900 dark:text-white">
                            @if ($boxSession->status == \App\Enums\BoxSessionStatusEnum::CLOSED)
                                @if ($boxSession->closing_amount < $boxSession->expected_amount)
                                    <svg class="w-10 h-10 text-red-600 dark:text-red-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd"
                                            d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm7.707-3.707a1 1 0 0 0-1.414 1.414L10.586 12l-2.293 2.293a1 1 0 1 0 1.414 1.414L12 13.414l2.293 2.293a1 1 0 0 0 1.414-1.414L13.414 12l2.293-2.293a1 1 0 0 0-1.414-1.414L12 10.586 9.707 8.293Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @endif

                                @if ($boxSession->closing_amount > $boxSession->expected_amount)
                                    <svg class="w-10 h-10 text-yellow-600 dark:text-yellow-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd"
                                            d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v5a1 1 0 1 0 2 0V8Zm-1 7a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H12Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @endif

                                @if ($boxSession->closing_amount == $boxSession->expected_amount)
                                    <svg class="w-10 h-10 text-green-600 dark:text-green-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd"
                                            d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm13.707-1.293a1 1 0 0 0-1.414-1.414L11 12.586l-1.793-1.793a1 1 0 0 0-1.414 1.414l2.5 2.5a1 1 0 0 0 1.414 0l4-4Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @endif
                                <span class="text-center">{{ $boxSession->difference }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-900 dark:text-white">
                            @if ($boxSession->opening_notes)
                                {{ __('Opening notes') }}:
                                <p class="text-gray-500 dark:text-gray-400 font-semibold">
                                    {{ $boxSession->opening_notes }}
                                </p>
                            @endif

                            @if ($boxSession->closing_notes)
                                {{ __('Closing notes') }}:
                                <p class="text-gray-500 dark:text-gray-400 font-semibold">
                                    {{ $boxSession->closing_notes }}
                                </p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-900 dark:text-white">
                            {{ $boxSession->status->label() }}
                        </td>
                        <td class="px-6 py-4">
                            <ul>
                                @if ($boxSession->status == \App\Enums\BoxSessionStatusEnum::OPEN)
                                    <li>
                                        <a wire:click='showForm({{ $boxSession->id }})'
                                            class="font-medium text-blue-600 dark:text-blue-500 hover:underline cursor-pointer">
                                            {{ __('Closing') }}
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <a href="{{ route('seller.detail', $boxSession->id) }}"
                                        class="font-medium text-green-600 dark:text-green-500 hover:underline cursor-pointer">
                                        {{ __('Sales') }}
                                    </a>
                                </li>
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-2">
            {{ $boxSessions->links() }}
        </div>
    </div>

    <flux:modal name="opening-form" class="md:w-120">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Opening box session') }}</flux:heading>
            </div>

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

            <flux:label>{{ __('Box') }}</flux:label>

            <flux:select class="mt-2" wire:model="form.box">
                <flux:select.option value="">{{ __('Select a box') }}</flux:select.option>
                @foreach ($boxes as $box)
                    <flux:select.option value="{{ $box->id }}">
                        {{ $box->name }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <flux:error name="form.box" />

            <flux:input wire:model='form.opening_amount' label="{{ __('Opening amount') }}" type="number"
                placeholder="{{ __('Example') }}: 12.20" min="0" step="0.01" />

            <flux:input wire:model='form.opening_notes' label="{{ __('Opening notes') }}"
                placeholder="{{ __('Example') }}: banknotes in poor condition" />

            <div class="flex">
                <flux:spacer />

                <flux:button wire:click='save' variant="primary">{{ __('Save') }}</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="closing-form" class="md:w-120">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Closing box session') }}</flux:heading>
            </div>

            <flux:input wire:model='form.closing_amount' label="{{ __('Closing amount') }}" type="number"
                placeholder="{{ __('Example') }}: 12.20" min="0" step="0.01" />

            <flux:input wire:model='form.closing_notes' label="{{ __('Closing notes') }}"
                placeholder="{{ __('Example') }}: banknotes in poor condition" />

            <div class="flex">
                <flux:spacer />

                <flux:button wire:click='save' variant="primary">{{ __('Save') }}</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
