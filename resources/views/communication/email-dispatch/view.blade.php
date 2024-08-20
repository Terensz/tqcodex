{{-- communication/email-dispatch/edit.blade.php --}}
@extends('customer.layouts.customer')
@section('content')
  <div class="">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
      <div class="max-w-xl">
      @livewire('email-dispatch-view', [
        'communicationDispatch' => $communicationDispatch
      ])
      </div>
    </div>
  </div>
@endsection