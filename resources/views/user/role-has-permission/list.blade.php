{{-- role-has-permission/list.blade.php --}}
@extends('admin.layouts.admin')
@section('content')
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('RoleHasPermissionList') }}
    </h2>
  </x-slot>

  <div class="">
    <div class="">
      @livewire('role-has-permission-list')
    </div>
  </div>
@endsection