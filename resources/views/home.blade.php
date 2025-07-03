<x-entry-layout>


    <form class="w-full flex justify-center" method="GET" action="{{ route('store') }}">
        <!-- @csrf -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-center py-4 w-full">


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
                    <option
                        {{ isset($filteredData['category_id']) && $category->id == $filteredData['category_id'] ? 'selected' : '' }}
                        value="{{ $category->id }}">{{ $category->name }}</option>
                @empty
                    <option disabled>No categories available</option>
                @endforelse
            </select>

            <!-- Search Box -->
            <div class="w-full">
                <input type="text" placeholder="Book name" name="book_name"
                    value="{{ isset($filteredData['book_name']) ? $filteredData['book_name'] : '' }}"
                    class="w-full pr-3 py-2 text-sm text-gray-700 border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-gray-400" />
            </div>

            <!-- Buttons Group -->
            <div class="flex gap-2 ">
                <button type="submit"
                    class="flex items-center gap-2 bg-gray-400 hover:bg-gray-900 text-white  font-medium px-4 py-2  text-sm transition duration-200 ease-in-out shadow-sm hover:shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35M17.65 17.65A7.5 7.5 0 1 0 5.2 5.2a7.5 7.5 0 0 0 10.6 10.6z" />
                    </svg>
                    Search
                </button>

                <a href="{{ route('index') }}"
                    class="flex items-center gap-2 bg-red-500 hover:bg-red-800 text-white font-medium px-4 py-2  text-sm transition duration-200 ease-in-out shadow-sm hover:shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
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
                                    <div class="bg-white  overflow-hidden hover:bg-gray-100 cursor-pointer relative">


                                        <div class="skeleton-loader absolute inset-0 bg-gray-200 animate-pulse z-10">
                                        </div>

                                        <!-- Actual image -->
                                        <div class="min-h-72">
                                            <a href="{{ route('detail.view', $book->id) }}">
                                                <img loading="lazy" src="{{ asset($book->images) }}" alt="Book cover"
                                                    class="book-image  w-full transition-opacity duration-500 opacity-0">
                                            </a>
                                        </div>
                                        <div class="flex flex-col gap-2 p-2">
                                            <div class="flex justify-between">
                                                <div>
                                                    <h3
                                                        class="text-xl  text-gray-500 text-base leading-tight h-[3rem] overflow-hidden line-clamp-2">
                                                        {{ $book->name }}</h3>
                                                    <p class="text-sm text-gray-500 hidden">{{ $book->description }}
                                                    </p>
                                                </div>
                                                <!--
                                                <a href="{{ route('detail.view', $book->id) }}"
                                                    class="inline-block text-gray-500 hover:text-gray-800 hover:underline transition">
                                                    Read Me
                                                </a> -->
                                            </div>
                                            <div class="flex flex-col gap-4 mt-4 ">
                                                <!-- Read More Button
                                                <a href="/reader"
                                                    class="flex items-center gap-2 text-indigo-600 hover:text-indigo-800 font-medium transition duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="currentColor" class="w-5 h-5">
                                                        <path
                                                            d="M11.25 4.533A9.707 9.707 0 0 0 6 3a9.735 9.735 0 0 0-3.25.555.75.75 0 0 0-.5.707v14.25a.75.75 0 0 0 1 .707A8.237 8.237 0 0 1 6 18.75c1.995 0 3.823.707 5.25 1.886V4.533ZM12.75 20.636A8.214 8.214 0 0 1 18 18.75c.966 0 1.89.166 2.75.47a.75.75 0 0 0 1-.708V4.262a.75.75 0 0 0-.5-.707A9.735 9.735 0 0 0 18 3a9.707 9.707 0 0 0-5.25 1.533v16.103Z" />
                                                    </svg>
                                                    <span>Read More</span>
                                                </a> -->



                                                <div class="flex justify-between items-center font-semibold text-lg">

                                                    <div>¥<span id="total">{{ $book->price }}</span></div>
                                                </div>

                                                <button type="button" onclick="addToCart(this,{{ $book }}, 1)"
                                                    class="flex items-center justify-center gap-2 bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-3 rounded transition p-2">
                                                    <!--  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="currentColor" class="w-5 h-5 cart-icon">
                                                        <path
                                                            d="M2.25 2.25a.75.75 0 0 0 0 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 0 0-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 0 0 0-1.5H5.378A2.25 2.25 0 0 1 7.5 15h11.218a.75.75 0 0 0 .674-.421 60.358 60.358 0 0 0 2.96-7.228.75.75 0 0 0-.525-.965A60.864 60.864 0 0 0 5.68 4.509l-.232-.867A1.875 1.875 0 0 0 3.636 2.25H2.25ZM3.75 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0ZM16.5 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z" />
                                                    </svg>-->
                                                    <span class="button-text">Add to Cart</span>
                                                    <span class="loading hidden">Loading...</span>
                                                </button>
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
                        <div class="swiper-pagination relative mt-8"></div>

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
                coverflowEffect: {
                    rotate: 50,
                    stretch: 0,
                    depth: 100,
                    modifier: 1,
                    slideShadows: true,
                },
                navigation: {
                    nextEl: container.querySelector('.swiper-button-next'),
                    prevEl: container.querySelector('.swiper-button-prev'),
                },
                pagination: {
                    el: ".swiper-pagination",
                },
                breakpoints: {
                    0: {
                        slidesPerView: 1,
                    },
                    640: {
                        slidesPerView: 4,
                    },
                    1024: {
                        slidesPerView: 7,
                    },
                },

            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const bookImages = document.querySelectorAll(".book-image");
            let loadedCount = 0;

            bookImages.forEach((img) => {
                if (img.complete) {
                    // Image is already loaded from cache
                    handleImageLoad(img);
                    loadedCount++;
                } else {
                    img.addEventListener("load", () => {
                        handleImageLoad(img);
                        loadedCount++;

                    });

                    img.addEventListener("error", () => {
                        // In case image fails to load, still count it to avoid hanging
                        loadedCount++;
                    });
                }
            });

            function handleImageLoad(img) {
                const wrapper = img.closest(".swiper-slide");
                const skeleton = wrapper?.querySelector(".skeleton-loader");

                if (skeleton) {
                    skeleton.remove(); // remove shimmer effect
                }

                img.classList.remove("opacity-0");
                img.classList.add("opacity-100");
            }


        });
    </script>



    <script>
        cartCountdisplay("{{ isset($cartCount) ? $cartCount : 0 }}")
        loggedInDevicesCount({{ isset($loggedInDevices) ? $loggedInDevices : 0 }})
    </script>

</x-entry-layout>
