<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

include 'db.php'; // Include database connection

// Statistik dashboard
$profileCount = 0;
$skillsCount = 0;
$projectsCount = 0;
$articlesCount = 0;
$contactsCount = 0;
$messagesCount = 0;

// Ambil jumlah data profil (diasumsikan hanya 1 baris di tabel profile)
$res = $conn->query("SELECT COUNT(*) as cnt FROM profile");
if ($res) {
    $row = $res->fetch_assoc();
    $profileCount = (int)$row['cnt'];
}

// Ambil jumlah skills
$res = $conn->query("SELECT COUNT(*) as cnt FROM skills");
if ($res) {
    $row = $res->fetch_assoc();
    $skillsCount = (int)$row['cnt'];
}

// Ambil jumlah proyek
$res = $conn->query("SELECT COUNT(*) as cnt FROM projects");
if ($res) {
    $row = $res->fetch_assoc();
    $projectsCount = (int)$row['cnt'];
}

// Ambil jumlah artikel
$res = $conn->query("SELECT COUNT(*) as cnt FROM articles");
if ($res) {
    $row = $res->fetch_assoc();
    $articlesCount = (int)$row['cnt'];
}

// Ambil jumlah kontak aktif (diasumsikan hanya 1 baris di tabel contact)
$res = $conn->query("SELECT COUNT(*) as cnt FROM contact");
if ($res) {
    $row = $res->fetch_assoc();
    $contactsCount = (int)$row['cnt'];
}

// Ambil jumlah pesan masuk (tanpa filter is_read)
if ($conn->query("SHOW TABLES LIKE 'contact_messages'")->num_rows > 0) {
    $res = $conn->query("SELECT COUNT(*) as cnt FROM contact_messages");
    if ($res) {
        $row = $res->fetch_assoc();
        $messagesCount = (int)$row['cnt'];
    }
}
?>

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
        html {
            /* -webkit-text-size-adjust: 100%; */
            text-size-adjust: 100%;
        }

        .glass-morphism {
            background: rgba(51, 51, 51, 0.8);
            backdrop-filter: blur(10px);
            /* -webkit-backdrop-filter: blur(10px); */
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glass-card {
            background: rgba(51, 51, 51, 0.9);
            backdrop-filter: blur(12px);
            /* -webkit-backdrop-filter: blur(12px); */
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
    <div class="flex min-h-screen relative z-10">
        <!-- Overlay (mobile only) -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden transition-opacity duration-300"></div>
        <!-- Sidebar -->
        <aside id="sidebar" class="max-w-xs sm:max-w-sm md:w-72 w-full glass-morphism border-r border-border-color flex flex-col justify-between py-4 px-2 sm:py-6 sm:px-4 fixed md:sticky top-0 h-screen z-40 sidebar-scroll overflow-hidden -left-80 md:left-0 transition-all duration-300">
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
                <a href="index.php" class="nav-item flex items-center gap-4 px-6 py-4 rounded-2xl font-semibold transition-all duration-300 bg-accent-primary/20 text-accent-primary border border-accent-primary/30">
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
                    <span>Kontak & Pesan</span>
                </a>
            </nav>

            <!-- User Profile Card -->
            <div class="mt-8 p-6 ">
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
        <main class="flex-1 p-2 sm:p-4 md:p-8 bg-dark-bg min-h-screen overflow-x-hidden">
            <!-- Hamburger (mobile only) -->
            <button id="hamburgerBtn" class="fixed top-4 left-4 z-50 md:hidden bg-dark-surface p-3 rounded-xl shadow-lg focus:outline-none focus:ring-2 focus:ring-accent-primary" aria-label="Buka sidebar">
                <span class="sr-only">Buka navigasi</span>
                <i class="fas fa-bars text-2xl text-white"></i>
            </button>
            <div class="flex flex-col md:flex-row justify-between items-center mb-12 gap-4">
                <div class="pl-16 md:pl-0 w-full">
                    <h1 class="text-3xl md:text-5xl font-bold mb-3 gradient-text">Dashboard Admin</h1>
                    <p class="text-text-secondary text-base md:text-lg">Kelola portofolio Anda dengan mudah dan profesional</p>
                </div>
            </div>

            <!-- Quick Stats Cards -->
            <div class="grid grid-cols-2 gap-2 md:gap-4 mb-8">
                <!-- Profile Card -->
                <div class="glass-card rounded-2xl p-2 md:p-4 card-hover shadow-2xl border border-border-color relative overflow-hidden min-h-[120px] md:min-h-[180px] flex flex-col justify-between items-center text-center md:items-start md:text-left">
                    <div class="absolute top-0 right-0 w-12 h-12 md:w-20 md:h-20 bg-gradient-to-br from-accent-primary/20 to-transparent rounded-full -translate-y-6 translate-x-6 md:-translate-y-10 md:translate-x-10"></div>
                    <div class="relative z-10 w-full flex flex-col items-center md:items-start">
                        <div class="gradient-primary text-white rounded-2xl p-2 md:p-3 mb-2 md:mb-4 w-fit mx-auto md:mx-0">
                            <i class="fas fa-user text-base md:text-xl"></i>
                        </div>
                        <h3 class="text-base md:text-lg font-bold mb-1 md:mb-2 text-text-primary">Profil</h3>
                        <p class="text-lg md:text-2xl font-black text-accent-primary mb-1 md:mb-2"></p>
                        <p class="text-xs md:text-sm text-text-secondary mb-2 md:mb-4">Data profil </p>
                        <a href="profile.php" class="inline-flex items-center gap-2 text-accent-primary hover:text-accent-secondary font-semibold transition-colors group text-xs md:text-sm">
                            <span>Kelola Profil</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-300 text-xs md:text-base"></i>
                        </a>
                    </div>
                </div>

                <!-- Skills Card -->
                <div class="glass-card rounded-2xl p-2 md:p-4 card-hover shadow-2xl border border-border-color relative overflow-hidden min-h-[120px] md:min-h-[180px] flex flex-col justify-between items-center text-center md:items-start md:text-left">
                    <div class="absolute top-0 right-0 w-12 h-12 md:w-20 md:h-20 bg-gradient-to-br from-accent-success/20 to-transparent rounded-full -translate-y-6 translate-x-6 md:-translate-y-10 md:translate-x-10"></div>
                    <div class="relative z-10 w-full flex flex-col items-center md:items-start">
                        <div class="gradient-success text-white rounded-2xl p-2 md:p-3 mb-2 md:mb-4 w-fit mx-auto md:mx-0">
                            <i class="fas fa-bolt text-base md:text-xl"></i>
                        </div>
                        <h3 class="text-base md:text-lg font-bold mb-1 md:mb-2 text-text-primary">Keahlian</h3>
                        <p class="text-lg md:text-2xl font-black text-accent-success mb-1 md:mb-2"><?php echo $skillsCount; ?>+</p>
                        <p class="text-xs md:text-sm text-text-secondary mb-2 md:mb-4">Skill terdaftar</p>
                        <a href="skills.php" class="inline-flex items-center gap-2 text-accent-success hover:text-green-400 font-semibold transition-colors group text-xs md:text-sm">
                            <span>Kelola Keahlian</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-300 text-xs md:text-base"></i>
                        </a>
                    </div>
                </div>

                <!-- Projects Card -->
                <div class="glass-card rounded-2xl p-2 md:p-4 card-hover shadow-2xl border border-border-color relative overflow-hidden min-h-[120px] md:min-h-[180px] flex flex-col justify-between items-center text-center md:items-start md:text-left">
                    <div class="absolute top-0 right-0 w-12 h-12 md:w-20 md:h-20 bg-gradient-to-br from-accent-primary/20 to-transparent rounded-full -translate-y-6 translate-x-6 md:-translate-y-10 md:translate-x-10"></div>
                    <div class="relative z-10 w-full flex flex-col items-center md:items-start">
                        <div class="gradient-primary text-white rounded-2xl p-2 md:p-3 mb-2 md:mb-4 w-fit mx-auto md:mx-0">
                            <i class="fas fa-rocket text-base md:text-xl"></i>
                        </div>
                        <h3 class="text-base md:text-lg font-bold mb-1 md:mb-2 text-text-primary">Proyek</h3>
                        <p class="text-lg md:text-2xl font-black text-accent-primary mb-1 md:mb-2"><?php echo $projectsCount; ?></p>
                        <p class="text-xs md:text-sm text-text-secondary mb-2 md:mb-4">Proyek selesai</p>
                        <a href="project.php" class="inline-flex items-center gap-2 text-accent-primary hover:text-accent-secondary font-semibold transition-colors group text-xs md:text-sm">
                            <span>Kelola Proyek</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-300 text-xs md:text-base"></i>
                        </a>
                    </div>
                </div>

                <!-- Articles Card -->
                <div class="glass-card rounded-2xl p-2 md:p-4 card-hover shadow-2xl border border-border-color relative overflow-hidden min-h-[120px] md:min-h-[180px] flex flex-col justify-between items-center text-center md:items-start md:text-left">
                    <div class="absolute top-0 right-0 w-12 h-12 md:w-20 md:h-20 bg-gradient-to-br from-accent-primary/20 to-transparent rounded-full -translate-y-6 translate-x-6 md:-translate-y-10 md:translate-x-10"></div>
                    <div class="relative z-10 w-full flex flex-col items-center md:items-start">
                        <div class="gradient-primary text-white rounded-2xl p-2 md:p-3 mb-2 md:mb-4 w-fit mx-auto md:mx-0">
                            <i class="fas fa-edit text-base md:text-xl"></i>
                        </div>
                        <h3 class="text-base md:text-lg font-bold mb-1 md:mb-2 text-text-primary">Artikel</h3>
                        <p class="text-lg md:text-2xl font-black text-accent-primary mb-1 md:mb-2"><?php echo $articlesCount; ?></p>
                        <p class="text-xs md:text-sm text-text-secondary mb-2 md:mb-4">Artikel terpublikasi</p>
                        <a href="articles.php" class="inline-flex items-center gap-2 text-accent-primary hover:text-accent-secondary font-semibold transition-colors group text-xs md:text-sm">
                            <span>Kelola Artikel</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-300 text-xs md:text-base"></i>
                        </a>
                    </div>
                </div>

                <!-- Contact Card -->
                <div class="glass-card rounded-2xl p-2 md:p-4 card-hover shadow-2xl border border-border-color relative overflow-hidden min-h-[120px] md:min-h-[180px] flex flex-col justify-between items-center text-center md:items-start md:text-left">
                    <div class="absolute top-0 right-0 w-12 h-12 md:w-20 md:h-20 bg-gradient-to-br from-accent-success/20 to-transparent rounded-full -translate-y-6 translate-x-6 md:-translate-y-10 md:translate-x-10"></div>
                    <div class="relative z-10 w-full flex flex-col items-center md:items-start">
                        <div class="gradient-success text-white rounded-2xl p-2 md:p-3 mb-2 md:mb-4 w-fit mx-auto md:mx-0">
                            <i class="fas fa-phone text-base md:text-xl"></i>
                        </div>
                        <h3 class="text-base md:text-lg font-bold mb-1 md:mb-2 text-text-primary">Kontak</h3>
                        <p class="text-lg md:text-2xl font-black text-accent-success mb-1 md:mb-2"><?php echo $contactsCount; ?></p>
                        <p class="text-xs md:text-sm text-text-secondary mb-2 md:mb-4">Data kontak aktif</p>
                        <a href="contact.php" class="inline-flex items-center gap-2 text-accent-success hover:text-green-400 font-semibold transition-colors group text-xs md:text-sm">
                            <span>Kelola Kontak</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-300 text-xs md:text-base"></i>
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        // Sidebar responsive toggle
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const hamburger = document.getElementById('hamburgerBtn');

        function openSidebar() {
            sidebar.classList.remove('-left-80');
            sidebar.classList.add('left-0');
            overlay.classList.remove('hidden');
            hamburger.classList.add('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-left-80');
            sidebar.classList.remove('left-0');
            overlay.classList.add('hidden');
            hamburger.classList.remove('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        hamburger.addEventListener('click', openSidebar);
        overlay.addEventListener('click', closeSidebar);
        // Tutup sidebar jika klik link di sidebar (mobile)
        sidebar.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 768) closeSidebar();
            });
        });
        // Tutup sidebar jika resize ke desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) closeSidebar();
        });
        // Tutup sidebar jika klik di luar sidebar (mobile)
        document.addEventListener('click', function(e) {
            if (window.innerWidth >= 768) return;
            if (!sidebar.contains(e.target) && !hamburger.contains(e.target) && !overlay.classList.contains('hidden')) {
                closeSidebar();
            }
        });
    </script>
</body>

</html>