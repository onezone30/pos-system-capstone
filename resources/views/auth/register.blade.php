<x-layout>

    <x-nav />

    <main class="max-w-[668px] mx-auto px-6 pb-6">

        <div class="mt-14 p-6 bg-gray-800 rounded-lg">

            <h1 class="mb-8 font-bold text-4xl text-center">
                Register
            </h1>

            <x-forms.form method="POST" action="/register" enctype="multipart/form-data">

                <x-forms.input 
                    name="name" 
                    label="Name" 
                    placeholder="Enter your name"
                    value="{{ old('name') }}"/>

                    
                <x-forms.input 
                    name="email" 
                    label="Email" 
                    placeholder="Enter your email" 
                    value="{{ old('email') }}"
                    type="email" />

                <x-forms.select label="Role" name="role">
                    @foreach (App\Models\User::ROLES as $role)
                    <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                    @endforeach
                </x-forms.select>

                <x-forms.input 
                    name="profile_image"
                    type="file" 
                    label="Profile Image" />

                <x-forms.password />

                <x-forms.password 
                    name="password_confirmation"
                    placeholder="Enter confirm password"/>


                <x-forms.divider />

                <div class="text-right">
                    <x-forms.button>
                        Sign Up
                    </x-forms.button>
                </div>
            </x-forms.form>
                

        </div>

    </main>




</x-layout>