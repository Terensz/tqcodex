<?php

use Illuminate\Support\Facades\Route;
use Domain\User\Services\UserService;
use Domain\User\Services\AccessTokenService;

$currentRouteName = Route::currentRouteName();

?>

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
      <a href="{{ route('admin.dashboard', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN)]) }}" class="text-gray-400 hover:text-white hover:bg-gray-800 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg border border-gray-700 bg-gray-800 text-[0.625rem] font-medium text-gray-400 group-hover:text-white">T</span>
        <span class="truncate">Tailwind Labs</span>
      </a>
    </li>
    <li>
      <a href="{{ route('admin.dashboard', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN)]) }}" class="text-gray-400 hover:text-white hover:bg-gray-800 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg border border-gray-700 bg-gray-800 text-[0.625rem] font-medium text-gray-400 group-hover:text-white">W</span>
        <span class="truncate">Workcation</span>
      </a>
    </li>
  </ul>
</li> -->
<li class="-mx-6 mt-auto">
  <div class="flex-shrink-0 flex border-t border-gray-200 p-4 mt-20">
    <a href="#" class="flex-shrink-0 group block">
      <div class="flex items-center">
        <div @click.away="open = false" class="relative" x-data="{ open: false }">
          <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="bottom-0 origin-top-right absolute left-0 mt-2 -mr-1 w-48 rounded-md shadow-lg">
            <div class="py-1 rounded-md bg-white shadow-xs relative">
              <div class="mt-2">
                <a href="{{ route('admin.profile.edit', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN)]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition ease-in-out duration-150">{{ __('user.Profile') }}</a>
              </div>
              <!-- <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition ease-in-out duration-150">Beállítások</a> -->
              {{-- Authentication --}}
              <div class="mb-3">
                <form method="POST" action="{{ route('admin.logout') }}">
                  @csrf

                  <x-dropdown-link :href="route('admin.logout')"
                    onclick="event.preventDefault();
                    this.closest('form').submit();">
                    {{ __('user.Logout') }}
                  </x-dropdown-link>
                </form>
              </div>
            </div>
          </div>
          <div>
            <button @click="open = !open" class="max-w-xs flex items-center text-sm rounded-full text-white focus:outline-none focus:shadow-solid transition ease-in-out duration-150">
              @if (UserService::getUser(UserService::ROLE_TYPE_ADMIN)->photo_path)
              <img class="inline-block h-10 w-10 rounded-full" src="{{ UserService::getUser(UserService::ROLE_TYPE_ADMIN)->photo_path }}" alt="{{ UserService::getUser(UserService::ROLE_TYPE_ADMIN)->name }}">
              @else
              <!-- <img class="inline-block h-10 w-10 rounded-full" src="/images/Shared/man-avatar.svg" alt="{{ UserService::getUser(UserService::ROLE_TYPE_ADMIN)->name ?? 'Felhasználó' }}"> -->
              @endif
              <div class="ml-3">
                <p class="text-base leading-6 font-medium text-blue-400 group-hover:text-blue-700">
                  {{ UserService::getUser(UserService::ROLE_TYPE_ADMIN)->name ?? 'Felhasználó' }}
                </p>
                <p class="text-sm text-left leading-5 font-medium text-amber-400er group-hover:text-amber-400 transition ease-in-out duration-150">
                  {{ __('user.ProfileAndLogout') }}
                </p>
              </div>
            </button>
          </div>
        </div>

      </div>
    </a>
  </div>
</li>