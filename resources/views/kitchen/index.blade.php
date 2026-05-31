@extends('layouts.app')
@section('content')
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Kitchen View</h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 mb-6 rounded font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($orders as $order)
                <div class="bg-white p-6 rounded-lg shadow-md border-t-8 border-blue-500">
                    
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h2 class="text-xl font-bold">Table: {{ $order->table->number }}</h2>
                        
                        <form action="{{ route('kitchen.markAllReady', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <x-btn-outline color="green">Mark All Ready</x-btn-outline>
                        </form>
                    </div>
                    
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
                                            
                                            <x-btn-outline color="red" class="flex-1">Start cooking</x-btn-outline>
                                        </div>
                                        
                                    @elseif ($item->status === 'cooking')
                                        <div class="flex gap-2">
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->quantity }}" 
                                                   class="w-16 border rounded text-center font-bold focus:border-yellow-500 focus:outline-none">
                                            <x-btn-outline color="green" class="flex-1">Mark as Ready</x-btn-outline>
                                        </div>
                                    @endif
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-8 text-gray-500 font-semibold">
                    No orders. Kitchen is idle.
                </div>
            @endforelse
        </div>
    </div>
@endsection