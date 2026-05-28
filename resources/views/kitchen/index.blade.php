<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Kitchen view</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    @include('partials.topbar')

    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Kitchen View</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($orders as $order)
                <div class="bg-white p-6 rounded-lg shadow-md border-t-8 border-blue-500">
                    <h2 class="text-xl font-bold mb-4 border-b pb-2">Table: {{ $order->table->number }}</h2>
                    
                    <div class="space-y-4">
                        @foreach ($order->orderItems->whereIn('status', ['pending', 'cooking']) as $item)
                            <div class="p-3 border rounded {{ $item->status === 'pending' ? 'bg-red-50' : 'bg-yellow-50' }}">
                                <div class="flex justify-between font-semibold">
                                    <span>{{ $item->menuItem->name }}</span>
                                    <span>x{{ $item->quantity }}</span>
                                </div>
                                <form action="{{ route('kitchen.changeStatus', $item) }}" method="POST" class="mt-2">
                                    @csrf
                                    
                                    @if ($item->status === 'pending')
                                        <div class="flex gap-2">
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->quantity }}" 
                                                   class="w-16 border rounded text-center font-bold focus:border-red-500 focus:outline-none">
                                            
                                            <button type="submit" class="flex-1 bg-red-500 text-white text-sm font-bold py-1 px-2 rounded hover:bg-red-600 transition">
                                                Start cooking
                                            </button>
                                        </div>
                                        
                                    @elseif ($item->status === 'cooking')
                                        <div class="flex gap-2">
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->quantity }}" 
                                                   class="w-16 border rounded text-center font-bold focus:border-yellow-500 focus:outline-none">
                                            
                                            <button type="submit" class="flex-1 bg-yellow-500 text-white text-sm font-bold py-1 px-2 rounded hover:bg-yellow-600 transition">
                                                Mark as Ready
                                            </button>
                                        </div>
                                    @endif
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-8 text-gray-500">
                    No orders. Kitchen is idle.
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>