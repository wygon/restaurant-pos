@extends('layouts.app')
@section('content')
    <div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Menu Management</h1>
            <div class="flex gap-2">
                <a href="{{ route('admin.createItem') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Add Menu Item</a>
                <a href="{{ route('admin.tables') }}" class="bg-gray-600 text-white px-4 py-2 rounded">Manage Tables</a>
                <a href="{{ route('admin.users') }}" class="bg-gray-600 text-white px-4 py-2 rounded">Manage Users</a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 mb-6 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-3 mb-6 rounded">
                <ul class="list-disc pl-5 font-semibold">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="p-4 border rounded bg-gray-50 md:col-span-1">
                <h2 class="font-bold mb-2">Add Category</h2>
                <form action="{{ route('admin.storeCategory') }}" method="POST" class="flex flex-col gap-2">
                    @csrf
                    <input type="text" name="name" placeholder="Category name..." required class="border p-2 rounded w-full bg-white">
                     <x-btn>Add</x-btn>
                </form>
            </div>

            <div class="p-4 border rounded bg-gray-50 md:col-span-2">
                <h2 class="font-bold mb-2">Search & Filter</h2>
                <form action="{{ route('admin.index') }}" method="GET" class="flex flex-col gap-3">
                    <div class="flex gap-2">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Search by name..." class="border p-2 rounded flex-1 bg-white">
                        <input type="number" name="min_price" value="{{ $minPrice }}" placeholder="Min PLN" min="0" step="0.01" class="border p-2 rounded w-24 bg-white">
                        <input type="number" name="max_price" value="{{ $maxPrice }}" placeholder="Max PLN" min="0" step="0.01" class="border p-2 rounded w-24 bg-white">
                    </div>
                    
                    <div class="border-t pt-2">
                        <span class="text-sm font-bold text-gray-700 block mb-1">Filter by Categories:</span>
                        <div class="flex flex-wrap gap-3">
                            @foreach($allCategories as $cat)
                                <label class="flex items-center gap-1 text-sm cursor-pointer">
                                    <input type="checkbox" name="categories[]" value="{{ $cat->id }}" class="rounded text-blue-600"
                                           {{ in_array($cat->id, $selectedCategories) ? 'checked' : '' }}>
                                    {{ $cat->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex gap-2 mt-1">
                        <x-btn color="gray" class="w-full">Apply Filters</x-btn> 
                        @if($search || $minPrice !== null || $maxPrice !== null || !empty($selectedCategories))
                            <a href="{{ route('admin.index') }}" class="bg-gray-300 text-black px-4 py-2 rounded text-center">Clear Filters</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div>
            <h2 class="text-xl font-bold mb-4">Current Menu</h2>
            
            @forelse($categories as $category)
                <div class="mb-6 p-4 border rounded {{ !$category->is_active ? 'bg-gray-100 opacity-75' : 'bg-white' }}">
                    <div class="flex items-center gap-3 mb-4 border-b pb-2">
                        <h3 class="font-bold text-lg {{ !$category->is_active ? 'line-through text-gray-500' : 'text-gray-800' }}">
                            {{ $category->name }}
                        </h3>
                        
                        <form action="{{ route('admin.toggleCategory', $category) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <x-btn-outline type="submit" :color="$category->is_active ? 'red' : 'green'">
                                {{ $category->is_active ? 'Deactivate Category' : 'Activate Category' }}
                            </x-btn-outline>
                        </form>

                        <form action="{{ route('admin.destroyCategory', $category) }}" method="POST" class="inline" onsubmit="return confirm('Delete this category? All items will be moved to Not signed.');">
                            @csrf
                            @method('DELETE')
                            <x-btn-outline color="gray">Delete Category</x-btn-outline>
                        </form>

                        @if($category->is_active)
                            <x-btn-outline href="{{ route('admin.createItem', ['category' => $category->id]) }}">+ Add item</x-btn-outline>
                        @endif
                    </div>
                    
                    <ul class="space-y-1">
                        @forelse($category->menuItems as $item)
                            <li class="flex justify-between items-center p-2 border-b border-gray-50 {{ !$item->is_active ? 'text-gray-400' : '' }}">
                                <div>
                                    <span class="{{ !$item->is_active ? 'line-through' : '' }} font-medium">{{ $item->name }}</span>
                                    <span class="ml-2">— {{ number_format($item->price, 2) }} PLN</span>
                                </div>
                                
                                <div class="flex gap-2">
                                    <x-btn-outline href="{{ route('admin.editItem', $item) }}" >Edit</x-btn-outline>
                                    
                                    <form action="{{ route('admin.toggleItem', $item) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <x-btn-outline class="{{ $item->is_active ? 'border-red-300 text-red-600' : 'border-green-300 text-green-600' }}">{{ $item->is_active ? 'Deactivate' : 'Activate' }} </x-btn-outline>
                                    </form>
                                </div>
                            </li>
                        @empty
                            <li class="p-2 text-gray-500 italic text-sm">No items found matching criteria.</li>
                        @endforelse
                    </ul>
                </div>
            @empty
                <p class="text-gray-500">No categories or items found.</p>
            @endforelse
        </div>
    </div>
@endsection