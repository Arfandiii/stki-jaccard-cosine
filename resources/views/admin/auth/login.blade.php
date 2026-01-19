<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Arsip Digital</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-linear-to-br from-blue-50 via-white to-indigo-50 fill-screen relative">
    <!-- Background Pattern -->
    <div class="absolute inset-0 overflow-hidden">
        <div
            class="absolute -top-40 -right-40 w-80 h-80 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob">
        </div>
        <div
            class="absolute -bottom-40 -left-40 w-80 h-80 bg-indigo-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000">
        </div>
        <div
            class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000">
        </div>
    </div>

    <style>
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>

    <!-- Main Container -->
    <div class="relative min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full">
            <!-- Logo/Header Section -->
            <div class="text-center mb-8 header">
                <h1 class="text-3xl font-bold bg-linear-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    Sistem Arsip Digital
                </h1>
                <p class="text-gray-600">Sistem Temu Kembali Informasi dengan Jaccard Similarity & Cosine Similarity</p>
            </div>

            <!-- Login Card -->
            <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-2xl p-8 border border-white/20">
                <!-- Card Header -->
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Selamat Datang</h2>
                    <p class="text-gray-600 text-sm">Silakan login untuk melanjutkan</p>
                </div>

                <!-- Login Form -->
                <form id="loginForm" class="space-y-5">
                    <!-- Username/Email Field -->
                    <div class="space-y-2">
                        <label for="username" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-user mr-2 text-blue-500"></i>Username atau Email
                        </label>
                        <div class="relative">
                            <input type="text" id="username" name="username"
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 bg-white/50 backdrop-blur-sm outline-none"
                                placeholder="Masukkan username atau email" required>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-lock mr-2 text-blue-500"></i>Password
                            </label>
                        </div>
                        <div class="relative">
                            <input type="password" id="password" name="password"
                                class="w-full pl-12 pr-12 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 bg-white/50 backdrop-blur-sm  outline-none   "
                                placeholder="Masukkan password" required>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <button type="button"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition duration-200"
                                onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Ingat saya di perangkat ini
                        </label>
                    </div>

                    <!-- Login Button -->
                    <button type="submit"
                        class="w-full bg-linear-to-r from-blue-600 to-indigo-600 text-white py-3 px-4 rounded-xl hover:from-blue-700 hover:to-indigo-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 cursor-pointer">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </button>

                    <!-- Additional Options -->
                    <div class="relative my-4">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Atau</span>
                        </div>
                    </div>
                </form>

                <!-- Footer -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Lupa Password?
                        <a href="#" class="text-blue-600 hover:text-blue-800 font-medium transition duration-200">
                            Hubungi Administrator
                        </a>
                    </p>
                </div>
            </div>

            <!-- System Info -->
            <div class="mt-6 text-center">
                <div class="inline-flex items-center space-x-4 text-sm text-gray-500">
                    <span class="flex items-center">
                        <i class="fas fa-shield-alt mr-1 text-green-500"></i>
                        Aman & Terpercaya
                    </span>
                    <span class="flex items-center">
                        <i class="fas fa-bolt mr-1 text-yellow-500"></i>
                        Cepat & Efisien
                    </span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle Password Visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Form Validation and Submission – via fetch (AJAX)
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            const remember = document.getElementById('remember').checked;

            if (!username || !password) {
                showNotification('Harap isi semua field!', 'error');
                return;
            }

            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;

            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
            btn.disabled = true;

            try {
                const res = await fetch('{{ route('login') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        username: username,
                        password: password,
                        remember: remember
                    })
                });

                const data = await res.json();

                if (res.ok) { // 200
                    showNotification('Login berhasil! Mengalihkan...', 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect || '{{ route('admin.dashboard') }}';
                    }, 1200);
                } else { // 422 / 401 / 419
                    showNotification(data.message || 'Login gagal!', 'error');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            } catch (err) { // network error
                showNotification('Terjadi kesalahan jaringan.', 'error');
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });

        // Notification System
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transition-all duration-300 ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                    ${message}
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Add smooth animations on page load
        window.addEventListener('load', function() {
            const elements = document.querySelectorAll(
                '.header, .bg-white\\/80, .text-center, #loginForm, .mt-6.text-center .text-sm'
            );
            elements.forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    el.style.transition = 'all 0.6s ease';
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>

</html>
