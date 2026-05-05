<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="0">
        
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex">
            <!-- Left Side - Branding -->
            <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 p-12 flex-col justify-between text-white">
                <div>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="bg-white/20 backdrop-blur-sm p-3 rounded-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold">Spin Express</h1>
                            <p class="text-blue-100 text-lg">Fast. Clean. Reliable.</p>
                        </div>
                    </div>

                    <div class="mt-16 space-y-6">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#BFDBFE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles-icon lucide-sparkles"><path d="M11.017 2.814a1 1 0 0 1 1.966 0l1.051 5.558a2 2 0 0 0 1.594 1.594l5.558 1.051a1 1 0 0 1 0 1.966l-5.558 1.051a2 2 0 0 0-1.594 1.594l-1.051 5.558a1 1 0 0 1-1.966 0l-1.051-5.558a2 2 0 0 0-1.594-1.594l-5.558-1.051a1 1 0 0 1 0-1.966l5.558-1.051a2 2 0 0 0 1.594-1.594z"/><path d="M20 2v4"/><path d="M22 4h-4"/><circle cx="4" cy="20" r="2"/></svg>
                            <h3 class="text-xl font-semibold mb-2">Laundry Management System</h3>
                            <p class="text-blue-100">Streamline your laundry operations with our comprehensive management system.</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-center opacity-20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-64 h-64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.5">
                        <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50">
                <div class="w-full max-w-md">
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <div class="mb-8">
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                            <p class="text-gray-600">Sign in to your account to continue</p>
                        </div>

                        <!-- User Type Toggle -->
                        <div class="mb-6 flex gap-2 p-1 bg-gray-100 rounded-lg">
                            <button type="button" id="adminBtn" class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all duration-200 bg-blue-600 text-white">
                                Admin Login
                            </button>
                            <button type="button" id="staffBtn" class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all duration-200 text-gray-600 hover:bg-gray-200">
                                Staff Login
                            </button>
                        </div>

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}" class="space-y-6" id="loginForm">
                            @csrf
                            <input type="hidden" name="user_type" id="user_type" value="admin">

                            <!-- Email Address -->
                            <div class="space-y-2">
                                <x-input-label for="email" :value="__('Email Address')" class="text-gray-700" />
                                <x-text-input 
                                    id="email" 
                                    class="block w-full h-12 px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-gray-900 bg-white" 
                                    type="email" 
                                    name="email" 
                                    :value="old('email')" 
                                    placeholder="Enter your email"
                                    required 
                                    autofocus 
                                    autocomplete="username" 
                                />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Password -->
                            <div class="space-y-2">
                                <x-input-label for="password" :value="__('Password')" class="text-gray-700" />
                                <x-text-input 
                                    id="password" 
                                    class="block w-full h-12 px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-gray-900 bg-white"
                                    type="password"
                                    name="password"
                                    placeholder="Enter your password"
                                    required 
                                    autocomplete="current-password" 
                                />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold h-12 px-4 rounded-lg transition duration-150 ease-in-out">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </form>

                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <p class="text-sm text-gray-600 text-center">
                                Demo credentials:
                                <br />
                                <span class="font-medium">Admin:</span> admin@gmail.com / admin123
                                <br />
                                <span class="font-medium">Staff:</span> staff@gmail.com / staff123
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const adminBtn = document.getElementById('adminBtn');
            const staffBtn = document.getElementById('staffBtn');
            const userTypeInput = document.getElementById('user_type');
            const loginForm = document.getElementById('loginForm');

            adminBtn.addEventListener('click', function() {
                adminBtn.classList.remove('text-gray-600', 'hover:bg-gray-200');
                adminBtn.classList.add('bg-blue-600', 'text-white');
                staffBtn.classList.remove('bg-blue-600', 'text-white');
                staffBtn.classList.add('text-gray-600', 'hover:bg-gray-200');
                userTypeInput.value = 'admin';
                loginForm.action = "{{ route('login') }}";
            });

            staffBtn.addEventListener('click', function() {
                staffBtn.classList.remove('text-gray-600', 'hover:bg-gray-200');
                staffBtn.classList.add('bg-blue-600', 'text-white');
                adminBtn.classList.remove('bg-blue-600', 'text-white');
                adminBtn.classList.add('text-gray-600', 'hover:bg-gray-200');
                userTypeInput.value = 'staff';
                loginForm.action = "{{ route('staff.login.submit') }}";
            });
            
            // Clear session storage on page load to prevent cached sessions
            sessionStorage.clear();
        </script>
    </body>
</html>