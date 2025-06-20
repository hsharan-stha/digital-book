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
                <form action="{{ route('purchase.list') }}" method="GET"
                    class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6 space-y-4">

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <input type="text" name="purchase_id" value="{{ request('purchase_id') }}"
                            placeholder="Search by Purchase ID"
                            class="px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-gray-100 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <input type="text" name="name" value="{{ request('name') }}"
                            placeholder="Search by User Name"
                            class="px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-gray-100 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" />


                        <input type="text" name="email" value="{{ request('email') }}"
                            placeholder="Search by Email"
                            class="px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-gray-100 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" />


                        <input type="date" name="created_date" value="{{ request('created_date') }}"
                            placeholder="Search by Created Date"
                            class="px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-gray-100 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <div class="flex space-x-4">
                        <button type="submit" class="px-4 py-2 bg-blue-600 0 text-white  rounded hover:bg-blue-700">
                            Search
                        </button>
                        <a href="{{ route('purchase.list') }}"
                            class="px-4 py-2 bg-gray-400  text-white  rounded hover:bg-gray-500 ">
                            Clear
                        </a>
                    </div>
                </form>

                <ul class="space-y-3">
                    @forelse($purchases as $purchase)
                        <li x-data="{
                            original: {{ $purchase->is_paid ? 'true' : 'false' }},
                            current: {{ $purchase->is_paid ? 'true' : 'false' }},
                            expanded: false
                        }" class="border-b pb-2">
                            {{-- Main row --}}
                            <div class="flex justify-between items-center">
                                <div class="w-1/4 text-left">
                                    <span
                                        class="text-gray-900 dark:text-gray-100">{{ $purchase->purchase_date }}</span>
                                </div>
                                <div class="w-1/4 text-center">
                                    <span class="text-gray-900 dark:text-gray-100">{{ $purchase->item_count }}</span>
                                </div>
                                <div class="w-1/4 text-center">
                                    <span
                                        class="text-gray-900 dark:text-gray-100">{{ $purchase->user->name ?? '-' }}</span>
                                </div>
                                <div class="w-1/4 text-center">
                                    <span
                                        class="text-gray-900 dark:text-gray-100">{{ $purchase->user->email ?? '-' }}</span>
                                </div>
                                <div class="w-1/4 text-center">
                                    <span
                                        class="text-gray-900 dark:text-gray-100 font-semibold bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                        ¥{{ number_format($purchase->total_amount) }}
                                    </span>
                                </div>
                                <div class="w-1/4 text-center">
                                    <span class="text-gray-900 dark:text-gray-100">{{ $purchase->created_at }}</span>
                                </div>

                                {{-- Toggle + Save --}}
                                <div
                                    class="text-gray-900 dark:text-gray-100 w-1/4 flex items-center justify-end space-x-2">
                                    {{-- Expand/collapse button --}}
                                    <button @click="expanded = !expanded" class="text-sm ">
                                        <span x-text="expanded ? '▲ Hide' : '▼ Show'"></span>
                                    </button>

                                    {{-- Paid Toggle with Status Label --}}
                                    <div class="flex items-center space-x-3">
                                        {{-- Toggle switch --}}
                                        <label class="inline-flex relative items-center cursor-pointer">
                                            <input type="checkbox" x-model="current" class="sr-only peer">
                                            <div
                                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500
            dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-600 peer-checked:bg-blue-600
            after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300
            after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full 
            peer-checked:after:border-white relative">
                                            </div>
                                        </label>

                                        {{-- Status Label --}}
                                        <span class="text-sm font-semibold"
                                            :class="current ? 'text-green-600' : 'text-red-500'"
                                            x-text="current ? 'Paid' : 'Unpaid'">
                                        </span>
                                    </div>

                                    {{-- Save Button (visible only if changed) --}}
                                    <form action="{{ route('purchase.update') }}" method="POST"
                                        x-show="original !== current" class="mt-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="is_paid" :value="current ? 1 : 0">
                                        <input type="hidden" name="id" value="{{ $purchase->id }}">
                                        
                                        @foreach (request()->query() as $key => $value)
                                            <input type="hidden" name="{{ $key }}"
                                                value="{{ $value }}">
                                        @endforeach

                                        <button type="submit"
                                            class="px-3 py-1 bg-green-600  rounded hover:bg-green-700 text-sm">
                                            Save
                                        </button>
                                    </form>

                                </div>
                            </div>

                            {{-- Expandable Row --}}
                            <div x-show="expanded"
                                class="mt-3 p-4 bg-gray-50 dark:bg-gray-700 rounded text-sm text-gray-800 dark:text-gray-200">

                                <p><strong>Purchase ID:</strong> {{ $purchase->purchase_date }}</p>
                                <div
                                    class="bg-gray-50 dark:bg-gray-800 p-4 rounded-md shadow-sm border border-gray-200 dark:border-gray-700">
                                    <h3 class="text-base font-semibold text-gray-700 dark:text-gray-100 mb-2">📚
                                        Purchase Details</h3>

                                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($purchase->details as $detail)
                                            <li class="py-2 flex justify-between items-center">
                                                <span class="text-sm text-gray-800 dark:text-gray-200">
                                                    {{ $detail->book->name ?? '📕 Unknown Book' }}
                                                </span>


                                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    ¥{{ number_format($detail->price) }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <div
                                        class="border-t border-gray-200 dark:border-gray-700 mt-4 pt-3 flex justify-between items-center">
                                        <span
                                            class="text-sm font-semibold text-gray-600 dark:text-gray-300">Total</span>
                                        <span class="text-base font-bold text-blue-600 dark:text-blue-400">
                                            ¥{{ number_format($purchase->total_amount) }}
                                        </span>
                                    </div>
                                </div>


                            </div>
                        </li>
                    @empty
                        <li class="text-gray-500">No any purchase</li>
                    @endforelse
                </ul>
                <div class="mt-6 flex justify-center rounded px-4 py-2">
                    {{ $purchases->onEachSide(1)->links('vendor.pagination.tailwind') }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
