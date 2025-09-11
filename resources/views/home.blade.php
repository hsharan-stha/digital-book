<x-entry-layout>

    <!-- Overlay -->
    <div id="overlayCart" class="fixed inset-0 hidden" onclick="closeSidebarOfCart()">
    </div>

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
                <option value="">{{ __('home.category') }}</option>
                @forelse ($categoryList as $category)
                    <option
                        {{ isset($filteredData['category_id']) && $category->id == $filteredData['category_id'] ? 'selected' : '' }}
                        value="{{ $category->id }}">{{ $category->name }}</option>
                @empty
                    <option disabled>{{ __('home.noCategoriesAvailable') }}</option>
                @endforelse
            </select>

            <!-- Search Box -->
            <div class="w-full">
                <input type="text" placeholder="{{ __('home.book_name') }}" name="book_name"
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
                    {{ __('home.search') }}
                </button>

                <a href="{{ route('index') }}"
                    class="flex items-center gap-2 bg-red-500 hover:bg-red-800 text-white font-medium px-4 py-2  text-sm transition duration-200 ease-in-out shadow-sm hover:shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    {{ __('home.clear') }}

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
                                    <div
                                        class=" bg-white shadow-md hover:shadow-lg transition overflow-hidden flex flex-col">

                                        <!-- Skeleton Loader -->
                                        <div class="skeleton-loader absolute inset-0 bg-gray-200 animate-pulse z-10">
                                        </div>

                                        <!-- Book Image -->
                                        <div class="aspect-[2/3] overflow-hidden">
                                            <a href="{{ route('detail.view', $book->id) }}">
                                                <img loading="lazy" src="{{ asset($book->images) }}" alt="Book cover"
                                                    class="book-image w-full h-full transition-opacity duration-500 opacity-0">
                                            </a>
                                        </div>

                                        <!-- Content Section -->
                                        <div class="p-4 flex flex-col justify-between gap-3 flex-1">
                                            <!-- Title & Description -->
                                            <div>
                                                <h3
                                                    class="text-xl  text-gray-500 text-base leading-tight h-[3rem] overflow-hidden line-clamp-2"">
                                                    {{ $book->name }}
                                                </h3>
                                                <p class="text-sm text-gray-500 mt-1 line-clamp-2 hidden">
                                                    {{ $book->description }}
                                                </p>
                                            </div>

                                            <!-- Price & Button -->
                                            <div class="mt-2 flex flex-col gap-3">
                                                <div class="text-gray-800 font-semibold text-lg">
                                                    ¥{{ number_format($book->price) }}
                                                </div>

                                                <!-- Button: always visible on mobile, shows on hover on desktop -->
                                                <button type="button"
                                                    onclick="addToCart(this, {{ $book }}, 1)"
                                                    class="bg-yellow-400 hover:bg-yellow-500 text-black font-medium py-2 rounded transition shadow hover:shadow-md
                               w-full block opacity-100 opacity-0 mtransition-opacity">
                                                    <span class="button-text">{{ __('home.addToCart') }}</span>
                                                    <span class="loading hidden">{{ __('home.loading') }}</span>
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
                lazy: true,
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
                    clickable: true,
                },
                breakpoints: {
                    0: {
                        slidesPerView: 1,
                    },
                    640: {
                        slidesPerView: 3,
                    },
                    1024: {
                        slidesPerView: 5,
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
        function addToCartBulk(payload) {
            const isLoggedIn = @json(Auth::check());
            const isEmailVerified = isLoggedIn ? @json(Auth::check() && Auth::user()->hasVerifiedEmail()) : false;

            if (isLoggedIn) {
                if (isEmailVerified) {
                    fetch('/cart-bulk', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                items: JSON.parse(payload)
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // showToast(data.message);
                                // cartCountdisplay(data.cartCount)
                                // renderCartFromApi()
                                localStorage.removeItem("cart_items")
                                window.location.href = "/cart-web"
                            } else {
                                // showToast(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                } else {
                    showToast("'Please verify your email before adding items to the cart.");
                }
            }

        }
    </script>

    <script>
        cartCountdisplay("{{ isset($cartCount) ? $cartCount : 0 }}")
        loggedInDevicesCount({{ isset($loggedInDevices) ? $loggedInDevices : 0 }})
        const isLoggedIn = @json(Auth::check());

        // const localStorageCartItems = localStorage.getItem("cart_items");
        //if (localStorageCartItems && isLoggedIn) {
        //   addToCartBulk(localStorageCartItems)
        //}
        document.addEventListener("DOMContentLoaded", () => {
            if (!isLoggedIn) {
                renderGuestCart();
            }
        });
    </script>

</x-entry-layout>
