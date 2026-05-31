@extends('layouts.app')
@section('content')
    <div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-6">Checkout: {{ $order->table->number }}</h1>

        <div class="mb-6 border-b pb-4">
            <h2 class="text-lg font-semibold mb-3">Ordered items:</h2>
            <ul class="space-y-2 mb-4">
                @foreach($order->orderItems->groupBy('menuItem.name') as $name => $items)
                    @php
                        $totalQty = $items->sum('quantity');
                        $totalPrice = $totalQty * $items->first()->menuItem->price;
                    @endphp
                    <li class="flex justify-between text-gray-800 border-b border-gray-100 pb-2">
                        <span>{{ $totalQty }}x {{ $name }}</span>
                        <span class="font-semibold">{{ number_format($totalPrice, 2) }} PLN</span>
                    </li>
                @endforeach
            </ul>
            
            <div class="flex justify-between items-center text-xl font-bold mt-4 pt-4 border-t border-gray-200">
                <span>Total:</span>
                <span>{{ number_format($total, 2) }} PLN</span>
            </div>
        </div>

        <form action="{{ route('payment.store', $order) }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="total_amount" value="{{ $total }}" id="totalAmount">

            <div>
                <label class="block font-bold mb-1">Tip (PLN)</label>
                
                <div class="flex flex-wrap gap-2 mb-2">
                    <button type="button" onclick="setTipRound(10)" class="text-xs bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-1 px-3 rounded transition">
                        Round to 10
                    </button>
                    <button type="button" onclick="setTipRound(50)" class="text-xs bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-1 px-3 rounded transition">
                        Round to 50
                    </button>
                    <button type="button" onclick="setTipPercent(10)" class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 font-semibold py-1 px-3 rounded transition">
                        10%
                    </button>
                    <button type="button" onclick="setTipPercent(15)" class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 font-semibold py-1 px-3 rounded transition">
                        15%
                    </button>
                    <button type="button" onclick="setTipPercent(20)" class="text-xs bg-green-200 hover:bg-green-300 text-green-700 font-semibold py-1 px-3 rounded transition">
                        20%
                    </button>
                    <button type="button" onclick="clearTip()" class="text-xs bg-red-100 hover:bg-red-200 text-red-700 font-semibold py-1 px-3 rounded transition ml-auto">
                        Clear
                    </button>
                </div>

                <input type="number" id="tipInput" name="tip" value="0" min="0" step="0.01" 
                       class="w-full border p-2 rounded bg-gray-50">
            </div>

            <div>
                <label class="block font-bold mb-1">Payment Method</label>
                <select name="payment_method" class="w-full border p-2 rounded bg-gray-50">
                    <option value="cash">Cash</option>
                    <option value="card">Card</option>
                </select>
            </div>

            <div class="flex gap-2 pt-4">
                <x-btn color="green" class="flex-1">Complete & Free Table</x-btn>
                <a href="{{ route('waiter.index') }}" class="flex-1 bg-gray-300 text-gray-800 text-center font-bold py-2 rounded hover:bg-gray-400 transition">
                    Back
                </a>
            </div>
        </form>
    </div>

    <script>
        const totalAmount = {{ $total }};
        const tipInput = document.getElementById('tipInput');

        function setTipRound(multiple) {
            let target = Math.ceil(totalAmount / multiple) * multiple;
            if (target === totalAmount) {
                target += multiple; 
            }
            
            tipInput.value = (target - totalAmount).toFixed(2);
        }

        function setTipPercent(percent) {
            let tip = totalAmount * (percent / 100);
            tipInput.value = tip.toFixed(2);
        }

        function clearTip() {
            tipInput.value = "0.00";
        }
    </script>
@endsection