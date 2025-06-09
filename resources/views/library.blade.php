<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kindle UI Clone</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Swiper CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <style>
    .swiper-slide img {
      width: 100%;
      height: auto;
      border-radius: 0.5rem;
    }
  </style>
</head>

<body class="bg-white text-black">
  <div class="flex items-center justify-between px-4 py-3 border-b shadow-sm sticky top-0 bg-white z-50">
    <div class="flex items-center space-x-2">
      <!-- <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/75/Kindle_logo.svg/2560px-Kindle_logo.svg.png" alt="Logo" class="h-6 object-contain"> -->
      <span class="text-xl font-semibold">Digital Book</span>
    </div>
    <div>
      <i class="ph ph-shopping-cart text-2xl"></i>
    </div>
  </div>

  <!-- Library Tab -->
  <div class="p-4 " id="libraryTab">
     <input type="text" placeholder="Search Kindle"
      class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4" />
    <h2 class="text-xl font-semibold mb-4">Your Library</h2>
    <div class="grid grid-cols-3 gap-4 mb-20">
      
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
          slidesPerView: 4,
          spaceBetween: 12,
          freeMode: true,
        });
      });
    </script>
</html>