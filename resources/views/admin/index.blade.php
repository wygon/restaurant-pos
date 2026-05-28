<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Admin - Menu Management</title>
</head>
<body class="bg-gray-100 p-6">
    @include('partials.topbar')

    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Menu Management</h1>
            <div class="flex gap-2">
                <a href="{{ route('admin.tables') }}" class="bg-gray-600 text-white px-4 py-2 rounded">Manage Tables</a>
                <a href="{{ route('admin.createItem') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Add Menu Item</a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 mb-6 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="p-4 border rounded bg-gray-50">
                <h2 class="font-bold mb-2">Add Category</h2>
                <form action="{{ route('admin.storeCategory') }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="text" name="name" placeholder="Category name..." required class="border p-2 rounded w-full">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Add</button>
                </form>
            </div>

            <div class="p-4 border rounded bg-gray-50">
                <h2 class="font-bold mb-2">Search Menu Items</h2>
                <form action="{{ route('admin.index') }}" method="GET" class="flex gap-2">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search dishes..." class="border p-2 rounded w-full">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Search</button>
                    @if($search)
                        <a href="{{ route('admin.index') }}" class="bg-gray-300 text-black px-4 py-2 rounded">Clear</a>
                    @endif
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
                            <button type="submit" class="text-xs border px-2 py-1 rounded {{ $category->is_active ? 'border-red-300 text-red-600 hover:bg-red-50' : 'border-green-300 text-green-600 hover:bg-green-50' }}">
                                {{ $category->is_active ? 'Deactivate Category' : 'Activate Category' }}
                            </button>
                        </form>

                        @if($category->is_active)
                            <a href="{{ route('admin.createItem', ['category' => $category->id]) }}" class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded hover:bg-blue-200 ml-auto">
                                + Add item
                            </a>
                        @endif
                    </div>
                    
                    <ul class="space-y-1">
                        @forelse($category->menuItems as $item)
                            <li class="flex justify-between items-center p-2 border-b border-gray-50 {{ !$item->is_active ? 'text-gray-400' : '' }}">
                                <div>
                                    <span class="{{ !$item->is_active ? 'line-through' : '' }} font-medium">{{ $item->name }}</span>
                                    <span class="ml-2">— {{ number_format($item->price, 2) }} PLN</span>
                                </div>
                                
                                <form action="{{ route('admin.toggleItem', $item) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs border px-3 py-1 rounded {{ $item->is_active ? 'border-red-300 text-red-600 hover:bg-red-50' : 'border-green-300 text-green-600 hover:bg-green-50' }}">
                                        {{ $item->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </li>
                        @empty
                            <li class="p-2 text-gray-500 italic text-sm">No items found.</li>
                        @endforelse
                    </ul>
                </div>
            @empty
                <p class="text-gray-500">No categories found.</p>
            @endforelse
        </div>
    </div>
</body>
</html>