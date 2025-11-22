@props(['user'])

@php
$menu = [
    [
        'route' => 'dashboard',
        'label' => 'Dashboard',
        'icon'  => 'ph ph-house',
        'roles' => ['admin', 'owner', 'cashier'],
    ],
    [
        'route' => 'products',
        'label' => 'Products',
        'icon'  => 'ph ph-bag',
        'roles' => ['admin'],
    ],
    [
        'route' => 'categories',
        'label' => 'Categories',
        'icon'  => 'ph ph-browsers',
        'roles' => ['admin'],
    ],
    [
        'route' => 'users',
        'label' => 'Users',
        'icon'  => 'ph ph-user',
        'roles' => ['admin'],
    ],
    [
        'route' => 'orders.create',
        'label' => 'Create Order',
        'icon'  => 'ph ph-list',
        'roles' => ['admin', 'cashier'],
    ],
    [
        'route' => 'orders',
        'label' => 'Orders',
        'icon'  => 'ph ph-clipboard-text',
        'roles' => ['admin', 'owner', 'cashier'],
    ],
    [
        'route' => 'sales',
        'label' => 'Sales',
        'icon'  => 'ph ph-cash-register',
        'roles' => ['admin', 'owner'],
    ],
    [
        'route' => 'inventory',
        'label' => 'Inventory',
        'icon'  => 'ph ph-cash-register',
        'roles' => ['admin', 'owner'],
    ],
];
@endphp

<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button"
                    class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none 
                    focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd"
                            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 
                            10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 
                            01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 
                            010 1.5H2.75A.75.75 0 012 10z"></path>
                    </svg>
                </button>
                <a href="#" class="flex ms-2 md:me-24">
                    <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">Soshie Buh</span>
                </a>
            </div>

            <div class="flex items-center">
                <div class="flex items-center space-x-3">
                    @if($user->profile_image == null)
                        <img class="w-10 h-10 rounded-full" alt="Profile Pic"
                            src="{{ asset('storage/images/profiles/default.jpg') }}">
                    @else
                        <img class="w-10 h-10 rounded-full" alt="Profile Pic"
                            src="{{ asset('storage/' . $user->profile_image) }}">
                    @endif

                    <div class="flex flex-col leading-tight text-gray-700 dark:text-gray-400">
                        <span class="font-semibold">{{ $user->name ?? 'Name' }}</span>
                        <span class="text-sm">{{ $user->email ?? 'Email' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-52 h-screen pt-20 transition-transform -translate-x-full bg-white 
    border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800 flex flex-col justify-between">
        <ul class="space-y-2 font-medium">
            @foreach ($menu as $item)
                @if(in_array($user->role, $item['roles']))
                    <li>
                        <x-sidebar-link href="{{ route($user->role . '.' . $item['route']) }}">
                            <x-slot:icon>
                                <i class="{{ $item['icon'] }}"></i>
                            </x-slot:icon>
                            {{ $item['label'] }}
                        </x-sidebar-link>
                    </li>
                @endif
            @endforeach
        </ul>

        <ul class="space-y-2 font-medium border-t border-gray-200 dark:border-gray-700 pt-4">
            <!-- <li>
                <x-sidebar-link href="{{ route('profile', $user->id) }}">
                    <x-slot:icon><i class="ph ph-user-circle"></i></x-slot:icon>
                    Profile
                </x-sidebar-link>
            </li> -->
            <li>
                <x-sidebar-link href="{{ route('logout') }}">
                    <x-slot:icon><i class="ph ph-sign-out"></i></x-slot:icon>
                    Logout
                </x-sidebar-link>
            </li>
        </ul>
    </div>
</aside>
