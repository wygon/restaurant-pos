@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">Tables</h1>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ($tables as $table)
                @if($table->status === 'available')
                    <a href="{{ route('waiter.createOrder', $table) }}" 
                       class="bg-green-500 hover:bg-green-600 text-white p-8 rounded-xl text-center transition flex flex-col items-center justify-center h-full">
                        <span class="block text-2xl font-bold">{{ $table->number }}</span>
                        <span class="text-sm mt-1">Free</span>
                    </a>
                @else
                     <a href="{{ route('waiter.editOrder', $table) }}"
                       class="{{ $table->bgColor }} text-white p-8 rounded-xl text-center shadow-lg transition flex flex-col items-center justify-center h-full">
                        <span class="block text-2xl font-bold">{{ $table->number }}</span>
                        <span class="text-sm mt-1">Occupied</span>
                        
                        @if($table->totalItems > 0)
                            <span class="mt-3 text-xs font-bold bg-white/20 px-3 py-1 rounded-full border border-white/30 shadow-sm">
                                {{ $table->readyItems }}/{{ $table->totalItems }} ready
                            </span>
                        @endif
                    </a>
                @endif
            @endforeach
        </div>
    </div>
@endsection