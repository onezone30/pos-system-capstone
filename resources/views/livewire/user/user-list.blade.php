<div
    id="user-list" 
    class="mt-5 grid grid-cols-1 gap-y-4 gap-x-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 items-stretch">

    @foreach ($users as $user)
        <x-user-card :card_user="$user" :key="$user->id"/>    
    @endforeach

    <!-- rendering modals -->
    <!-- Edit Modal -->
    <x-modals.edit action="edit">
        <livewire:user.edit-user-form />
    </x-modals.edit>
    <!-- View Modal -->
    <x-modals.view-user />
    <!-- Delete Modal -->
    <x-modals.delete action="delete" />
</div>

