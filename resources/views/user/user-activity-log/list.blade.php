{{-- user-activity-log/list.blade.php --}}
@extends('admin.layouts.admin')
@section('content')
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('UsersActivityLogs') }}
    </h2>
  </x-slot>

  <div class="">
    <!-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6"> -->
    <div class="">
      @livewire('user-activity-log-list')
    </div>
  </div>
@endsection