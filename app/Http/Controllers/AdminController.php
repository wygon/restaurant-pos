<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->filled('search') ? $request->input('search') : null;
        $minPrice = $request->filled('min_price') ? $request->input('min_price') : null;
        $maxPrice = $request->filled('max_price') ? $request->input('max_price') : null;
        
        $selectedCategories = $request->input('categories', []); 

        $allCategories = Category::all();

        $query = Category::query();

        if (!empty($selectedCategories)) {
            $query->whereIn('id', $selectedCategories);
        }

        if ($search || $minPrice !== null || $maxPrice !== null) {
            $query->whereHas('menuItems', function ($q) use ($search, $minPrice, $maxPrice) {
                if ($search) $q->where('name', 'like', '%' . $search . '%');
                if ($minPrice !== null) $q->where('price', '>=', $minPrice);
                if ($maxPrice !== null) $q->where('price', '<=', $maxPrice);
            })->with(['menuItems' => function ($q) use ($search, $minPrice, $maxPrice) {
                if ($search) $q->where('name', 'like', '%' . $search . '%');
                if ($minPrice !== null) $q->where('price', '>=', $minPrice);
                if ($maxPrice !== null) $q->where('price', '<=', $maxPrice);
            }]);
        } else {
            $query->with('menuItems');
        }

        $categories = $query->get();
        
        return view('admin.index', compact('categories', 'allCategories', 'search', 'minPrice', 'maxPrice', 'selectedCategories'));
    }

    //Categories
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|min:5'
        ]);

        $existingCategory = Category::withTrashed()->where('name', $request->name)->first();

        if ($existingCategory) {
            if ($existingCategory->trashed()) {
                $existingCategory->restore();
                $existingCategory->update(['is_active' => true]);
                
                return back()->with('success', 'Category restored from archive.');
            }
            
            return back()->withErrors(['name' => 'The category name has already been taken.']);
        }

        Category::create([
            'name' => $request->name
        ]);

        return back()->with('success', 'Category created successfully.');
    }

    public function toggleCategoryStatus(Category $category)
    {
        $category->update([
            'is_active' => !$category->is_active
        ]);

        $status = $category->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Category successfully {$status}.");
    }

    public function destroyCategory(Category $category)
    {
        $defaultCategory = Category::firstOrCreate(
            ['name' => 'Not signed'],
            ['is_active' => false]
        );

        if ($category->id === $defaultCategory->id) {
            return back()->with('error', 'You cannot delete the default "Not signed" category.');
        }

        MenuItem::where('category_id', $category->id)
            ->update(['category_id' => $defaultCategory->id]);

        $category->delete();

        return redirect()->route('admin.index')->with('success', 'Category deleted and its items moved to "Not signed".');
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

    public function editItem(MenuItem $menuItem)
    {
        $categories = Category::all();
        return view('admin.edit_item', compact('menuItem', 'categories'));
    }

    public function updateItem(Request $request, MenuItem $menuItem)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        $menuItem->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.index')->with('success', 'Menu item updated successfully.');
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
            'number' => 'required|string|unique:tables,number,' . $table->id,
            'capacity' => 'required|integer|min:1'
        ]);

        $table->update([
            'number' => $request->number,
            'capacity' => $request->capacity
        ]);

        return redirect()->route('admin.tables')->with('success', 'Table updated successfully.');
    }

    //Users
    public function users()
    {
        $users = User::orderBy('role')->orderBy('name')->get();
        return view('admin.users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:admin,waiter,cook',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return back()->with('success', 'User account created successfully.');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }
        
        $user->delete();
        
        return back()->with('success', 'User deleted successfully.');
    }
}
