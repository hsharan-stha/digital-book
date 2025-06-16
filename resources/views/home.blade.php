<x-entry-layout>


  <form class="w-full flex justify-center" method="GET" action="/">
    <!-- @csrf -->
    <div
      class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-center py-4 w-full">


      <!-- Organization Select -->
      <!-- <select id="organization" name="company_id"
      class="bg-white text-gray-700 border border-gray-300 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 w-full">
      <option value="">Organization</option>
      <option value="senmon">Senmonkyoiku Publications</option>
    </select> -->

      <!-- Category Select -->
      <select id="category" name="category_id"
        class="bg-white text-gray-700 border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 w-full">
        <option value="">Category</option>
        @forelse ($categoryList as $category)
      <option {{ isset($filteredData['category_id']) && $category->id == $filteredData['category_id'] ? "selected" : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
    @empty
      <option disabled>No categories available</option>
    @endforelse
      </select>

      <!-- Search Box -->
      <div class="w-full col-span-1 sm:col-span-2 lg:col-span-1">
        <input type="text" placeholder="Book name" name="book_name"
          value="{{ isset($filteredData['book_name']) ? $filteredData['book_name'] : '' }}"
          class="w-full pr-3 py-2 text-sm text-gray-700 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-gray-400" />
      </div>

      <!-- Buttons Group -->
      <div class="flex gap-2 ">
        <button type="submit"
          class="flex items-center gap-2 bg-gray-600 hover:bg-gray-900 text-white  font-medium px-4 py-2 rounded-full text-sm transition duration-200 ease-in-out shadow-sm hover:shadow-md">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-4.35-4.35M17.65 17.65A7.5 7.5 0 1 0 5.2 5.2a7.5 7.5 0 0 0 10.6 10.6z" />
          </svg>
          Search
        </button>

        <a href="/"
          class="flex items-center gap-2 bg-red-500 hover:bg-red-800 text-white font-medium px-4 py-2 rounded-full text-sm transition duration-200 ease-in-out shadow-sm hover:shadow-md">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
          Clear
        </a>
      </div>

    </div>
  </form>




  @forelse ($categories as $category)
    @if (count($category->books) > 0)


    <div class="flex flex-col gap-8">
    <h2 class="text-2xl font-semibold text-gray-500 capitalize">{{ $category->name }}
    </h2>
    <!-- <p class="text-sm text-gray-500 mb-10">サブ情報</p> -->
    <div>
      <div class="swiper mySwiper">

      <div class="swiper-wrapper">
      @forelse ($category->books as $book)
      <div class="swiper-slide">
      <div class="bg-white rounded-lg overflow-hidden hover:bg-gray-100 cursor-pointer relative">
      <!-- Loader spinner -->
      <div class="loader absolute inset-0 flex items-center justify-center bg-white">
      <!-- Simple spinner, you can replace with your own -->
      <svg class="animate-spin h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
      viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
      </svg>
      </div>
      <!-- Actual image -->
      <img loading="lazy" src="{{ asset($book->images) }}" alt="Book cover"
      class="object-cover opacity-0 transition-opacity duration-500">

      <div class="flex flex-col gap-2 p-2">
      <h3 class="text-xl  text-gray-500 ">{{  $book->name }}</h3>
      <p class="text-sm text-gray-500">{{  $book->description }}</p>
      <div class="flex justify-between w-full">
      <a href="/reader" class="flex gap-2  text-indigo-400  hover:underline">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
  <path d="M11.25 4.533A9.707 9.707 0 0 0 6 3a9.735 9.735 0 0 0-3.25.555.75.75 0 0 0-.5.707v14.25a.75.75 0 0 0 1 .707A8.237 8.237 0 0 1 6 18.75c1.995 0 3.823.707 5.25 1.886V4.533ZM12.75 20.636A8.214 8.214 0 0 1 18 18.75c.966 0 1.89.166 2.75.47a.75.75 0 0 0 1-.708V4.262a.75.75 0 0 0-.5-.707A9.735 9.735 0 0 0 18 3a9.707 9.707 0 0 0-5.25 1.533v16.103Z" />
</svg>

Read More</a>
      <a href="" class="flex gap-2 text-indigo-400  hover:underline">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
  <path d="M2.25 2.25a.75.75 0 0 0 0 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 0 0-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 0 0 0-1.5H5.378A2.25 2.25 0 0 1 7.5 15h11.218a.75.75 0 0 0 .674-.421 60.358 60.358 0 0 0 2.96-7.228.75.75 0 0 0-.525-.965A60.864 60.864 0 0 0 5.68 4.509l-.232-.867A1.875 1.875 0 0 0 3.636 2.25H2.25ZM3.75 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0ZM16.5 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z" />
</svg>


      Add to cart</a>
      </div>
      </div>
      </div>
      </div>

      @empty
      <p class="text-gray-500">No books in this category.</p>
      @endforelse
      </div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>

      </div>
    </div>
    </div>
    @endif
  @empty
  @endforelse


  <script>
    const swipers = document.querySelectorAll('.mySwiper');
    console.log(swipers)
    swipers.forEach(container => {
      new Swiper(container, {
        spaceBetween: 12,
        freeMode: true,
        navigation: {
          nextEl: container.querySelector('.swiper-button-next'),
          prevEl: container.querySelector('.swiper-button-prev'),
        },
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

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const slides = document.querySelectorAll('.swiper-slide');

      slides.forEach(slide => {
        const img = slide.querySelector('img');
        const loader = slide.querySelector('.loader');

        // When image is loaded
        img.addEventListener('load', () => {
          // Hide loader
          loader.style.display = 'none';
          // Show image smoothly
          img.style.opacity = '1';
        });

        // In case the image is cached and already loaded
        if (img.complete) {
          loader.style.display = 'none';
          img.style.opacity = '1';
        }
      });
    });
  </script>

</x-entry-layout>