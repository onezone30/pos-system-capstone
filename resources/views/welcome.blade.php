<x-layout>
    <x-nav />

    <main class="bg-white dark:bg-gray-800 min-h-screen">
        <!-- Hero Section -->
        <section class="relative bg-orange-100 dark:bg-orange-900">
            <div class="max-w-7xl mx-auto px-4 py-20 text-center">
                <h1 class="text-4xl md:text-6xl font-bold text-gray-900 dark:text-white">
                    Welcome to Soshie Buh
                </h1>
                <p class="mt-4 text-lg md:text-xl text-gray-700 dark:text-gray-300">
                    Fresh, tasty, and delivered straight to your door.
                </p>
                <a href="#menu" class="mt-8 inline-block bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-3 rounded-lg transition">
                    Explore Menu
                </a>
            </div>
        </section>

        <!-- Menu Section: Categories Only -->
        <section id="menu" class="max-w-7xl mx-auto px-4 py-16">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white text-center mb-12">
                Explore Our Categories
            </h2>

            @php
                $categories = [
                    ['name' => 'Burgers', 'image' => 'https://source.unsplash.com/400x300/?burger'],
                    ['name' => 'Pizza', 'image' => 'https://source.unsplash.com/400x300/?pizza'],
                    ['name' => 'Drinks', 'image' => 'https://source.unsplash.com/400x300/?drink'],
                    ['name' => 'Desserts', 'image' => 'https://source.unsplash.com/400x300/?dessert'],
                    ['name' => 'Salads', 'image' => 'https://source.unsplash.com/400x300/?salad'],
                    ['name' => 'Takoyaki', 'image' => 'https://source.unsplash.com/400x300/?salad'],
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($categories as $category)
                    <a href="#" class="relative group rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition">
                        <img src="{{ $category['image'] }}" alt="{{ $category['name'] }}" class="w-full h-48 object-cover group-hover:scale-105 transition transform">
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                            <h3 class="text-2xl font-bold text-white">{{ $category['name'] }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>


        <!-- About Section -->
        <section class="bg-gray-100 dark:bg-gray-900 py-16">
            <div class="max-w-4xl mx-auto text-center px-4">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">About Us</h2>
                <p class="mt-4 text-gray-700 dark:text-gray-300">
                    At Delicious Bites, we believe in fresh ingredients, amazing flavors, and delivering happiness to every table.
                </p>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="max-w-4xl mx-auto py-16 px-4 text-center">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Get in Touch</h2>
            <p class="mt-4 text-gray-700 dark:text-gray-300 mb-8">
                Have questions? Reach out and we’ll get back to you in no time!
            </p>
            <form class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input type="text" placeholder="Your Name" class="p-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:outline-none">
                <input type="email" placeholder="Your Email" class="p-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:outline-none">
                <textarea placeholder="Message" class="sm:col-span-2 p-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:outline-none"></textarea>
                <button type="submit" class="sm:col-span-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-3 rounded-lg transition">
                    Send Message
                </button>
            </form>
        </section>
    </main>
</x-layout>