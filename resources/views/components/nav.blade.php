<nav class="bg-gray-800 shadow-xl dark:bg-gray-900 border-b border-gray-700">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <a href="{{ url('/') }}" class="flex items-center space-x-3 rtl:space-x-reverse transition duration-150 ease-in-out hover:opacity-90">
                <span class="self-center text-xl font-extrabold text-white">
                    Sushie Buh
                </span>
            </a>

            <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-400 rounded-lg md:hidden hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600" aria-controls="navbar-default" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                </svg>
            </button>

            <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                <ul class="font-medium flex flex-col md:flex-row md:space-x-4 md:mt-0 md:border-0 md:bg-gray-800">
                    <!-- @guest
                        <li>
                            <a href="{{ route('login') }}" class="block px-3 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                                Log In
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('register') }}" class="block px-3 py-2 text-sm font-semibold rounded-md text-white bg-indigo-600 hover:bg-indigo-500 transition duration-150 ease-in-out">
                                Register
                            </a>
                        </li>
                    @endguest -->
                    @auth
                        <li>
                            <a href="{{ route(auth()->user()->role . '.dashboard') ?? url('/dashboard') }}" class="block px-3 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-3 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                                Log Out
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </div>
</nav>