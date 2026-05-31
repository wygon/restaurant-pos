<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Order;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index()
    {
        $orders = Order::whereHas('orderItems', function($query) {
            $query->whereIn('status', ['pending', 'cooking']);
        })->with(['table', 'orderItems.menuItem'])->get();

        return view('kitchen.index', compact('orders'));
    }

    public function changeStatus(Request $request, OrderItem $orderItem)
    {
        if ($orderItem->status === 'pending') {
            $request->validate([
                'quantity' => 'required|integer|min:1|max:' . $orderItem->quantity
            ]);

            $quantityToCook = $request->quantity;

            if ($quantityToCook == $orderItem->quantity) {
                $orderItem->update(['status' => 'cooking']);
            } else {
                $cookingItem = $orderItem->replicate();
                $cookingItem->quantity = $quantityToCook;
                $cookingItem->status = 'cooking';
                $cookingItem->save();

                $orderItem->update(['quantity' => $orderItem->quantity - $quantityToCook]);
            }
        } 
        elseif ($orderItem->status === 'cooking') {
            $request->validate([
                'quantity' => 'required|integer|min:1|max:' . $orderItem->quantity
            ]);

            $quantityToReady = $request->quantity;

            if ($quantityToReady == $orderItem->quantity) {
                $orderItem->update(['status' => 'ready']);
            } else {
                $readyItem = $orderItem->replicate();
                $readyItem->quantity = $quantityToReady;
                $readyItem->status = 'ready';
                $readyItem->save();

                $orderItem->update(['quantity' => $orderItem->quantity - $quantityToReady]);
            }
        }

        return back();
    }

    public function markAllReady(Order $order)
    {
        $order->orderItems()
              ->whereIn('status', ['pending', 'cooking'])
              ->update(['status' => 'ready']);

        return back()->with('success', 'All meals are ready.');
    }

}
