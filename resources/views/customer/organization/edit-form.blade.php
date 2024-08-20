@php 
use Domain\Shared\Livewire\Base\BaseEditComponent;
@endphp 
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ $pageTitle }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ $pageShortDescription }}
        </p>
    </header>
    <div class="form-container">
        <form class="mt-6 space-y-6">
            @include('customer.organization.edit-form-inner')
            @include('customer.organization.edit-form-scripts')
            @include('common.general-edit-form-buttons')
        </form>
    </div>
</section>