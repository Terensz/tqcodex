<?php
use Domain\Shared\Livewire\Base\BaseLivewireForm;
use Domain\Shared\Helpers\ValidationHelper;
use Domain\Shared\Livewire\Base\BaseEditComponent;
$textInputClass = 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm';
// $textInputClass = '';
?>
            @foreach($formData as $formDataRow)
                @php 
                    $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                    $formProperty = 'form.'.$property;
                @endphp
                @if($formDataRow[BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE] == ValidationHelper::INPUT_TYPE_TEXT)
                <div>
                    <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
                    @if(isset($formDataRow[BaseLivewireForm::FORM_DATA_KEY_DEFER]) && $formDataRow[BaseLivewireForm::FORM_DATA_KEY_DEFER])
                        <input type="text" wire:model.defer="{{ $formProperty }}" class="{{ $textInputClass }}" style="width: 100%;">
                    @else
                        <input type="text" wire:model="{{ $formProperty }}" class="{{ $textInputClass }}" style="width: 100%;">
                    @endif
                    @error($formProperty)
                    <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                        <li>{{ $message }}</li>
                    </ul>
                    @enderror
                </div>
                @endif
                @if($formDataRow[BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE] == ValidationHelper::INPUT_TYPE_SIMPLE_SELECT)
                <div>
                    <?php
                    // dump($formDataRow[BaseLivewireForm::FORM_DATA_KEY_OPTIONS]);
                    // foreach ($formDataRow[BaseLivewireForm::FORM_DATA_KEY_OPTIONS] as $optionData) {
                    //     dump($optionData[ValidationHelper::OPTION_LABEL]);
                    // }
                    ?>
                    <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
                    @if(isset($formDataRow[BaseLivewireForm::FORM_DATA_KEY_DEFER]) && $formDataRow[BaseLivewireForm::FORM_DATA_KEY_DEFER])
                    <select wire:model.defer="{{ $formProperty }}" class="{{ $textInputClass }}" style="width: 100%;">
                    @else
                    <select wire:model="{{ $formProperty }}" class="{{ $textInputClass }}" style="width: 100%;">
                    @endif
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
                @endif
            @endforeach
