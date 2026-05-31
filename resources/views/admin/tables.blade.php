@extends('layouts.app')
@section('content')

    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Manage Tables</h1>
            <div class="flex gap-2">
                <a href="{{ route('admin.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">Back to Menu</a>
                <a href="{{ route('admin.createTable') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add New Table</a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        <div class="space-y-3">
            @forelse($tables as $table)
                <div class="flex justify-between items-center bg-gray-50 p-4 rounded border">
                    <div>
                        <span class="font-bold text-lg text-gray-800">{{ $table->number }}</span>
                        <span class="text-sm text-gray-500 ml-2">(Capacity: {{ $table->capacity }})</span>
                    </div>
                    <div class="flex items-center gap-4">
                        @if($table->status === 'available')
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-bold rounded">Available</span>
                        @else
                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-bold rounded">Occupied</span>
                        @endif
                        
                        <a href="{{ route('admin.editTable', $table) }}" class="text-blue-600 hover:underline font-semibold">Edit</a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">No tables found.</p>
            @endforelse
        </div>
    </div>
@endsection