<?php
include 'admin/db.php';
// Ambil data profile utama
$profileData = $conn->query("SELECT * FROM profile LIMIT 1")->fetch_assoc();
// Ambil gambar profile aktif
$profileImg = $conn->query("SELECT * FROM profile_images WHERE is_active = 1 LIMIT 1")->fetch_assoc();
$profile = [
    'name' => $profileData['full_name'] ?? 'Nama Anda',
    'profession' => $profileData['profession'] ?? 'Profesi',
    'photo' => isset($profileImg['filename']) ? 'images/' . $profileImg['filename'] : 'images/Profile.jpg',
];
// Ambil data about
$aboutData = $conn->query("SELECT * FROM about LIMIT 1")->fetch_assoc();

// Ambil data education
$educations = [];
$eduResult = $conn->query("SELECT * FROM education ORDER BY id DESC");
if ($eduResult && $eduResult->num_rows > 0) {
    while ($row = $eduResult->fetch_assoc()) {
        $educations[] = $row;
    }
}
// Ambil data organisasi
$organizations = [];
$orgResult = $conn->query("SELECT * FROM organization ORDER BY id DESC");
if ($orgResult && $orgResult->num_rows > 0) {
    while ($row = $orgResult->fetch_assoc()) {
        $organizations[] = $row;
    }
}
// Ambil data skills
$skills = [];
$skillResult = $conn->query("SELECT * FROM skills ORDER BY id DESC");
if ($skillResult && $skillResult->num_rows > 0) {
    while ($row = $skillResult->fetch_assoc()) {
        $skills[] = $row;
    }
}
// Ambil data projects
$projects = [];
$projectResult = $conn->query("SELECT * FROM projects ORDER BY id DESC");
if ($projectResult && $projectResult->num_rows > 0) {
    while ($row = $projectResult->fetch_assoc()) {
        $projects[] = $row;
    }
}
// Ambil data articles
$articles = [];
$articleResult = $conn->query("SELECT * FROM articles ORDER BY id DESC");
if ($articleResult && $articleResult->num_rows > 0) {
    while ($row = $articleResult->fetch_assoc()) {
        $articles[] = $row;
    }
}
// Ambil data kontak
$contact = $conn->query("SELECT * FROM contact LIMIT 1")->fetch_assoc();
// Ambil data social media
$socials = [];
$socialResult = $conn->query("SELECT * FROM social_media ORDER BY id DESC");
if ($socialResult && $socialResult->num_rows > 0) {
    while ($row = $socialResult->fetch_assoc()) {
        $socials[] = $row;
    }
}
// Ambil data activity
$activities = [];
$activityResult = $conn->query("SELECT * FROM activity ORDER BY id DESC");
if ($activityResult && $activityResult->num_rows > 0) {
    while ($row = $activityResult->fetch_assoc()) {
        $activities[] = $row;
    }
}


?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Raja AryansahPutra</title>
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
                        'pulse-glow': 'pulseGlow 2s ease-in-out infinite alternate'
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
                                boxShadow: '0 0 5px rgba(74, 93, 35, 0.3)'
                            },
                            '100%': {
                                boxShadow: '0 0 20px rgba(74, 93, 35, 0.6)'
                            }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(245, 245, 245, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>

<body class="bg-dark-bg text-light-surface min-h-screen">
    <nav class="bg-gray-900 px-4 py-3 flex items-center justify-between md:hidden">
        <div class="text-white font-bold text-lg">Portofolio Admin</div>
        <button id="navToggle" class="text-white focus:outline-none">
            <!-- Hamburger icon -->
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </nav>
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 glass-effect">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="text-2xl font-bold text-warm-wood animate-pulse-glow">
                    Portfolio
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="#home" class="hover:text-warm-wood transition-colors duration-300 relative group">
                        Beranda
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-warm-wood transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="#about" class="hover:text-warm-wood transition-colors duration-300 relative group">
                        Tentang
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-warm-wood transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="#projects" class="hover:text-warm-wood transition-colors duration-300 relative group">
                        Proyek
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-warm-wood transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="index.php#articles" class="hover:text-warm-wood transition-colors duration-300 relative group">
                        Artikel
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-warm-wood transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="#contact" class="hover:text-warm-wood transition-colors duration-300 relative group">
                        Kontak
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-warm-wood transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </div>
                <button class="md:hidden text-light-surface focus:outline-none" onclick="toggleMobileMenu()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden md:hidden mt-4 space-y-2">
                <a href="#home" class="block py-2 hover:text-warm-wood transition-colors duration-300">Beranda</a>
                <a href="#about" class="block py-2 hover:text-warm-wood transition-colors duration-300">Tentang</a>
                <a href="#projects" class="block py-2 hover:text-warm-wood transition-colors duration-300">Proyek</a>
                <a href="#article" class="block py-2 hover:text-warm-wood transition-colors duration-300">Artikel</a>
                <a href="#contact" class="block py-2 hover:text-warm-wood transition-colors duration-300">Kontak</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="min-h-screen flex items-center justify-center relative overflow-hidden">
        <!-- Background Gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-dark-bg via-accent-green/20 to-dark-bg"></div>

        <!-- Container with Flexbox Layout -->
        <div class="container mx-auto px-6 flex flex-col md:flex-row items-center justify-center relative z-10">
            <!-- Photo on the Left -->
            <div class="md:w-80 mb-8 md:mb-0 animate-slide-up">
                <img
                    src="<?php echo htmlspecialchars($profile['photo']); ?>"
                    alt="Foto <?php echo htmlspecialchars($profile['name']); ?>"
                    class="w-64 h-64 rounded-full object-cover shadow-lg border-4 border-warm-wood" />
            </div>

            <!-- Text Content on the Right -->
            <div class="md:w-1/2 text-center md:text-left animate-slide-up">
                <h1 class="text-5xl md:text-7xl font-bold mb-10 text-shadow">
                    <span class="text-light-surface">Hi, Saya</span>
                    <span class="text-warm-wood block animate-float"><?php echo htmlspecialchars($profile['name']); ?></span>
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-gray-300 max-w-2xl mx-auto md:mx-0 animate-fade-in">
                    <?php echo htmlspecialchars($profile['profession']); ?>
                </p>
                <div class="flex flex-col md:flex-row md:space-x-4 space-y-4 md:space-y-0 items-center md:justify-start justify-center animate-slide-up">
                    <button onclick="document.getElementById('projects').scrollIntoView({ behavior: 'smooth' });" class="bg-warm-wood text-dark-bg w-full md:w-auto px-8 py-3 rounded-lg font-semibold hover:bg-opacity-90 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                        Lihat Project
                    </button>
                    <button onclick="document.getElementById('contact').scrollIntoView({ behavior: 'smooth' });" class="border-2 border-warm-wood text-warm-wood w-full md:w-auto px-8 py-3 rounded-lg font-semibold hover:bg-warm-wood hover:text-dark-bg transition-all duration-300">
                        Hubungi Saya
                    </button>
                </div>
            </div>
        </div>

        <!-- Floating Elements -->
        <div class="absolute top-20 left-10 w-4 h-4 bg-warm-wood rounded-full animate-float opacity-60"></div>
        <div class="absolute bottom-32 right-16 w-6 h-6 bg-accent-green rounded-full animate-float opacity-40" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/3 right-8 w-3 h-3 bg-light-surface rounded-full animate-float opacity-30" style="animation-delay: 4s;"></div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 relative">
        <div class="container mx-auto px-6">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-4xl md:text-5xl font-bold text-center mb-16 text-warm-wood">Tentang Saya</h2>

                <!-- Main About Content -->
                <div class="grid md:grid-cols-2 gap-12 items-center mb-16">
                    <div class="space-y-6 animate-slide-up">
                        <p class="text-lg text-justify text-gray-300 leading-relaxed">
                            <?php echo nl2br(htmlspecialchars($aboutData['main_description'] ?? 'Belum ada deskripsi utama.')); ?>
                        </p>
                        <p class="text-lg text-justify text-gray-300 leading-relaxed">
                            <?php echo nl2br(htmlspecialchars($aboutData['additional_description'] ?? 'Belum ada deskripsi tambahan.')); ?>
                        </p>
                    </div>
                    <div class="flex justify-center animate-slide-up">
                        <img src="<?php echo htmlspecialchars($profile['photo']); ?>" alt="Ilustrasi <?php echo htmlspecialchars($profile['name']); ?>" class="w-80 h-80 rounded-lg shadow-lg" />
                    </div>
                </div>

                <!-- Education & Organization Section Side by Side -->
                <div class="mt-20">
                    <div class="grid md:grid-cols-2 gap-12">
                        <!-- Pendidikan -->
                        <div>
                            <h3 class="text-3xl font-bold text-center mb-12 text-warm-wood">Pendidikan</h3>
                            <div class="max-w-2xl mx-auto">
                                <?php if (count($educations) > 0): foreach ($educations as $edu): ?>
                                        <div class="glass-effect min-h-48 p-8 rounded-2xl animate-fade-in mb-6">
                                            <div class="flex flex-col items-start justify-between">
                                                <div class="flex-1">
                                                    <h4 class="text-xl font-semibold text-light-surface mb-2">
                                                        <?php echo htmlspecialchars($edu['institution']); ?>
                                                    </h4>
                                                    <p class="text-warm-wood font-medium mb-2">
                                                        <?php echo htmlspecialchars($edu['program']); ?>
                                                    </p>
                                                    <p class="text-gray-300 text-sm">
                                                        <?php echo htmlspecialchars($edu['description']); ?>
                                                    </p>
                                                </div>
                                                <div class="mt-4">
                                                    <div class="bg-warm-wood/20 px-4 py-2 rounded-full">
                                                        <span class="text-warm-wood font-medium text-sm">Aktif</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach;
                                else: ?>
                                    <div class="text-gray-400 text-center">Belum ada data pendidikan.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- Organisasi -->
                        <div>
                            <h3 class="text-3xl font-bold text-center mb-12 text-warm-wood">Organisasi</h3>
                            <div class="max-w-2xl mx-auto">
                                <?php if (count($organizations) > 0): foreach ($organizations as $org): ?>
                                        <div class="glass-effect min-h-48 p-8 rounded-2xl animate-fade-in mb-6">
                                            <div class="flex flex-col items-start justify-between">
                                                <div class="flex-1">
                                                    <h4 class="text-xl font-semibold text-light-surface mb-2">
                                                        <?php echo htmlspecialchars($org['name']); ?>
                                                    </h4>
                                                    <p class="text-warm-wood font-medium mb-2">
                                                        <?php echo htmlspecialchars($org['position']); ?>
                                                    </p>
                                                    <p class="text-gray-300 text-sm">
                                                        <?php echo htmlspecialchars($org['description']); ?>
                                                    </p>
                                                </div>
                                                <div class="mt-4">
                                                    <div class="bg-warm-wood/20 px-4 py-2 rounded-full">
                                                        <span class="text-warm-wood font-medium text-sm">Aktif</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach;
                                else: ?>
                                    <div class="text-gray-400 text-center">Belum ada data organisasi.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- aktivitas -->
                <div class="container mx-auto px-6 py-10">
                    <h2 class="text-3xl md:text-4xl font-bold text-center mb-10 text-warm-wood">Aktivitas Terbaru</h2>
                    <div class="max-w-4xl mx-auto">
                        <?php if (count($activities) > 0): ?>
                            <div class="grid md:grid-cols-2 gap-6">
                                <?php foreach ($activities as $act): ?>
                                    <div class="glass-effect p-6 rounded-xl border border-warm-wood/30 shadow-lg hover:shadow-xl transition-shadow duration-300 flex items-start space-x-4 group">
                                        <!-- Dot Indicator -->
                                        <span class="w-3 h-3 mt-2 bg-accent-green rounded-full flex-shrink-0 transition-transform duration-300 group-hover:scale-125"></span>

                                        <!-- Activity Content -->
                                        <div>
                                            <p class="text-light-surface font-semibold text-lg mb-1 transition-colors duration-300 group-hover:text-accent-green">
                                                <?php echo htmlspecialchars($act['name']); ?>
                                            </p>
                                            <span class="text-gray-400 text-sm line-clamp-2">
                                                <?php echo htmlspecialchars($act['description']); ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="glass-effect p-8 rounded-2xl shadow-inner text-center border border-warm-wood/20 animate-fade-in">
                                <p class="text-gray-400 italic">Belum ada data aktivitas.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
    </section>

    <style>
        /* Custom CSS for animations and effects */
        .glass-effect {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .animate-slide-up {
            animation: slideUp 0.6s ease-out;
        }

        .animate-fade-in {
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Assuming these colors are defined in your CSS */
        .text-warm-wood {
            color: #D4A574;
            /* Adjust color as needed */
        }

        .text-light-surface {
            color: #F5F5F5;
            /* Adjust color as needed */
        }

        .bg-warm-wood {
            background-color: #D4A574;
            /* Adjust color as needed */
        }
    </style>

    <!-- Skills Section Dinamis -->
    <?php include 'skills.php' ?>


    <!-- Projects Section Dinamis -->
    <section id="projects" class="py-20 bg-gradient-to-b from-dark-bg to-accent-green/10">
        <div class="container mx-auto px-6">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-16">
                <div class="w-full flex justify-center">
                    <h2 class="text-4xl md:text-5xl font-bold text-warm-wood mb-4 sm:mb-0 text-center">Proyek Terbaru</h2>
                </div>
            </div>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <?php if (count($projects) > 0):
                // Tampilkan hanya 3 proyek terbaru untuk halaman index
                $displayProjects = array_slice($projects, 0, 3);
                foreach ($displayProjects as $project): ?>
                    <div class="glass-effect rounded-2xl p-6 transform hover:scale-105 transition-all duration-300 hover:shadow-2xl group">
                        <?php if (!empty($project['image'])): ?>
                            <div class="flex justify-center mb-6">
                                <img src="<?php echo htmlspecialchars($project['image']); ?>"
                                    alt="<?php echo htmlspecialchars($project['title']); ?>"
                                    class="w-full h-40 object-contain rounded-lg shadow border-2 border-warm-wood transition-transform duration-300 group-hover:scale-105 bg-glass" />
                            </div>
                        <?php endif; ?>
                        <h3 class="text-xl font-bold mb-3 text-light-surface"><?php echo htmlspecialchars($project['title']); ?></h3>
                        <p class="text-gray-300 mb-4 text-justify"><?php echo htmlspecialchars($project['description']); ?></p>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach (explode(',', $project['technologies']) as $tech): ?>
                                <span class="bg-dark-bg px-3 py-1 rounded-full text-xs"><?php echo htmlspecialchars(trim($tech)); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-4 flex gap-2">
                            <?php if (!empty($project['github'])): ?>
                                <a href="<?php echo htmlspecialchars($project['github']); ?>" target="_blank" class="text-warm-wood underline hover:text-accent-green transition-colors">Github</a>
                            <?php endif; ?>
                            <?php if (!empty($project['demo'])): ?>
                                <a href="<?php echo htmlspecialchars($project['demo']); ?>" target="_blank" class="text-warm-wood underline hover:text-accent-green transition-colors">Demo</a>
                            <?php endif; ?>
                        </div>

                    </div>
                <?php endforeach;
            else: ?>
                <div class="text-gray-400 text-center w-full col-span-full">Belum ada data proyek.</div>
            <?php endif; ?>
        </div>
        <?php if (count($projects) > 3): ?>
            <div class="w-full flex justify-center mt-8">
                <button
                    onclick="openProjectsModal()"
                    class="bg-light-surface/20 backdrop-blur-sm border border-light-surface/30 text-light-surface transition-all duration-300 text-sm font-medium py-2 px-6 rounded-full shadow-md hover:shadow-lg transform hover:-translate-y-0.5 cursor-pointer">
                    Lihat Semua (<?php echo count($projects); ?>)
                </button>
            </div>
        <?php endif; ?>
        </div>
    </section>

    <!-- Modal All Projects -->
    <div id="projectsModal" class="fixed inset-0 z-50 hidden overflow-y-auto transition-all duration-300">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Background Overlay -->
            <div class="fixed inset-0 bg-dark-bg/80 backdrop-blur-sm transition-opacity" onclick="closeProjectsModal()"></div>

            <!-- Modal Content -->
            <div class="inline-block w-full max-w-6xl p-6 my-8 overflow-hidden align-middle transition-all transform rounded-3xl shadow-2xl border-2 border-warm-wood bg-dark-bg/95 backdrop-blur-md animate-fade-in"
                style="animation: fadeInModal 0.5s cubic-bezier(.4,0,.2,1)">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6 border-b border-warm-wood/40 pb-3">
                    <h3 class="text-2xl sm:text-3xl font-bold text-warm-wood">Semua Proyek</h3>
                    <button onclick="closeProjectsModal()" class="p-1 text-gray-400 hover:text-accent-green transition-colors duration-300 focus:outline-none rounded-full hover:bg-dark-bg/50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-7">
                    <?php foreach ($projects as $index => $project): ?>
                        <div class="glass-effect rounded-2xl p-6 border-2 border-warm-wood/60 shadow-lg hover:shadow-2xl hover:scale-[1.03] transition-all duration-300 group animate-fade-in"
                            style="animation-delay: <?php echo ($index * 0.06); ?>s; animation-fill-mode: both;">
                            <!-- Image -->
                            <?php if (!empty($project['image'])): ?>
                                <div class="mb-4 aspect-video rounded-lg overflow-hidden border border-warm-wood/30 group-hover:shadow-inner transition-shadow bg-dark-bg/40">
                                    <img src="<?php echo htmlspecialchars($project['image']); ?>"
                                        alt="<?php echo htmlspecialchars($project['title']); ?>"
                                        class="w-full h-40 object-contain rounded-lg transition-transform duration-300 group-hover:scale-105" />
                                </div>
                            <?php endif; ?>

                            <!-- Title -->
                            <h4 class="text-xl font-semibold mb-2 text-light-surface truncate">
                                <?php echo htmlspecialchars($project['title']); ?>
                            </h4>

                            <!-- Description -->
                            <p class="text-gray-300 text-sm mb-4 line-clamp-3">
                                <?php echo htmlspecialchars($project['description']); ?>
                            </p>

                            <!-- Tags -->
                            <div class="flex flex-wrap gap-1.5 mb-5">
                                <?php foreach (array_slice(explode(',', $project['technologies']), 0, 3) as $tech): ?>
                                    <span class="px-2 py-1 text-xs rounded-full bg-dark-bg border border-warm-wood/40 text-light-surface">
                                        <?php echo htmlspecialchars(trim($tech)); ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>

                            <!-- Buttons -->
                            <div class="flex gap-2 mt-auto">
                                <?php if (!empty($project['github'])): ?>
                                    <a href="<?php echo htmlspecialchars($project['github']); ?>" target="_blank"
                                        class="flex-1 text-center bg-warm-wood text-dark-bg py-2 px-3 rounded text-sm font-medium hover:bg-accent-green hover:text-white transition-colors duration-300 shadow">
                                        Github
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($project['demo'])): ?>
                                    <a href="<?php echo htmlspecialchars($project['demo']); ?>" target="_blank"
                                        class="flex-1 text-center bg-accent-green text-dark-bg py-2 px-3 rounded text-sm font-medium hover:bg-warm-wood hover:text-white transition-colors duration-300 shadow">
                                        Demo
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>


    <!-- Article Section Dinamis -->
    <section id="articles" class="py-20 bg-gradient-to-b from-dark-bg to-accent-green/10">
        <div class="container mx-auto px-6">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-16">
                <div class="w-full flex justify-center">
                    <h2 class="text-4xl md:text-5xl font-bold text-warm-wood mb-4 sm:mb-0 text-center">Artikel Terbaru</h2>
                </div>
            </div>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <?php if (count($articles) > 0):
                // Tampilkan hanya 3 artikel terbaru untuk halaman index
                $displayArticles = array_slice($articles, 0, 3);
                foreach ($displayArticles as $article): ?>
                    <div class="glass-effect rounded-2xl p-6 transform hover:scale-105 transition-all duration-300 hover:shadow-2xl group flex flex-col h-full">
                        <?php if (!empty($article['image'])): ?>
                            <div class="flex justify-center mb-6">
                                <img src="<?php echo htmlspecialchars($article['image']); ?>"
                                    alt="<?php echo htmlspecialchars($article['title']); ?>"
                                    class="w-full h-40 object-cover rounded-lg shadow border-2 border-warm-wood transition-transform duration-300 group-hover:scale-105 bg-glass" />
                            </div>
                        <?php endif; ?>
                        <h3 class="text-xl font-bold mb-3 text-light-surface"><?php echo htmlspecialchars($article['title']); ?></h3>
                        <p class="text-gray-300 mb-4 text-justify flex-1"><?php echo htmlspecialchars($article['excerpt']); ?></p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="bg-dark-bg px-3 py-1 rounded-full text-xs"><?php echo date('d M Y', strtotime($article['publish_date'])); ?></span>
                        </div>
                        <div class="mt-auto">
                            <div class="flex items-end justify-between h-full">
                                <a href="articles.php?id=<?php echo $article['id']; ?>"
                                    class="flex items-center gap-2 bg-warm-wood text-dark-bg rounded-lg px-4 py-2 font-medium shadow hover:bg-accent-green hover:text-white transition-all duration-300 mt-4"
                                    style="position: absolute; right: 1.25rem; bottom: 1.25rem;">
                                    Lihat Detail
                                    <span class="ml-2 text-lg"><i class="fas fa-arrow-right"></i></span>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach;
            else: ?>
                <div class="text-gray-400 text-center w-full col-span-full">Belum ada data artikel.</div>
            <?php endif; ?>
        </div>
        <?php if (count($articles) > 3): ?>
            <div class="w-full flex justify-center mt-8">
                <button
                    onclick="openArticlesModal()"
                    class="bg-light-surface/20 backdrop-blur-sm border border-light-surface/30 text-light-surface transition-all duration-300 text-sm font-medium py-2 px-6 rounded-full shadow-md hover:shadow-lg transform hover:-translate-y-0.5 cursor-pointer">
                    Lihat Semua (<?php echo count($articles); ?>)
                </button>
            </div>
        <?php endif; ?>
        </div>
    </section>

    <!-- Modal All Articles -->
    <div id="articlesModal" class="fixed inset-0 z-50 hidden overflow-y-auto transition-all duration-300">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Background Overlay -->
            <div class="fixed inset-0 bg-dark-bg/80 backdrop-blur-sm transition-opacity" onclick="closeArticlesModal()"></div>

            <!-- Modal Content -->
            <div class="inline-block w-full max-w-6xl p-6 my-8 overflow-hidden align-middle transition-all transform rounded-3xl shadow-2xl border-2 border-warm-wood bg-dark-bg/95 backdrop-blur-md animate-fade-in"
                style="animation: fadeInModal 0.5s cubic-bezier(.4,0,.2,1)">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6 border-b border-warm-wood/40 pb-3">
                    <h3 class="text-2xl sm:text-3xl font-bold text-warm-wood">Semua Artikel</h3>
                    <button onclick="closeArticlesModal()" class="p-1 text-gray-400 hover:text-accent-green transition-colors duration-300 focus:outline-none rounded-full hover:bg-dark-bg/50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-7">
                    <?php foreach ($articles as $index => $article): ?>
                        <div class="glass-effect rounded-2xl p-6 border-2 border-warm-wood/60 shadow-lg hover:shadow-2xl hover:scale-[1.03] transition-all duration-300 group animate-fade-in flex flex-col h-full"
                            style="animation-delay: <?php echo ($index * 0.06); ?>s; animation-fill-mode: both;">
                            <?php if (!empty($article['image'])): ?>
                                <div class="mb-4 aspect-video rounded-lg overflow-hidden border border-warm-wood/30 group-hover:shadow-inner transition-shadow bg-dark-bg/40 flex items-center justify-center">
                                    <img src="<?php echo htmlspecialchars($article['image']); ?>"
                                        alt="<?php echo htmlspecialchars($article['title']); ?>"
                                        class="w-full h-40 object-cover rounded-lg transition-transform duration-300 group-hover:scale-105" />
                                </div>
                            <?php endif; ?>
                            <h4 class="text-xl font-semibold mb-2 text-light-surface truncate">
                                <?php echo htmlspecialchars($article['title']); ?>
                            </h4>
                            <p class="text-gray-300 text-sm mb-4 line-clamp-3 flex-1">
                                <?php echo htmlspecialchars($article['excerpt']); ?>
                            </p>
                            <div class="flex flex-wrap gap-1.5 mb-5">
                                <span class="px-2 py-1 text-xs rounded-full bg-dark-bg border border-warm-wood/40 text-light-surface">
                                    <?php echo date('d M Y', strtotime($article['publish_date'])); ?>
                                </span>
                            </div>
                            <div class="mt-auto">
                                <a href="articles.php?id=<?php echo $article['id']; ?>" class="flex-1 text-center bg-warm-wood rounded-lg text-dark-bg py-2 px-3 rounded text-sm font-medium hover:bg-accent-green hover:text-white transition-colors duration-300 shadow block">Lihat Detail</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Section Sederhana -->
    <section id="contact" class="py-20 px-4 bg-dark-bg">
        <div class="max-w-3xl mx-auto flex flex-col items-center">
            <!-- Tengah: Info Kontak & Value per Bulan -->
            <div class="glass-effect p-8 rounded-2xl shadow-lg w-full mb-10">
                <h2 class="text-3xl md:text-4xl font-bold mb-6 text-warm-wood text-center">Kontak Saya</h2>
                <div class="space-y-4 mb-8">
                    <div class="flex items-center gap-3 ">
                        <span class="w-8 h-8 flex items-center justify-center bg-warm-wood rounded-full"><svg class="w-5 h-5 text-dark-bg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26c.67.36 1.45.36 2.12 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg></span>
                        <span class="text-light-surface text-lg"><?php echo htmlspecialchars($contact['email'] ?? '-'); ?></span>
                    </div>
                    <div class="flex items-center gap-3 ">
                        <span class="w-8 h-8 flex items-center justify-center bg-warm-wood rounded-full"><svg class="w-5 h-5 text-dark-bg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg></span>
                        <span class="text-light-surface text-lg"><?php echo htmlspecialchars($contact['phone'] ?? '-'); ?></span>
                    </div>
                    <div class="flex items-center gap-3 ">
                        <span class="w-8 h-8 flex items-center justify-center bg-warm-wood rounded-full"><svg class="w-5 h-5 text-dark-bg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg></span>
                        <span class="text-light-surface text-lg"><?php echo htmlspecialchars($contact['location'] ?? '-'); ?></span>
                    </div>
                </div>
                <div class="mb-8 glass-effect p-6 rounded-2xl shadow-lg text-center">
                    <div class="text-warm-wood font-semibold text-lg mb-1">Value per Bulan</div>
                    <div class="text-2xl font-bold text-light-surface">Rp <?php echo number_format($profileData['value_per_month'] ?? 0, 0, ',', '.'); ?>,-</div>
                    <div class="text-xs text-gray-400 mt-1">*Harga rata-rata jasa/bisnis per bulan</div>
                </div>
                <div class="text-center mb-6">
                    <p class="text-lg text-gray-300">Ingin berdiskusi lebih lanjut? Silakan kirim pesan melalui form di bawah ini.</p>
                </div>
                <div class="flex justify-center mt-8">
                    <button onclick="openContactModal()" class="bg-warm-wood text-dark-bg px-8 py-3 rounded-lg font-semibold hover:bg-opacity-90 transform hover:scale-105 transition-all duration-300 shadow-lg w-full md:w-auto">
                        Kirim Pesan
                    </button>
                </div>
            </div>

        </div>
    </section>

    <!-- Modal Contact Form -->
    <div id="contactModal" class="fixed inset-0 z-50 hidden overflow-y-auto transition-all duration-300">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Background Overlay -->
            <div class="fixed inset-0 bg-dark-bg/80 backdrop-blur-sm transition-opacity" onclick="closeContactModal()"></div>
            <!-- Modal Content -->
            <div class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden align-middle transition-all transform rounded-3xl shadow-2xl border-2 border-warm-wood bg-dark-bg/95 backdrop-blur-md animate-fade-in relative"
                style="animation: fadeInModal 0.5s cubic-bezier(.4,0,.2,1)">
                <button onclick="closeContactModal()" class="absolute top-3 right-3 p-1 text-gray-400 hover:text-accent-green transition-colors duration-300 focus:outline-none rounded-full hover:bg-dark-bg/50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <h2 class="text-2xl font-bold text-warm-wood mb-6 text-center">Kirim Pesan</h2>
                <form method="post" action="#contact" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium mb-2 text-light-surface">Nama</label>
                        <input type="text" name="contact_name" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-3 text-light-surface focus:outline-none focus:ring-2 focus:ring-warm-wood focus:border-transparent transition-all duration-300" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 text-light-surface">Email</label>
                        <input type="email" name="contact_email" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-3 text-light-surface focus:outline-none focus:ring-2 focus:ring-warm-wood focus:border-transparent transition-all duration-300" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 text-light-surface">Pesan</label>
                        <textarea name="contact_message" rows="5" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-3 text-light-surface focus:outline-none focus:ring-2 focus:ring-warm-wood focus:border-transparent transition-all duration-300" required></textarea>
                    </div>
                    <div class="flex justify-center">
                        <button type="submit" name="send_message" class="bg-warm-wood text-dark-bg px-8 py-3 rounded-lg font-semibold hover:bg-opacity-90 transform hover:scale-105 transition-all duration-300 shadow-lg w-full md:w-auto">
                            Kirim Pesan
                        </button>
                    </div>
                </form>
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
                    $name = $conn->real_escape_string($_POST['contact_name']);
                    $email = $conn->real_escape_string($_POST['contact_email']);
                    $message = $conn->real_escape_string($_POST['contact_message']);
                    $conn->query("INSERT INTO contact_messages (name, email, message, created_at) VALUES ('$name', '$email', '$message', NOW())");
                    echo '<div class="mt-4 text-green-400 text-center">Pesan berhasil dikirim!</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Font Awesome CDN for icons (if not already included) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-dark-bg to-accent-green/20 py-8 border-t border-gray-700">
        <div class="container mx-auto px-6 text-center">
            <p class="text-gray-400 mb-4">© 2025 Portfolio. Raja aryansahPutra.</p>
            <div class="flex justify-center space-x-6">
                <?php
                // Tampilkan icon sosial media dari $socials
                foreach ($socials as $social):
                    $icon = '';
                    $url = $social['url'] ?? '#';
                    $name = strtolower($social['platform'] ?? '');
                    if (strpos($name, 'instagram') !== false) {
                        $icon = '<i class="fab fa-instagram"></i>';
                    } elseif (strpos($name, 'linkedin') !== false) {
                        $icon = '<i class="fab fa-linkedin"></i>';
                    } elseif (strpos($name, 'github') !== false) {
                        $icon = '<i class="fab fa-github"></i>';
                    } elseif (strpos($name, 'twitter') !== false || strpos($name, 'x.com') !== false) {
                        $icon = '<i class="fab fa-x-twitter"></i>';
                    } elseif (strpos($name, 'facebook') !== false) {
                        $icon = '<i class="fab fa-facebook"></i>';
                    } elseif (strpos($name, 'youtube') !== false) {
                        $icon = '<i class="fab fa-youtube"></i>';
                    } else {
                        $icon = '<i class="fas fa-globe"></i>';
                    }
                ?>
                    <a href="<?php echo htmlspecialchars($url); ?>" target="_blank" rel="noopener" class="text-gray-400 hover:text-warm-wood transition-colors duration-300 text-2xl">
                        <?php echo $icon; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
                // Close mobile menu if open
                document.getElementById('mobileMenu').classList.add('hidden');
            });
        });

        // Add scroll effect to navigation
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 100) {
                nav.classList.add('backdrop-blur-lg');
            } else {
                nav.classList.remove('backdrop-blur-lg');
            }
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all elements with animation classes
        document.querySelectorAll('.animate-slide-up, .animate-fade-in').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'opacity 0.8s ease-out, transform 0.8s ease-out';
            observer.observe(el);
        });

        // Parallax effect for floating elements
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const parallax = document.querySelectorAll('.animate-float');

            parallax.forEach(element => {
                const speed = 0.2;
                element.style.transform = `translateY(-${scrolled * speed}px)`;
            });
        });
    </script>
    <script>
        // JavaScript untuk Modal Projects
        function openProjectsModal() {
            const modal = document.getElementById('projectsModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scroll

            // Add animation
            setTimeout(() => {
                modal.classList.add('opacity-100');
            }, 50);
        }

        function closeProjectsModal() {
            const modal = document.getElementById('projectsModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto'; // Restore background scroll
        }

        // Close modal when clicking outside or pressing Escape
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('projectsModal');

            // Close on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeProjectsModal();
                }
            });

            // Prevent modal content click from closing modal
            modal.querySelector('.inline-block').addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>

    <script>
        // JavaScript untuk Modal Articles
        function openArticlesModal() {
            const modal = document.getElementById('articlesModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scroll

            // Add animation
            setTimeout(() => {
                modal.classList.add('opacity-100');
            }, 50);
        }

        function closeArticlesModal() {
            const modal = document.getElementById('articlesModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto'; // Restore background scroll
        }

        // Close modal when clicking outside or pressing Escape
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('articlesModal');

            // Close on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeArticlesModal();
                }
            });

            // Prevent modal content click from closing modal
            if (modal.querySelector('.inline-block')) {
                modal.querySelector('.inline-block').addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        });
    </script>

    <script>
        // Modal Contact
        function openContactModal() {
            const modal = document.getElementById('contactModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                modal.classList.add('opacity-100');
            }, 50);
        }

        function closeContactModal() {
            const modal = document.getElementById('contactModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        // Close modal on Escape key
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('contactModal');
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeContactModal();
                }
            });
            if (modal.querySelector('.inline-block')) {
                modal.querySelector('.inline-block').addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        });
    </script>
</body>

</html>