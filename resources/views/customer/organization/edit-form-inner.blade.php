<?php

use Domain\Shared\Livewire\Base\BaseLivewireForm;
use Domain\Shared\Helpers\ValidationHelper;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Domain\Shared\Helpers\StringHelper;
use Illuminate\Support\ViewErrorBag;

$textInputClass = 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm';
// $textInputClass = '';
// dump($mainAddressForm);
// dump($error);
?>
                @php 
                /**
                 * is_banned
                 * select
                 */
                $formDataRow = $formData['is_banned'];
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
                 * name
                 * text
                 */
                $formDataRow = $formData['name'];
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
                 * long_name
                 * text
                 */
                $formDataRow = $formData['long_name'];
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
                 * location
                 * select
                 */
                $formDataRow = $formData['location'];
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
                 * taxpayer_id
                 * text
                 */
                $formDataRow = $formData['taxpayer_id'];
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
                 * vat_code
                 * select
                 */
                $formDataRow = $formData['vat_code'];
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
                 * org_type
                 * select
                 */
                $formDataRow = $formData['org_type'];
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
                 * email
                 * text
                 */
                $formDataRow = $formData['email'];
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
                 * phone
                 * text
                 */
                $formDataRow = $formData['phone'];
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
                 * vat_banned
                 * select
                 */
                $formDataRow = $formData['vat_banned'];
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

                <div id="mainAddress-container" class="mt-6 space-y-6">

                    <div>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            A szervezet elsődleges címe
                        </h2>
                    </div>

                    @php 
                    /**
                    * country_id
                    * select
                    */
                    $formDataRow = $mainAddressFormData['country_id'];
                    $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                    $formProperty = 'mainAddressForm.'.$property;
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
                    * title
                    * text
                    */
                    $formDataRow = $mainAddressFormData['title'];
                    $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                    $formProperty = 'mainAddressForm.'.$property;
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
                    * postal_code
                    * text
                    */
                    $formDataRow = $mainAddressFormData['postal_code'];
                    $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                    $formProperty = 'mainAddressForm.'.$property;
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
                    * city
                    * text
                    */
                    $formDataRow = $mainAddressFormData['city'];
                    $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                    $formProperty = 'mainAddressForm.'.$property;
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
                    * street_name
                    * text
                    */
                    $formDataRow = $mainAddressFormData['street_name'];
                    $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                    $formProperty = 'mainAddressForm.'.$property;
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
                    * public_place_category
                    * select
                    */
                    $formDataRow = $mainAddressFormData['public_place_category'];
                    $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                    $formProperty = 'mainAddressForm.'.$property;
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
                    * number
                    * text
                    */
                    $formDataRow = $mainAddressFormData['number'];
                    $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                    $formProperty = 'mainAddressForm.'.$property;
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
                    * building
                    * text
                    */
                    $formDataRow = $mainAddressFormData['building'];
                    $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                    $formProperty = 'mainAddressForm.'.$property;
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
                    * floor
                    * text
                    */
                    $formDataRow = $mainAddressFormData['floor'];
                    $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                    $formProperty = 'mainAddressForm.'.$property;
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
                    * door
                    * text
                    */
                    $formDataRow = $mainAddressFormData['door'];
                    $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                    $formProperty = 'mainAddressForm.'.$property;
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

                </div>