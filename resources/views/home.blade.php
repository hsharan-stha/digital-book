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
                    class="flex items-center gap-2 bg-gray-600 hover:bg-gray-900 text-white  font-medium px-4 py-2 rounded-full text-sm transition duration-200 ease-in-out shadow-sm hover:shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35M17.65 17.65A7.5 7.5 0 1 0 5.2 5.2a7.5 7.5 0 0 0 10.6 10.6z" />
                    </svg>
                    Search
                </button>

                <a href="{{ route('index') }}"
                    class="flex items-center gap-2 bg-red-500 hover:bg-red-800 text-white font-medium px-4 py-2 rounded-full text-sm transition duration-200 ease-in-out shadow-sm hover:shadow-md">
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
                                    <div
                                        class="bg-white rounded-lg overflow-hidden hover:bg-gray-100 cursor-pointer relative">


                                        <div class="skeleton-loader absolute inset-0 bg-gray-200 animate-pulse z-10">
                                        </div>

                                        <!-- Actual image -->
                                        <img loading="lazy" src="{{ asset($book->images) }}" alt="Book cover"
                                            class="book-image object-cover w-full h-64 transition-opacity duration-500 opacity-0">

                                        <div class="flex flex-col gap-2 p-2">
                                            <h3 class="text-xl  text-gray-500 ">{{ $book->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $book->description }}</p>
                                            <div class="flex items-center gap-4 mt-4">
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

                                                <!-- Add to Cart Button -->
                                                <button type="button" onclick="addToCart(this,{{ $book->id }}, 1)"
                                                    class="flex items-center gap-2 text-green-600 hover:text-green-800 font-medium transition duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="currentColor" class="w-5 h-5 cart-icon">
                                                        <path
                                                            d="M2.25 2.25a.75.75 0 0 0 0 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 0 0-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 0 0 0-1.5H5.378A2.25 2.25 0 0 1 7.5 15h11.218a.75.75 0 0 0 .674-.421 60.358 60.358 0 0 0 2.96-7.228.75.75 0 0 0-.525-.965A60.864 60.864 0 0 0 5.68 4.509l-.232-.867A1.875 1.875 0 0 0 3.636 2.25H2.25ZM3.75 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0ZM16.5 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z" />
                                                    </svg>
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

                    </div>
                </div>
            </div>
        @endif
    @empty
    @endforelse

    <!-- Info Modal -->
    <div id="infoModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96 text-center">
            <h2 id="informationTitle" class="text-xl font-semibold mb-4">Information</h2>
            <p id="infoModalMessage" class="text-gray-700">This is your message.</p>
            <div class="mt-6">
                <button onclick="closeInfoModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    OK
                </button>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="infoToast" class="fixed bottom-5 right-5 z-50 hidden">
        <div
            class="bg-white border-l-4 border-blue-600 text-gray-800 px-4 py-3 shadow-lg rounded-md flex items-start space-x-3 max-w-xs">
            <svg class="w-6 h-6 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 2a7 7 0 107 7H9V2z" />
                <path d="M13 13H7v2h6v-2z" />
            </svg>
            <div>
                <p id="toastMessage" class="text-sm font-medium">This is your message</p>
            </div>
        </div>
    </div>



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
        document.addEventListener("DOMContentLoaded", () => {
            const bookImages = document.querySelectorAll(".book-image");

            bookImages.forEach((img) => {
                img.addEventListener("load", () => {
                    const wrapper = img.closest(".swiper-slide");
                    const skeleton = wrapper.querySelector(".skeleton-loader");

                    if (skeleton) {
                        skeleton.remove(); // remove shimmer effect
                    }

                    img.classList.remove("opacity-0");
                    img.classList.add("opacity-100");
                });
            });
        });
    </script>

    <script>
        function addToCart(button, bookId, quantity = 1) {

            const isLoggedIn = "{{ Auth::check() }}";
            if (isLoggedIn) {

                const textSpan = button.querySelector('.button-text');
                const loadingSpan = button.querySelector('.loading');
                const icon = button.querySelector('.cart-icon');

                // Show loading state
                textSpan.classList.add('hidden');
                icon.classList.add('hidden');
                loadingSpan.classList.remove('hidden');
                fetch('/cart', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            book_id: bookId,
                            quantity: quantity
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message);
                            cartCountdisplay(data.cartCount)
                            // Optionally redirect
                            // window.location.href = data.redirect;

                        } else {
                            showToast(data.message);
                        }

                        textSpan.classList.remove('hidden');
                        icon.classList.remove('hidden');
                        loadingSpan.classList.add('hidden');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            } else {
                showToast("Please login before add to cart");
            }
        }

        function showInfoModal(message, title) {
            document.getElementById('informationTitle').innerText = title;
            document.getElementById('infoModalMessage').innerText = message;
            document.getElementById('infoModal').classList.remove('hidden');
        }

        function closeInfoModal() {
            document.getElementById('infoModal').classList.add('hidden');
        }
    </script>

    <script>
        function showToast(message, duration = 3000) {
            const toast = document.getElementById('infoToast');
            const toastMessage = document.getElementById('toastMessage');

            toastMessage.textContent = message;
            toast.classList.remove('hidden');

            setTimeout(() => {
                toast.classList.add('hidden');
            }, duration);
        }
    </script>

    <script>
        cartCountdisplay("{{ isset($cartCount) ? $cartCount : 0 }}")
    </script>

</x-entry-layout>
