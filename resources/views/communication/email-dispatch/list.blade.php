{{-- communication/email-dispatch/list.blade.php --}}
@extends('customer.layouts.customer')
@section('content')
  <div class="">
    <!-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6"> -->
    <div class="">
      @livewire('email-dispatch-list', [
        'communicationDispatchProcess' => $communicationDispatchProcess
      ])
    </div>
  </div>
@endsection