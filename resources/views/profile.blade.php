<x-main>
    <x-page-title text="Profile" />

    <div class="max-w-4xl mt-6 ml-0 sm:ml-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">

            {{-- Profile Header --}}
            <div class="flex flex-col sm:flex-row items-center gap-6">
                <img
                    src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                    alt="Profile Image"
                    class="w-28 h-28 rounded-full border object-cover"
                >

                <div class="flex-1">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
                        {{ $user->name }}
                    </h2>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                        Role: <span class="{{ $user->role_color }}">{{ ucfirst($user->role) }}</span>
                    </p>
                </div>
            </div>

            <form
                method="POST"
                action="{{ route('profile.update', $user->id) }}"
                enctype="multipart/form-data"
                class="mt-8 space-y-6"
            >
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <x-forms.input
                            label="Full Name"
                            type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                        />
                    </div>

                    <div>
                        <x-forms.input
                            label="Email Address"
                            type="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                        />

                    </div>

                    <div>
                        <x-forms.input
                            label="Role"
                            type="text"
                            value="{{ ucfirst($user->role) }}"
                            disabled
                        />


                    </div>

                    <div>
                        <x-forms.input
                            label="Profile Image"
                            type="file"
                            name="profile_image"
                        />
                    </div>
                </div>

                <div class="flex justify-end">
                    <x-forms.button>
                        Save Changes
                    </x-forms.button>
                </div>
            </form>
        </div>
    </div>
</x-main>
