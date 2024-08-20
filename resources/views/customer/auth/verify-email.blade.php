{{-- verify-email.blade.php --}}
@extends('components.layouts.guest')

@section('body')
<div class="flex min-h-full mx-auto max-w-4xl flex-col justify-center px-6 py-12 lg:px-8">
  <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

    <div class="mb-4 text-base lg:text-xl text-gray-400">
      {{ __('user.RegisterVerificationEmailNotice') }}
    </div>

    @if (session('status') == 'verification-link-sent')
      <div class="mb-4 text-sm font-medium text-green-600 dark:text-green-400">
        {{ __('user.NewVerificationLinkSent') }}
      </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
      <form method="POST" action="{{ route('customer.verification.send') }}">
        @csrf

        <div>
          <x-primary-button>
            {{ __('user.ResendVerificationEmail') }}
          </x-primary-button>
        </div>
      </form>

      <form method="POST" action="{{ route('customer.logout') }}">
        @csrf

        <button type="submit"
          class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800">
          {{ __('user.LogOut') }}
        </button>
      </form>
    </div>

  </div>
</div>
@endsection