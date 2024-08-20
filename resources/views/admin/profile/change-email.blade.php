{{-- login.blade.php --}}
@extends('components.layouts.guest')

@section('body')
  <div class="flex min-h-full mx-auto max-w-4xl flex-col justify-center px-6 py-12 lg:px-8">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

      <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <h2 class="mt-10 mb-4 text-center text-2xl lg:text-3xl font-bold leading-9 text-gray-700">{{ __('user.ChangingEmailAddress') }}</h2>
      </div>
      <form method="POST" action="{{ route('admin.email-change.store') }}" accept-charset="utf-8"
            enctype="multipart/form-data">
        @csrf

        {{-- E-mail Change Token --}}
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        {{-- Old e-mail Address --}}
        <div class="mt-4">
          <x-input-label for="old_email" class="block text-sm font-medium leading-6 text-gray-600" :value="__('user.OldEmailAddress')" />
          <div class="mt-2">
            <x-text-input id="old_email" class="mt-1 block w-full" type="email" name="old_email" :value="old('old_email', $request->old_email)" required
            autofocus />
          </div>
          <x-input-error :messages="$errors->get('old_email')" class="mt-2" />
        </div>

        {{-- New e-mail Address --}}
        <div class="mt-4">
          <x-input-label for="email" class="block text-sm font-medium leading-6 text-gray-600" :value="__('user.NewEmailAddress')" />
          <div class="mt-2">
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email', $request->email)" required
            autofocus />
          </div>
          <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Password --}}
        <div class="mt-4">
          <x-input-label for="password" class="block text-sm font-medium leading-6 text-gray-600" :value="__('user.Password')" />
          <div class="mt-2">
            <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" :value="old('password', $request->password)" required />
          </div>
          <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4 flex items-center justify-end">
          <x-primary-button>
            {{ __('user.IChangeMyEmailAddress') }}
          </x-primary-button>
        </div>
      </form>

    </div>
  </div>
@endsection
