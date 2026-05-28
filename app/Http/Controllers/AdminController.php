<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Table;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $categories = Category::when($search, function ($query) use ($search) {
            $query->whereHas('menuItems', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        })->with(['menuItems' => function ($q) use ($search) {
            if ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            }
        }])->get();
        
        return view('admin.index', compact('categories', 'search'));
    }

    //Categories
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name'
        ]);

        Category::create([
            'name' => $request->name
        ]);

        return back()->with('success', 'Category created.');
    }

    public function toggleCategoryStatus(\App\Models\Category $category)
    {
        $category->update([
            'is_active' => !$category->is_active
        ]);

        $status = $category->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Category successfully {$status}.");
    }

    //MenuItems
    public function createItem(Request $request)
    {
        $categories = Category::all();
        $selectedCategory = $request->query('category');
        
        return view('admin.create_item', compact('categories', 'selectedCategory'));
    }

    public function toggleItemStatus(MenuItem $menuItem)
    {
        $menuItem->update([
            'is_active' => !$menuItem->is_active
        ]);

        $status = $menuItem->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Item successfully {$status}.");
    }

    public function storeItem(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        MenuItem::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description
        ]);

        return redirect()->route('admin.index')->with('success', 'Menu item added.');
    }

    //Tables
    public function tables()
    {
        $tables = Table::orderBy('number')->get();
        return view('admin.tables', compact('tables'));
    }

    public function storeTable(Request $request)
    {
        $request->validate([
            'number' => 'required|string|max:255|unique:tables,number', 
            'capacity' => 'nullable|integer|min:1'
        ]);

        \App\Models\Table::create([
            'number' => $request->number,
            'capacity' => $request->capacity ?? 2,
            'status' => 'available'
        ]);

        return redirect()->route('admin.tables')->with('success', 'Table added successfully.');
    }

    
    public function createTable()
    {
        return view('admin.create_table');
    }

    public function editTable(Table $table)
    {
        return view('admin.edit_table', compact('table'));
    }

    public function updateTable(Request $request, Table $table)
    {
        $request->validate([
            // Ignoruj unikalność nazwy dla aktualnie edytowanego stolika
            'number' => 'required|string|unique:tables,number,' . $table->id,
            'capacity' => 'required|integer|min:1'
        ]);

        $table->update([
            'number' => $request->number,
            'capacity' => $request->capacity
        ]);

        return redirect()->route('admin.tables')->with('success', 'Table updated successfully.');
    }
}
