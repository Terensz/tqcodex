<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ $pageTitle }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ $pageShortDescription }}
        </p>
    </header>
    @php 
    // dump($form)
    @endphp
    <div class="form-container">
        <form class="mt-6 space-y-6">
        @include('common.general-edit-form-inner')
        @include('common.general-edit-form-buttons')
        @include('common.general-edit-form-scripts')
        </form>
    </div>
</section>
