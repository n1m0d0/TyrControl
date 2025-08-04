<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TyrControl</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s ease-in-out infinite',
                        'fade-in-up': 'fadeInUp 0.6s ease forwards',
                        'bounce-slow': 'bounce 3s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0px) rotate(0deg)',
                                opacity: '0.3'
                            },
                            '50%': {
                                transform: 'translateY(-20px) rotate(180deg)',
                                opacity: '0.8'
                            }
                        },
                        fadeInUp: {
                            'from': {
                                opacity: '0',
                                transform: 'translateY(30px)'
                            },
                            'to': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        }
                    }
                }
            }
        }
    </script>
</head>

<body
    class="font-sans bg-gradient-to-br from-blue-500 via-purple-600 to-indigo-700 dark:from-gray-900 dark:via-blue-900 dark:to-purple-900 min-h-screen overflow-hidden transition-all duration-500">
    <!-- Particles Background -->
    <div id="particles" class="absolute inset-0 z-0"></div>

    <!-- Theme Toggle & Auth Links -->
    <div class="absolute top-4 right-4 z-50 flex items-center gap-4">
        <button id="themeToggle"
            class="p-2 rounded-full bg-white/20 dark:bg-gray-800/50 backdrop-blur-sm border border-white/30 dark:border-gray-600/30 text-white hover:bg-white/30 dark:hover:bg-gray-700/50 transition-all duration-300">
            <!-- Sun Icon (Light Mode) -->
            <svg class="w-5 h-5 hidden dark:block" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.166a.75.75 0 00-1.06-1.06l-1.591 1.59a.75.75 0 101.06 1.061l1.591-1.59zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5H21a.75.75 0 01.75.75zM18.894 17.834a.75.75 0 00-1.06 1.06l-1.591-1.59a.75.75 0 111.06-1.061l1.591 1.59zM12 18a.75.75 0 01.75.75V21a.75.75 0 01-1.5 0v-2.25A.75.75 0 0112 18zM7.758 17.303a.75.75 0 00-1.061-1.06l-1.591 1.59a.75.75 0 001.06 1.061l1.591-1.59zM6 12a.75.75 0 01-.75.75H3a.75.75 0 010-1.5h2.25A.75.75 0 016 12zM6.697 7.757a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 00-1.061 1.06l1.59 1.591z" />
            </svg>
            <!-- Moon Icon (Dark Mode) -->
            <svg class="w-5 h-5 block dark:hidden" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M9.528 1.718a.75.75 0 01.162.819A8.97 8.97 0 009 6a9 9 0 009 9 8.97 8.97 0 003.463-.69.75.75 0 01.981.98 10.503 10.503 0 01-9.694 6.46c-5.799 0-10.5-4.701-10.5-10.5 0-4.368 2.667-8.112 6.46-9.694a.75.75 0 01.818.162z" />
            </svg>
        </button>

        <div class="flex gap-2">
            @auth
                <a href="{{ url('/dashboard') }}"
                    class="px-4 py-2 text-sm text-white bg-emerald-500/80 hover:bg-emerald-500 rounded-full transition-all duration-300 font-medium">
                    {{ __('Dashboard') }}
                </a>
            @else
                <a href="{{ route('login') }}"
                    class="px-4 py-2 text-sm text-white bg-white/20 dark:bg-gray-800/50 backdrop-blur-sm border border-white/30 dark:border-gray-600/30 rounded-full hover:bg-white/30 dark:hover:bg-gray-700/50 transition-all duration-300">
                    {{ __('Log in') }}
                </a>
            @endauth
        </div>
    </div>

    <!-- Main Container -->
    <div class="relative z-10 min-h-screen flex flex-col lg:flex-row">
        <!-- Left Panel -->
        <div
            class="flex-1 flex flex-col justify-center items-center p-8 bg-white/10 dark:bg-gray-900/20 backdrop-blur-lg border-r border-white/20 dark:border-gray-700/30">
            <!-- Logo Section -->
            <div class="text-center mb-12">
                <div
                    class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-emerald-400 to-teal-600 rounded-full flex items-center justify-center shadow-2xl animate-pulse-slow">
                    <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4M11,6V8H9V10H11V12H9V14H11V16H13V14H15V12H13V10H15V8H13V6H11Z" />
                    </svg>
                </div>
                <h1 class="text-5xl lg:text-6xl font-bold text-white dark:text-gray-100 mb-2 tracking-tight">
                    SARE
                </h1>
                <p class="text-xl text-white/90 dark:text-gray-300 font-light">
                    Salud al rescate
                </p>
            </div>

            <!-- Welcome Text -->
            <div class="text-center mb-12 max-w-md">
                <h2 class="text-3xl font-light text-white dark:text-gray-100 mb-4">
                    Bienvenido
                </h2>
                <p class="text-lg text-white/90 dark:text-gray-300 leading-relaxed">
                    Gestiona tu negocio de manera eficiente con nuestro sistema integral.
                </p>
            </div>
        </div>

        <!-- Right Panel - Features -->
        <div class="flex-1 flex items-center justify-center p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl">
                <!-- Feature Card 1 -->
                <div
                    class="feature-card bg-white/10 dark:bg-gray-800/20 backdrop-blur-lg rounded-2xl p-6 border border-white/20 dark:border-gray-700/30 hover:bg-white/15 dark:hover:bg-gray-800/30 transform hover:-translate-y-2 transition-all duration-300 group">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M17,8H20V6H17V8M17,16H20V14H17V16M17,12H20V10H17V12M13,8V6H4V8H13M13,16V14H4V16H13M13,12V10H4V12H13Z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white dark:text-gray-100 mb-2">
                        Inventario
                    </h3>
                    <p class="text-white/80 dark:text-gray-300 leading-relaxed">
                        Control completo de los productos con alertas
                    </p>
                </div>

                <!-- Feature Card 2 -->
                <div
                    class="feature-card bg-white/10 dark:bg-gray-800/20 backdrop-blur-lg rounded-2xl p-6 border border-white/20 dark:border-gray-700/30 hover:bg-white/15 dark:hover:bg-gray-800/30 transform hover:-translate-y-2 transition-all duration-300 group">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4M12,6A6,6 0 0,1 18,12A6,6 0 0,1 12,18A6,6 0 0,1 6,12A6,6 0 0,1 12,6M12,8A4,4 0 0,0 8,12A4,4 0 0,0 12,16A4,4 0 0,0 16,12A4,4 0 0,0 12,8Z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white dark:text-gray-100 mb-2">
                        Ventas
                    </h3>
                    <p class="text-white/80 dark:text-gray-300 leading-relaxed">
                        Sistema de ventas rápido con nota de venta automática
                    </p>
                </div>

                <!-- Feature Card 3 -->
                <div
                    class="feature-card bg-white/10 dark:bg-gray-800/20 backdrop-blur-lg rounded-2xl p-6 border border-white/20 dark:border-gray-700/30 hover:bg-white/15 dark:hover:bg-gray-800/30 transform hover:-translate-y-2 transition-all duration-300 group">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M16,17V19H2V17S2,13 9,13 16,17 16,17M12.5,7.5A3.5,3.5 0 0,1 9,11A3.5,3.5 0 0,1 5.5,7.5A3.5,3.5 0 0,1 9,4A3.5,3.5 0 0,1 12.5,7.5M15.94,13A5.32,5.32 0 0,1 18,17V19H22V17S22,13.37 15.94,13M15,4A3.39,3.39 0 0,1 16.56,5.44A3.5,3.5 0 0,0 16.56,9.56A3.39,3.39 0 0,1 15,11V9A1.5,1.5 0 0,0 15,6V4Z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white dark:text-gray-100 mb-2">
                        Clientes
                    </h3>
                    <p class="text-white/80 dark:text-gray-300 leading-relaxed">
                        Gestión de clientes
                    </p>
                </div>

                <!-- Feature Card 4 -->
                <div
                    class="feature-card bg-white/10 dark:bg-gray-800/20 backdrop-blur-lg rounded-2xl p-6 border border-white/20 dark:border-gray-700/30 hover:bg-white/15 dark:hover:bg-gray-800/30 transform hover:-translate-y-2 transition-all duration-300 group">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M19,3H5C3.9,3 3,3.9 3,5V19C3,20.1 3.9,21 5,21H19C20.1,21 21,20.1 21,19V5C21,3.9 20.1,3 19,3M5,19V5H19V6.5H17C15.9,6.5 15,7.4 15,8.5V15.5C15,16.6 15.9,17.5 17,17.5H19V19H5M17,15.5V8.5H19V15.5H17Z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white dark:text-gray-100 mb-2">
                        Reportes
                    </h3>
                    <p class="text-white/80 dark:text-gray-300 leading-relaxed">
                        Análisis detallados y reportes financieros
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Bar -->
    <div
        class="absolute bottom-0 left-0 right-0 z-20 bg-white/10 dark:bg-gray-900/20 backdrop-blur-sm border-t border-white/20 dark:border-gray-700/30">
        <div
            class="max-w-7xl mx-auto px-6 py-3 flex justify-between items-center text-sm text-white/80 dark:text-gray-300">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                    <span>TyrControl</span>
                </div>
                <div class="hidden sm:block">|</div>
                <div class="hidden sm:flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M12,4A8,8 0 0,0 4,12C4,16.41 7.59,20 12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4M12,6A6,6 0 0,1 18,12A6,6 0 0,1 12,18A6,6 0 0,1 6,12A6,6 0 0,1 12,6M12,8A4,4 0 0,0 8,12A4,4 0 0,0 12,16A4,4 0 0,0 16,12A4,4 0 0,0 12,8Z" />
                    </svg>
                    <span>Versión 1.0.0</span>
                </div>
            </div>
            <div class="text-xs opacity-75">
                © 2025 SARE. Todos los derechos reservados.
            </div>
        </div>
    </div>

    <script>
        // Theme Toggle Functionality
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;

        // Check for saved theme or default to light mode
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            html.classList.toggle('dark', savedTheme === 'dark');
        } else {
            // Auto-detect system preference
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            html.classList.toggle('dark', prefersDark);
        }

        themeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            const isDark = html.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });

        // Create animated particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 40;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'absolute w-1 h-1 bg-white/30 dark:bg-gray-300/20 rounded-full animate-float';

                // Random position
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';

                // Random animation delay and duration
                particle.style.animationDelay = Math.random() * 6 + 's';
                particle.style.animationDuration = (Math.random() * 3 + 3) + 's';

                particlesContainer.appendChild(particle);
            }
        }

        // Animate feature cards on load
        function animateCards() {
            const cards = document.querySelectorAll('.feature-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 150);
            });
        }

        // Initialize on page load
        window.addEventListener('load', () => {
            createParticles();
            animateCards();
        });

        // System theme change listener
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('theme')) {
                html.classList.toggle('dark', e.matches);
            }
        });

        // Add smooth scroll behavior and enhance button interactions
        document.querySelectorAll('button, a').forEach(element => {
            element.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px) scale(1.02)';
            });

            element.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    </script>
</body>

</html>
