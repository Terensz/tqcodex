{{-- edit.blade.php --}}
@extends('admin.layouts.admin')
@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('user.Profile') }}
        </h2>
    </x-slot>

    <div>

        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg mb-2">
            <div class="max-w-xl">
                @include('admin.profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg mb-2">
            <div class="max-w-xl">
                @include('admin.profile.partials.update-password-form')
            </div>
        </div>

        {{--
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg mb-2">
            <div class="max-w-xl">
                @include('admin.profile.partials.delete-user-form')
            </div>
        </div>
        --}}
        
    </div>
@endsection