<x-main :user="$user">

<div class="flex justify-between items-center">


    <h1 class="text-2xl font-bold">
        Users
    </h1>
    
    <x-forms.form action="/admin/users" method="POST" enctype="multipart/form-data">
        
        <x-button data-modal-target="crud-modal" data-modal-toggle="crud-modal">
            Add Users
        </x-button>

        <!-- add modal -->
        <x-modals.create header="Create User">

            <div class="space-y-6">
                <x-forms.input 
                    name="name"
                    label="Name"
                    placeholder="Enter your name"/>

                <x-forms.select label="Select role" name="role">
                    @foreach (App\Models\User::ROLES as $role)
                        <option value="{{ $role }}">
                            {{ $role }}
                        </option>
                    @endforeach
                </x-forms.select>

                <x-forms.input 
                    name="email" 
                    label="Email" 
                    placeholder="Enter your email" 
                    type="email" />

                <x-forms.input 
                    name="profile_image"
                    type="file" 
                    label="Profile Image" />

                <x-forms.input 
                    name="password" 
                    label="Password" 
                    placeholder="Enter your password" 
                    type="password" />

                <x-forms.input 
                    name="password_confirmation" 
                    label="Confirm Password" 
                    placeholder="Enter your confirm password" 
                    type="password" />


                <x-slot name="footer">
                    <x-forms.button>
                        Create User
                    </x-forms.button>
                </x-slot>
            </div>

        </x-modals.create>

    </x-forms.form>

</div>

<x-section>


    <div class="grid gap-y-6 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 ">

        @foreach ($users as $user)
            <x-user-card :user="$user"/>
        @endforeach

    </div>
    @foreach ($users as $user)
            <x-modals.delete
                :title="$user->name"
                id="{{ $user->id }}" 
                :action="route('admin.users.destroy', $user->id)"/>
            <x-modals.edit 
                id="{{ $user->id }}"
                header="Edit User: {{ $user->name }}"
                :action="route('admin.users.update', $user->id)">


                <div class="space-y-6">
                    <x-forms.input 
                        name="name"
                        label="Name"
                        placeholder="Enter your name"
                        value="{{ $user->name ?? '' }}"/>

                    <x-forms.select 
                        id="role_{{ $user->id }}" 
                        label="Select role" 
                        value="{{ $user->role }}"
                        name="role">
                        @foreach (App\Models\User::ROLES as $role)
                            <option value="{{ $role }}">
                                {{ $role }}
                            </option>
                        @endforeach
                    </x-forms.select>

                    <x-forms.input 
                        name="email" 
                        label="Email" 
                        placeholder="Enter your email" 
                        value="{{ $user->email ?? '' }}"
                        type="email" />

                    <x-forms.input 
                        name="profile_image"
                        type="file" 
                        label="Profile Image" />

                    <x-forms.input 
                        name="password" 
                        label="Password" 
                        placeholder="Enter your password" 
                        type="password" />

                    <x-forms.input 
                        name="password_confirmation" 
                        label="Confirm Password" 
                        placeholder="Enter your confirm password" 
                        type="password" />


                    <x-slot name="footer">
                        <x-forms.button>
                            Create User
                        </x-forms.button>
                    </x-slot>
                </div>
            
            </x-modals.edit>
            <x-modals.view-user
                :user="$user"
                :id="$user->id"
                header="User Details: {{ $user->name }}" />
    @endforeach

</x-section>

</x-main>