<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>New Order</title>
</head>
<body class="bg-gray-100 p-8">
    @include('partials.topbar')

    <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-md">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">New Order: Table {{ $table->number }}</h1>

        <form action="{{ route('waiter.storeOrder', $table) }}" method="POST">
            @csrf
            
            <div class="mb-8">
                {{-- Grupujemy pobrane elementy po nazwie kategorii z relacji --}}
                @foreach ($menuItems->groupBy('category.name') as $categoryName => $items)
                    
                    {{-- Nagłówek Kategorii --}}
                    <div class="mt-8 mb-4">
                        <h2 class="text-xl font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500 pb-1">
                            {{ $categoryName }}
                        </h2>
                    </div>

                    {{-- Lista dań w danej kategorii --}}
                    <div class="space-y-4 pl-2">
                        @foreach ($items as $menuItem)
                            <div class="flex justify-between items-center border-b border-gray-100 pb-2 hover:bg-gray-50 transition p-2 rounded">
                                <div>
                                    <p class="font-bold text-gray-800">{{ $menuItem->name }}</p>
                                    <p class="text-sm text-gray-500">{{ number_format($menuItem->price, 2) }} PLN</p>
                                </div>
                                <input type="number" name="items[{{ $menuItem->id }}]" value="0" min="0" 
                                       class="w-20 border-2 border-gray-200 rounded-lg px-2 py-1 text-center focus:border-blue-500 focus:outline-none transition">
                            </div>
                        @endforeach
                    </div>
                    
                @endforeach
            </div>

            <div class="flex gap-4 sticky bottom-0 bg-white pt-4 border-t mt-4">
                    <button type="submit" class="flex-1 bg-blue-600 text-white font-bold py-3 rounded-lg shadow-lg hover:bg-blue-700 hover:shadow-xl transition transform hover:-translate-y-0.5">
                    Send to kitchen
                </button>
                <a href="{{ route('waiter.index') }}" class="flex-1 bg-gray-200 text-center py-3 rounded-lg font-semibold text-gray-700 hover:bg-gray-300 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</body>
</html>