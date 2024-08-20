@php 
use Domain\Shared\Livewire\Base\BaseEditComponent;
@endphp
@script
<script>
    $wire.on('save-successful', (params) => {
        console.log('params: ', params);
        let actionType = params[0].actionType;
        let id = params[0].id;
        if (actionType == '{{ BaseEditComponent::ACTION_TYPE_NEW }}' && !isNaN(id) && parseInt(id) > 0) {
            window.location.href = params[0].editRoute;
        }
        Toaster.success("{{ __('shared.SaveSuccessful') }}");
    });
    $wire.on('save-failed', () => {
        Toaster.error("{{ __('shared.SaveFailed') }}");
    });
</script>
@endscript