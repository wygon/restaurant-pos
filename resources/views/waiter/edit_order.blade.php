<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Manage Table: {{ $table->number }}</title>
</head>
<body class="bg-gray-100 p-8">
    @include('partials.topbar')

    <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-red-500 flex flex-col">
            <div class="flex justify-between items-center mb-6 border-b pb-2">
                <h1 class="text-2xl font-bold">Order: {{ $table->number }}</h1>
            </div>

            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-2 rounded mb-4 text-sm">{{ session('success') }}</div>
            @endif

            <div class="flex-grow">
                <h2 class="font-bold text-gray-700 mb-4">Ordered items:</h2>
                <ul class="space-y-3">
                    @forelse($order->orderItems as $item)
                        <li class="flex justify-between items-center bg-gray-50 p-2 rounded border">
                            <div>
                                <span class="font-bold">{{ $item->quantity }}x</span> {{ $item->menuItem->name }}
                                <div class="text-xs text-gray-500">
                                    Status: 
                                    @if($item->status == 'pending') <span class="text-red-500 font-bold">In kitchen (Waiting)</span>
                                    @elseif($item->status == 'cooking') <span class="text-yellow-600 font-bold">Cooking</span>
                                    @else <span class="text-green-500 font-bold">Ready</span> @endif
                                </div>
                            </div>
                            @if($item->status == 'pending')
                                <form action="{{ route('waiter.removeItem', $item) }}" method="POST" class="flex gap-2">
                                    @csrf
                                    @method('DELETE')
                                    <input type="number" name="remove_quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->quantity }}" 
                                           class="w-16 border rounded text-center text-sm focus:border-red-500 focus:outline-none">
                                    <button type="submit" class="text-red-500 bg-red-50 hover:bg-red-100 px-3 py-1 rounded transition text-sm font-semibold">
                                        Delete
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-gray-400 italic">Locked</span>
                            @endif
                            <!-- <form action="{{ route('waiter.removeItem', $item) }}" method="POST" onsubmit="return confirm('Delete this item?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:bg-red-100 p-2 rounded transition">
                                    Delete
                                </button>
                            </form> -->
                        </li>
                    @empty
                        <li class="text-gray-500">No items on bill.</li>
                    @endforelse
                </ul>
            </div>

            <div class="mt-8 border-t pt-4">
                <a href="{{ route('payment.create', $order) }}" class="block w-full text-center bg-green-500 text-white font-bold py-3 rounded-lg hover:bg-green-600 transition shadow-lg text-lg">
                    Go to payment / Checkout
                </a>
            </div>
            <div class="mt-4">
                <a href="{{ route('waiter.index') }}" class="block w-full text-center bg-gray-200 text-gray-800 font-bold py-2 rounded-lg hover:bg-gray-300 transition">
                    Back to tables
                </a>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-blue-500 h-[80vh] overflow-y-auto">
            <h2 class="text-xl font-bold mb-4 border-b pb-2">Add to order</h2>

            <form action="{{ route('waiter.updateOrder', $table) }}" method="POST">
                @csrf
                
                <div class="mb-8">
                    @foreach ($menuItems->groupBy('category.name') as $categoryName => $items)
                        <div class="mt-6 mb-2">
                            <h3 class="text-lg font-bold text-gray-700 bg-gray-100 px-2 py-1 rounded">{{ $categoryName }}</h3>
                        </div>

                        <div class="space-y-2 pl-2">
                            @foreach ($items as $menuItem)
                                <div class="flex justify-between items-center border-b border-gray-100 pb-2 hover:bg-gray-50 p-2 rounded">
                                    <div>
                                        <p class="font-bold text-sm text-gray-800">{{ $menuItem->name }}</p>
                                        <p class="text-xs text-gray-500">{{ number_format($menuItem->price, 2) }} PLN</p>
                                    </div>
                                    <input type="number" name="items[{{ $menuItem->id }}]" value="0" min="0" 
                                           class="w-16 border-2 border-gray-200 rounded px-1 py-1 text-center text-sm focus:border-blue-500 focus:outline-none">
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <div class="sticky bottom-0 bg-white pt-4 border-t mt-4">
                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg shadow hover:bg-blue-700 transition">
                        Send new items to kitchen
                    </button>
                </div>
            </form>
        </div>

    </div>
</body>
</html>