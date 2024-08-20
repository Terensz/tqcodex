{{-- dashboard.blade.php --}}
@extends('customer.layouts.customer')
@section('content')
<div>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('shared.Dashboard') }}
    </h2>
  </x-slot>

  <div class="">
    <div class="sm:px-0 lg:px-0">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          {{ __("You're logged in!") }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection