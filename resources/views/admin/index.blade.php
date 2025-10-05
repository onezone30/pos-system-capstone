<x-main :user="$user">

        <p>{{ $user->id }}</p>
        <p>{{ $user->name }}</p>
        <p>{{ $user->role }}</p>
        <p>{{ $user->email }}</p>
        <p>{{ $user->password }}</p>

</x-main>