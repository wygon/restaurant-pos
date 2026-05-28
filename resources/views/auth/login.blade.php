<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Logowanie do POS</title>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md border-t-8 border-blue-600">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Restaurant POS 🍽️</h1>
            <p class="text-gray-500 mt-2">Zaloguj się do swojego panelu</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
                Niekprawidłowy email lub hasło.
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Adres Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full border-2 border-gray-200 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none transition">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-bold mb-2">Hasło</label>
                <input id="password" type="password" name="password" required
                       class="w-full border-2 border-gray-200 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none transition">
            </div>

            <div class="mb-6 flex items-center">
                <input id="remember_me" type="checkbox" name="remember" class="mr-2 rounded text-blue-600 focus:ring-blue-500">
                <label for="remember_me" class="text-sm text-gray-600">Zapamiętaj mnie</label>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg shadow-md hover:bg-blue-700 transition transform hover:-translate-y-0.5">
                Zaloguj się
            </button>
        </form>
    </div>
</body>
</html>