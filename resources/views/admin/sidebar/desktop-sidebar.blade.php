<?php

use Domain\User\Services\UserService;
use Domain\User\Services\AccessTokenService;

?>

{{-- Static sidebar for desktop --}}
<div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
  <!-- Sidebar component, swap this element with another sidebar if you like -->
  <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-gray-700 px-6">
    <!-- <div class="flex h-16 shrink-0 items-center">
      <x-application-logo class="block h-12 w-auto bg-white" />
      <span class="text-3xl text-amber-400 font-bold"></span>
    </div> -->
    @include('admin.sidebar.top-placeholder')
    <nav class="flex flex-1 flex-col">
      <ul role="list" class="flex flex-1 flex-col gap-y-7">
        <li>
          <ul role="list" class="-mx-2 space-y-1">

          @include('project.admin.sidebar.item-list')

          </ul>
        </li>

        @include('admin.sidebar.user-dropdown')

      </ul>
    </nav>
  </div>
</div>

<div class="sticky top-0 z-40 flex items-center gap-x-6 bg-gray-700 px-4 py-4 shadow-sm sm:px-6 lg:hidden" style="width: 100%; position: relative;">
  <button type="button" class="-m-2.5 p-2.5 text-gray-400 lg:hidden" @click="open = true">
    <span class="sr-only">Oldalsáv nyitása</span>
    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
      <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"></path>
    </svg>
  </button>
  <div class="flex-1 text-sm font-semibold leading-6 text-white">Műszerfal</div>
  {{-- Settings Dropdown --}} 
  <div class="hidden sm:ml-6 sm:flex sm:items-center">

  </div>
  <!-- Settings Icon -->
  <!-- <div class="ml-1">
    <svg class="h-4 w-4 fill-current text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path d="M12 7V2H8v5H3v4h5v5h4v-5h5V7h-5z" />
    </svg>
  </div> -->
  <a href="{{ route('admin.profile.edit', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN)]) }}">
    @include('components.icons.svg.person-gear')
  </a>
  <!-- <a href="{{ route('admin.system.setting.list', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN)]) }}">
    @include('components.icons.svg.settings')
  </a> -->
  {{-- Settings Dropdown for mobile --}}
  <div class="ml-6 flex items-center sm:hidden">

  </div>
</div>