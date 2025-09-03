<x-layout>

    <x-nav />

    <main class="mt-14 max-w-[668px] mx-auto px-6">

        <div class="p-6 bg-gray-800 rounded-lg">

            <p class="text-gray-300 text-center mb-6">
                Forgot your password? Don’t worry — it happens to the best of us. 
                Just enter the email address linked to your account, and we’ll send you 
                a secure link to reset your password. Follow the instructions in the email, 
                and you’ll be back in your account in no time.
            </p>


            <x-forms.form method="POST" action="{{ route('forgot-password.email') }}">

                <x-forms.input 
                    name="email" 
                    placeholder="Enter your email" 
                    type="email" />

                <x-forms.divider />

                <div class="text-right">
                    <x-forms.button>
                        Send Email
                    </x-forms.button>
                </div>
            </x-forms.form>
                

        </div>

    </main>




</x-layout>