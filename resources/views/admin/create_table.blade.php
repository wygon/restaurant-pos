@extends('layouts.app')
@section('content')

    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Add New Table</h1>
            <a href="{{ route('admin.tables') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded">Back to Tables</a>
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

        <form action="{{ route('admin.storeTable') }}" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label class="block font-bold mb-1">Table Name / Number</label>
                <input type="text" name="number" required class="border p-2 rounded w-full bg-gray-50">
            </div>

            <div>
                <label class="block font-bold mb-1">Capacity (Seats)</label>
                <input type="number" name="capacity" required min="1" value="2" class="border p-2 rounded w-full bg-gray-50">
            </div>

            <div class="pt-4 border-t">
                <x-btn>Save Table</x-btn>
            </div>
        </form>
    </div>
@endsection