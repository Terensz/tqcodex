{{-- login.blade.php --}}
@extends('components.layouts.guest')

@section('body')
  <div class="flex min-h-full mx-auto max-w-4xl flex-col justify-center px-6 py-12 lg:px-8">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

      {{-- Session Status --}}
      <x-auth-session-status class="mb-4" :status="session('status')" />

      <div class="sm:mx-auto sm:w-full ">
        <!-- <x-application-logo class="block h-32 w-auto mx-auto" /> -->
        <h2 class="mt-10 text-center text-2xl lg:text-3xl font-bold leading-9 text-gray-600">{{ __('user.LoginToSystem', ['ToCurrentSystemName' => __('project.ToCurrentSystemName')]) }}</h2>
      </div>

      <!-- <div class="max-w-xl">   -->
      <div class="">  

        <div class="mt-10 sm:mx-auto ">
          <form class="space-y-6" action="{{ route('customer.login') }}" method="POST" accept-charset="utf-8"
              enctype="multipart/form-data">
            @csrf
            <div>
              <x-input-label for="email" class="block text-sm font-medium leading-6 text-gray-600" :value="__('user.Email')" />
              <div class="mt-2">
                <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
              </div>
              <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
              <div class="flex items-center justify-between">
                <x-input-label for="password" class="block text-sm font-medium leading-6 text-gray-600" :value="__('user.Password')" />
                @php 
                  $showPasswordRequestLink = true;
                @endphp 
                @if (Route::has('customer.password.request') && $showPasswordRequestLink)
                <div class="text-sm">
                  <a href="{{ route('customer.password.request') }}" class="font-semibold text-blue-500 hover:text-blue-700">{{ __('user.DidYouForgetYourPassword') }}</a>
                </div>
                @endif
              </div>
              <div class="mt-2">
                <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="current-password" />
              </div>
              <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            {{-- Remember Me --}}
            <div class="mt-4 block">
              <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-700-dark shadow-sm focus:ring-kblue" name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('user.RememberMe') }}</span>
              </label>
            </div>

            <div>
              <x-primary-button>
              {{ __('user.Login') }}
              </x-primary-button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
  @endsection