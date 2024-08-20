{{-- customer/contact/register.blade.php --}}
@extends('project.layouts.general')
@section('content')
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('project.ContactRegister') }}
    </h2>
  </x-slot>

  @livewire('contact-register', [
    'invitedRegister' => $invitedRegister,
    'partnerEmail' => $partnerEmail,
    'partnerName' => $partnerName,
    'partnerContact' => $partnerContact,
  ])
@endsection