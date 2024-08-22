<nav x-data="{ open: false }" class="border-b border-gray-200 bg-white">
  <!-- Primary Navigation Menu -->
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="flex h-16 justify-between">
      <div class="flex">
        <!-- Logo -->
        <div class="flex flex-shrink-0 items-center">
          <a href="{{ route('project.homepage') }}">
            <x-application-logo class="block h-12 w-auto bg-white" />
          </a>
        </div>

      </div>
    </div>
  </div>

  <!-- Responsive Navigation Menu -->
  <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
    <div class="space-y-1 pb-3 pt-2">
      <x-responsive-nav-link :href="route('project.homepage')" :active="request()->routeIs('project.homepage')">
        <x-application-logo class="block h-6 w-auto bg-white" /> {{ __('shared.Homepage') }}
      </x-responsive-nav-link>
    </div>
  </div>
</nav>
