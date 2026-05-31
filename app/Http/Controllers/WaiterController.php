<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use Illuminate\Http\Request;

class WaiterController extends Controller
{
    // public function index()
    // {
    //     $tables = Table::with(['orders' => function ($query) {
    //         $query->where('status', 'open')->with('orderItems');
    //     }])
    //     ->orderBy('status', 'desc')
    //     ->orderBy('number', 'asc')
    //     ->get();

    //     return view('waiter.index', compact('tables'));
    // }
    public function index()
    {
        $tables = Table::with(['orders' => function ($query) {
            $query->where('status', 'open')->with('orderItems');
        }])
        ->orderBy('status', 'desc')
        ->orderBy('number', 'asc')
        ->get()
        ->map(function ($table) {
            if ($table->status !== 'available') {
                $activeOrder = $table->orders->first();
                
                $table->totalItems = $activeOrder ? $activeOrder->orderItems->sum('quantity') : 0;
                $table->readyItems = $activeOrder ? $activeOrder->orderItems->where('status', 'ready')->sum('quantity') : 0;
                
                $allReady = ($table->totalItems > 0 && $table->readyItems === $table->totalItems);
                
                $table->bgColor = $allReady ? 'bg-orange-500 hover:bg-orange-600' : 'bg-red-500 hover:bg-red-600';
            }
            
            return $table;
        });

        return view('waiter.index', compact('tables'));
    }

    public function createOrder(Table $table)
    {
        $menuItems = MenuItem::with('category')
            ->where('is_active', true)
            ->whereHas('category', function($q) {
                $q->where('is_active', true)
                  ->where('name', '!=', 'Not signed');
            })
            ->get();
        return view('waiter.create_order', compact('table', 'menuItems'));
    }

    public function editOrder(Table $table)
    {
        $order = $table->orders()->where('status', 'open')->firstOrFail();
        $menuItems = MenuItem::with('category')
            ->where('is_active', true)
            ->whereHas('category', function($q) {
                $q->where('is_active', true)
                  ->where('name', '!=', 'Not signed');
            })
            ->get();

        return view('waiter.edit_order', compact('table', 'order', 'menuItems'));
    }

    public function updateOrder(Request $request, Table $table)
    {
        $order = $table->orders()->where('status', 'open')->firstOrFail();
        
        $items = array_filter($request->items ?? [], fn($q) => $q > 0);

        if (empty($items)) {
            return back()->with('error', 'No new items selected.');
        }

        foreach ($items as $itemId => $quantity) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_item_id' => $itemId,
                'quantity' => $quantity,
                'status' => 'pending'
            ]);
        }

        return back()->with('success', 'Items added to order.');
    }

    public function removeItem(Request $request, OrderItem $orderItem)
    {
        if ($orderItem->status !== 'pending') {
            return back()->with('error', 'Cannot remove items that are already cooking or ready!');
        }

        $request->validate([
            'remove_quantity' => 'required|integer|min:1|max:' . $orderItem->quantity
        ]);

        $quantityToRemove = $request->remove_quantity;

        if ($quantityToRemove == $orderItem->quantity) {
            $orderItem->delete();
            return back()->with('success', 'Item completely removed from the order.');
        } 
        else {
            $orderItem->update(['quantity' => $orderItem->quantity - $quantityToRemove]);
            return back()->with('success', "Removed {$quantityToRemove}x from the order.");
        }
    }

    public function storeOrder(Request $request, Table $table)
    {
        $items = array_filter($request->items, fn($q) => $q > 0);
        
        if (empty($items)) {
            return back()->with('error', 'Please select at least one item!');
        }

        $order = Order::create([
            'table_id' => $table->id,
            'status' => 'open'
        ]);

        foreach ($items as $itemId => $quantity) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_item_id' => $itemId,
                'quantity' => $quantity,
                'status' => 'pending'
            ]);
        }

        $table->update(['status' => 'occupied']);

        return redirect()->route('waiter.index')->with('success', 'Order created!');
    }
}
