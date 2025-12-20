<x-layout>

    <x-nav />

    @guest

    <main class="mt-14 max-w-[668px] mx-auto px-6">

        <div class="p-6 bg-gray-800 rounded-lg">

            <h1 class="mb-8 font-bold text-4xl text-center">
                Welcome Back!
            </h1>

            <x-forms.form method="POST" action="{{ route('login.store') }}">

                <x-forms.input 
                    name="email" 
                    label="Email" 
                    placeholder="Enter your email" 
                    type="email" />

                <x-forms.password />

                <x-forms.divider />

                <div class="text-right space-x-2 ">
                    <a href="{{ route('forgot-password') }}" class="text-sm underline">
                        Forgot your password?
                    </a>
                    <x-forms.button>
                        Sign In
                    </x-forms.button>
                </div>
            </x-forms.form>
                

        </div>

    </main>

    @endguest



</x-layout>