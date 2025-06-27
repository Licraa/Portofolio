<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';
// Notifikasi dari session (jika ada)
if (isset($_SESSION['notif'])) {
    $notif = $_SESSION['notif'];
    unset($_SESSION['notif']);
}
// --- Handle Profile Image Upload & Switch ---
$profileImages = [];
$profileImagesDir = '../images/';
$profileImagesDb = [];
$sql = "SELECT * FROM profile_images ORDER BY id DESC LIMIT 3";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $profileImagesDb[] = $row;
        if ($row['is_active']) {
            $activeProfileImage = $row['filename'];
        }
    }
}
if (empty($activeProfileImage)) {
    $activeProfileImage = 'Profile.jpg';
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profileImage'])) {
    $file = $_FILES['profileImage'];
    if ($file['error'] === UPLOAD_ERR_OK && $file['size'] <= 2 * 1024 * 1024) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
            $newName = 'profile_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
            $target = $profileImagesDir . $newName;
            if (move_uploaded_file($file['tmp_name'], $target)) {
                $conn->query("UPDATE profile_images SET is_active=0");
                $conn->query("INSERT INTO profile_images (filename, is_active) VALUES ('{$conn->real_escape_string($newName)}', 1)");
                $result = $conn->query("SELECT id, filename FROM profile_images ORDER BY id DESC");
                $images = [];
                while ($row = $result->fetch_assoc()) $images[] = $row;
                if (count($images) > 3) {
                    $toKeep = array_slice($images, 0, 3);
                    $toDelete = array_slice($images, 3);
                    foreach ($toDelete as $img) {
                        $conn->query("DELETE FROM profile_images WHERE id=" . intval($img['id']));
                    }
                }
                $notif = ['type' => 'success', 'msg' => 'Foto profil berhasil diupload!'];
            }
        }
    } else {
        $notif = ['type' => 'error', 'msg' => 'Gagal mengupload foto profil. Pastikan format file JPG/PNG dan ukuran maksimal 2MB.'];
    }
    header("Location: profile.php");
    exit;
}
if (isset($_GET['use_profile']) && is_numeric($_GET['use_profile'])) {
    $id = intval($_GET['use_profile']);
    $conn->query("UPDATE profile_images SET is_active=0");
    $conn->query("UPDATE profile_images SET is_active=1 WHERE id=$id");
    $notif = ['type' => 'success', 'msg' => 'Foto profil aktif diganti!'];
    header("Location: profile.php");
    exit;
}

// --- Fetch Profile Data ---
$profileData = [
    'full_name' => '',
    'profession' => '',
    'birth_place' => '',
    'birth_date' => '',
    'value_per_month' => ''
];
$profileSql = "SELECT * FROM profile LIMIT 1";
$profileResult = $conn->query($profileSql);
if ($profileResult && $profileResult->num_rows > 0) {
    $profileData = $profileResult->fetch_assoc();
}
// --- Handle update profile ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_profile'])) {
    $fullName = $conn->real_escape_string($_POST['full_name']);
    $profession = $conn->real_escape_string($_POST['profession']);
    $birthPlace = $conn->real_escape_string($_POST['birth_place']);
    $birthDate = $conn->real_escape_string($_POST['birth_date']);
    $valuePerMonth = isset($_POST['value_per_month']) ? $conn->real_escape_string($_POST['value_per_month']) : '';
    $check = $conn->query("SELECT id FROM profile LIMIT 1");
    if ($check && $check->num_rows > 0) {
        $conn->query("UPDATE profile SET full_name='$fullName', profession='$profession', birth_place='$birthPlace', birth_date='$birthDate', value_per_month='$valuePerMonth' LIMIT 1");
        $notif = ['type' => 'success', 'msg' => 'Profil berhasil diupdate!'];
    } else {
        $conn->query("INSERT INTO profile (full_name, profession, birth_place, birth_date, value_per_month) VALUES ('$fullName', '$profession', '$birthPlace', '$birthDate', '$valuePerMonth')");
        $notif = ['type' => 'success', 'msg' => 'Profil berhasil ditambah!'];
    }
    header("Location: profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Profil</title>
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
                <a href="index.php" class="nav-item flex items-center gap-4 px-6 py-4 rounded-2xl font-semibold transition-all duration-300 hover:bg-accent-primary/10 hover:text-accent-primary text-text-secondary">
                    <i class="fas fa-home text-lg w-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="profile.php" class="nav-item flex items-center gap-4 px-6 py-4 rounded-2xl font-semibold transition-all duration-300 bg-accent-primary/20 text-accent-primary border border-accent-primary/30">
                    <i class="fas fa-user text-lg w-5"></i>
                    <span>Profil</span>
                    <div class="ml-auto w-2 h-2 bg-accent-primary rounded-full animate-bounce-subtle"></div>
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
        <!-- Hamburger (mobile only) -->
        <button id="hamburgerBtn" class="fixed top-4 left-4 z-50 md:hidden bg-dark-surface p-3 rounded-xl shadow-lg focus:outline-none focus:ring-2 focus:ring-accent-primary" aria-label="Buka sidebar">
            <span class="sr-only">Buka navigasi</span>
            <i class="fas fa-bars text-2xl text-white"></i>
        </button>
        <main class="flex-1 p-4 sm:p-8 md:p-12 bg-dark-bg min-h-screen overflow-x-auto">
            <div class="max-w-3xl w-full mx-auto px-0 flex flex-col gap-8">
                <div class="glass-card rounded-3xl p-8 shadow-2xl border border-border-color mb-8">
                    <h2 class="text-3xl font-bold gradient-text mb-8 flex items-center gap-3 pl-16 md:pl-0"><i class="fas fa-user"></i>Kelola Profil</h2>
                    <div class="grid md:grid-cols-2 gap-8">
                        <!-- Profile Picture -->
                        <div>
                            <form method="post" enctype="multipart/form-data">
                                <label class="block text-sm font-medium mb-2">Foto Profil</label>
                                <div class="flex items-center space-x-4">
                                    <img id="profilePreview" src="<?php echo htmlspecialchars($profileImagesDir . $activeProfileImage); ?>" alt="Profile" class="w-32 h-32 rounded-full object-cover border-4 border-accent-primary">
                                    <div>
                                        <input type="file" id="profileImage" name="profileImage" accept="image/*" class="hidden" onchange="this.form.submit()">
                                        <button type="button" onclick="document.getElementById('profileImage').click()" class="bg-accent-primary text-white px-4 py-2 rounded-lg hover:bg-accent-secondary transition-colors flex items-center gap-2"><i class="fas fa-upload"></i>Ubah Foto</button>
                                        <p class="text-xs text-text-secondary mt-2">JPG, PNG max 2MB</p>
                                    </div>
                                </div>
                            </form>
                            <?php if (!empty($profileImagesDb) && count($profileImagesDb) > 1): ?>
                                <div class="mt-6">
                                    <label class="block text-xs font-semibold mb-2 text-text-secondary">Pilih Foto Sebelumnya:</label>
                                    <div class="flex flex-wrap gap-3">
                                        <?php foreach ($profileImagesDb as $img): ?>
                                            <form method="get" style="display:inline;">
                                                <input type="hidden" name="use_profile" value="<?php echo $img['id']; ?>">
                                                <button type="submit"
                                                    class="relative group focus:outline-none transition-shadow duration-200 <?php echo $img['is_active'] ? 'ring-4 ring-accent-primary' : 'hover:ring-2 hover:ring-accent-primary'; ?>">
                                                    <img src="<?php echo htmlspecialchars($profileImagesDir . $img['filename']); ?>" alt="Profile" class="w-12 h-12 rounded-full object-cover">
                                                </button>
                                            </form>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <!-- Basic Info -->
                        <div class="space-y-4">
                            <form method="post" id="profileForm">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-2">Nama Lengkap</label>
                                    <input type="text" name="full_name" value="<?php echo htmlspecialchars($profileData['full_name']); ?>" class="profile-input w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary" required>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-2">Profesi</label>
                                    <input type="text" name="profession" value="<?php echo htmlspecialchars($profileData['profession']); ?>" class="profile-input w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary" required>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-2">Tempat Lahir</label>
                                    <input type="text" name="birth_place" value="<?php echo htmlspecialchars($profileData['birth_place']); ?>" class="profile-input w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary" required>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-2">Tanggal Lahir</label>
                                    <input type="date" name="birth_date" value="<?php echo htmlspecialchars($profileData['birth_date']); ?>" class="profile-input w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary" required>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-2">Nilai Per Bulan</label>
                                    <input type="text" name="value_per_month" value="<?php echo htmlspecialchars($profileData['value_per_month']); ?>" class="profile-input w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary">
                                </div>
                                <div class="mt-8 flex justify-end">
                                    <button type="submit" name="save_profile" class="bg-accent-success text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2 shadow"><i class="fas fa-save"></i> Simpan Profil</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php if (isset($notif)): ?>
                        <script>
                            window.addEventListener('DOMContentLoaded', () => {
                                const container = document.createElement('div');
                                container.className = 'fixed top-6 right-6 z-50';
                                const notif = document.createElement('div');
                                notif.className = 'bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center transition-opacity duration-300';
                                notif.innerHTML = '<span><?php echo addslashes($notif['msg']); ?></span>';
                                container.appendChild(notif);
                                document.body.appendChild(container);
                                setTimeout(() => {
                                    setTimeout(() => container.remove(), 300);
                                }, 2500);
                            });
                        </script>
                    <?php endif; ?>
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