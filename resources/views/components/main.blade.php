<x-layout>

    <x-sidebar :user="Auth::user()" />

    <main class="p-4 min-h-screen sm:ml-52 mt-14 bg-white dark:bg-gray-800">

        {{ $slot }}

    </main>

</x-layout>