<x-entry-layout>


 <div class="w-full hidden md:flex">
                    <div class="flex gap-6">
                        <select id="organization"
                            class="border-gray-200 hover:bg-gray-200   text-primary cursor-pointer rounded-full">
                            <option value="" class="bg-white text-black">Organization</option>
                            <option value="games" class="bg-white text-black">Senmonkyoiku publications</option>
                        </select>

                        <select id="category"
                            class="border-gray-200 hover:bg-gray-200  text-primary cursor-pointer rounded-full">
                            <option value="">Category</option>
                            <option value="games">Games</option>
                            <option value="apps">Apps</option>
                            <option value="books">Books</option>
                            <option value="kids">Kids</option>
                        </select>
                    </div>
                    
                </div>


  <div>
    <h2 class="text-2xl font-semibold text-gray-500">ベストセラー
    </h2>
    <p class="text-sm text-gray-500 mb-10">サブ情報</p>

    <div class="swiper mySwiper">
      <div class="swiper-wrapper">
        <!-- Slides -->
        <div class="swiper-slide">
          <div class="bg-white rounded-lg overflow-hidden hover:bg-gray-100 cursor-pointer">
            <img loading="lazy" src="{{asset("images/cover.png")  }}" alt="Book cover" class=" object-cover">
            <div class="flex flex-col gap-2 p-2">
              <h3 class="text-xl  text-gray-500 ">新しい録音技術 <br/> New Recording Technology</h3>
              <p class="text-sm text-gray-500">日本ビデオコミュニケーション協会 <br/> by Japan Video Communication Association</p>
              <div class="flex justify-between w-full">
                <a href="/reader" class="text-indigo-600  hover:underline">続きを読む <br/> Read More</a>
                <a href="" class="text-indigo-400  hover:underline">カートに追加 <br/> Add to cart</a>
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-slide">
          <div class="bg-white rounded-lg overflow-hidden hover:bg-gray-100 cursor-pointer">
            <img loading="lazy" src="{{asset("images/sample/5.jpg")  }}" alt="Book cover" class=" object-cover">
            <div class="flex flex-col gap-2 p-2">
              <h3 class="text-xl  text-gray-500 ">新しい録音技術 <br/> New Recording Technology</h3>
              <p class="text-sm text-gray-500">日本ビデオコミュニケーション協会 <br/> by Japan Video Communication Association</p>
              <div class="flex justify-between w-full">
                <a href="/reader" class="text-indigo-600  hover:underline">続きを読む <br/> Read More</a>
                <a href="" class="text-indigo-400  hover:underline">カートに追加 <br/> Add to cart</a>
              </div>
            </div>
          </div>
        </div>

        <div class="swiper-slide">
          <div class="bg-white rounded-lg overflow-hidden hover:bg-gray-100 cursor-pointer">
            <img loading="lazy" src="{{asset("images/sample/4.jpg")  }}" alt="Book cover" class=" object-cover">
            <div class="flex flex-col gap-2 p-2">
              <h3 class="text-xl  text-gray-500 ">新しい録音技術 <br/> New Recording Technology</h3>
              <p class="text-sm text-gray-500">日本ビデオコミュニケーション協会 <br/> by Japan Video Communication Association</p>
              <div class="flex justify-between w-full">
                <a href="/reader" class="text-indigo-600  hover:underline">続きを読む <br/> Read More</a>
                <a href="" class="text-indigo-400  hover:underline">カートに追加 <br/> Add to cart</a>
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-slide">
          <div class="bg-white rounded-lg overflow-hidden hover:bg-gray-100 cursor-pointer">
            <img loading="lazy" src="{{asset("images/sample/1.jpg")  }}" alt="Book cover" class=" object-cover">
            <div class="flex flex-col gap-2 p-2">
              <h3 class="text-xl  text-gray-500 ">新しい録音技術 <br/> New Recording Technology</h3>
              <p class="text-sm text-gray-500">日本ビデオコミュニケーション協会 <br/> by Japan Video Communication Association</p>
              <div class="flex justify-between w-full">
                <a href="/reader" class="text-indigo-600  hover:underline">続きを読む <br/> Read More</a>
                <a href="" class="text-indigo-400  hover:underline">カートに追加 <br/> Add to cart</a>
              </div>
            </div>
          </div>
        </div>
       <div class="swiper-slide">
          <div class="bg-white rounded-lg overflow-hidden hover:bg-gray-100 cursor-pointer">
            <img loading="lazy" src="{{asset("images/sample/2.jpg")  }}" alt="Book cover" class=" object-cover">
            <div class="flex flex-col gap-2 p-2">
              <h3 class="text-xl  text-gray-500 ">新しい録音技術 <br/> New Recording Technology</h3>
              <p class="text-sm text-gray-500">日本ビデオコミュニケーション協会 <br/> by Japan Video Communication Association</p>
              <div class="flex justify-between w-full">
                <a href="/reader" class="text-indigo-600  hover:underline">続きを読む <br/> Read More</a>
                <a href="" class="text-indigo-400  hover:underline">カートに追加 <br/> Add to cart</a>
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-slide">
          <div class="bg-white rounded-lg overflow-hidden hover:bg-gray-100 cursor-pointer">
            <img loading="lazy" src="{{asset("images/sample/3.jpg")  }}" alt="Book cover" class=" object-cover">
            <div class="flex flex-col gap-2 p-2">
              <h3 class="text-xl  text-gray-500 ">新しい録音技術 <br/> New Recording Technology</h3>
              <p class="text-sm text-gray-500">日本ビデオコミュニケーション協会 <br/> by Japan Video Communication Association</p>
              <div class="flex justify-between w-full">
                <a href="/reader" class="text-indigo-600  hover:underline">続きを読む <br/> Read More</a>
                <a href="" class="text-indigo-400  hover:underline">カートに追加 <br/> Add to cart</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
    </div>
  </div>


<div>
    <h2 class="text-2xl font-semibold text-gray-500">ベストセラー
    </h2>
    <p class="text-sm text-gray-500 mb-10">サブ情報</p>

    <div class="swiper mySwiper">
      <div class="swiper-wrapper">
        <!-- Slides -->
        <div class="swiper-slide">
          <div class="bg-white rounded-lg overflow-hidden hover:bg-gray-100 cursor-pointer">
            <img loading="lazy" src="{{asset("images/cover.png")  }}" alt="Book cover" class=" object-cover">
            <div class="flex flex-col gap-2 p-2">
              <h3 class="text-xl  text-gray-500 ">新しい録音技術 <br/> New Recording Technology</h3>
              <p class="text-sm text-gray-500">日本ビデオコミュニケーション協会 <br/> by Japan Video Communication Association</p>
              <div class="flex justify-between w-full">
                <a href="/reader" class="text-indigo-600  hover:underline">続きを読む <br/> Read More</a>
                <a href="" class="text-indigo-400  hover:underline">カートに追加 <br/> Add to cart</a>
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-slide">
          <div class="bg-white rounded-lg overflow-hidden hover:bg-gray-100 cursor-pointer">
            <img loading="lazy" src="{{asset("images/sample/5.jpg")  }}" alt="Book cover" class=" object-cover">
            <div class="flex flex-col gap-2 p-2">
              <h3 class="text-xl  text-gray-500 ">新しい録音技術 <br/> New Recording Technology</h3>
              <p class="text-sm text-gray-500">日本ビデオコミュニケーション協会 <br/> by Japan Video Communication Association</p>
              <div class="flex justify-between w-full">
                <a href="/reader" class="text-indigo-600  hover:underline">続きを読む <br/> Read More</a>
                <a href="" class="text-indigo-400  hover:underline">カートに追加 <br/> Add to cart</a>
              </div>
            </div>
          </div>
        </div>

        <div class="swiper-slide">
          <div class="bg-white rounded-lg overflow-hidden hover:bg-gray-100 cursor-pointer">
            <img loading="lazy" src="{{asset("images/sample/4.jpg")  }}" alt="Book cover" class=" object-cover">
            <div class="flex flex-col gap-2 p-2">
              <h3 class="text-xl  text-gray-500 ">新しい録音技術 <br/> New Recording Technology</h3>
              <p class="text-sm text-gray-500">日本ビデオコミュニケーション協会 <br/> by Japan Video Communication Association</p>
              <div class="flex justify-between w-full">
                <a href="/reader" class="text-indigo-600  hover:underline">続きを読む <br/> Read More</a>
                <a href="" class="text-indigo-400  hover:underline">カートに追加 <br/> Add to cart</a>
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-slide">
          <div class="bg-white rounded-lg overflow-hidden hover:bg-gray-100 cursor-pointer">
            <img loading="lazy" src="{{asset("images/sample/1.jpg")  }}" alt="Book cover" class=" object-cover">
            <div class="flex flex-col gap-2 p-2">
              <h3 class="text-xl  text-gray-500 ">新しい録音技術 <br/> New Recording Technology</h3>
              <p class="text-sm text-gray-500">日本ビデオコミュニケーション協会 <br/> by Japan Video Communication Association</p>
              <div class="flex justify-between w-full">
                <a href="/reader" class="text-indigo-600  hover:underline">続きを読む <br/> Read More</a>
                <a href="" class="text-indigo-400  hover:underline">カートに追加 <br/> Add to cart</a>
              </div>
            </div>
          </div>
        </div>
       <div class="swiper-slide">
          <div class="bg-white rounded-lg overflow-hidden hover:bg-gray-100 cursor-pointer">
            <img loading="lazy" src="{{asset("images/sample/2.jpg")  }}" alt="Book cover" class=" object-cover">
            <div class="flex flex-col gap-2 p-2">
              <h3 class="text-xl  text-gray-500 ">新しい録音技術 <br/> New Recording Technology</h3>
              <p class="text-sm text-gray-500">日本ビデオコミュニケーション協会 <br/> by Japan Video Communication Association</p>
              <div class="flex justify-between w-full">
                <a href="/reader" class="text-indigo-600  hover:underline">続きを読む <br/> Read More</a>
                <a href="" class="text-indigo-400  hover:underline">カートに追加 <br/> Add to cart</a>
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-slide">
          <div class="bg-white rounded-lg overflow-hidden hover:bg-gray-100 cursor-pointer">
            <img loading="lazy" src="{{asset("images/sample/3.jpg")  }}" alt="Book cover" class=" object-cover">
            <div class="flex flex-col gap-2 p-2">
              <h3 class="text-xl  text-gray-500 ">新しい録音技術 <br/> New Recording Technology</h3>
              <p class="text-sm text-gray-500">日本ビデオコミュニケーション協会 <br/> by Japan Video Communication Association</p>
              <div class="flex justify-between w-full">
                <a href="/reader" class="text-indigo-600  hover:underline">続きを読む <br/> Read More</a>
                <a href="" class="text-indigo-400  hover:underline">カートに追加 <br/> Add to cart</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
    </div>
  </div>


  <div>
    <h2 class="text-2xl font-semibold text-gray-500">ベストセラー
    </h2>
    <p class="text-sm text-gray-500 mb-10">サブ情報</p>

    <div class="swiper mySwiper">
      <div class="swiper-wrapper">
        <!-- Slides -->
        <div class="swiper-slide">
          <div class="bg-white rounded-lg overflow-hidden hover:bg-gray-100 cursor-pointer">
            <img loading="lazy" src="{{asset("images/cover.png")  }}" alt="Book cover" class=" object-cover">
            <div class="flex flex-col gap-2 p-2">
              <h3 class="text-xl  text-gray-500 ">新しい録音技術 <br/> New Recording Technology</h3>
              <p class="text-sm text-gray-500">日本ビデオコミュニケーション協会 <br/> by Japan Video Communication Association</p>
              <div class="flex justify-between w-full">
                <a href="/reader" class="text-indigo-600  hover:underline">続きを読む <br/> Read More</a>
                <a href="" class="text-indigo-400  hover:underline">カートに追加 <br/> Add to cart</a>
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-slide">
          <div class="bg-white rounded-lg overflow-hidden hover:bg-gray-100 cursor-pointer">
            <img loading="lazy" src="{{asset("images/sample/5.jpg")  }}" alt="Book cover" class=" object-cover">
            <div class="flex flex-col gap-2 p-2">
              <h3 class="text-xl  text-gray-500 ">新しい録音技術 <br/> New Recording Technology</h3>
              <p class="text-sm text-gray-500">日本ビデオコミュニケーション協会 <br/> by Japan Video Communication Association</p>
              <div class="flex justify-between w-full">
                <a href="/reader" class="text-indigo-600  hover:underline">続きを読む <br/> Read More</a>
                <a href="" class="text-indigo-400  hover:underline">カートに追加 <br/> Add to cart</a>
              </div>
            </div>
          </div>
        </div>

        <div class="swiper-slide">
          <div class="bg-white rounded-lg overflow-hidden hover:bg-gray-100 cursor-pointer">
            <img loading="lazy" src="{{asset("images/sample/4.jpg")  }}" alt="Book cover" class=" object-cover">
            <div class="flex flex-col gap-2 p-2">
              <h3 class="text-xl  text-gray-500 ">新しい録音技術 <br/> New Recording Technology</h3>
              <p class="text-sm text-gray-500">日本ビデオコミュニケーション協会 <br/> by Japan Video Communication Association</p>
              <div class="flex justify-between w-full">
                <a href="/reader" class="text-indigo-600  hover:underline">続きを読む <br/> Read More</a>
                <a href="" class="text-indigo-400  hover:underline">カートに追加 <br/> Add to cart</a>
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-slide">
          <div class="bg-white rounded-lg overflow-hidden hover:bg-gray-100 cursor-pointer">
            <img loading="lazy" src="{{asset("images/sample/1.jpg")  }}" alt="Book cover" class=" object-cover">
            <div class="flex flex-col gap-2 p-2">
              <h3 class="text-xl  text-gray-500 ">新しい録音技術 <br/> New Recording Technology</h3>
              <p class="text-sm text-gray-500">日本ビデオコミュニケーション協会 <br/> by Japan Video Communication Association</p>
              <div class="flex justify-between w-full">
                <a href="/reader" class="text-indigo-600  hover:underline">続きを読む <br/> Read More</a>
                <a href="" class="text-indigo-400  hover:underline">カートに追加 <br/> Add to cart</a>
              </div>
            </div>
          </div>
        </div>
       <div class="swiper-slide">
          <div class="bg-white rounded-lg overflow-hidden hover:bg-gray-100 cursor-pointer">
            <img loading="lazy" src="{{asset("images/sample/2.jpg")  }}" alt="Book cover" class=" object-cover">
            <div class="flex flex-col gap-2 p-2">
              <h3 class="text-xl  text-gray-500 ">新しい録音技術 <br/> New Recording Technology</h3>
              <p class="text-sm text-gray-500">日本ビデオコミュニケーション協会 <br/> by Japan Video Communication Association</p>
              <div class="flex justify-between w-full">
                <a href="/reader" class="text-indigo-600  hover:underline">続きを読む <br/> Read More</a>
                <a href="" class="text-indigo-400  hover:underline">カートに追加 <br/> Add to cart</a>
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-slide">
          <div class="bg-white rounded-lg overflow-hidden hover:bg-gray-100 cursor-pointer">
            <img loading="lazy" src="{{asset("images/sample/3.jpg")  }}" alt="Book cover" class=" object-cover">
            <div class="flex flex-col gap-2 p-2">
              <h3 class="text-xl  text-gray-500 ">新しい録音技術 <br/> New Recording Technology</h3>
              <p class="text-sm text-gray-500">日本ビデオコミュニケーション協会 <br/> by Japan Video Communication Association</p>
              <div class="flex justify-between w-full">
                <a href="/reader" class="text-indigo-600  hover:underline">続きを読む <br/> Read More</a>
                <a href="" class="text-indigo-400  hover:underline">カートに追加 <br/> Add to cart</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
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
    const img = document.getElementById('main-img');
    const loader = document.getElementById('img-loader');

    img.onload = () => {
        img.classList.remove('opacity-0');
        img.classList.add('opacity-100');
        loader.style.display = 'none';
    };
</script>

</x-entry-layout>