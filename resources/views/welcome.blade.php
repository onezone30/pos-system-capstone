<x-layout>

    <x-nav />

    <main class="mt-14 max-w-[768px] mx-auto px-6">
    
        @guest

        <h1>
            You are not logged in
        </h1>

        @endguest

        @auth

        <h1>
            You are logged in
        </h1>

        @endauth

    </main>

</x-layout>