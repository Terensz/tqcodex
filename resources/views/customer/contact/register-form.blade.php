<?php 

use Domain\Shared\Livewire\Base\BaseLivewireForm;
use Domain\Shared\Helpers\ValidationHelper;
// use Domain\Shared\Livewire\Base\BaseEditComponent;
$textInputClass = 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm';

?> 
<div class="flex min-h-full mx-auto max-w-4xl flex-col justify-center px-6 py-12 lg:px-8">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <form wire:submit.prevent="save">
        @csrf

        @php 
        $formDataRow = $formData['title'];
        $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
        $formProperty = 'form.'.$property;
        $fieldId = 'input_'.$property;
        @endphp 
        <div class="mt-4">
            <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
            <input type="text" wire:model="{{ $formProperty }}" id="{{ $fieldId }}" class="{{ $textInputClass }}" style="width: 100%;">
            @error($formProperty)
            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                <li>{{ $message }}</li>
            </ul>
            @enderror
        </div>

        @php 
        $formDataRow = $formData['lastname'];
        $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
        $formProperty = 'form.'.$property;
        $fieldId = 'input_'.$property;
        @endphp 
        <div class="mt-4">
            <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
            <input type="text" wire:model="{{ $formProperty }}" id="{{ $fieldId }}" class="{{ $textInputClass }}" style="width: 100%;">
            @error($formProperty)
            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                <li>{{ $message }}</li>
            </ul>
            @enderror
        </div>

        @php 
        $formDataRow = $formData['middlename'];
        $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
        $formProperty = 'form.'.$property;
        $fieldId = 'input_'.$property;
        @endphp 
        <div class="mt-4">
            <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
            <input type="text" wire:model="{{ $formProperty }}" id="{{ $fieldId }}" class="{{ $textInputClass }}" style="width: 100%;">
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
        $fieldId = 'input_'.$property;
        @endphp 
        <div class="mt-4">
            <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
            <input type="text" wire:model="{{ $formProperty }}" id="{{ $fieldId }}" class="{{ $textInputClass }}" style="width: 100%;">
            @error($formProperty)
            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                <li>{{ $message }}</li>
            </ul>
            @enderror
        </div>

        @php 
        $formDataRow = $formData['password'];
        $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
        $formProperty = 'form.'.$property;
        $fieldId = 'input_'.$property;
        @endphp 
        <div class="mt-4">
            <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
            <input type="password" wire:model="{{ $formProperty }}" id="{{ $fieldId }}" class="{{ $textInputClass }}" style="width: 100%;">
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
        $fieldId = 'input_'.$property;
        @endphp 
        <div class="mt-4">
            <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
            <input type="text" wire:model="{{ $formProperty }}" id="{{ $fieldId }}" class="{{ $textInputClass }}" style="width: 100%;">
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
        $fieldId = 'input_'.$property;
        @endphp 
        <div class="mt-4">
            <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
            <input type="text" wire:model="{{ $formProperty }}" id="{{ $fieldId }}" class="{{ $textInputClass }}" style="width: 100%;">
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
        $fieldId = 'input_'.$property;
        @endphp 
        <div class="mt-4">
            <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
            <input type="text" wire:model="{{ $formProperty }}" id="{{ $fieldId }}" class="{{ $textInputClass }}" style="width: 100%;">
            @error($formProperty)
            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                <li>{{ $message }}</li>
            </ul>
            @enderror
        </div>

        @php 
        $formDataRow = $formData['organization_name'];
        $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
        $formProperty = 'form.'.$property;
        $fieldId = 'input_'.$property;
        @endphp 
        <div class="mt-4">
            <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
            <input type="text" wire:model="{{ $formProperty }}" id="{{ $fieldId }}" class="{{ $textInputClass }}" style="width: 100%;">
            @error($formProperty)
            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                <li>{{ $message }}</li>
            </ul>
            @enderror
        </div>

        @php 
        $formDataRow = $formData['organization_long_name'];
        $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
        $formProperty = 'form.'.$property;
        $fieldId = 'input_'.$property;
        @endphp 
        <div class="mt-4">
            <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
            <input type="text" wire:model="{{ $formProperty }}" id="{{ $fieldId }}" class="{{ $textInputClass }}" style="width: 100%;">
            @error($formProperty)
            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                <li>{{ $message }}</li>
            </ul>
            @enderror
        </div>

        @php 
        $formDataRow = $formData['organization_org_type'];
        $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
        $formProperty = 'form.'.$property;
        $fieldId = 'input_'.$property;
        @endphp 
        <div class="mt-4">
            <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
            <select wire:model="{{ $formProperty }}" id="{{ $fieldId }}" class="{{ $textInputClass }}" style="width: 100%;">
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
        $formDataRow = $formData['organization_email'];
        $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
        $formProperty = 'form.'.$property;
        $fieldId = 'input_'.$property;
        @endphp 
        <div class="mt-4">
            <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
            <input type="text" wire:model="{{ $formProperty }}" id="{{ $fieldId }}" class="{{ $textInputClass }}" style="width: 100%;">
            @error($formProperty)
            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                <li>{{ $message }}</li>
            </ul>
            @enderror
        </div>

        @php 
        $formDataRow = $formData['organization_phone'];
        $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
        $formProperty = 'form.'.$property;
        $fieldId = 'input_'.$property;
        @endphp 
        <div class="mt-4">
            <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
            <input type="text" wire:model="{{ $formProperty }}" id="{{ $fieldId }}" class="{{ $textInputClass }}" style="width: 100%;">
            @error($formProperty)
            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                <li>{{ $message }}</li>
            </ul>
            @enderror
        </div>

        @php 
        $formDataRow = $formData['organization_taxpayer_id'];
        $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
        $formProperty = 'form.'.$property;
        $fieldId = 'input_'.$property;
        @endphp 
        <div class="mt-4">
            <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
            <input type="text" wire:model="{{ $formProperty }}" id="{{ $fieldId }}" class="{{ $textInputClass }}" style="width: 100%;">
            @error($formProperty)
            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                <li>{{ $message }}</li>
            </ul>
            @enderror
        </div>

        @php 
        $formDataRow = $formData['organization_eutaxid'];
        $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
        $formProperty = 'form.'.$property;
        $fieldId = 'input_'.$property;
        @endphp 
        <div class="mt-4">
            <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
            <input type="text" wire:model="{{ $formProperty }}" id="{{ $fieldId }}" class="{{ $textInputClass }}" style="width: 100%;">
            @error($formProperty)
            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                <li>{{ $message }}</li>
            </ul>
            @enderror
        </div>

        @php 
        $formDataRow = $formData['organization_taxid'];
        $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
        $formProperty = 'form.'.$property;
        $fieldId = 'input_'.$property;
        @endphp 
        <div class="mt-4">
            <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
            <input type="text" wire:model="{{ $formProperty }}" id="{{ $fieldId }}" class="{{ $textInputClass }}" style="width: 100%;">
            @error($formProperty)
            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                <li>{{ $message }}</li>
            </ul>
            @enderror
        </div>

        @php 
        $formDataRow = $formData['organization_vat_banned'];
        $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
        $formProperty = 'form.'.$property;
        $fieldId = 'input_'.$property;
        @endphp 
        <div class="mt-4">
            <x-input-label for="{{ $formProperty }}" :value="__($formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE])" />
            <select wire:model="{{ $formProperty }}" id="{{ $fieldId }}" class="{{ $textInputClass }}" style="width: 100%;">
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

        <div class="mt-4 flex items-center justify-end">
            <a class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
            href="{{ route('customer.login') }}">
            {{ __('shared.Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
            {{ __('shared.Register') }}
            </x-primary-button>
        </div>

        </form>

    </div>
</div>