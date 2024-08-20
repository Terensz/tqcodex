{{-- confirm-password.blade.php --}}
@extends('components.layouts.guest')

@section('body')
<div class="flex min-h-full mx-auto max-w-4xl flex-col justify-center px-6 py-12 lg:px-8">
  <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

    <div class="flex min-h-full mx-auto max-w-3xl flex-col justify-center px-6 py-12 lg:px-8">
      <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <h2 class="mt-10 mb-4 text-center text-2xl lg:text-3xl font-bold leading-9 text-amber-400">{{ __('This is a secure area of the application. Please confirm your password before continuing.') }}</h2>
      </div>
      <form method="POST" action="{{ route('customer.password.store') }}" accept-charset="utf-8"
            enctype="multipart/form-data">
        @csrf

        {{-- Password --}}
        <div class="mt-4">
          <x-input-label for="password" class="block text-sm font-medium leading-6 text-white" :value="__('Password')" />
          <div class="mt-2">
            <x-text-input id="password" class="block w-full rounded-md border-0 bg-white/5 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-kblue sm:text-sm sm:leading-6" type="password" name="password" required
            autocomplete="current-password" />
          </div>
          <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4 flex items-center justify-end">
          <x-primary-button>
            {{ __('Confirm') }}
          </x-primary-button>
        </div>
      </form>
    </div>
    
  </div>
</div>
@endsection
