<?php

use Domain\Shared\Helpers\StringHelper;
use Domain\Shared\Livewire\Base\BaseListComponent;
use Domain\User\Services\UserService;

?>

<div class="dataTable" id="dataTable">
    <style>
        span[wire\:key^="paginator-page-page"] span[aria-current="page"] .bg-white {
            background-color: #d3d3d3;
            color: white;
        }
        span[wire\:key^="paginator-page-page"] button {
            background-color: white;
        }
        .dataTable tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }
        .dataTable tbody tr:nth-child(even) {
            background-color: #ffffff;
        }
        .dataTable-td {
            color: #000;
        }
    </style>
    <?php
        $additionalHeaderElementsCount = $editRoute || (isset($modalViewRoute) && $modalViewRoute) || ($componentClass && $applyRowRoute) ? 1 : 0;
        $totalCols = count($tableData) + $additionalHeaderElementsCount;
    ?> 

    <x-modal name="details-modal" :show="(isset($openDetailsModal) && $openDetailsModal ? true : false)" focusable>
        alma
    </x-modal>

    <div class="inset-0 border border-black/5 rounded dark:border-white/5" style="background-color: #fcfcfc; width: fit-content;">
        <form name="DataGrid_form" id="DataGrid_form" method="get" action="">
            <table class="text-sm w-full table-auto">
                <thead class="dataTable-head">
                    <tr>
                        <th colspan="{{ $totalCols }}" class="font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                            <div class="flex mt-4">
                                @if($editRoute)
                                <div class="ml-0">
                                    @include('components.button-link', [
                                        'icon' => 'plus', 
                                        'text' => __('shared.CreateNew'),
                                        'backgroundColor' => 'green',
                                        'href' => route($editRoute, UserService::getRouteParamArray($roleType , [$entityClassReference => null]))
                                        ])
                                </div>
                                <div class="ml-4">
                                </div>
                                @endif
                                @if($allowExportToExcel)
                                <div class="ml-0" style="cursor: pointer;">
                                    @include('components.button-link-livewire', [
                                        'wire' => 'wire:click.prevent=exportToExcel',
                                        'icon' => 'table-cells', 
                                        'text' => __('shared.ExportToExcel')
                                        ])
                                </div>
                                @endif
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="{{ $totalCols }}" class="font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                            <div class="flex ">
                                <div class="ml-0" style="cursor: pointer;">
                                    @include('components.button-link-livewire', [
                                        'wire' => 'wire:click.prevent=search', 
                                        'icon' => 'magnifying-glass', 
                                        'text' => __('shared.Search'), 'backgroundColor' => 'gray'])
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        @php 
                            $textInputClass = 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm';
                            // $textInputClass = '';
                        @endphp 
                        @foreach ($tableData as $fieldData)
                        <th class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left" style="cursor: pointer;">
                            @if($fieldData[BaseListComponent::INPUT_TYPE] === 'text')
                                <input wire:model="{{ $fieldData[BaseListComponent::PROPERTY] }}" 
                                    class="dataGrid-{{ $fieldData[BaseListComponent::PROPERTY] }} {{ $textInputClass }}" 
                                    autocomplete="false" type="text" 
                                    placeholder="{{ __('shared.SearchItem', ['item' => __($fieldData[BaseListComponent::TRANSLATION_REFERENCE])]) }}"
                                    wire:keydown.enter="refreshList">
                            @elseif($fieldData[BaseListComponent::INPUT_TYPE] === 'select2')
                                <div wire:ignore>
                                    <select id="inputImitation_{{ $fieldData[BaseListComponent::PROPERTY] }}" multiple class="multiselect-input form-control dataGrid-{{ $fieldData[BaseListComponent::PROPERTY] }}" autocomplete="false">
                                        <option value="">{{ __('shared.PleaseChoose') }}</option>
                                        @foreach($fieldData[BaseListComponent::OPTIONS] as $optionProperties)
                                        <option value="{{ $optionProperties[BaseListComponent::OPTION_PROPERTY_KEY_VALUE] }}">{{ $optionProperties[BaseListComponent::OPTION_PROPERTY_KEY_DISPLAYED] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else 
                                <div style="height:40px;"></div>
                            @endif
                            @if(! array_key_exists(BaseListComponent::DISPLAY_SORTER, $fieldData) || $fieldData[BaseListComponent::DISPLAY_SORTER])
                            <div wire:click="doSort('{{ $fieldData[BaseListComponent::PROPERTY] }}')" style="width: 100%; margin-top: 10px; display: flex; align-items: center;">
                                <span>{{ __($fieldData[BaseListComponent::TRANSLATION_REFERENCE]) }}</span>
                                @if ($sortColumn == $fieldData[BaseListComponent::PROPERTY])
                                    @if ($sortDirection == 'ASC')
                                    <img wire:click="toggleSortDirection('{{ $fieldData[BaseListComponent::PROPERTY] }}')" src="/Bootstrap-icons/chevron-down.svg">
                                    @else
                                    <img wire:click="toggleSortDirection('{{ $fieldData[BaseListComponent::PROPERTY] }}')" src="/Bootstrap-icons/chevron-up.svg">
                                    @endif
                                @else
                                    <img src="/Bootstrap-icons/chevron-expand.svg">
                                @endif
                            </div>
                            @endif
                        </th>
                        @endforeach
                        @if($totalCols > count($tableData))
                            @for($i = 0; $i < $totalCols - count($tableData); $i++)
                            <th class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                            </th>
                            @endfor
                        @endif
                    </tr>
                </thead>
                <tbody> 
                    @foreach($objectCollection as $object)
                    <tr class="DataGrid-row">
                        @foreach($tableData as $fieldData)
                        <td class="dataTable-td border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                            <?php
                            $rawValue = $object->{$fieldData[BaseListComponent::PROPERTY]};
                            $value = '';
                            if (isset($fieldData[BaseListComponent::USE_LABEL_OF_ENUM])) {
                                $value = $fieldData[BaseListComponent::USE_LABEL_OF_ENUM]::{$rawValue}->label();
                            } else {
                                $value = StringHelper::cutLongString($rawValue);
                            }
                            // dump($value);

                            if (is_bool($value)) {
                                $value = $value ? __('shared.True') : __('shared.False');
                            }
                            ?>
                            {{ $fieldData[BaseListComponent::TRANSLATE_CELL_DATA] 
                                ? __($object->{$rawValue})
                                : $value }}
                        </td>
                        @endforeach
                        @if($componentClass && $applyRowRoute)
                        @php 
                        $rowRouteComponents = $componentClass::getRowRouteComponents($object);
                        @endphp 
                        <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                            @if($rowRouteComponents['type'] == 'view')
                            <x-button-link-view class="mr-2" :href="route($rowRouteComponents['name'], $rowRouteComponents['paramArray'])" />
                            @elseif($rowRouteComponents['type'] == 'edit')
                            <x-button-link-edit class="mr-2" :href="route($rowRouteComponents['name'], $rowRouteComponents['paramArray'])" />
                            @endif
                        </td>
                        @endif

                        @if($editRoute)
                        <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                            <x-button-link-edit class="mr-2" :href="route($editRoute, UserService::getRouteParamArray($roleType , [$entityClassReference => $object->id]))" />
                        </td>
                        @endif
                        @if(isset($modalViewRoute) && $modalViewRoute)
                        <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                            <x-edit-button
                                x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'details-modal')"
                                >{{ __('user.Alma') }}
                            </x-edit-button>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
        @if($totalPagesCount > 1)
        @php 
            $linksView = $objectCollection->links();
        @endphp
        <div class="font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left mt-4">
        {{ $linksView }}
        </div>
        @endif
    </div>


    @script
    <script>
        // This Javascript will get executed every time this component is loaded onto the page...
        console.log('rendered teszt2');
    </script>
    @endscript

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // initializeSelect2();
            const multiselectInput = document.querySelector('.multiselect-input');
            // $(multiselectInput).select2({
            //     placeholder: "{{ __('admin.SelectAnOption') }}"
            // });
        });
    </script>

    @script
    <script>
        $wire.on('rendered', () => {
            console.log('rendered');
            // initializeSelect2();
            // Livewire.hook('message.processed', (message, component) => {
            //     initializeSelect2();
            // });
        });
    </script>
    @endscript
</div>
