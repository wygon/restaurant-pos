<div class="w-full lg:max-w-4xl mx-auto bg-white p-4 shadow-md mb-6 flex items-center justify-between rounded-b-lg">
    <div class="text-sm font-medium text-gray-600">
        Zalogowany jako: <span class="font-bold text-gray-900">{{ Auth::user()->name }}</span> 
        <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs uppercase">{{ Auth::user()->role }}</span>
    </div>

    <div class="flex items-center gap-6">
        {{-- Linki zależne od prawdziwej roli użytkownika --}}
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.index') }}" class="text-sm text-blue-600 hover:underline font-semibold">Panel Admina</a>
            <a href="{{ route('kitchen.index') }}" class="text-sm text-blue-600 hover:underline font-semibold">Kuchnia</a>
            <a href="{{ route('waiter.index') }}" class="text-sm text-blue-600 hover:underline font-semibold">Sala (Kelner)</a>
        @elseif(Auth::user()->role === 'cook')
            <a href="{{ route('kitchen.index') }}" class="text-sm text-blue-600 hover:underline font-semibold">Kuchnia</a>
        @elseif(Auth::user()->role === 'waiter')
            <a href="{{ route('waiter.index') }}" class="text-sm text-blue-600 hover:underline font-semibold">Sala (Kelner)</a>
        @endif

        {{-- Wylogowywanie dostarczone przez Laravel Breeze --}}
        <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="text-sm bg-red-500 text-white px-4 py-2 rounded shadow hover:bg-red-600 transition">
                Wyloguj
            </button>
        </form>
    </div>
</div>