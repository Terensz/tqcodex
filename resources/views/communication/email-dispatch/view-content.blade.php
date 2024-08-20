<div>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('communication.EmailDispatchView') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            
        </p>
    </header>
<?php  
// dump($modelObject);
$textInputClass = 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm';
?>

    <form class="mt-6 space-y-6">
        <div>
            <x-input-label for="sender_address" :value="__('communication.SenderEmailAddress')" />
            <div id="sender_address" class="{{ $textInputClass }}" style="width: 100%;">
                {{ $modelObject->sender_address }}
            </div>
        </div>

        <div>
            <x-input-label for="sender_name" :value="__('communication.SenderName')" />
            <div id="sender_name" class="{{ $textInputClass }}" style="width: 100%;">
                {{ $modelObject->sender_name }}
            </div>
        </div>

        <div>
            <x-input-label for="recipient_address" :value="__('communication.RecipientEmailAddress')" />
            <div id="recipient_address" class="{{ $textInputClass }}" style="width: 100%;">
                {{ $modelObject->recipient_address }}
            </div>
        </div>

        <div>
            <x-input-label for="recipient_name" :value="__('communication.RecipientName')" />
            <div id="recipient_name" class="{{ $textInputClass }}" style="width: 100%;">
                {{ $modelObject->recipient_name }}
            </div>
        </div>

        <div>
            <x-input-label for="subject" :value="__('communication.Subject')" />
            <div id="subject" class="{{ $textInputClass }}" style="width: 100%;">
                {{ $modelObject->subject }}
            </div>
        </div>

        <div>
            <x-input-label for="body" :value="__('communication.EmailBody')" />
            <div id="body" class="{{ $textInputClass }}" style="width: 100%;">
                <iframe srcdoc="{{ $modelObject->body }}" style="width: 100%; min-height: 300px; border: none;"></iframe>
            </div>
        </div>

        <!-- <div>
            <x-input-label for="bounced_message" :value="__('communication.BouncedMessage')" />
            <div id="bounced_message" class="{{ $textInputClass }}" style="width: 100%;">
                {{ $modelObject->bounced_message ?? 'N/A' }}
            </div>
        </div> -->

        <div>
            <x-input-label for="sent_at" :value="__('communication.SentAt')" />
            <div id="sent_at" class="{{ $textInputClass }}" style="width: 100%;">
                {{ $modelObject->sent_at }}
            </div>
        </div>
    </form>
</div>