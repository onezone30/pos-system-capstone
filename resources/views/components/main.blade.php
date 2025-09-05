@props(['user'])

<x-layout>

    <x-sidebar :user="$user" />

    <main class="p-4 sm:ml-64 mt-14">

        {{ $slot }}

    </main>

</x-layout>