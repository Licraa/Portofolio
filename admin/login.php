<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Modern Authentication</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'dark-bg': '#2D2D2D',
                        'light-surface': '#F5F5F5',
                        'accent-green': '#1D4ED8',
                        'warm-wood': '#D4A574'
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.8s ease-in-out',
                        'slide-up': 'slideUp 0.8s ease-out',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-glow': 'pulseGlow 2s ease-in-out infinite alternate',
                        'shake': 'shake 0.5s ease-in-out'
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            }
                        },
                        slideUp: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(30px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        },
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0px)'
                            },
                            '50%': {
                                transform: 'translateY(-10px)'
                            }
                        },
                        pulseGlow: {
                            '0%': {
                                boxShadow: '0 0 5px rgba(212, 165, 116, 0.3)'
                            },
                            '100%': {
                                boxShadow: '0 0 20px rgba(212, 165, 116, 0.6)'
                            }
                        },
                        shake: {
                            '0%, 100%': {
                                transform: 'translateX(0)'
                            },
                            '10%, 30%, 50%, 70%, 90%': {
                                transform: 'translateX(-5px)'
                            },
                            '20%, 40%, 60%, 80%': {
                                transform: 'translateX(5px)'
                            }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass-effect {
            backdrop-filter: blur(16px);
            background: rgba(245, 245, 245, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #2D2D2D 0%, #1D4ED8 50%, #2D2D2D 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .floating-shapes::before {
            content: '';
            position: absolute;
            top: 20%;
            left: 10%;
            width: 100px;
            height: 100px;
            background: rgba(212, 165, 116, 0.1);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }

        .floating-shapes::after {
            content: '';
            position: absolute;
            bottom: 20%;
            right: 15%;
            width: 150px;
            height: 150px;
            background: rgba(29, 78, 216, 0.1);
            border-radius: 30% 70% 60% 40%;
            animation: float 10s ease-in-out infinite reverse;
        }
    </style>
</head>

<body class="bg-dark-bg min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Floating Background Shapes -->
    <div class="floating-shapes absolute inset-0"></div>

    <!-- Login Container -->
    <div class="w-full max-w-md animate-fade-in">

        <!-- Logo/Brand Section -->
        <div class="text-center mb-8 animate-slide-up">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-warm-wood rounded-full mb-4 animate-pulse-glow">
                <svg class="w-10 h-10 text-dark-bg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Welcome Back</h1>
            <p class="text-gray-300">Sign in to your account</p>
        </div>

        <!-- Login Form -->
        <div class="glass-effect rounded-2xl p-8 shadow-2xl animate-slide-up">
            <form id="loginForm" class="space-y-6" method="POST" action="login_process.php">

                <!-- Email Field -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-white">
                        Email Address
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            required
                            class="w-full pl-10 pr-4 py-3 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-lg text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-warm-wood focus:border-transparent transition-all"
                            placeholder="Enter your email">
                    </div>
                </div>

                <!-- Password Field -->
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-medium text-white">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input
                            type="text"
                            id="password"
                            name="password"
                            required
                            class="w-full pl-10 pr-12 py-3 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-lg text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-warm-wood focus:border-transparent transition-all"
                            placeholder="Enter your password">
                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-white transition-colors">
                            <svg id="eyeIcon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>



                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-warm-wood hover:bg-opacity-90 text-dark-bg font-semibold py-3 px-4 rounded-lg transition-all transform hover:scale-[1.02] active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-warm-wood focus:ring-offset-2 focus:ring-offset-transparent">
                    <span id="loginText">Sign In</span>
                    <span id="loadingText" class="hidden">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-dark-bg inline" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Signing In...
                    </span>
                </button>
            </form>
        </div>

    </div>

    <!-- Error/Success Messages -->
    <div id="messageContainer" class="fixed top-4 right-4 z-50"></div>

    <script>
        // Toggle Password Visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }

        // Show Message
        function showMessage(message, type = 'success') {
            const container = document.getElementById('messageContainer');
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-yellow-500',
                info: 'bg-blue-500'
            };

            const messageDiv = document.createElement('div');
            messageDiv.className = `${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg mb-2 animate-slide-up`;
            messageDiv.textContent = message;

            container.appendChild(messageDiv);

            setTimeout(() => {
                messageDiv.remove();
            }, 5000);
        }

        // Form Submission
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const loginText = document.getElementById('loginText');
            const loadingText = document.getElementById('loadingText');
            const submitButton = e.target.querySelector('button[type="submit"]');

            // Basic validation
            if (!email || !password) {
                showMessage('Please fill in all fields', 'error');
                document.querySelector('.glass-effect').classList.add('animate-shake');
                setTimeout(() => {
                    document.querySelector('.glass-effect').classList.remove('animate-shake');
                }, 500);
                return;
            }

            // Show loading state
            loginText.classList.add('hidden');
            loadingText.classList.remove('hidden');
            submitButton.disabled = true;

            // Kirim data ke backend
            fetch('login_process.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
                })
                .then(response => response.json())
                .then(data => {
                    loginText.classList.remove('hidden');
                    loadingText.classList.add('hidden');
                    submitButton.disabled = false;
                    if (data.success) {
                        showMessage(data.message, 'success');
                        setTimeout(() => {
                            window.location.href = 'index.php';
                        }, 1200);
                    } else {
                        showMessage(data.message, 'error');
                        document.querySelector('.glass-effect').classList.add('animate-shake');
                        setTimeout(() => {
                            document.querySelector('.glass-effect').classList.remove('animate-shake');
                        }, 500);
                    }
                })
                .catch(() => {
                    loginText.classList.remove('hidden');
                    loadingText.classList.add('hidden');
                    submitButton.disabled = false;
                    showMessage('Terjadi kesalahan server.', 'error');
                });
        });

        // Add input focus effects
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-warm-wood');
            });

            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-warm-wood');
            });
        });
    </script>
</body>

</html>