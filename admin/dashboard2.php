<?php
// Dashboard Admin
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'dark-bg': '#1E1E2E',
                        'darker-bg': '#161622',
                        'light-surface': '#F5F5F5',
                        'accent-blue': '#1D4ED8',
                        'accent-blue-light': '#3B82F6',
                        'warm-wood': '#D4A574',
                        'success': '#10B981',
                        'warning': '#F59E0B',
                        'danger': '#EF4444',
                        'card-bg': '#2A2A3A'
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-glow': 'pulseGlow 2s ease-in-out infinite alternate',
                        'bounce-slow': 'bounceSlow 3s infinite'
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
                                transform: 'translateY(20px)'
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
                                transform: 'translateY(-8px)'
                            }
                        },
                        pulseGlow: {
                            '0%': {
                                boxShadow: '0 0 5px rgba(29, 78, 216, 0.3)'
                            },
                            '100%': {
                                boxShadow: '0 0 20px rgba(29, 78, 216, 0.6)'
                            }
                        },
                        bounceSlow: {
                            '0%, 100%': {
                                transform: 'translateY(-5%)',
                                animationTimingFunction: 'cubic-bezier(0.8, 0, 1, 1)'
                            },
                            '50%': {
                                transform: 'translateY(0)',
                                animationTimingFunction: 'cubic-bezier(0, 0, 0.2, 1)'
                            }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass-effect {
            backdrop-filter: blur(12px);
            background: rgba(30, 30, 46, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .gradient-text {
            background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .sidebar-link {
            position: relative;
            transition: all 0.3s ease;
        }

        .sidebar-link:hover {
            transform: translateX(5px);
        }

        .sidebar-link.active {
            background: linear-gradient(90deg, rgba(29, 78, 216, 0.2) 0%, rgba(29, 78, 216, 0) 100%);
            border-left: 3px solid #1D4ED8;
        }

        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: #1D4ED8;
            border-radius: 0 3px 3px 0;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .notification-dot {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 10px;
            height: 10px;
            background: #EF4444;
            border-radius: 50%;
            border: 2px solid #2A2A3A;
        }

        .progress-ring__circle {
            transition: stroke-dashoffset 0.5s ease;
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
        }
    </style>
</head>

<body class="bg-darker-bg text-light-surface min-h-screen font-sans">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-dark-bg border-r border-gray-700 flex flex-col py-6 px-4 sticky top-0 h-screen z-40">
            <div class="flex items-center gap-3 mb-8 px-4">
                <div class="w-10 h-10 bg-gradient-to-br from-accent-blue to-accent-blue-light rounded-xl flex items-center justify-center shadow-lg">
                    <img src="../images/Logo.png" alt="Logo" class="w-8 h-8 rounded-lg">
                </div>
                <span class="nav-logo font-bold text-2xl tracking-tight gradient-text">Portfolio Admin</span>
            </div>

            <div class="px-4 mb-6">
                <div class="relative">
                    <input type="text" placeholder="Cari menu..." class="w-full bg-gray-700/50 border border-gray-600 rounded-lg py-2 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-accent-blue focus:border-transparent">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                </div>
            </div>

            <nav class="flex-1 flex flex-col gap-1 px-2">
                <a href="index.php?page=dashboard" class="sidebar-link active flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-all duration-200 hover:bg-gray-700/50 text-white">
                    <i class="fas fa-home w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>
                <a href="index.php?page=profile" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-all duration-200 hover:bg-gray-700/50 text-gray-300">
                    <i class="fas fa-user w-5 text-center"></i>
                    <span>Profil</span>
                </a>
                <a href="index.php?page=about" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-all duration-200 hover:bg-gray-700/50 text-gray-300">
                    <i class="fas fa-info-circle w-5 text-center"></i>
                    <span>Tentang</span>
                </a>
                <a href="index.php?page=skills" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-all duration-200 hover:bg-gray-700/50 text-gray-300">
                    <i class="fas fa-bolt w-5 text-center"></i>
                    <span>Keahlian</span>
                </a>
                <a href="index.php?page=projects" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-all duration-200 hover:bg-gray-700/50 text-gray-300">
                    <i class="fas fa-rocket w-5 text-center"></i>
                    <span>Proyek</span>
                    <span class="notification-dot"></span>
                </a>
                <a href="index.php?page=articles" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-all duration-200 hover:bg-gray-700/50 text-gray-300">
                    <i class="fas fa-newspaper w-5 text-center"></i>
                    <span>Artikel</span>
                </a>
                <a href="index.php?page=contact" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-all duration-200 hover:bg-gray-700/50 text-gray-300">
                    <i class="fas fa-envelope w-5 text-center"></i>
                    <span>Kontak</span>
                </a>
                <a href="index.php?page=contact-messages" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-all duration-200 hover:bg-gray-700/50 text-gray-300">
                    <i class="fas fa-comments w-5 text-center"></i>
                    <span>Pesan Masuk</span>
                    <span class="ml-auto bg-danger text-white text-xs font-bold px-2 py-0.5 rounded-full">5</span>
                </a>
            </nav>

            <div class="px-4 mt-auto">
                <div class="bg-gray-700/50 rounded-lg p-4 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <img src="https://via.placeholder.com/40" alt="User" class="w-10 h-10 rounded-full border-2 border-accent-blue">
                            <span class="absolute bottom-0 right-0 w-3 h-3 bg-success rounded-full border-2 border-gray-700"></span>
                        </div>
                        <div>
                            <p class="font-medium text-sm">Admin User</p>
                            <p class="text-gray-400 text-xs">Administrator</p>
                        </div>
                    </div>
                </div>

                <form method="post" action="logout.php">
                    <button type="submit" class="w-full bg-gray-700 hover:bg-gray-600 text-white py-2.5 rounded-lg font-medium transition-colors flex items-center gap-2 justify-center text-sm">
                        <i class="fas fa-sign-out-alt"></i>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 bg-darker-bg min-h-screen overflow-hidden">
            <!-- Header -->
            <header class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold gradient-text">Dashboard Admin</h1>
                    <p class="text-gray-400">Selamat datang kembali, Admin!</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <button class="p-2 rounded-full bg-gray-700 hover:bg-gray-600 text-gray-300">
                            <i class="fas fa-bell"></i>
                            <span class="notification-dot"></span>
                        </button>
                    </div>
                    <div class="flex items-center gap-2 bg-gray-700/50 rounded-full pl-2 pr-4 py-1 cursor-pointer hover:bg-gray-700">
                        <img src="https://via.placeholder.com/32" alt="User" class="w-8 h-8 rounded-full">
                        <span class="text-sm font-medium">Admin</span>
                    </div>
                </div>
            </header>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Profile Completion -->
                <div class="bg-card-bg rounded-xl p-6 shadow-lg card-hover border border-gray-700 animate-fade-in">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-400 text-sm font-medium">Profil</p>
                            <h3 class="text-2xl font-bold mt-1">75%</h3>
                            <p class="text-gray-400 text-xs mt-2">Lengkapi profil Anda</p>
                        </div>
                        <div class="relative w-12 h-12">
                            <svg class="w-full h-full" viewBox="0 0 36 36">
                                <path d="M18 2.0845
                                    a 15.9155 15.9155 0 0 1 0 31.831
                                    a 15.9155 15.9155 0 0 1 0 -31.831"
                                    fill="none"
                                    stroke="#2A2A3A"
                                    stroke-width="3" />
                                <path d="M18 2.0845
                                    a 15.9155 15.9155 0 0 1 0 31.831
                                    a 15.9155 15.9155 0 0 1 0 -31.831"
                                    fill="none"
                                    stroke="#1D4ED8"
                                    stroke-width="3"
                                    stroke-dasharray="75, 100" />
                                <text x="18" y="20.5" text-anchor="middle" fill="#fff" font-size="8" font-weight="bold">75%</text>
                            </svg>
                        </div>
                    </div>
                    <a href="index.php?page=profile" class="mt-4 inline-flex items-center text-sm text-accent-blue hover:underline">
                        Kelola Profil <i class="fas fa-chevron-right ml-1 text-xs"></i>
                    </a>
                </div>

                <!-- Skills -->
                <div class="bg-card-bg rounded-xl p-6 shadow-lg card-hover border border-gray-700 animate-fade-in">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-400 text-sm font-medium">Keahlian</p>
                            <h3 class="text-2xl font-bold mt-1">12</h3>
                            <p class="text-gray-400 text-xs mt-2">+2 dari bulan lalu</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-blue-900/30 flex items-center justify-center text-accent-blue-light">
                            <i class="fas fa-bolt text-xl"></i>
                        </div>
                    </div>
                    <a href="index.php?page=skills" class="mt-4 inline-flex items-center text-sm text-accent-blue hover:underline">
                        Kelola Keahlian <i class="fas fa-chevron-right ml-1 text-xs"></i>
                    </a>
                </div>

                <!-- Projects -->
                <div class="bg-card-bg rounded-xl p-6 shadow-lg card-hover border border-gray-700 animate-fade-in">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-400 text-sm font-medium">Proyek</p>
                            <h3 class="text-2xl font-bold mt-1">8</h3>
                            <p class="text-gray-400 text-xs mt-2">3 menunggu review</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-purple-900/30 flex items-center justify-center text-purple-400">
                            <i class="fas fa-rocket text-xl"></i>
                        </div>
                    </div>
                    <a href="index.php?page=projects" class="mt-4 inline-flex items-center text-sm text-accent-blue hover:underline">
                        Kelola Proyek <i class="fas fa-chevron-right ml-1 text-xs"></i>
                    </a>
                </div>

                <!-- Messages -->
                <div class="bg-card-bg rounded-xl p-6 shadow-lg card-hover border border-gray-700 animate-fade-in">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-400 text-sm font-medium">Pesan Masuk</p>
                            <h3 class="text-2xl font-bold mt-1">5</h3>
                            <p class="text-gray-400 text-xs mt-2">2 belum dibaca</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-green-900/30 flex items-center justify-center text-green-400">
                            <i class="fas fa-envelope text-xl"></i>
                        </div>
                    </div>
                    <a href="index.php?page=contact-messages" class="mt-4 inline-flex items-center text-sm text-accent-blue hover:underline">
                        Lihat Pesan <i class="fas fa-chevron-right ml-1 text-xs"></i>
                    </a>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Activity -->
                <div class="lg:col-span-2 bg-card-bg rounded-xl p-6 shadow-lg border border-gray-700 animate-fade-in">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold gradient-text">Aktivitas Terkini</h2>
                        <button class="text-sm text-accent-blue hover:underline">Lihat Semua</button>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start gap-3 p-3 hover:bg-gray-700/30 rounded-lg transition-colors">
                            <div class="w-8 h-8 rounded-full bg-blue-900/30 flex items-center justify-center text-accent-blue-light mt-1">
                                <i class="fas fa-edit text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium">Anda memperbarui halaman profil</p>
                                <p class="text-xs text-gray-400 mt-1">2 jam yang lalu</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3 hover:bg-gray-700/30 rounded-lg transition-colors">
                            <div class="w-8 h-8 rounded-full bg-green-900/30 flex items-center justify-center text-green-400 mt-1">
                                <i class="fas fa-comment-alt text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium">Pesan baru dari John Doe</p>
                                <p class="text-xs text-gray-400 mt-1">5 jam yang lalu</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3 hover:bg-gray-700/30 rounded-lg transition-colors">
                            <div class="w-8 h-8 rounded-full bg-purple-900/30 flex items-center justify-center text-purple-400 mt-1">
                                <i class="fas fa-plus text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium">Proyek baru ditambahkan</p>
                                <p class="text-xs text-gray-400 mt-1">Kemarin, 14:32</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3 hover:bg-gray-700/30 rounded-lg transition-colors">
                            <div class="w-8 h-8 rounded-full bg-yellow-900/30 flex items-center justify-center text-yellow-400 mt-1">
                                <i class="fas fa-share-alt text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium">Proyek Anda dibagikan 12 kali</p>
                                <p class="text-xs text-gray-400 mt-1">2 hari yang lalu</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-card-bg rounded-xl p-6 shadow-lg border border-gray-700 animate-fade-in">
                    <h2 class="text-xl font-bold gradient-text mb-6">Aksi Cepat</h2>

                    <div class="grid grid-cols-2 gap-4">
                        <a href="index.php?page=profile" class="bg-gray-700/50 hover:bg-gray-700 rounded-lg p-4 flex flex-col items-center justify-center transition-colors">
                            <div class="w-10 h-10 rounded-full bg-blue-900/30 flex items-center justify-center text-accent-blue-light mb-2">
                                <i class="fas fa-user-edit"></i>
                            </div>
                            <span class="text-sm text-center">Edit Profil</span>
                        </a>

                        <a href="index.php?page=projects&action=add" class="bg-gray-700/50 hover:bg-gray-700 rounded-lg p-4 flex flex-col items-center justify-center transition-colors">
                            <div class="w-10 h-10 rounded-full bg-purple-900/30 flex items-center justify-center text-purple-400 mb-2">
                                <i class="fas fa-plus"></i>
                            </div>
                            <span class="text-sm text-center">Tambah Proyek</span>
                        </a>

                        <a href="index.php?page=articles&action=add" class="bg-gray-700/50 hover:bg-gray-700 rounded-lg p-4 flex flex-col items-center justify-center transition-colors">
                            <div class="w-10 h-10 rounded-full bg-green-900/30 flex items-center justify-center text-green-400 mb-2">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <span class="text-sm text-center">Tulis Artikel</span>
                        </a>

                        <a href="index.php?page=skills&action=add" class="bg-gray-700/50 hover:bg-gray-700 rounded-lg p-4 flex flex-col items-center justify-center transition-colors">
                            <div class="w-10 h-10 rounded-full bg-yellow-900/30 flex items-center justify-center text-yellow-400 mb-2">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <span class="text-sm text-center">Tambah Skill</span>
                        </a>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-700">
                        <h3 class="text-sm font-medium mb-3">Portofolio Preview</h3>
                        <a href="../" target="_blank" class="w-full bg-accent-blue hover:bg-accent-blue-light text-white py-2 rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-external-link-alt"></i> Lihat Portofolio
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Messages -->
            <div class="mt-6 bg-card-bg rounded-xl p-6 shadow-lg border border-gray-700 animate-fade-in">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold gradient-text">Pesan Terbaru</h2>
                    <button class="text-sm text-accent-blue hover:underline">Lihat Semua</button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left border-b border-gray-700 text-gray-400">
                                <th class="pb-3">Pengirim</th>
                                <th class="pb-3">Pesan</th>
                                <th class="pb-3">Tanggal</th>
                                <th class="pb-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            <tr class="hover:bg-gray-700/30">
                                <td class="py-3">
                                    <div class="flex items-center gap-2">
                                        <img src="https://via.placeholder.com/32" alt="User" class="w-6 h-6 rounded-full">
                                        <span>John Doe</span>
                                    </div>
                                </td>
                                <td class="py-3 text-gray-400">Menanyakan tentang kolaborasi proyek...</td>
                                <td class="py-3 text-gray-400">2 jam lalu</td>
                                <td class="py-3 text-right">
                                    <button class="text-accent-blue hover:text-accent-blue-light">
                                        <i class="fas fa-reply"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-700/30">
                                <td class="py-3">
                                    <div class="flex items-center gap-2">
                                        <img src="https://via.placeholder.com/32" alt="User" class="w-6 h-6 rounded-full">
                                        <span>Jane Smith</span>
                                    </div>
                                </td>
                                <td class="py-3 text-gray-400">Pertanyaan tentang artikel terbaru...</td>
                                <td class="py-3 text-gray-400">5 jam lalu</td>
                                <td class="py-3 text-right">
                                    <button class="text-accent-blue hover:text-accent-blue-light">
                                        <i class="fas fa-reply"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-700/30">
                                <td class="py-3">
                                    <div class="flex items-center gap-2">
                                        <img src="https://via.placeholder.com/32" alt="User" class="w-6 h-6 rounded-full">
                                        <span>Acme Corp</span>
                                    </div>
                                </td>
                                <td class="py-3 text-gray-400">Tawaran kerja sama...</td>
                                <td class="py-3 text-gray-400">1 hari lalu</td>
                                <td class="py-3 text-right">
                                    <button class="text-accent-blue hover:text-accent-blue-light">
                                        <i class="fas fa-reply"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Simple script to handle active tab state
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = window.location.search.split('page=')[1] || 'dashboard';
            const links = document.querySelectorAll('.sidebar-link');

            links.forEach(link => {
                const linkPage = link.getAttribute('href').split('page=')[1];
                if (linkPage === currentPage) {
                    link.classList.add('active');
                    link.classList.remove('text-gray-300');
                    link.classList.add('text-white');
                }
            });

            // Add animation to cards on scroll
            const animateOnScroll = function() {
                const elements = document.querySelectorAll('.animate-fade-in');
                elements.forEach(element => {
                    const elementPosition = element.getBoundingClientRect().top;
                    const windowHeight = window.innerHeight;

                    if (elementPosition < windowHeight - 100) {
                        element.style.opacity = '1';
                        element.style.transform = 'translateY(0)';
                    }
                });
            };

            // Initial check
            animateOnScroll();

            // Check on scroll
            window.addEventListener('scroll', animateOnScroll);
        });
    </script>
</body>

</html>