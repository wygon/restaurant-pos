<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Restaurant POS</title>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md border-t-8 border-blue-600">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Restaurant POS</h1>
            <p class="text-gray-500 mt-2">Login</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
                Invalid email or password
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full border-2 border-gray-200 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none transition">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                <input id="password" type="password" name="password" required
                       class="w-full border-2 border-gray-200 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none transition">
            </div>

            <div class="mb-6 flex items-center">
                <input id="remember_me" type="checkbox" name="remember" class="mr-2 rounded text-blue-600 focus:ring-blue-500">
                <label for="remember_me" class="text-sm text-gray-600">Rememvber me</label>
            </div>
            <x-btn>Login</x-btn>
            <x-btn href="{{ route('register') }}">Register</x-btn>
        </form>
    </div>
</body>
</html>