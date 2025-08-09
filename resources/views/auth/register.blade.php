<x-layout>

    <x-nav />

    <main class="max-w-[668px] mx-auto px-6 pb-6">

        <div class="mt-14 p-6 bg-gray-800 rounded-lg">

            <h1 class="mb-8 font-bold text-4xl text-center">
                Register
            </h1>

            <x-forms.form method="POST" action="/register">

                <x-forms.input 
                    name="name" 
                    label="Name" 
                    placeholder="Enter your name"/>

                    
                <x-forms.input 
                    name="email" 
                    label="Email" 
                    placeholder="Enter your email" 
                    type="email" />

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