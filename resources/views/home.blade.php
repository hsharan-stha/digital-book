<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kindle UI Clone</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Swiper CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!-- Phosphor Icons -->
<script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>

<body class="bg-white text-black">


<div class="flex items-center justify-between px-4 py-3 sticky top-0 bg-gradient-to-r from-indigo-500 to-purple-500 shadow-md text-white z-50">
  <!-- Left: Menu and Title -->
  <div class="flex items-center space-x-3">
    <i class="ph ph-book text-2xl cursor-pointer hover:text-indigo-200"></i>
    <span class="text-xl font-bold tracking-wide">Digital Book</span>
  </div>

  <!-- Right: Icons -->
  <div class="flex items-center space-x-4">
    <!-- <i class="ph ph-magnifying-glass text-2xl cursor-pointer hover:text-indigo-200"></i>
    <i class="ph ph-user text-2xl cursor-pointer hover:text-indigo-200"></i> -->
    <i class="ph ph-shopping-cart text-2xl cursor-pointer hover:text-indigo-200"></i>
  </div>
</div>

  <!-- Search Bar -->
  <div class="p-4" id="homeTab">
    <input type="text" placeholder="Search Kindle"
      class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4" />


    <!-- Multiple Categories Example -->
    <div class="space-y-8 pb-24">

      <!-- Category 1 -->
      <div>
        <h2 class="text-xl font-semibold mb-4">Best Sellers</h2>
        <div class="swiper mySwiper">
          <div class="swiper-wrapper">
            <!-- Slides -->
            <div class="swiper-slide w-24">
               <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <img loading="lazy" src="{{asset("images/cover.png")  }}"
                  alt="Book cover" class="w-full h-56 object-cover">
                <div class="p-4">
                  <h3 class="font-semibold text-lg mb-2">New Recording Technology</h3>
                  <p class="text-sm text-gray-600 mb-4">by Japan Video Communication Association</p>
                  <a href="/reader" class="text-indigo-600 font-semibold hover:underline">Read More</a>
                </div>
              </div>
            </div>
            <div class="swiper-slide w-24">
              <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <img src="https://images.unsplash.com/photo-1529655683826-aba9b3e77383?auto=format&fit=crop&w=400&q=80"
                  alt="Book cover" class="w-full h-56 object-cover">
                <div class="p-4">
                  <h3 class="font-semibold text-lg mb-2">1984</h3>
                  <p class="text-sm text-gray-600 mb-4">by George Orwell</p>
                  <a href="#" class="text-indigo-600 font-semibold hover:underline">Read More</a>
                </div>
              </div>
            </div>
           
            <div class="swiper-slide w-24">
              <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=400&q=80"
                  alt="Book cover" class="w-full h-56 object-cover">
                <div class="p-4">
                  <h3 class="font-semibold text-lg mb-2">The Great Gatsby</h3>
                  <p class="text-sm text-gray-600 mb-4">by F. Scott Fitzgerald</p>
                  <a href="#" class="text-indigo-600 font-semibold hover:underline">Read More</a>
                </div>
              </div>
            </div>
            <div class="swiper-slide w-24">
              <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <img src="https://images.unsplash.com/photo-1529655683826-aba9b3e77383?auto=format&fit=crop&w=400&q=80"
                  alt="Book cover" class="w-full h-56 object-cover">
                <div class="p-4">
                  <h3 class="font-semibold text-lg mb-2">1984</h3>
                  <p class="text-sm text-gray-600 mb-4">by George Orwell</p>
                  <a href="#" class="text-indigo-600 font-semibold hover:underline">Read More</a>
                </div>
              </div>
            </div>
            <div class="swiper-slide w-24">
              <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <img src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=400&q=80"
                  alt="Book cover" class="w-full h-56 object-cover">
                <div class="p-4">
                  <h3 class="font-semibold text-lg mb-2">Pride and Prejudice</h3>
                  <p class="text-sm text-gray-600 mb-4">by Jane Austen</p>
                  <a href="#" class="text-indigo-600 font-semibold hover:underline">Read More</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Category 2 -->
      <div>
        <h2 class="text-xl font-semibold mb-4">Recommended For You</h2>
        <div class="swiper mySwiper">
          <div class="swiper-wrapper">
            <div class="swiper-slide w-24">
              <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=400&q=80"
                  alt="Book cover" class="w-full h-56 object-cover">
                <div class="p-4">
                  <h3 class="font-semibold text-lg mb-2">The Great Gatsby</h3>
                  <p class="text-sm text-gray-600 mb-4">by F. Scott Fitzgerald</p>
                  <a href="#" class="text-indigo-600 font-semibold hover:underline">Read More</a>
                </div>
              </div>
            </div>
            <div class="swiper-slide w-24">
              <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <img src="https://images.unsplash.com/photo-1529655683826-aba9b3e77383?auto=format&fit=crop&w=400&q=80"
                  alt="Book cover" class="w-full h-56 object-cover">
                <div class="p-4">
                  <h3 class="font-semibold text-lg mb-2">1984</h3>
                  <p class="text-sm text-gray-600 mb-4">by George Orwell</p>
                  <a href="#" class="text-indigo-600 font-semibold hover:underline">Read More</a>
                </div>
              </div>
            </div>
            <div class="swiper-slide w-24">
              <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <img src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=400&q=80"
                  alt="Book cover" class="w-full h-56 object-cover">
                <div class="p-4">
                  <h3 class="font-semibold text-lg mb-2">Pride and Prejudice</h3>
                  <p class="text-sm text-gray-600 mb-4">by Jane Austen</p>
                  <a href="#" class="text-indigo-600 font-semibold hover:underline">Read More</a>
                </div>
              </div>
            </div>
            <div class="swiper-slide w-24">
              <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=400&q=80"
                  alt="Book cover" class="w-full h-56 object-cover">
                <div class="p-4">
                  <h3 class="font-semibold text-lg mb-2">The Great Gatsby</h3>
                  <p class="text-sm text-gray-600 mb-4">by F. Scott Fitzgerald</p>
                  <a href="#" class="text-indigo-600 font-semibold hover:underline">Read More</a>
                </div>
              </div>
            </div>
            <div class="swiper-slide w-24">
              <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <img src="https://images.unsplash.com/photo-1529655683826-aba9b3e77383?auto=format&fit=crop&w=400&q=80"
                  alt="Book cover" class="w-full h-56 object-cover">
                <div class="p-4">
                  <h3 class="font-semibold text-lg mb-2">1984</h3>
                  <p class="text-sm text-gray-600 mb-4">by George Orwell</p>
                  <a href="#" class="text-indigo-600 font-semibold hover:underline">Read More</a>
                </div>
              </div>
            </div>
            <div class="swiper-slide w-24">
              <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <img src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=400&q=80"
                  alt="Book cover" class="w-full h-56 object-cover">
                <div class="p-4">
                  <h3 class="font-semibold text-lg mb-2">Pride and Prejudice</h3>
                  <p class="text-sm text-gray-600 mb-4">by Jane Austen</p>
                  <a href="#" class="text-indigo-600 font-semibold hover:underline">Read More</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Category 3 -->
      <div>
        <h2 class="text-xl font-semibold mb-4">New Releases</h2>
        <div class="swiper mySwiper">
          <div class="swiper-wrapper">
            <div class="swiper-slide w-24">
              <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=400&q=80"
                  alt="Book cover" class="w-full h-56 object-cover">
                <div class="p-4">
                  <h3 class="font-semibold text-lg mb-2">The Great Gatsby</h3>
                  <p class="text-sm text-gray-600 mb-4">by F. Scott Fitzgerald</p>
                  <a href="#" class="text-indigo-600 font-semibold hover:underline">Read More</a>
                </div>
              </div>
            </div>
            <div class="swiper-slide w-24">
              <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <img src="https://images.unsplash.com/photo-1529655683826-aba9b3e77383?auto=format&fit=crop&w=400&q=80"
                  alt="Book cover" class="w-full h-56 object-cover">
                <div class="p-4">
                  <h3 class="font-semibold text-lg mb-2">1984</h3>
                  <p class="text-sm text-gray-600 mb-4">by George Orwell</p>
                  <a href="#" class="text-indigo-600 font-semibold hover:underline">Read More</a>
                </div>
              </div>
            </div>
            <div class="swiper-slide w-24">
              <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <img src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=400&q=80"
                  alt="Book cover" class="w-full h-56 object-cover">
                <div class="p-4">
                  <h3 class="font-semibold text-lg mb-2">Pride and Prejudice</h3>
                  <p class="text-sm text-gray-600 mb-4">by Jane Austen</p>
                  <a href="#" class="text-indigo-600 font-semibold hover:underline">Read More</a>
                </div>
              </div>
            </div>
            <div class="swiper-slide w-24">
              <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=400&q=80"
                  alt="Book cover" class="w-full h-56 object-cover">
                <div class="p-4">
                  <h3 class="font-semibold text-lg mb-2">The Great Gatsby</h3>
                  <p class="text-sm text-gray-600 mb-4">by F. Scott Fitzgerald</p>
                  <a href="#" class="text-indigo-600 font-semibold hover:underline">Read More</a>
                </div>
              </div>
            </div>
            <div class="swiper-slide w-24">
              <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <img src="https://images.unsplash.com/photo-1529655683826-aba9b3e77383?auto=format&fit=crop&w=400&q=80"
                  alt="Book cover" class="w-full h-56 object-cover">
                <div class="p-4">
                  <h3 class="font-semibold text-lg mb-2">1984</h3>
                  <p class="text-sm text-gray-600 mb-4">by George Orwell</p>
                  <a href="#" class="text-indigo-600 font-semibold hover:underline">Read More</a>
                </div>
              </div>
            </div>
            <div class="swiper-slide w-24">
              <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <img src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=400&q=80"
                  alt="Book cover" class="w-full h-56 object-cover">
                <div class="p-4">
                  <h3 class="font-semibold text-lg mb-2">Pride and Prejudice</h3>
                  <p class="text-sm text-gray-600 mb-4">by Jane Austen</p>
                  <a href="#" class="text-indigo-600 font-semibold hover:underline">Read More</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>



  </div>



  <!-- Bottom Navigation -->
  <div class="fixed bottom-0 left-0 right-0 bg-white border-t flex justify-around py-2 z-[1111]">
    <a href="/" id="homeBtn" class="text-blue-600 font-semibold">Home</a>
    <a href="/library" id="libraryBtn" class="text-gray-500">Library</a>
  </div>



</body>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
  const swipers = document.querySelectorAll('.mySwiper');
  swipers.forEach(container => {
    new Swiper(container, {
      spaceBetween: 12,
      freeMode: true,
      breakpoints: {
        0: {
          slidesPerView: 1,
        },
        640: {
          slidesPerView: 2,
        },
        1024: {
          slidesPerView: 4,
        },
      },
    });
  });
</script>

</html>