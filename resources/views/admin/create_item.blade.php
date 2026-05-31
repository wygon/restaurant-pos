@extends('layouts.app')
@section('content')
    <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-6">Add Menu Item</h1>

        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-3 mb-6 rounded">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.storeItem') }}" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label class="block font-bold mb-1">Category</label>
                <select name="category_id" required class="border p-2 rounded w-full">
                    <option value="" disabled selected>-- Select category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ (old('category_id') ?? $selectedCategory ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold mb-1">Name</label>
                <input type="text" name="name" required class="border p-2 rounded w-full">
            </div>

            <div>
                <label class="block font-bold mb-1">Price (PLN)</label>
                <input type="number" name="price" required min="0" step="0.01" class="border p-2 rounded w-full">
            </div>

            <div>
                <label class="block font-bold mb-1">Description (Optional)</label>
                <textarea name="description" rows="3" class="border p-2 rounded w-full"></textarea>
            </div>

            <div class="flex gap-2 pt-2">
                <x-btn>Save item</x-btn>
                <a href="{{ route('admin.index') }}" class="bg-gray-300 text-black px-4 py-2 rounded text-center">Cancel</a>
            </div>
        </form>
    </div>
@endsection