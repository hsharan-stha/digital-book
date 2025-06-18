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
                        <li class="flex justify-between items-center border-b pb-2">
                            <div class="w-1/3 text-left">
                                <span class="text-gray-900 dark:text-gray-100">{{ $purchase->purchase_date }}</span>
                            </div>

                            <div class="w-1/3 text-center">
                                <span class="text-gray-900 dark:text-gray-100">{{ $purchase->item_count }}</span>
                            </div>

                            <div class="w-1/3 text-center">
                                <span class="text-gray-900 dark:text-gray-100">{{ $purchase->total_amount }}</span>
                            </div>

                            <div class="w-1/3 text-right">
                                <span class="text-gray-900 dark:text-gray-100">{{ $purchase->is_paid }}</span>
                            </div>

                            <div class="w-1/3 text-right space-x-2 flex items-center justify-end">
                                <a href="{{ route('purchases.edit', $purchase) }}" class="text-indigo-600 hover:text-indigo-800" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.232 5.232l3.536 3.536M9 13l6.768-6.768a2 2 0 012.828 0l.172.172a2 2 0 010 2.828L12 17H9v-3z" />
                                    </svg>
                                </a>

                                <form action="{{ route('purchases.destroy', $purchase) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete it?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-7" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
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