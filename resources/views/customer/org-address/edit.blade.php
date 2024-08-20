{{-- customer/org-address/edit.blade.php --}}
@extends('customer.layouts.customer')
@section('content')
  <div class="">
    <!-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6"> -->

      <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="max-w-xl">
        @livewire('org-address-edit', [
          'orgAddress' => $orgAddress,
          'actionType' => $actionType
        ])
        </div>
      </div>
      
    <!-- </div> -->
  </div>
@endsection