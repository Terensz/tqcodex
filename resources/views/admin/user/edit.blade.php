{{-- user/edit.blade.php --}}
@extends('admin.layouts.admin')
@section('content')
  <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
    <div class="max-w-xl">
      @livewire('user-edit', [
        'user' => $user,
        'actionType' => $actionType
      ])
    </div>
  </div>
@endsection