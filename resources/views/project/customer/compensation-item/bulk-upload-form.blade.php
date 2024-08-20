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
                @error('photo')
                <span class="error">{{ $message }}</span>
                @enderror
            </form>

        </div>
    </div>

    @if(isset($data['stats']['errors']) && $data['stats']['errors']['totalFormalErrorsCount'] > 0)
    <div class="mt-2">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg mt-2">
            <div class="max-w-xl">
                @if($data['stats']['errors']['criticalFormalErrorsCount'] > 0)
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
                    @foreach($data['errors']['classifiedTriggeredFormalErrors']['critical'] as $formalError)
                        @foreach($formalError as $code => $description)
                        <div class="bg-red-100 dark:bg-red-900 p-2 rounded mb-2">
                            @if($description['type'] == 'boolean')
                                {{ __($description['errorMessageTranslationReference'], ['value' => empty($description['value']) ? '' : StringHelper::getLocalizedBoolean($description['value'])]) }}
                            @elseif($description['type'] == 'collection')
                                {{ __($description['errorMessageTranslationReference'], ['valueArray' => PHPHelper::implode(', ', $description['valueArray'])]) }}
                            @endif
                        </div>
                        @endforeach
                    @endforeach
                </div>
                @endif

                @if($data['stats']['errors']['nonCriticalFormalErrorsCount'] > 0)
                <div class="mb-4">
                    <div class="text-xl font-bold text-yellow-600 mb-2">
                        {{ __('project.NonCriticalErrors') }}
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                        {{ __('project.NonCriticalErrorsInfo') }}
                    </p>
                    @foreach($data['errors']['classifiedTriggeredFormalErrors']['nonCritical'] as $formalError)
                        @foreach($formalError as $code => $description)
                        <div class="bg-red-100 dark:bg-red-900 p-2 rounded mb-2">
                            <?php
                            // dump($formalError);
                            ?>
                            @if($description['type'] == 'boolean')
                                {{ __($description['errorMessageTranslationReference'], ['value' => empty($description['value']) ? '' : StringHelper::getLocalizedBoolean($description['value'])]) }}
                            @elseif($description['type'] == 'collection')
                                {{ __($description['errorMessageTranslationReference'], ['valueArray' => PHPHelper::implode(', ', $description['valueArray'], ' '.__('shared.or').' ')]) }}
                            @endif
                        </div>
                        @endforeach
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    @if(! empty($data['sortedInputData']))
    <div class="mt-2">
        <form wire:submit.prevent="refreshForm">
            <table class="min-w-full border-collapse border border-gray-300">
                <thead>
                    <tr>
                        <th class="border border-gray-300 p-2"></th>
                        <?php 
                        $unidentifiedDataLabelIndex = 0;
                        ?>
                        @foreach($data['processedLabels'] as $processedLabelIndex => $processedLabel)
                        <th class="border border-gray-300 p-2">
                            <?php
                            $isUnidentified = false;
                            foreach ($data['unidentifiedLabels'] as $unidentifiedLabel) {
                                if ($unidentifiedLabel['processedLabelIndex'] == $processedLabelIndex) {
                                    $isUnidentified = true;
                                }
                            }
                            ?>
                            @if(! $processedLabel['identifiedTranslatedLabel'])
                            <?php
                            /**
                             * Unidentified label
                            */
                            ?>
                                @if($isUnidentified)
                                    <div class="text-red-600">
                                        <div>{{ __('project.UnidentifiableHeader') }}</div>
                                        <div>{{ __('project.Given') }}: {{ $processedLabel['originalLabel'] }}</div>
                                        @if(isset($data['formData'][$processedLabelIndex]) && isset($data['formData'][$processedLabelIndex]['translationReference']))
                                        <div>{{ __('project.DidYouMeanThis') }} {{ __($data['formData'][$processedLabelIndex]['translationReference']) }}</div>
                                        @endif
                                    </div>
                                    <select id="unidentifiedDataLabel-{{ $processedLabelIndex }}" @change="$dispatch('init-select-unidentified-data-label', '{{ $processedLabelIndex }}')">
                                        <option>{{ __('shared.PleaseChoose', [], $data['language']) }}</option>
                                        @foreach($data['unusedFormData'] as $unusedFormDataRow)
                                        <option value="{{ $unusedFormDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY] }}">{{ __($unusedFormDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE], [], $data['language']) }}</option>
                                        @endforeach
                                    </select>
                                @endif
                                <?php 
                                $unidentifiedDataLabelIndex++;
                                ?>
                            @else
                            <?php
                            /**
                             * Identified label
                            */
                            ?>
                            {{ $processedLabel['identifiedTranslatedLabel'] }}
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
                    @foreach($this->data['sortedInputData'] as $rowIndex => $sortedInputDataRow)
                    <tr>
                        <td class="border p-2">
                            <a href="#" class="text-blue-500 cursor-pointer hover:underline" @click.prevent="$dispatch('init-delete-row', '{{ $rowIndex }}')">{{ __('shared.Delete') }}</a>
                        </td>
                        @foreach($sortedInputDataRow as $columnIndex => $column)
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
                                // if (isset($data['formData'][][BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE]))
                                // if ()
                                $processedLabel = $data['processedLabels'][$columnIndex];
                                $identifiedProperty = $processedLabel['identifiedProperty'];
                                $errorsString = '';
                                // $styleString = '';
                                $borderErrorClassString = '';

                                if (isset($data['errors']['validationErrors'][$rowIndex][$identifiedProperty])) {
                                    // $styleString = ' style="border: 1px solid red;"';
                                    $errorsString = implode(',', $data['errors']['validationErrors'][$rowIndex][$identifiedProperty]);
                                    $borderErrorClassString = ' border-red-600';
                                }
                            ?>
                            @if($data['stats']['errors']['totalErrorsCount'] > 0)
                            <input type="text" wire:model.defer="data.sortedInputData.{{ $rowIndex }}.{{ $columnIndex }}" class="w-full border{!! $borderErrorClassString !!}">
                            <div style="color: red;">{{ $errorsString }}</div>
                            @else
                            <div class="p-2">{{ $column }}</div>
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
                @if($data['stats']['errors']['totalErrorsCount'] > 0)
                {{ __('project.TotalErrorsCount', ['totalErrorsCount' => $data['stats']['errors']['totalErrorsCount']]) }}
                @endif
            </div>
            @if($data['stats']['errors']['totalErrorsCount'] > 0)
            <button type="submit" class="mt-4 bg-blue-500 text-white p-2 rounded">{{ __('shared.CheckingData') }}</button>
            @endif
            <!-- <button type="submit" class="mt-4 bg-blue-500 text-white p-2 rounded">{{ __('shared.CheckingData') }}</button> -->
        </form>
    </div>
    @endif

    @include('emails.project.style.body-style')

    @if(isset($data['stats']['errors']) && $data['stats']['errors']['totalErrorsCount'] == 0 && ! empty($data['collectedUniqueProperties']))
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
                @foreach($data['collectedUniqueProperties']['partner_email'] as $collectedUniquePropertyData)
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

    <?php 
    // dump(isset($data['stats']['errors']));
    // dump($data['stats']['errors']['totalErrorsCount'] == 0);
    // dump(! empty($tableBodyData) && ! $editingInviteEmail);
    // dump($tableBodyData);
    // dump($editingInviteEmail);
    ?>
    @if(isset($data['stats']['errors']) && $data['stats']['errors']['totalErrorsCount'] == 0 && ! empty($data['sortedInputData']) && ! $editingInviteEmail)
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