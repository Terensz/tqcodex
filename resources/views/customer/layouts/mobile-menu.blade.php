<?php

use Illuminate\Support\Facades\Route;
use Domain\User\Services\UserService;
use Domain\User\Services\AccessTokenService;

$currentRouteName = Route::currentRouteName();

?>

{{-- Off-canvas menu for mobile, show/hide based on off-canvas menu state. --}}
<div x-show="open" class="relative z-50 lg:hidden" x-description="Off-canvas menu for mobile" x-ref="dialog" aria-modal="true" style="display: none;">
  <div 
    x-show="open" 
    x-transition:enter="transition-opacity ease-linear duration-300" 
    x-transition:enter-start="opacity-0" 
    x-transition:enter-end="opacity-100" 
    x-transition:leave="transition-opacity ease-linear duration-300" 
    x-transition:leave-start="opacity-100" 
    x-transition:leave-end="opacity-0" 
    class="fixed inset-0 bg-gray-900/80" 
    x-description="Off-canvas menu backdrop, show/hide based on off-canvas menu state." 
    style="display: none;"></div>

  <div class="fixed inset-0 flex">
    <div 
      x-show="open" 
      x-transition:enter="transition ease-in-out duration-300 transform" 
      x-transition:enter-start="-translate-x-full" 
      x-transition:enter-end="translate-x-0" 
      x-transition:leave="transition ease-in-out duration-300 transform" 
      x-transition:leave-start="translate-x-0" 
      x-transition:leave-end="-translate-x-full" 
      x-description="Off-canvas menu, show/hide based on off-canvas menu state." 
      class="relative mr-16 flex w-full max-w-xs flex-1" 
      @click.away="open = false" 
      style="display: none;">
      <div 
        x-show="open" 
        x-transition:enter="ease-in-out duration-300" 
        x-transition:enter-start="opacity-0" 
        x-transition:enter-end="opacity-100" 
        x-transition:leave="ease-in-out duration-300" 
        x-transition:leave-start="opacity-100" 
        x-transition:leave-end="opacity-0" 
        x-description="Close button, show/hide based on off-canvas menu state." 
        class="absolute left-full top-0 flex w-16 justify-center pt-5" 
        style="display: none;">
        <button type="button" class="-m-2.5 p-2.5" @click="open = false">
          <span class="sr-only">Oldalsáv bezár</span>
          <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>

      <!-- Sidebar component, swap this element with another sidebar if you like -->
      <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-gray-900 px-6 pb-2 ring-1 ring-white/10">
        <!-- <div class="flex h-16 shrink-0 items-center">
          <img class="h-12 w-auto" src="/images/negyevszaklogo_128.webp" alt="Négy Évszak Training Kft." width="48" height="48">
        </div> -->
        @include('customer.sidebar.top-placeholder')
        <nav class="flex flex-1 flex-col">
          <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
              <ul role="list" class="-mx-2 space-y-1">
                @include('project.customer.sidebar.item-list')
              </ul>
            </li>
            <!-- <li>
              <div class="text-xs font-semibold leading-6 text-gray-400">Your teams</div>
              <ul role="list" class="-mx-2 mt-2 space-y-1">
                <li>
                  <a href="#" class="text-gray-400 hover:text-white hover:bg-gray-800 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg border border-gray-700 bg-gray-800 text-[0.625rem] font-medium text-gray-400 group-hover:text-white">H</span>
                    <span class="truncate">Heroicons</span>
                  </a>
                </li>
                <li>
                  <a href="#" class="text-gray-400 hover:text-white hover:bg-gray-800 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg border border-gray-700 bg-gray-800 text-[0.625rem] font-medium text-gray-400 group-hover:text-white">T</span>
                    <span class="truncate">Tailwind Labs</span>
                  </a>
                </li>
                <li>
                  <a href="#" class="text-gray-400 hover:text-white hover:bg-gray-800 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg border border-gray-700 bg-gray-800 text-[0.625rem] font-medium text-gray-400 group-hover:text-white">W</span>
                    <span class="truncate">Workcation</span>
                  </a>
                </li>
              </ul>
            </li> -->

            @include('customer.sidebar.user-dropdown')

          </ul>
        </nav>
      </div>
    </div>
  </div>
</div>