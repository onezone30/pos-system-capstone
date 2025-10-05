<x-layout>

    <x-sidebar :user="Auth::user()" />

    <main class="p-4 sm:ml-64 mt-14">

        {{ $slot }}

    </main>

</x-layout>