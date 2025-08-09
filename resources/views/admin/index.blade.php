<x-layout>

    <x-nav />

    <main class="max-w-[768px] mx-auto px-6">

        <p>{{ $user->id }}</p>
        <p>{{ $user->name }}</p>
        <p>{{ $user->role }}</p>
        <p>{{ $user->email }}</p>
        <p>{{ $user->password }}</p>

        <a href="/logout">

            <x-forms.button>
                Log Out
            </x-forms.button>

        </a>

    </main>

</x-layout>