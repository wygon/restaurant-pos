@extends('layouts.app')
@section('content')
    <div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Team</h1>
            <div class="flex gap-2">
                <a href="{{ route('admin.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded">Menu</a>
                <a href="{{ route('admin.tables') }}" class="bg-gray-600 text-white px-4 py-2 rounded">Tables</a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 mb-6 rounded font-semibold">
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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <div class="p-5 border rounded-xl bg-gray-50 h-fit shadow-sm md:col-span-1">
                <h2 class="font-bold mb-4 text-lg border-b border-gray-200 pb-2">Create New User</h2>
                <form action="{{ route('admin.storeUser') }}" method="POST" class="flex flex-col gap-4">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required 
                               class="border p-2 rounded w-full bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required 
                               class="border p-2 rounded w-full bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Role</label>
                        <select name="role" required class="border p-2 rounded w-full bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            <option value="" disabled selected>-- Select Role --</option>
                            <option value="waiter">Waiter</option>
                            <option value="cook">Cook</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" required minlength="8" class="border p-2 rounded w-full bg-white">
                    </div>
                    <x-btn>Create User</x-btn>
                </form>
            </div>

            <div class="md:col-span-2">
                <div class="bg-white border rounded-xl overflow-hidden shadow-sm">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700 text-sm border-b">
                                <th class="p-4 font-bold">Name</th>
                                <th class="p-4 font-bold">Email</th>
                                <th class="p-4 font-bold">Role</th>
                                <th class="p-4 font-bold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr class="border-b hover:bg-gray-50 transition">
                                    <td class="p-4 ">{{ $user->name }}</td>
                                    <td class="p-4 text-sm">{{ $user->email }}</td>
                                    <td class="p-4 text-sm">
                                        @if($user->role === 'admin')
                                            Admin
                                        @elseif($user->role === 'cook')
                                            Cook
                                        @else
                                            Waiter
                                        @endif
                                    </td>
                                    <td class="p-4 text-right">
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.destroyUser', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete {{ $user->name }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <x-btn-outline color="red">Delete</x-btn-outline>
                                            </form>
                                        @else
                                            <span class="text-xs bg-gray-200 text-gray-600 px-3 py-1 rounded font-bold">You</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection