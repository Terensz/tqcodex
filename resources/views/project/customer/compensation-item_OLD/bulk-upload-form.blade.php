<?php
use Domain\Shared\Livewire\Base\BaseLivewireForm;
use Domain\Shared\Helpers\StringHelper;
use Domain\Shared\Helpers\PHPHelper;
use Domain\User\Services\UserService;

?>

<div>
<?php 
$contactId = UserService::getContact()->id;
?>
    <div wire:loading class="fixed inset-0 flex items-center justify-center z-50">
        <div class="bg-gray-100 bg-opacity-75 text-black text-lg items-center justify-center z-50">
            {{ __('shared.PleaseWait') }}
        </div>
    </div>
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="">

            <form wire:submit="save">
                @php 
                    // dump($file);
                @endphp
                <input id="file" name="file" type="file" wire:model="file">
                <!-- <div>Feltöltés</div> -->
                @error('photo') 
                <span class="error">{{ $message }}</span> 
                @enderror
            
                <!-- <button type="submit">Save photo</button> -->
            </form>

        </div>
      </div>

    <?php
    // dump($errorStats['totalValidationErrorsCount']); 
    /**
     * Ha bármilyen formai hiba van a feltöltött csv-ben, akkor ezt a card-ot megjelenítjük.
    */
    ?>
    @if(isset($errorStats['totalErrorsCount']) && $errorStats['totalErrorsCount'] > 0)
    <div class="mt-2">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg mt-2">
            <div class="max-w-xl">
                @if(isset($errorStats['criticalErrorsCount']) && $errorStats['criticalErrorsCount'] > 0)
                <div class="mb-4">
                    <div class="text-xl font-bold text-red-600 mb-2">
                        {{ __('project.CriticalErrors') }}
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                        {{ __('project.CriticalErrorsInfo1') }}
                    </p>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                        {{ __('project.CriticalErrorsInfo2') }}
                    </p>
                    @foreach($arrangedFormalErrors['criticalErrors'] as $formalError)
                        <div class="bg-red-100 dark:bg-red-900 p-2 rounded mb-2">
                            @if($formalError['type'] == 'boolean')
                                {{ __($formalError['errorMessageTranslationReference'], ['value' => StringHelper::getLocalizedBoolean(($formalError['value']))]) }}
                            @elseif($formalError['type'] == 'collection')
                                {{ __($formalError['errorMessageTranslationReference'], ['valueArray' => PHPHelper::implode(', ', $formalError['valueArray'])]) }}
                            @endif
                        </div>
                    @endforeach
                </div>
                @endif

                @if(isset($errorStats['nonCriticalErrorsCount']) && $errorStats['nonCriticalErrorsCount'] > 0)
                <div class="mb-4">
                    <div class="text-xl font-bold text-yellow-600 mb-2">
                        {{ __('project.NonCriticalErrors') }}
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                        {{ __('project.NonCriticalErrorsInfo') }}
                    </p>
                    @foreach($arrangedFormalErrors['nonCriticalErrors'] as $formalError)
                        <div class="bg-red-100 dark:bg-red-900 p-2 rounded mb-2">
                            @if($formalError['type'] == 'boolean')
                                {{ __($formalError['errorMessageTranslationReference'], ['value' => StringHelper::getLocalizedBoolean(($formalError['value']))]) }}
                            @elseif($formalError['type'] == 'collection')
                                {{ __($formalError['errorMessageTranslationReference'], ['valueArray' => PHPHelper::implode(', ', $formalError['valueArray'], ' '.__('shared.or').' ')]) }}
                            @endif
                        </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    @if(! empty($tableBodyData))

        @if($tableBodyData)
        @php 
            $unidentifiedDataLabelIndex = 0;
        @endphp 
        <div class="mt-2">
            <form wire:submit.prevent="refreshForm">
                <table class="min-w-full border-collapse border border-gray-300">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 p-2"></th>
                            @foreach($dataLabels as $dataLabelIndex => $dataLabel)
                            <th class="border border-gray-300 p-2">
                                <?php 
                                $isUnidentified = false;
                                foreach ($unidentifiedDataLabels as $unidentifiedDataLabel) {
                                    if ($unidentifiedDataLabel['dataLabelIndex'] == $dataLabelIndex) {
                                        $isUnidentified = true;
                                    }
                                }
                                // dump($dataLabel['identifiedTranslatedLabel']);
                                ?>
                                @if(! $dataLabel['identifiedTranslatedLabel'])
                                <?php  
                                /**
                                 * Unidentified label
                                */
                                ?>
                                    @if($isUnidentified)
                                        <div class="text-red-600">
                                            <div>{{ __('project.UnidentifiableHeader') }}</div>
                                            <div>{{ __('project.Given') }}: {{ $dataLabel['originalLabel'] }}</div>
                                            @if(isset($formData[$dataLabelIndex]) && isset($formData[$dataLabelIndex]['translationReference']))
                                            <div>{{ __('project.DidYouMeanThis') }} {{ __($formData[$dataLabelIndex]['translationReference']) }}</div>
                                            @endif
                                        </div>
                                        <select id="unidentifiedDataLabel-{{ $dataLabelIndex }}" @change="$dispatch('init-select-unidentified-data-label', '{{ $dataLabelIndex }}')">
                                            <option>{{ __('shared.PleaseChoose', [], $language) }}</option>
                                            @foreach($unusedFormData as $unusedFormDataRow)
                                            <option value="{{ $unusedFormDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY] }}">{{ __($unusedFormDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE], [], $language) }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                    @php 
                                        $unidentifiedDataLabelIndex++;
                                    @endphp 
                                @else
                                <?php  
                                /**
                                 * Identified label
                                */
                                ?>
                                {{ $dataLabel['identifiedTranslatedLabel'] }}
                                @endif
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <?php
                    /**
                     * Table body
                    */
                    ?>
                    <tbody>
                        @foreach($tableBodyData as $rowIndex => $rowDataCollection)
                        <tr>
                            <td class="border p-2">
                                <a href="#" class="text-blue-500 cursor-pointer hover:underline" @click.prevent="$dispatch('init-delete-row', '{{ $rowIndex }}')">Törlés</a>
                            </td>
                            @foreach($rowDataCollection['rowData'] as $columnIndex => $column)
                            <td class="border p-2" style="min-width: 200px;">
                                <?php
                                    /*
                                    // Structure
                                    $rowDataCollection 
                                        ['rowData'] => [
                                            0 => 'Example value 1'
                                            1 => 'Example value 2'
                                        ],
                                        ['errors'] => [
                                            ['example_property'] => [
                                                0 => 'Translated error text 1'
                                                0 => 'Translated error text 2'
                                            ]
                                        ],
                                        ['stringModelAssignatures'] => [
                                            'exampleProperty3' => [
                                                'bindingPoint' => 'binding_id', 
                                                'models' => [
                                                    'string reference' => ModelObject
                                                ]
                                            ]
                                        ]
                                    */
                                    // $property = $tableDataLabels[] identifiedProperty
                                    // if (isset($formData[][BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE]))
                                    // if ()
                                    $dataLabel = $dataLabels[$columnIndex];
                                    $identifiedProperty = $dataLabel['identifiedProperty'];
                                    $errorsString = '';
                                    // $styleString = '';
                                    $borderErrorClassString = '';

                                    if (isset($rowDataCollection['errors'][$identifiedProperty])) {
                                        // $styleString = ' style="border: 1px solid red;"';
                                        $errorsString = implode(',', $rowDataCollection['errors'][$identifiedProperty]);
                                        $borderErrorClassString = ' border-red-600';
                                    }
                                ?>
                                @if($errorStats['totalErrorsCount'] > 0)
                                <input type="text" wire:model.defer="tableBodyData.{{ $rowIndex }}.rowData.{{ $columnIndex }}" class="w-full border{!! $borderErrorClassString !!}">
                                <div style="color: red;">{{ $errorsString }}</div>
                                @else 
                                <div class="p-2">{{ $tableBodyData[$rowIndex]['rowData'][$columnIndex] }}</div>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div>
                    <?php 
                    // dump($errorStats);
                    ?>
                    @if($errorStats['totalErrorsCount'] > 0)
                    {{ __('project.TotalErrorsCount', ['totalErrorsCount' => $errorStats['totalErrorsCount']]) }}
                    @endif
                </div>
                @if($errorStats['totalErrorsCount'] > 0)
                <button type="submit" class="mt-4 bg-blue-500 text-white p-2 rounded">{{ __('shared.CheckingData') }}</button>
                @endif
                <!-- <button type="submit" class="mt-4 bg-blue-500 text-white p-2 rounded">{{ __('shared.CheckingData') }}</button> -->
            </form>
        </div>
        @php 
        // dump($unidentifiedDataLabels);
        // dump($unusedFormData);
        // dump($formData);
        @endphp 
        @endif
    @endif 

<?php 
// dump($arrangedFormalErrors);
?>
@include('emails.project.style.body-style')

<!-- <textarea id="easymde_editor"></textarea> -->

    @if($errorStats['totalErrorsCount'] == 0 && ! empty($collectedUniqueProperties))
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="">
            <div>
                <p>
                    {{ __('project.EmailNotFountInfo1') }}.<br>
                    {{ __('project.EmailNotFountInfo2') }}
                </p>
                <p>
                    {{ __('project.EmailNotFountInfo3') }}
                </p>
                @foreach($collectedUniqueProperties['partner_email'] as $collectedUniquePropertyData)
                    <div><b>{{ $collectedUniquePropertyData['partner_email'] }}</b></div>
                @endforeach

                <p>
                    
                </p>
            </div>
            <div>
                @if($editingInviteEmail)
                <button @click="$dispatch('cancel-edit-invite-email')" class="mt-4 bg-blue-500 text-white p-2 rounded">{{ __('project.CancelEditingText') }}</button>
                @else 
                <button @click="$dispatch('edit-invite-email')" class="mt-4 bg-blue-500 text-white p-2 rounded">{{ __('project.EditText') }}</button>
                @endif
            </div>
            <div class="email-body-main">
                <!-- trix(\Domain\Shared\Models\Post::class, 'content') -->
                <!-- livewire('trix') --> 
                @if($editingInviteEmail)
                <trix-editor containerElement="p" wire:model="inviteEmailBody" class="trix-content" id="inviteEmailBody_editor"></trix-editor>
                <button @click="$dispatch('submit-edit-invite-email')" class="mt-4 bg-blue-500 text-white p-2 rounded">{{ __('shared.Save') }}</button>
                @else 
                <div class="border border-gray-300 p-2">{!! $inviteEmailBody !!}</div>
                @endif
            </div>
<style>
.email-body-main .trix-button--icon-number-list {
    display: none !important;
}
.email-body-main .trix-button--icon-link {
    display: none !important;
}
.email-body-main .trix-button-group--file-tools {
    display: none !important;
}
.email-body-main .trix-button--icon-code {
    display: none !important;
}
.email-body-main .trix-button--icon-quote {
    display: none !important;
}
</style>
            <!-- <div>
                <div>
                    A kiküldött levelek így fognak kinézni:
                </div>
            </div> -->
        </div>
    </div>
    @endif

    @if($errorStats['totalErrorsCount'] == 0 && ! empty($tableBodyData) && ! $editingInviteEmail)
        <button @click="$dispatch('init-submit-form')" class="mt-4 bg-blue-500 text-white p-2 rounded">{{ __('shared.Submit') }}</button>
    @endif

    <div style="display: none;">
        {{ $contactId }}
    </div>
</div>
<script>
// document.addEventListener("DOMContentLoaded", function() {
//     console.log('hello')
//     if (document.getElementById('alma_editor')) {
//         new EasyMDE({ element: document.getElementById('alma_editor') });
//     }
// });
</script>

@script
<script>
    //  
    $wire.on('init-select-unidentified-data-label', (unidentifiedDataLabelIndex) => {
        let inputId = 'unidentifiedDataLabel-' + unidentifiedDataLabelIndex;
        let input = document.getElementById(inputId);
        let value = input.value;
        $wire.$call('selectUnidentifiedDataLabel', unidentifiedDataLabelIndex, value);
    });

    $wire.on('init-delete-row', (rowIndex) => {
        $wire.$call('deleteRow', rowIndex);
    });

    $wire.on('init-submit-form', () => {
        $wire.$call('submitForm');
    });

    $wire.on('edit-invite-email', () => {
        $wire.$call('editInviteEmail');
    });

    $wire.on('submit-edit-invite-email', () => {
        let bodyEditorId = 'inviteEmailBody_editor';
        let bodyEditorInput = document.getElementById(bodyEditorId);
        let body = bodyEditorInput.innerHTML;

        $wire.$call('submitEditInviteEmail', body);
    });

    $wire.on('cancel-edit-invite-email', () => {
        $wire.$call('cancelEditInviteEmail');
    });
</script>
@endscript
