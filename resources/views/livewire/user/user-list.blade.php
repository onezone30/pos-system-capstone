<div
    x-data="userFilter()"
    @user-filter.window="updateFilter($event.detail)"
    @user-search.window="search = $event.detail"
>
    <div
        id="user-list" 
        class="mt-5 grid grid-cols-1 gap-y-4 gap-x-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 items-stretch">

        @forelse ($users as $user)
                <div
                    x-show="matches(
                        $el.dataset.role,
                        $el.dataset.name
                    )"
                    x-cloak
                    data-name="{{ $user->name }}"
                    data-role="{{ $user->role }}"
                >
                    <x-user-card :card_user="$user" :key="$user->id"/>    
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500 py-4">
                    No users found.
                </div>
        @endforelse

        <!-- rendering modals -->
        <!-- Edit Modal -->
        <x-modals.edit>
            <livewire:user.edit-user-form />
        </x-modals.edit>
        <!-- View Modal -->
        <x-modals.view-user />
        <!-- Delete Modal -->
        <x-modals.delete />
    </div>
</div>


    @push('js')

    <script>
        const userFilter = () => {
            return {
                search: '',
                name: '',
                role: '',

                updateFilter(payload) {
                    this.role = payload.role ?? '';
                },

                matches(role, name) {
                    role = (role || '').toString().toLowerCase();
                    name = (name || '').toString().toLowerCase();

                    const s = this.search.toLowerCase().trim();

                    if(s && !name.includes(s)) return false;

                    if(this.role && role !== this.role.toLowerCase()) return false;

                    return true;
                }
            }
        }
    </script>

    @endpush

