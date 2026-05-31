@extends('layouts.app')
@section('content')
    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Edit Menu Item</h1>
            <a href="{{ route('admin.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded">Back to Menu</a>
        </div>

        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-3 mb-6 rounded">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.updateItem', $menuItem) }}" method="POST" class="space-y-4 max-w-lg">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block font-bold mb-1">Category</label>
                <select name="category_id" required class="border p-2 rounded w-full bg-gray-50">
                    <option value="" disabled>-- Select category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ (old('category_id', $menuItem->category_id) == $category->id) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name', $menuItem->name) }}" required class="border p-2 rounded w-full bg-gray-50">
            </div>

            <div>
                <label class="block font-bold mb-1">Price (PLN)</label>
                <input type="number" name="price" value="{{ old('price', $menuItem->price) }}" required min="0" step="0.01" class="border p-2 rounded w-full bg-gray-50">
            </div>

            <div>
                <label class="block font-bold mb-1">Description (Optional)</label>
                <textarea name="description" rows="3" class="border p-2 rounded w-full bg-gray-50">{{ old('description', $menuItem->description) }}</textarea>
            </div>

            <div class="py-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500" 
                           {{ old('is_active', $menuItem->is_active) ? 'checked' : '' }}>
                    <span class="font-bold text-gray-800">Item is Active (Visible on menu)</span>
                </label>
            </div>

            <div class="pt-4 border-t">
                 <x-btn>Update Item</x-btn>
            </div>
        </form>
    </div>
@endsection