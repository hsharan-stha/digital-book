<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Purchases') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 text-green-600 font-medium">
                    {{ session('success') }}
                </div>
            @endif            

            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg p-6">
                <ul class="space-y-3">
                    @forelse($purchases as $purchase)
                        <li class="flex justify-between items-center border-b pb-2" x-data="{ 
                            original: {{ $purchase->is_paid ? 'true' : 'false' }},
                            current: {{ $purchase->is_paid ? 'true' : 'false' }}
                        }">
                            {{-- 1. Sana --}}
                            <div class="w-1/4 text-left">
                                <span class="text-gray-900 dark:text-gray-100">{{ $purchase->purchase_date }}</span>
                            </div>

                            {{-- 2. Item soni --}}
                            <div class="w-1/4 text-center">
                                <span class="text-gray-900 dark:text-gray-100">{{ $purchase->item_count }}</span>
                            </div>

                            {{-- 3. Umumiy narx --}}
                            <div class="w-1/4 text-center">
                                <span class="text-gray-900 dark:text-gray-100">{{ $purchase->total_amount }}</span>
                            </div>

                            {{-- 4. is_paid toggle va save button --}}
                            <div class="w-1/4 flex items-center justify-end space-x-2">
                                {{-- Toggle --}}
                                <label class="inline-flex relative items-center cursor-pointer">
                                    <input type="checkbox" x-model="current" class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500
                                        dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-600 peer-checked:bg-blue-600
                                        after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300
                                        after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full 
                                        peer-checked:after:border-white relative"></div>
                                </label>

                                {{-- Save button (ko‘rinishi faqat o‘zgarish bo‘lsa) --}}
                                <form action="{{ route('purchase.update') }}" method="POST" x-show="original !== current">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="is_paid" :value="current ? 1 : 0">
                                    <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                                    <button type="submit"
                                            class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                                        Save
                                    </button>
                                </form>
                            </div>
                        </li>
                    @empty
                        <li class="text-gray-500">No any purchase</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>