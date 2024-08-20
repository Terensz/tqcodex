<x-mail::message>
# {{ __('communication.ResetPasswordMailTitle', ['FromCurrentSystemName' => __('project.FromCurrentSystemName')]) }}

{{ __('communication.ResetPasswordMailLine1', ['FromCurrentSystemName' => __('project.FromCurrentSystemName')]) }}

{{ __('communication.ResetPasswordMailLine2') }}

<x-mail::button :url="$url" color="green">
{{ __('communication.ResetPasswordMailButtonText') }}
</x-mail::button>

{{ __('communication.ResetPasswordMailLine3') }}

{{ __('communication.ResetPasswordMailLine4', ['FromCurrentSystemName' => __('project.FromCurrentSystemName')]) }}
</x-mail::message>
