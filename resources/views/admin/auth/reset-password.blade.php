<?php 
// dump('alma');
?>

{{-- reset-password.blade.php --}}
@extends('components.layouts.guest')

@section('body')
  <!-- <div class="flex min-h-full mx-auto max-w-3xl flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <h2 class="mt-10 mb-4 text-center text-2xl lg:text-3xl font-bold leading-9 text-amber-400">Jelszó-helyreállítás</h2>
    </div> -->

<div class="flex min-h-full mx-auto max-w-4xl flex-col justify-center px-6 py-12 lg:px-8">
  <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

    {{-- Session Status --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <h2 class="mt-10 mb-4 text-center text-2xl lg:text-3xl font-bold leading-9 text-gray-700">Jelszó-helyreállítás</h2>
    </div>

    <form method="POST" action="{{ route('admin.password.store') }}" accept-charset="utf-8"
          enctype="multipart/form-data">
      @csrf

      {{-- Password Reset Token --}}
      <input type="hidden" name="token" value="{{ $request->route('token') }}">

      {{-- Email Address --}}
      <div>
        <x-input-label for="email" class="block text-sm font-medium leading-6 text-gray-600" :value="__('E-mail cím')" />
        <div class="mt-2">
          <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email', $request->email)" required
          autofocus autocomplete="username" />
        </div>
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
      </div>

      {{-- Password --}}
      <div class="mt-4">
        <x-input-label for="password" class="block text-sm font-medium leading-6 text-gray-600" :value="__('Új jelszó')" />
        <div class="mt-2">
          <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" :value="old('password', $request->password)" required
          autocomplete="password" />
        </div>
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
      </div>

      {{-- Confirm Password --}}
      <div class="mt-4">
        <x-input-label for="password_confirmation" class="block text-sm font-medium leading-6 text-gray-600" :value="__('Jelszó megerősítése')" />
        <div class="mt-2">
          <x-text-input id="password_confirmation" class="mt-1 block w-full" type="password" name="password_confirmation" :value="old('password_confirmation', $request->password_confirmation)"
          required autocomplete="new-password" />
        </div>
        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
      </div>

      <div class="mt-4 flex items-center justify-end">
        <x-primary-button>
          {{ __('Új jelszót kérek') }}
        </x-primary-button>
      </div>
    </form>

  </div>
</div>
@endsection
