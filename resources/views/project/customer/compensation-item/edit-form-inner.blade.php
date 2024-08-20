<?php

use Domain\Shared\Livewire\Base\BaseLivewireForm;
use Domain\Shared\Helpers\ValidationHelper;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Domain\Shared\Helpers\StringHelper;
use Illuminate\Support\ViewErrorBag;

$textInputClass = 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm';
// $textInputClass = '';

?>
<style>
.top-100 {top: 100%}
.bottom-100 {bottom: 100%}
.max-h-select {
    max-height: 300px;
}
</style>
<?php  

// dump($errors->getMessages());
// dump($errors->get('form.partner_org_id'));

?>
                @php 
                /**
                 * contact_id
                 * select
                 */
                $formDataRow = $formData['contact_id'];
                $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                $formProperty = 'form.'.$property;
                @endphp 
                <div>
                    <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
                    <select wire:model="{{ $formProperty }}" class="{{ $textInputClass }}" style="width: 100%;">
                    @if(is_iterable($formDataRow[BaseLivewireForm::FORM_DATA_KEY_OPTIONS]))
                        @foreach($formDataRow[BaseLivewireForm::FORM_DATA_KEY_OPTIONS] as $optionData)
                            <option value="{{ $optionData[ValidationHelper::OPTION_VALUE] }}">
                                {{ $optionData[ValidationHelper::OPTION_LABEL] }}
                            </option>
                        @endforeach
                    @endif
                    </select>
                    @error($formProperty)
                    <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                        <li>{{ $message }}</li>
                    </ul>
                    @enderror
                </div>

                @php 
                /**
                 * organization_id
                 * select
                 */
                $formDataRow = $formData['organization_id'];
                $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                $formProperty = 'form.'.$property;
                @endphp 
                <div>
                    <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
                    <select wire:model="{{ $formProperty }}" class="{{ $textInputClass }}" style="width: 100%;">
                    @if(is_iterable($formDataRow[BaseLivewireForm::FORM_DATA_KEY_OPTIONS]))
                        @foreach($formDataRow[BaseLivewireForm::FORM_DATA_KEY_OPTIONS] as $optionData)
                            <option value="{{ $optionData[ValidationHelper::OPTION_VALUE] }}">
                                {{ $optionData[ValidationHelper::OPTION_LABEL] }}
                            </option>
                        @endforeach
                    @endif
                    </select>
                    @error($formProperty)
                    <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                        <li>{{ $message }}</li>
                    </ul>
                    @enderror
                </div>

                @php 
                /**
                 * invoice_id_for_compensation
                 * text
                 */
                $formDataRow = $formData['invoice_id_for_compensation'];
                $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                $formProperty = 'form.'.$property;
                @endphp 
                <div>
                    <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
                    <input type="text" wire:model="{{ $formProperty }}" class="{{ $textInputClass }}" style="width: 100%;">
                    @error($formProperty)
                    <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                        <li>{{ $message }}</li>
                    </ul>
                    @enderror
                </div>

                @php 
                /**
                 * invoice_internal_id
                 * text
                 */
                $formDataRow = $formData['invoice_internal_id'];
                $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                $formProperty = 'form.'.$property;
                @endphp 
                <div>
                    <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
                    <input type="text" wire:model="{{ $formProperty }}" class="{{ $textInputClass }}" style="width: 100%;">
                    @error($formProperty)
                    <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                        <li>{{ $message }}</li>
                    </ul>
                    @enderror
                </div>

                @php 
                /**
                 * due_date
                 * datepicker
                 */
                $formDataRow = $formData['due_date'];
                $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                $formProperty = 'form.'.$property;
                @endphp 
                <div>
                    <div wire:ignore>
                        <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
                        <x-flatpickr type="text" wire:model="{{ $formProperty }}" class="{{ $textInputClass }}" style="width: 100%;" />
                    </div>
                    @error($formProperty)
                    <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                        <li>{{ $message }}</li>
                    </ul>
                    @enderror
                </div>

                @php 
                /**
                 * invoice_date
                 * datepicker
                 */
                $formDataRow = $formData['invoice_date'];
                $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                $formProperty = 'form.'.$property;
                @endphp 
                <div>
                    <div wire:ignore>
                        <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
                        <x-flatpickr type="text" wire:model="{{ $formProperty }}" class="{{ $textInputClass }}" style="width: 100%;" />
                    </div>
                    @error($formProperty)
                    <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                        <li>{{ $message }}</li>
                    </ul>
                    @enderror
                </div>

                @php 
                /**
                 * fulfilment_date
                 * datepicker
                 */
                $formDataRow = $formData['fulfilment_date'];
                $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                $formProperty = 'form.'.$property;
                @endphp 
                <div>
                    <div wire:ignore>
                        <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
                        <x-flatpickr type="text" wire:model="{{ $formProperty }}" class="{{ $textInputClass }}" style="width: 100%;" />
                    </div>
                    @error($formProperty)
                    <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                        <li>{{ $message }}</li>
                    </ul>
                    @enderror
                </div>

                @php 
                /**
                 * late_interest_rate
                 * text
                 */
                $formDataRow = $formData['late_interest_rate'];
                $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                $formProperty = 'form.'.$property;
                @endphp 
                <div>
                    <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
                    <input type="text" wire:model="{{ $formProperty }}" class="{{ $textInputClass }}" style="width: 100%;">
                    @error($formProperty)
                    <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                        <li>{{ $message }}</li>
                    </ul>
                    @enderror
                </div>

                @php 
                /**
                 * late_interest_amount
                 * text
                 */
                $formDataRow = $formData['late_interest_amount'];
                $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                $formProperty = 'form.'.$property;
                @endphp 
                <div>
                    <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
                    <input type="text" wire:model="{{ $formProperty }}" class="{{ $textInputClass }}" style="width: 100%;">
                    @error($formProperty)
                    <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                        <li>{{ $message }}</li>
                    </ul>
                    @enderror
                </div>

                @php 
                /**
                 * invoice_amount
                 * text
                 */
                $formDataRow = $formData['invoice_amount'];
                $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                $formProperty = 'form.'.$property;
                @endphp 
                <div>
                    <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
                    <input type="text" wire:model="{{ $formProperty }}" class="{{ $textInputClass }}" style="width: 100%;">
                    @error($formProperty)
                    <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                        <li>{{ $message }}</li>
                    </ul>
                    @enderror
                </div>

                @php 
                /**
                 * invoice_type
                 * select
                 */
                $formDataRow = $formData['invoice_type'];
                $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                $formProperty = 'form.'.$property;
                @endphp 
                <div>
                    <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
                    <select wire:model="{{ $formProperty }}" class="{{ $textInputClass }}" style="width: 100%;">
                    @if(is_iterable($formDataRow[BaseLivewireForm::FORM_DATA_KEY_OPTIONS]))
                        @foreach($formDataRow[BaseLivewireForm::FORM_DATA_KEY_OPTIONS] as $optionData)
                            <option value="{{ $optionData[ValidationHelper::OPTION_VALUE] }}">
                                {{ $optionData[ValidationHelper::OPTION_LABEL] }}
                            </option>
                        @endforeach
                    @endif
                    </select>
                    @error($formProperty)
                    <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                        <li>{{ $message }}</li>
                    </ul>
                    @enderror
                </div>

                @php 
                /**
                 * payment_mode
                 * select
                 */
                $formDataRow = $formData['payment_mode'];
                $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                $formProperty = 'form.'.$property;
                @endphp 
                <div>
                    <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
                    <select wire:model="{{ $formProperty }}" class="{{ $textInputClass }}" style="width: 100%;">
                    @if(is_iterable($formDataRow[BaseLivewireForm::FORM_DATA_KEY_OPTIONS]))
                        @foreach($formDataRow[BaseLivewireForm::FORM_DATA_KEY_OPTIONS] as $optionData)
                            <option value="{{ $optionData[ValidationHelper::OPTION_VALUE] }}">
                                {{ $optionData[ValidationHelper::OPTION_LABEL] }}
                            </option>
                        @endforeach
                    @endif
                    </select>
                    @error($formProperty)
                    <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                        <li>{{ $message }}</li>
                    </ul>
                    @enderror
                </div>

                @php 
                /**
                 * currency
                 * select
                 */
                $formDataRow = $formData['currency'];
                $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                $formProperty = 'form.'.$property;
                @endphp 
                <div>
                    <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
                    <select wire:model="{{ $formProperty }}" class="{{ $textInputClass }}" style="width: 100%;">
                    @if(is_iterable($formDataRow[BaseLivewireForm::FORM_DATA_KEY_OPTIONS]))
                        @foreach($formDataRow[BaseLivewireForm::FORM_DATA_KEY_OPTIONS] as $optionData)
                            <option value="{{ $optionData[ValidationHelper::OPTION_VALUE] }}">
                                {{ $optionData[ValidationHelper::OPTION_LABEL] }}
                            </option>
                        @endforeach
                    @endif
                    </select>
                    @error($formProperty)
                    <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                        <li>{{ $message }}</li>
                    </ul>
                    @enderror
                </div>

                @php 
                /**
                 * description
                 * textarea
                 */
                $formDataRow = $formData['description'];
                $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                $formProperty = 'form.'.$property;
                @endphp 
                <div>
                    <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
                    <textarea wire:model="{{ $formProperty }}" class="{{ $textInputClass }}" style="width: 100%;"></textarea>
                    @error($formProperty)
                    <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                        <li>{{ $message }}</li>
                    </ul>
                    @enderror
                </div>

                @php 
                /**
                 * is_part_amount
                 * select
                 */
                $formDataRow = $formData['is_part_amount'];
                $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                $formProperty = 'form.'.$property;
                @endphp 
                <div>
                    <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
                    <select wire:model="{{ $formProperty }}" class="{{ $textInputClass }}" style="width: 100%;">
                    @if(is_iterable($formDataRow[BaseLivewireForm::FORM_DATA_KEY_OPTIONS]))
                        @foreach($formDataRow[BaseLivewireForm::FORM_DATA_KEY_OPTIONS] as $optionData)
                            <option value="{{ $optionData[ValidationHelper::OPTION_VALUE] }}">
                                {{ $optionData[ValidationHelper::OPTION_LABEL] }}
                            </option>
                        @endforeach
                    @endif
                    </select>
                    @error($formProperty)
                    <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                        <li>{{ $message }}</li>
                    </ul>
                    @enderror
                </div>

                @php 
                /**
                 * is_disputed
                 * select
                 */
                $formDataRow = $formData['is_disputed'];
                $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                $formProperty = 'form.'.$property;
                @endphp 
                <div>
                    <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
                    <select wire:model="{{ $formProperty }}" class="{{ $textInputClass }}" style="width: 100%;">
                    @if(is_iterable($formDataRow[BaseLivewireForm::FORM_DATA_KEY_OPTIONS]))
                        @foreach($formDataRow[BaseLivewireForm::FORM_DATA_KEY_OPTIONS] as $optionData)
                            <option value="{{ $optionData[ValidationHelper::OPTION_VALUE] }}">
                                {{ $optionData[ValidationHelper::OPTION_LABEL] }}
                            </option>
                        @endforeach
                    @endif
                    </select>
                    @error($formProperty)
                    <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                        <li>{{ $message }}</li>
                    </ul>
                    @enderror
                </div>

                <?php  
                // dump($inviteOrganization);
                // dump($form->partner_org_id);
                // dump($form->partner_name);
                // dump($form->partner_taxpayer_id);
                // dump($form->partner_eutaxid);
                // dump($form->partner_address);
                // dump($form->partner_email);
                ?>

                <!-- Partner org section -->
                <div>
                    <!-- toggle-invite-organization -->
                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input wire:model="inviteOrganization" id="inviteOrganization_toggler" type="checkbox" class="form-checkbox" @click="$dispatch('toggle-invite-organization')">
                            <span class="ml-2">{{ __('Meghívom a cég képviselőjét') }}</span>
                        </label>
                    </div>

                    @if(! $inviteOrganization)
                    <!-- partner_org_id section -->
                    <div>
                        @php 
                        /**
                        * partner_org_id
                        * ajaxSearch
                        */
                        $idDataKey = 'partner_org_id';
                        $stringDataKey = 'partner_org_string';
                        $dropdownId = $idDataKey;
                        $dropdownParamSet = $dropdownParams[$dropdownId];

                        // $formDataRow contains the binding entity data identified by the already selected id.
                        $formDataRow = $formData[$idDataKey];
                        $stringProperty = 'form.'.$stringDataKey;
                        @endphp 
                        @if(! $partnerOrg)
                        <div>
                            <x-input-label :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
                            <div class="flex flex-col items-center relative">
                                <div class="w-full">
                                    <div @click.away="$dispatch('init-close-dropdown', '{{ $dropdownId }}')" class="my-2 p-1 bg-white flex border border-gray-200 rounded rounded-md">
                                        <input id="stringInput-{{ $dropdownId }}" 
                                            x-transition:leave="transition ease-in duration-100"
                                            x-transition:leave-start="opacity-100"
                                            x-transition:leave-end="opacity-0"
                                            wire:mousedown="$dispatch('init-open-dropdown', '{{ $dropdownId }}')"
                                            wire:keyup.debounce="$dispatch('init-string-typing-finished', '{{ $dropdownId }}')"
                                            class="p-1 px-2 appearance-none outline-none w-full text-gray-800 rounded-l-md">
                                        <div class="text-gray-300 w-8 py-1 pl-2 pr-1 border-l flex items-center border-gray-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                @if($dropdownParamSet['showChevronUp'])
                                                <polyline points="18 15 12 20 6 15"></polyline>
                                                @elseif($dropdownParamSet['showChevronDown'])
                                                <polyline points="18 15 12 9 6 15"></polyline>
                                                @endif
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($dropdownParamSet['showList'])
                                <div class="absolute shadow bg-white top-100 z-40 w-full lef-0 rounded max-h-select overflow-y-auto svelte-5uyqqj">
                                    <div class="flex flex-col w-full">
                                        @foreach($dropdownParamSet['filteredData'] as $dataRow)
                                        <div @click="$dispatch('init-select-dropdown-option', ['{{ $dropdownId }}', '{{ $dataRow['id'] }}'])" class="" aria-selected="">
                                            <div class="flex w-full items-center p-2 pl-2 border-transparent border-l-2 relative hover:border-teal-100" style="cursor: pointer;">
                                                <div class="w-full items-center flex">
                                                    <div class="mx-2 -mt-1">
                                                        <span>
                                                            {{ $dataRow['value'] }}
                                                        </span>
                                                        <div class="text-xs truncate w-full normal-case font-normal -mt-1 text-gray-500">
                                                            {{ $dataRow['label'] }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @else 
                        <div>
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="form.invoice_internal_id">
                                {{ __('finance.PartnerOrg') }}
                            </label>
                            <div class="flex">
                                <div class="relative w-full">
                                    <input type="text" disabled value="{{ $partnerOrg ? $partnerOrg->name : '' }}" class="rounded rounded-l-md disabled:opacity-75 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" 
                                        style="width: 100%;">
                                </div>
                                <button @click="$dispatch('init-remove-selected-dropdown-option', '{{ $dropdownId }}')" class="rounded rounded-r-md flex-shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-gray-900 bg-gray-100 border border-e-0 border-gray-300 dark:border-gray-700 dark:text-white hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800" type="button">
                                    {{ __('shared.Delete') }}
                                </button>
                            </div>
                        </div>
                        @endif
                        <?php 
                        // dump($form->partner_org_id);
                        ?>
                        @error('form.partner_org_id')
                        <ul class="text-sm text-red-600 dark:text-red-400 space-y-1" style="margin: 0px;">
                            <li>{{ $message }}</li>
                        </ul>
                        @enderror
                    </div>
                    <!-- / partner_org_id section -->
                    @endif

                    @if($inviteOrganization)
                    <!-- Inviting section -->
                    <div>
                        @php 
                        /**
                        * partner_name
                        * text
                        */
                        $formDataRow = $formData['partner_name'];
                        $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                        $formProperty = 'form.'.$property;
                        @endphp 
                        <div>
                            <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
                            <input type="text" wire:model="{{ $formProperty }}" class="{{ $textInputClass }}" style="width: 100%;">
                            @error($formProperty)
                            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                                <li>{{ $message }}</li>
                            </ul>
                            @enderror
                        </div>

                        @php 
                        /**
                        * partner_taxpayer_id
                        * text
                        */
                        $formDataRow = $formData['partner_taxpayer_id'];
                        $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                        $formProperty = 'form.'.$property;
                        @endphp 
                        <div class="mt-6">
                            <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
                            <input type="text" wire:model="{{ $formProperty }}" class="{{ $textInputClass }}" style="width: 100%;">
                            @error($formProperty)
                            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                                <li>{{ $message }}</li>
                            </ul>
                            @enderror
                        </div>

                        @php 
                        /**
                        * partner_eutaxid
                        * text
                        */
                        $formDataRow = $formData['partner_eutaxid'];
                        $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                        $formProperty = 'form.'.$property;
                        @endphp 
                        <div class="mt-6">
                            <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
                            <input type="text" wire:model="{{ $formProperty }}" class="{{ $textInputClass }}" style="width: 100%;">
                            @error($formProperty)
                            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                                <li>{{ $message }}</li>
                            </ul>
                            @enderror
                        </div>

                        @php 
                        /**
                        * partner_address
                        * text
                        */
                        $formDataRow = $formData['partner_address'];
                        $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                        $formProperty = 'form.'.$property;
                        @endphp 
                        <div class="mt-6">
                            <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
                            <input type="text" wire:model="{{ $formProperty }}" class="{{ $textInputClass }}" style="width: 100%;">
                            @error($formProperty)
                            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                                <li>{{ $message }}</li>
                            </ul>
                            @enderror
                        </div>

                        @php 
                        /**
                        * partner_email
                        * text
                        */
                        $formDataRow = $formData['partner_email'];
                        $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                        $formProperty = 'form.'.$property;
                        @endphp 
                        <div class="mt-6">
                            <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
                            <input type="text" wire:model="{{ $formProperty }}" class="{{ $textInputClass }}" style="width: 100%;">
                            @error($formProperty)
                            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                                <li>{{ $message }}</li>
                            </ul>
                            @enderror
                        </div>

                        @php 
                        /**
                        * partner_phone
                        * text
                        */
                        $formDataRow = $formData['partner_phone'];
                        $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                        $formProperty = 'form.'.$property;
                        @endphp 
                        <div class="mt-6">
                            <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
                            <input type="text" wire:model="{{ $formProperty }}" class="{{ $textInputClass }}" style="width: 100%;">
                            @error($formProperty)
                            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                                <li>{{ $message }}</li>
                            </ul>
                            @enderror
                        </div>

                    </div>
                    <!-- / Inviting section -->
                    @endif
                </div>
                <!-- / Partner org section -->

                @php 
                /**
                * partner_contact
                * text
                */
                $formDataRow = $formData['partner_contact'];
                $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                $formProperty = 'form.'.$property;
                @endphp 
                <div class="mt-6">
                    <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
                    <input type="text" wire:model="{{ $formProperty }}" class="{{ $textInputClass }}" style="width: 100%;">
                    @error($formProperty)
                    <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                        <li>{{ $message }}</li>
                    </ul>
                    @enderror
                </div>

                @php 
                /**
                 * is_compensed
                 * select
                 */
                $formDataRow = $formData['is_compensed'];
                $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                $formProperty = 'form.'.$property;
                @endphp 
                <div>
                    <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
                    <select wire:model="{{ $formProperty }}" class="{{ $textInputClass }}" style="width: 100%;">
                    @if(is_iterable($formDataRow[BaseLivewireForm::FORM_DATA_KEY_OPTIONS]))
                        @foreach($formDataRow[BaseLivewireForm::FORM_DATA_KEY_OPTIONS] as $optionData)
                            <option value="{{ $optionData[ValidationHelper::OPTION_VALUE] }}">
                                {{ $optionData[ValidationHelper::OPTION_LABEL] }}
                            </option>
                        @endforeach
                    @endif
                    </select>
                    @error($formProperty)
                    <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                        <li>{{ $message }}</li>
                    </ul>
                    @enderror
                </div>

                

@script
<script>
    // Open 
    $wire.on('init-open-dropdown', (dropdownId) => {
        $wire.$call('initOpenDropdown', dropdownId);
    });

    // Close 
    $wire.on('init-close-dropdown', (params) => {
        $wire.$call('initCloseDropdown', params);
    });

    // Typing
    $wire.on('init-string-typing-finished', (dropdownId) => {
        let stringInputId = 'stringInput-' + dropdownId;
        let stringInput = document.getElementById(stringInputId);
        let string = stringInput.value;

        $wire.$call('initStringTypingFinished', dropdownId, string);
    });

    // Select option
    $wire.on('init-select-dropdown-option', (params) => {
        let dropdownId = params[0];
        let optionId = params[1];

        $wire.$call('initSelectDropdownOption', dropdownId, optionId);
    });

    // Deselect option
    $wire.on('init-remove-selected-dropdown-option', (params) => {
        $wire.$call('initRemoveSelectedDropdownOption', params);
    });

    $wire.on('toggle-invite-organization', (params) => {
        let checkedState = document.querySelector('#inviteOrganization_toggler').checked;
        $wire.$call('toggleInviteOrganization', checkedState);
    });
</script>
@endscript