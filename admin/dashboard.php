<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'dark-bg': '#1a1a1a',
                        'dark-surface': '#2a2a2a',
                        'dark-card': '#333333',
                        'accent-primary': '#4a90e2',
                        'accent-secondary': '#5ba2f5',
                        'accent-success': '#27ae60',
                        'accent-warning': '#f39c12',
                        'accent-danger': '#e74c3c',
                        'text-primary': '#ffffff',
                        'text-secondary': '#b0b0b0',
                        'border-color': '#404040'
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.6s ease-out',
                        'slide-up': 'slideUp 0.7s ease-out',
                        'slide-right': 'slideRight 0.5s ease-out',
                        'bounce-subtle': 'bounceSubtle 2s ease-in-out infinite',
                        'glow-pulse': 'glowPulse 2s ease-in-out infinite alternate',
                        'scale-hover': 'scaleHover 0.3s ease-out',
                        'float': 'float 6s ease-in-out infinite'
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(20px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        },
                        slideUp: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(40px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        },
                        slideRight: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateX(-30px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateX(0)'
                            }
                        },
                        bounceSubtle: {
                            '0%, 100%': {
                                transform: 'translateY(0px)'
                            },
                            '50%': {
                                transform: 'translateY(-5px)'
                            }
                        },
                        glowPulse: {
                            '0%': {
                                boxShadow: '0 0 15px rgba(74, 144, 226, 0.3)'
                            },
                            '100%': {
                                boxShadow: '0 0 25px rgba(74, 144, 226, 0.5)'
                            }
                        },
                        scaleHover: {
                            '0%': {
                                transform: 'scale(1)'
                            },
                            '100%': {
                                transform: 'scale(1.05)'
                            }
                        },
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0px)'
                            },
                            '50%': {
                                transform: 'translateY(-10px)'
                            }
                        }
                    },
                    backdropBlur: {
                        'xs': '2px'
                    }
                }
            }
        }
    </script>
    <style>
        .glass-morphism {
            background: rgba(51, 51, 51, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glass-card {
            background: rgba(51, 51, 51, 0.9);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .gradient-primary {
            background: linear-gradient(135deg, #4a90e2 0%, #5ba2f5 100%);
        }

        .gradient-success {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
        }

        .gradient-warning {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }

        .gradient-text {
            background: linear-gradient(135deg, #4a90e2 0%, #ffffff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-item {
            position: relative;
            overflow: hidden;
        }

        .nav-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s;
        }

        .nav-item:hover::before {
            left: 100%;
        }

        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(248, 250, 252, 0.1);
        }

        .status-indicator {
            position: relative;
        }

        .status-indicator::after {
            content: '';
            position: absolute;
            top: -2px;
            right: -2px;
            width: 8px;
            height: 8px;
            background: #10B981;
            border-radius: 50%;
            animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;
        }

        @keyframes ping {

            75%,
            100% {
                transform: scale(2);
                opacity: 0;
            }
        }

        .sidebar-scroll {
            scrollbar-width: thin;
            scrollbar-color: rgba(99, 102, 241, 0.3) transparent;
        }

        .sidebar-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(74, 144, 226, 0.3);
            border-radius: 10px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(74, 144, 226, 0.5);
        }
    </style>
</head>

<body class="bg-dark-bg text-text-primary min-h-screen font-sans overflow-x-hidden">
    <!-- Background Elements -->
    <div class="fixed inset-0 z-0">
        <div class="absolute top-20 left-20 w-64 h-64 bg-accent-primary rounded-full mix-blend-multiply filter blur-xl opacity-5"></div>
        <div class="absolute top-40 right-20 w-64 h-64 bg-accent-success rounded-full mix-blend-multiply filter blur-xl opacity-5"></div>
        <div class="absolute bottom-20 left-1/2 w-64 h-64 bg-accent-warning rounded-full mix-blend-multiply filter blur-xl opacity-5"></div>
    </div>

    <div class="flex min-h-screen relative z-10">
        <!-- Sidebar -->
        <aside class="w-80 bg-dark-surface glass-morphism border-r border-border-color flex flex-col py-8 px-6 sticky top-0 h-screen z-40 sidebar-scroll overflow-y-auto">
            <!-- Logo Section -->
            <div class="flex items-center gap-4 mb-12">
                <div class="relative">
                    <div class="w-12 h-12 gradient-primary rounded-2xl flex items-center justify-center shadow-2xl status-indicator">
                        <i class="fas fa-crown text-white text-xl"></i>
                    </div>
                </div>
                <div>
                    <h1 class="font-bold text-2xl tracking-tight gradient-text">Portfolio</h1>
                    <p class="text-text-secondary text-sm">Admin Dashboard</p>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 flex flex-col gap-2">
                <a href="dashboard.php" class="nav-item flex items-center gap-4 px-6 py-4 rounded-2xl font-semibold transition-all duration-300 bg-accent-primary/20 text-accent-primary border border-accent-primary/30">
                    <i class="fas fa-home text-lg w-5"></i>
                    <span>Dashboard</span>
                    <div class="ml-auto w-2 h-2 bg-accent-primary rounded-full animate-bounce-subtle"></div>
                </a>

                <a href="profile.php" class="nav-item flex items-center gap-4 px-6 py-4 rounded-2xl font-semibold transition-all duration-300 hover:bg-accent-primary/10 hover:text-accent-primary text-text-secondary">
                    <i class="fas fa-user text-lg w-5"></i>
                    <span>Profil</span>
                </a>

                <a href="about.php" class="nav-item flex items-center gap-4 px-6 py-4 rounded-2xl font-semibold transition-all duration-300 hover:bg-accent-primary/10 hover:text-accent-primary text-text-secondary">
                    <i class="fas fa-info-circle text-lg w-5"></i>
                    <span>Tentang</span>
                </a>

                <a href="skills.php" class="nav-item flex items-center gap-4 px-6 py-4 rounded-2xl font-semibold transition-all duration-300 hover:bg-accent-primary/10 hover:text-accent-primary text-text-secondary">
                    <i class="fas fa-bolt text-lg w-5"></i>
                    <span>Keahlian</span>
                </a>

                <a href="project.php" class="nav-item flex items-center gap-4 px-6 py-4 rounded-2xl font-semibold transition-all duration-300 hover:bg-accent-primary/10 hover:text-accent-primary text-text-secondary">
                    <i class="fas fa-rocket text-lg w-5"></i>
                    <span>Proyek</span>
                </a>

                <a href="articles.php" class="nav-item flex items-center gap-4 px-6 py-4 rounded-2xl font-semibold transition-all duration-300 hover:bg-accent-primary/10 hover:text-accent-primary text-text-secondary">
                    <i class="fas fa-edit text-lg w-5"></i>
                    <span>Artikel</span>
                </a>

                <a href="contact.php" class="nav-item flex items-center gap-4 px-6 py-4 rounded-2xl font-semibold transition-all duration-300 hover:bg-accent-primary/10 hover:text-accent-primary text-text-secondary">
                    <i class="fas fa-phone text-lg w-5"></i>
                    <span>Kontak</span>
                </a>

                <a href="message.php" class="nav-item flex items-center gap-4 px-6 py-4 rounded-2xl font-semibold transition-all duration-300 hover:bg-accent-primary/10 hover:text-accent-primary text-text-secondary">
                    <i class="fas fa-comments text-lg w-5"></i>
                    <span>Pesan Masuk</span>
                    <span class="ml-auto bg-accent-danger text-xs px-2 py-1 rounded-full animate-bounce-subtle">3</span>
                </a>
            </nav>

            <!-- User Profile Card -->
            <div class="mt-8 p-6 glass-card rounded-2xl">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 gradient-primary rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-sm">Admin User</p>
                        <p class="text-text-secondary text-xs">administrator</p>
                    </div>
                </div>
                <form method="post" action="logout.php">
                    <button type="submit" class="w-full bg-gradient-to-r from-accent-danger to-red-600 text-white py-3 rounded-xl font-semibold hover:shadow-2xl transition-all duration-300 flex items-center gap-3 justify-center group">
                        <i class="fas fa-sign-out-alt group-hover:translate-x-1 transition-transform duration-300"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8 md:p-12 bg-dark-bg min-h-screen">
            <!-- Header -->
            <div class="flex justify-between items-center mb-12">
                <div>
                    <h1 class="text-5xl font-bold mb-3 gradient-text">Dashboard Admin</h1>
                    <p class="text-text-secondary text-lg">Kelola portofolio Anda dengan mudah dan profesional</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="bg-dark-card px-6 py-3 rounded-2xl border border-border-color">
                        <p class="text-text-secondary text-sm">Selamat datang kembali</p>
                        <p class="font-semibold text-accent-primary">Admin</p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <!-- Profile Card -->
                <div class="glass-card rounded-3xl p-8 card-hover shadow-2xl border border-border-color relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-accent-primary/20 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                    <div class="relative z-10">
                        <div class="gradient-primary text-white rounded-2xl p-4 mb-6 w-fit">
                            <i class="fas fa-user text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-3 text-text-primary">Profil</h3>
                        <p class="text-4xl font-black text-accent-primary mb-4">85%</p>
                        <p class="text-text-secondary mb-6">Data profil lengkap</p>
                        <a href="profile.php" class="inline-flex items-center gap-2 text-accent-primary hover:text-accent-secondary font-semibold transition-colors group">
                            <span>Kelola Profil</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-300"></i>
                        </a>
                    </div>
                </div>

                <!-- Skills Card -->
                <div class="glass-card rounded-3xl p-8 card-hover shadow-2xl border border-border-color relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-accent-success/20 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                    <div class="relative z-10">
                        <div class="gradient-success text-white rounded-2xl p-4 mb-6 w-fit">
                            <i class="fas fa-bolt text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-3 text-text-primary">Keahlian</h3>
                        <p class="text-4xl font-black text-accent-success mb-4">12+</p>
                        <p class="text-text-secondary mb-6">Skill terdaftar</p>
                        <a href="skills.php" class="inline-flex items-center gap-2 text-accent-success hover:text-green-400 font-semibold transition-colors group">
                            <span>Kelola Keahlian</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-300"></i>
                        </a>
                    </div>
                </div>

                <!-- Projects Card -->
                <div class="glass-card rounded-3xl p-8 card-hover shadow-2xl border border-border-color relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-accent-warning/20 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                    <div class="relative z-10">
                        <div class="gradient-warning text-white rounded-2xl p-4 mb-6 w-fit">
                            <i class="fas fa-rocket text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-3 text-text-primary">Proyek</h3>
                        <p class="text-4xl font-black text-accent-warning mb-4">8</p>
                        <p class="text-text-secondary mb-6">Proyek selesai</p>
                        <a href="project.php" class="inline-flex items-center gap-2 text-accent-warning hover:text-yellow-400 font-semibold transition-colors group">
                            <span>Kelola Proyek</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-300"></i>
                        </a>
                    </div>
                </div>

                <!-- Articles Card -->
                <div class="glass-card rounded-3xl p-8 card-hover shadow-2xl border border-border-color relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-accent-primary/20 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                    <div class="relative z-10">
                        <div class="gradient-primary text-white rounded-2xl p-4 mb-6 w-fit">
                            <i class="fas fa-edit text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-3 text-text-primary">Artikel</h3>
                        <p class="text-4xl font-black text-accent-primary mb-4">24</p>
                        <p class="text-text-secondary mb-6">Artikel terpublikasi</p>
                        <a href="articles.php" class="inline-flex items-center gap-2 text-accent-primary hover:text-accent-secondary font-semibold transition-colors group">
                            <span>Kelola Artikel</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-300"></i>
                        </a>
                    </div>
                </div>

                <!-- Contact Card -->
                <div class="glass-card rounded-3xl p-8 card-hover shadow-2xl border border-border-color relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-accent-success/20 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                    <div class="relative z-10">
                        <div class="gradient-success text-white rounded-2xl p-4 mb-6 w-fit">
                            <i class="fas fa-phone text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-3 text-text-primary">Kontak</h3>
                        <p class="text-4xl font-black text-accent-success mb-4">1</p>
                        <p class="text-text-secondary mb-6">Data kontak aktif</p>
                        <a href="contact.php" class="inline-flex items-center gap-2 text-accent-success hover:text-green-400 font-semibold transition-colors group">
                            <span>Kelola Kontak</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-300"></i>
                        </a>
                    </div>
                </div>

                <!-- Messages Card -->
                <div class="glass-card rounded-3xl p-8 card-hover shadow-2xl border border-border-color relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-accent-danger/20 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                    <div class="relative z-10">
                        <div class="gradient-warning text-white rounded-2xl p-4 mb-6 w-fit bg-gradient-to-br from-accent-danger to-accent-warning">
                            <i class="fas fa-comments text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-3 text-text-primary">Pesan Masuk</h3>
                        <p class="text-4xl font-black text-accent-danger mb-4">3</p>
                        <p class="text-text-secondary mb-6">Pesan belum dibaca</p>
                        <a href="message.php" class="inline-flex items-center gap-2 text-accent-danger hover:text-red-400 font-semibold transition-colors group">
                            <span>Kelola Pesan</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-300"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistics & Management Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Portfolio Statistics -->
                <div class="glass-card rounded-3xl p-8 shadow-2xl border border-border-color">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="gradient-primary rounded-2xl p-3">
                            <i class="fas fa-chart-line text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold gradient-text">Statistik Portfolio</h2>
                            <p class="text-text-secondary">Overview data terkini</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-center justify-between p-4 bg-dark-surface/50 rounded-2xl border border-border-color/50">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-accent-primary/20 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-edit text-accent-primary"></i>
                                </div>
                                <div>
                                    <p class="font-semibold">Artikel</p>
                                    <p class="text-text-secondary text-sm">Published articles</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-accent-primary">24</p>
                                <p class="text-xs text-accent-success">+3 bulan ini</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-dark-surface/50 rounded-2xl border border-border-color/50">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-accent-success/20 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-bolt text-accent-success"></i>
                                </div>
                                <div>
                                    <p class="font-semibold">Skills</p>
                                    <p class="text-text-secondary text-sm">Technical skills</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-accent-success">15</p>
                                <p class="text-xs text-accent-success">+2 baru</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-dark-surface/50 rounded-2xl border border-border-color/50">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-accent-warning/20 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-rocket text-accent-warning"></i>
                                </div>
                                <div>
                                    <p class="font-semibold">Proyek</p>
                                    <p class="text-text-secondary text-sm">Completed projects</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-accent-warning">8</p>
                                <p class="text-xs text-accent-success">100% selesai</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-dark-surface/50 rounded-2xl border border-border-color/50">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-accent-danger/20 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-comments text-accent-danger"></i>
                                </div>
                                <div>
                                    <p class="font-semibold">Pesan Masuk</p>
                                    <p class="text-text-secondary text-sm">Contact messages</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-accent-danger">3</p>
                                <p class="text-xs text-accent-warning">Perlu respon</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Management Tips -->
                <div class="glass-card rounded-3xl p-8 shadow-2xl border border-border-color">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="gradient-success rounded-2xl p-3">
                            <i class="fas fa-lightbulb text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold gradient-text">Tips Pengelolaan</h2>
                            <p class="text-text-secondary">Panduan untuk sukses</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 bg-gradient-to-r from-accent-primary/10 to-transparent rounded-2xl border-l-4 border-accent-primary">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-user-tie text-accent-primary mt-1"></i>
                                <div>
                                    <h4 class="font-semibold mb-1">Profil Profesional</h4>
                                    <p class="text-text-secondary text-sm">Lengkapi data profil untuk memberikan kesan profesional kepada visitor.</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-gradient-to-r from-accent-success/10 to-transparent rounded-2xl border-l-4 border-accent-success">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-sync-alt text-accent-success mt-1"></i>
                                <div>
                                    <h4 class="font-semibold mb-1">Update Berkala</h4>
                                    <p class="text-text-secondary text-sm">Tambahkan skill dan proyek terbaru secara rutin untuk menunjukkan perkembangan.</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-gradient-to-r from-accent-warning/10 to-transparent rounded-2xl border-l-4 border-accent-warning">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-pen-fancy text-accent-warning mt-1"></i>
                                <div>
                                    <h4 class="font-semibold mb-1">Content Marketing</h4>
                                    <p class="text-text-secondary text-sm">Tulis artikel berkualitas untuk membangun personal branding dan expertise.</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-gradient-to-r from-accent-danger/10 to-transparent rounded-2xl border-l-4 border-accent-danger">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-reply text-accent-danger mt-1"></i>
                                <div>
                                    <h4 class="font-semibold mb-1">Responsif Communication</h4>
                                    <p class="text-text-secondary text-sm">Respon pesan masuk dengan cepat untuk membuka peluang kolaborasi.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>