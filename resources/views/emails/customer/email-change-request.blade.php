<x-mail::message>
# {{ __('communication.EmailChangeRequestMailSubject') }}

{{ __('communication.EmailChangeRequestMailBodyLine1', ['FromCurrentSystemName' => __('project.FromCurrentSystemName')]) }}

{{ __('communication.EmailChangeRequestMailBodyLine2') }}

<x-mail::button :url="$url" color="green">
{{ __('communication.EmailChangeRequestMailBodyLineButtonText') }}
</x-mail::button>

{{ __('communication.EmailChangeRequestMailBodyLine3') }}

{{ __('communication.EmailChangeRequestMailBodyLine4', ['FromCurrentSystemName' => __('project.FromCurrentSystemName')]) }}
</x-mail::message>
