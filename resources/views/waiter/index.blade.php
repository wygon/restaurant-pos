<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Waiter Panel</title>
</head>
<body class="bg-gray-100 p-8">
    @include('partials.topbar')

    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">Tables</h1>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ($tables as $table)
                @if($table->status === 'available')
                    <a href="{{ route('waiter.createOrder', $table) }}" 
                       class="bg-green-500 hover:bg-green-600 text-white p-8 rounded-xl text-center transition">
                        <span class="block text-2xl font-bold">{{ $table->number }}</span>
                        <span class="text-sm">Free</span>
                    </a>
                @else
                    @php $activeOrder = $table->orders()->where('status', 'open')->first(); @endphp
                     <a href="{{ route('waiter.editOrder', $table) }}"
                       class="bg-red-500 hover:bg-red-600 text-white p-8 rounded-xl text-center shadow-lg transition">
                        <span class="block text-2xl font-bold">{{ $table->number }}</span>
                        <span class="text-sm">Occupied - Checkout</span>
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</body>
</html>