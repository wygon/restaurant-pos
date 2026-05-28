<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function create(Order $order)
    {
        $total = $order->orderItems->sum(function($item) {
            return $item->quantity * $item->menuItem->price;
        });

        return view('payment.create', compact('order', 'total'));
    }

    public function store(Request $request, Order $order)
    {
        Payment::create([
            'order_id' => $order->id,
            'amount' => $request->total_amount,
            'tip' => $request->tip ?? 0,
            'payment_method' => $request->payment_method
        ]);

        $order->update(['status' => 'closed', 'total_amount' => $request->total_amount]);
        $order->table->update(['status' => 'available']);

        return redirect()->route('waiter.index')->with('success', 'Rachunek zamknięty!');
    }
}
