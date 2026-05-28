<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Rozliczenie Stolika</title>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-lg mx-auto bg-white p-8 rounded-xl shadow-md">
        <h1 class="text-2xl font-bold mb-6">Rozliczenie: {{ $order->table->number }}</h1>

        <div class="mb-6 border-b pb-4">
            <h2 class="text-lg font-semibold mb-3">Zamówione pozycje:</h2>
            <ul class="space-y-2 mb-6">
                {{-- Grupujemy pozycje po nazwie dania z relacji --}}
                @foreach($order->orderItems->groupBy('menuItem.name') as $name => $items)
                    @php
                        // Sumujemy ilość z połączonych rekordów
                        $totalQty = $items->sum('quantity');
                        // Pobieramy cenę jednostkową z pierwszego elementu grupy i mnożymy
                        $totalPrice = $totalQty * $items->first()->menuItem->price;
                    @endphp
                    <li class="flex justify-between text-gray-700 border-b border-gray-50 pb-1">
                        <span class="font-medium">{{ $totalQty }}x {{ $name }}</span>
                        <span class="font-semibold">{{ number_format($totalPrice, 2) }} zł</span>
                    </li>
                @endforeach
            </ul>
            <div class="flex justify-between items-center text-2xl font-bold text-blue-600 mt-4 pt-2 border-t border-dashed border-gray-300">
                <span>Do zapłaty:</span>
                <span>{{ number_format($total, 2) }} zł</span>
            </div>
        </div>

        <form action="{{ route('payment.store', $order) }}" method="POST">
            @csrf
            <input type="hidden" name="total_amount" value="{{ $total }}">

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Napiwek (zł):</label>
                <input type="number" name="tip" value="0" min="0" step="0.01" 
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500 bg-gray-50">
            </div>

            <div class="mb-8">
                <label class="block text-gray-700 font-bold mb-2">Metoda płatności:</label>
                <select name="payment_method" class="w-full border rounded px-3 py-2 bg-white focus:outline-none focus:border-blue-500">
                    <option value="cash">Gotówka</option>
                    <option value="card">Karta płatnicza</option>
                </select>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-green-500 text-white font-bold py-3 rounded-lg shadow-md hover:bg-green-600 hover:shadow-lg transition">
                    Zakończ i zwolnij stolik
                </button>
                <a href="{{ route('waiter.index') }}" class="flex-1 bg-gray-200 text-center py-3 rounded-lg text-gray-800 font-semibold hover:bg-gray-300 transition">
                    Wróć
                </a>
            </div>
        </form>
    </div>
</body>
</html>