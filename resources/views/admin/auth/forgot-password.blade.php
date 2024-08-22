{{-- forgot-password.blade.php --}}
@extends('components.layouts.guest')

@section('body')
  <div class="flex min-h-full mx-auto max-w-4xl flex-col justify-center px-6 py-12 lg:px-8">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

      {{-- Session Status --}}
      <x-auth-session-status class="mb-4" :status="session('status')" />

      <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <h2 class="mt-10 mb-4 text-center text-2xl lg:text-3xl font-bold leading-9 text-gray-700">
          {{ __('user.PasswordReset') }}
        </h2>
      </div>

      <div class="mb-4 text-base lg:text-xl text-gray-400">
        {{ __('user.PasswordResetDescription') }}
      </div>

      <form method="POST" action="{{ route('admin.password.email') }}" accept-charset="utf-8"
            enctype="multipart/form-data">
        @csrf

        {{-- Email Address --}}
        <div class="mt-4">
          <x-input-label for="email" class="block text-sm font-medium leading-6 text-gray-600" :value="__('E-mail cím')" />
          <div class="mt-2">
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus />
          </div>
          <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4 flex items-center justify-end">
          <x-primary-button>
            {{ __('user.PleaseSendMePasswordResetLink') }}
          </x-primary-button>
        </div>
      </form>

    </div>
  </div>
@endsection
