<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Sistem Perpustakaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center h-screen font-sans antialiased">
    
    <div class="w-full max-w-md bg-white p-8 md:p-10 rounded-2xl shadow-sm border border-gray-200">
        
        <!-- Header Login -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-blue-100 rounded-2xl mb-4">
                <svg class="w-7 h-7 text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Login Admin</h2>
            <p class="text-sm text-gray-500 mt-1">Silakan masuk untuk mengelola sistem perpustakaan.</p>
        </div>

        <!-- Alert Error (Jika email/password salah) -->
        @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
            <p class="text-red-700 text-sm font-medium">{{ session('error') }}</p>
        </div>
        @endif

        <!-- Form Login -->
        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
            @csrf
            
            <!-- Input Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Administrator</label>
                <input type="email" name="email" required autofocus placeholder="admin@perpustakaan.com" 
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-biru focus:border-biru outline-none transition bg-gray-50 focus:bg-white">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Input Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required placeholder="••••••••" 
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-biru focus:border-biru outline-none transition bg-gray-50 focus:bg-white">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Fitur Remember Me -->
            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500 cursor-pointer">
                    <span class="ml-2 text-sm text-gray-600 font-medium">Ingat sesi saya</span>
                </label>
            </div>

            <!-- Tombol Submit -->
            <div class="pt-2">
                <button type="submit" class="w-full bg-blue-700 hover:bg-blue-900 text-white font-bold py-3.5 px-4 rounded-xl shadow-md transition transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    Masuk ke Dashboard
                </button>
            </div>
        </form>
        
    </div>
</body>
</html>