<x-layout>

    <x-nav />

    <main class="mt-14 max-w-[668px] mx-auto px-6">

        <div class="p-6 bg-gray-800 rounded-lg">

            <p class="text-gray-300 text-center mb-6">
                Reset your password by creating a new one below. 
                Choose a strong password that you haven’t used before 
                to keep your account secure. Once updated, you can log in 
                with your new password right away.
            </p>


            <x-forms.form method="POST" action="{{ route('password.update', ['token' => $token]) }}">

                <x-forms.input 
                    type="hidden"
                    name="email"
                    value="{{ $email }}" />

                <x-forms.input 
                    type="hidden"
                    name="token"
                    value="{{ $token }}" />

                <x-forms.password />

                <x-forms.password 
                    name="password_confirmation"
                    placeholder="Enter confirm password"/>

                <x-forms.divider />

                <div class="text-right">
                    <x-forms.button>
                        Set Password
                    </x-forms.button>
                </div>
            </x-forms.form>
                

        </div>

    </main>




</x-layout>