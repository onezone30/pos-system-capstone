<div
    id="user-list" 
    class="mt-5 grid grid-cols-1 gap-y-4 gap-x-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

    @foreach ($users as $user)
        <x-user-card :card_user="$user" :key="$user->id"/>    
    @endforeach


    <!-- Delete Modal -->
    <x-modals.delete action="delete" />
</div>

