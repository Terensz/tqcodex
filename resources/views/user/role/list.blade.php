{{-- role/list.blade.php --}}
@extends('admin.layouts.admin')
@section('content')
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('RoleList') }}
    </h2>
  </x-slot>

  <div class="">
    <div class="">
      @livewire('role-list')
    </div>
  </div>
@endsection