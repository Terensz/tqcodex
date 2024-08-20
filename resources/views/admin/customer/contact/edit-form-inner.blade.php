<?php
use Domain\Shared\Livewire\Base\BaseLivewireForm;
use Domain\Shared\Helpers\ValidationHelper;
use Domain\Shared\Livewire\Base\BaseEditComponent;
$textInputClass = 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm';
?>
                @php 
                $formDataRow = $formData['lastname'];
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
                $formDataRow = $formData['firstname'];
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
                $formDataRow = $formData['mobile'];
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
                $formDataRow = $formData['technicalPassword'];
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