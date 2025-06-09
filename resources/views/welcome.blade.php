<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Digital Book - Buy & Read Books</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900">

  {{-- Navbar --}}
  <nav class="bg-white shadow-md">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
      <a href="#" class="text-2xl font-bold text-indigo-600">Digital Book</a>
      <div class="space-x-6 hidden md:flex">
        <a href="#featured" class="hover:text-indigo-600">Featured</a>
        <a href="#categories" class="hover:text-indigo-600">Categories</a>
        <a href="#about" class="hover:text-indigo-600">About</a>
      </div>
      <button class="md:hidden text-indigo-600 focus:outline-none">
        <!-- Mobile menu icon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
          viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 8h16M4 16h16" />
        </svg>
      </button>
    </div>
  </nav>

  {{-- Hero Section --}}
  <section class="bg-indigo-600 text-white py-20 px-6 text-center">
    <h1 class="text-4xl md:text-5xl font-extrabold max-w-3xl mx-auto mb-4">
      Discover & Read Your Next Favorite Book
    </h1>
    <p class="max-w-xl mx-auto mb-8 text-indigo-200">
      Buy, sell, and read amazing books from a huge collection — anytime, anywhere.
    </p>
    <a href="#featured" class="inline-block bg-white text-indigo-600 px-6 py-3 rounded-lg font-semibold shadow hover:bg-indigo-50 transition">
      Explore Books
    </a>
  </section>

  {{-- Featured Books --}}
  <section id="featured" class="max-w-7xl mx-auto px-6 py-16">
    <h2 class="text-3xl font-bold mb-8 text-center text-gray-800">Featured Books</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">

      {{-- Example Book Card --}}
      <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
        <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=400&q=80" alt="Book cover" class="w-full h-56 object-cover">
        <div class="p-4">
          <h3 class="font-semibold text-lg mb-2">The Great Gatsby</h3>
          <p class="text-sm text-gray-600 mb-4">by F. Scott Fitzgerald</p>
          <a href="#" class="text-indigo-600 font-semibold hover:underline">Read More</a>
        </div>
      </div>

      {{-- Repeat for other books --}}
      <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
        <img src="https://images.unsplash.com/photo-1529655683826-aba9b3e77383?auto=format&fit=crop&w=400&q=80" alt="Book cover" class="w-full h-56 object-cover">
        <div class="p-4">
          <h3 class="font-semibold text-lg mb-2">1984</h3>
          <p class="text-sm text-gray-600 mb-4">by George Orwell</p>
          <a href="#" class="text-indigo-600 font-semibold hover:underline">Read More</a>
        </div>
      </div>

    

      <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
        <img src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=400&q=80" alt="Book cover" class="w-full h-56 object-cover">
        <div class="p-4">
          <h3 class="font-semibold text-lg mb-2">Pride and Prejudice</h3>
          <p class="text-sm text-gray-600 mb-4">by Jane Austen</p>
          <a href="#" class="text-indigo-600 font-semibold hover:underline">Read More</a>
        </div>
      </div>

    </div>
  </section>

  {{-- Categories --}}
  <section id="categories" class="bg-white py-16 px-6">
    <div class="max-w-7xl mx-auto text-center">
      <h2 class="text-3xl font-bold mb-8 text-gray-800">Browse by Categories</h2>

      <div class="flex flex-wrap justify-center gap-6">

        <a href="#" class="px-6 py-3 bg-indigo-100 text-indigo-700 rounded-full font-semibold hover:bg-indigo-200 transition">Fiction</a>
        <a href="#" class="px-6 py-3 bg-indigo-100 text-indigo-700 rounded-full font-semibold hover:bg-indigo-200 transition">Non-Fiction</a>
        <a href="#" class="px-6 py-3 bg-indigo-100 text-indigo-700 rounded-full font-semibold hover:bg-indigo-200 transition">Mystery</a>
        <a href="#" class="px-6 py-3 bg-indigo-100 text-indigo-700 rounded-full font-semibold hover:bg-indigo-200 transition">Science</a>
        <a href="#" class="px-6 py-3 bg-indigo-100 text-indigo-700 rounded-full font-semibold hover:bg-indigo-200 transition">Fantasy</a>
        <a href="#" class="px-6 py-3 bg-indigo-100 text-indigo-700 rounded-full font-semibold hover:bg-indigo-200 transition">History</a>

      </div>
    </div>
  </section>

  {{-- About Section --}}
  <section id="about" class="max-w-7xl mx-auto px-6 py-16 text-center">
    <h2 class="text-3xl font-bold mb-4">About BookNest</h2>
    <p class="max-w-3xl mx-auto text-gray-700 leading-relaxed">
      BookNest is your one-stop platform to buy, sell, and read books online. We offer a vast collection across all genres, with easy access to reading and purchasing from anywhere.
    </p>
  </section>

  {{-- Footer --}}
  <footer class="bg-indigo-600 text-white py-8 text-center">
    <p>&copy; 2025 BookNest. All rights reserved.</p>
  </footer>

</body>
</html>
