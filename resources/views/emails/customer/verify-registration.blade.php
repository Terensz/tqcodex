<x-mail::message>
# {{ __('communication.VerifyRegistrationSubject') }}

{{ __('communication.VerifyRegistrationLine1') }}

<x-mail::button :url="$url" color="green">
{{ __('communication.VerifyRegistrationSubject') }}
</x-mail::button>

{{ __('communication.VerifyRegistrationLine2') }}
</x-mail::message>