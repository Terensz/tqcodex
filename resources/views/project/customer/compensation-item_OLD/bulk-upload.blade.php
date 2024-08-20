{{-- project/customer/compensation-item/bulk-upload.blade.php --}}
@extends('customer.layouts.customer')
@section('content')
<div class="">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 sm:rounded-lg overflow-x-auto">
      <div class="">
        @livewire('compensation-item-bulk-upload', [
        ])
      </div>
    </div>
  </div>
@endsection