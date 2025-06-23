<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';
if (isset($_SESSION['notif'])) {
    $notif = $_SESSION['notif'];
    unset($_SESSION['notif']);
}
// --- Handle Save/Add Contact ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_contact'])) {
    $email = $conn->real_escape_string($_POST['contact_email']);
    $phone = $conn->real_escape_string($_POST['contact_phone']);
    $location = $conn->real_escape_string($_POST['contact_location']);
    $check = $conn->query("SELECT id FROM contact LIMIT 1");
    if ($check && $check->num_rows > 0) {
        $conn->query("UPDATE contact SET email='$email', phone='$phone', location='$location' LIMIT 1");
        $_SESSION['notif'] = ['type' => 'success', 'msg' => 'Kontak berhasil diupdate!'];
    } else {
        $conn->query("INSERT INTO contact (email, phone, location) VALUES ('$email', '$phone', '$location')");
        $_SESSION['notif'] = ['type' => 'success', 'msg' => 'Kontak berhasil ditambah!'];
    }
    header("Location: contact.php");
    exit;
}
// --- Handle Edit Contact ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_contact'])) {
    $id = intval($_POST['contact_id']);
    $email = $conn->real_escape_string($_POST['contact_email']);
    $phone = $conn->real_escape_string($_POST['contact_phone']);
    $location = $conn->real_escape_string($_POST['contact_location']);
    $conn->query("UPDATE contact SET email='$email', phone='$phone', location='$location' WHERE id=$id");
    $_SESSION['notif'] = ['type' => 'success', 'msg' => 'Kontak berhasil diupdate!'];
    header("Location: contact.php");
    exit;
}
// --- Handle Delete Contact ---
if (isset($_GET['delete_contact']) && is_numeric($_GET['delete_contact'])) {
    $id = intval($_GET['delete_contact']);
    $conn->query("DELETE FROM contact WHERE id=$id");
    $_SESSION['notif'] = ['type' => 'success', 'msg' => 'Kontak berhasil dihapus!'];
    header("Location: contact.php");
    exit;
}
// --- Handle Delete Message ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_message'])) {
    $id = intval($_POST['delete_message_id']);
    $conn->query("DELETE FROM contact_messages WHERE id=$id");
    $_SESSION['notif'] = ['type' => 'success', 'msg' => 'Pesan berhasil dihapus!'];
    header("Location: contact.php");
    exit;
}
function js_escape($str)
{
    return str_replace(["\\", "'", "\n", "\r"], ["\\\\", "\\'", "\\n", "\\r"], $str);
}
// --- Handle Add/Edit Social Media ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_social'])) {
    $platform = $conn->real_escape_string($_POST['platform']);
    $url = $conn->real_escape_string($_POST['url']);
    $conn->query("INSERT INTO social_media (platform, url) VALUES ('$platform', '$url')");
    $_SESSION['notif'] = ['type' => 'success', 'msg' => 'Sosial media berhasil ditambah!'];
    header("Location: contact.php");
    exit;
}
// Handle edit sosial media
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_social'])) {
    $id = intval($_POST['social_id']);
    $platform = $conn->real_escape_string($_POST['platform']);
    $url = $conn->real_escape_string($_POST['url']);
    $conn->query("UPDATE social_media SET platform='$platform', url='$url' WHERE id=$id");
    $_SESSION['notif'] = ['type' => 'success', 'msg' => 'Sosial media berhasil diupdate!'];
    header("Location: contact.php");
    exit;
}
// Handle hapus sosial media
if (isset($_GET['delete_social']) && is_numeric($_GET['delete_social'])) {
    $id = intval($_GET['delete_social']);
    $conn->query("DELETE FROM social_media WHERE id=$id");
    $_SESSION['notif'] = ['type' => 'success', 'msg' => 'Sosial media berhasil dihapus!'];
    header("Location: contact.php");
    exit;
}
// Ambil data sosial media
$socials = [];
$socialQ = $conn->query("SELECT * FROM social_media ORDER BY id DESC");
if ($socialQ && $socialQ->num_rows > 0) {
    while ($row = $socialQ->fetch_assoc()) {
        $socials[] = $row;
    }
    header("Location: contact.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kontak & Pesan Masuk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'border-color': '#404040',
                        'accent-primary': '#4a90e2',
                        'accent-success': '#27ae60',
                        'accent-warning': '#f39c12',
                        'accent-danger': '#e74c3c',
                        'dark-bg': '#2D2D2D',
                        'dark-surface': '#232323',
                        'dark-card': '#222',
                        'text-primary': '#E5E7EB',
                        'text-secondary': '#A3A3A3',
                    },
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

        .notif-success {
            background: linear-gradient(90deg, #27ae60 0%, #2ecc71 100%);
            color: #fff;
        }

        .notif-danger {
            background: linear-gradient(90deg, #e74c3c 0%, #ff7675 100%);
            color: #fff;
        }

        /* Animasi untuk modal */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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
                <a href="contact.php" class="nav-item flex items-center gap-4 px-6 py-4 rounded-2xl font-semibold transition-all duration-300 bg-accent-primary/20 text-accent-primary border border-accent-primary/30">
                    <i class="fas fa-phone text-lg w-5"></i>
                    <span>Kontak & Pesan</span>
                    <div class="ml-auto w-2 h-2 bg-accent-primary rounded-full animate-bounce-subtle"></div>
                </a>
            </nav>
            <!-- User Profile Card -->
            <div class="mt-8 p-6">
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
            <!-- Pesan Notifikasi -->
            <?php if (isset($notif)): ?>
                <div class="fixed top-4 right-4 z-50 w-full max-w-xs mx-auto">
                    <div class="p-4 rounded-xl <?php echo $notif['type'] === 'success' ? 'notif-success' : 'notif-danger'; ?> shadow-md flex items-center gap-3">
                        <i class="fas fa-info-circle"></i>
                        <span><?php echo htmlspecialchars($notif['msg']); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <div class="max-w-4xl w-full mx-auto px-0 flex flex-col gap-8">
                <div class="glass-card rounded-3xl p-6 sm:p-8 shadow-2xl border border-border-color mb-8">
                    <h2 class="text-3xl font-bold gradient-text mb-8 flex items-center gap-3 pl-16 md:pl-0"><i class="fas fa-address-book text-accent-primary"></i>Kelola Kontak & Sosial Media</h2>
                    <?php
                    $contactQ = $conn->query("SELECT * FROM contact ORDER BY id DESC LIMIT 1");
                    $hasContact = ($contactQ && $contactQ->num_rows > 0);
                    $c = $hasContact ? $contactQ->fetch_assoc() : ["email" => "", "phone" => "", "location" => ""];
                    ?>
                    <form method="post" class="mb-8 grid gap-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-2">Email</label>
                                <input type="email" name="contact_email" value="<?php echo htmlspecialchars($c['email']); ?>" class="w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">No. Telepon</label>
                                <input type="text" name="contact_phone" value="<?php echo htmlspecialchars($c['phone']); ?>" class="w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Lokasi</label>
                            <input type="text" name="contact_location" value="<?php echo htmlspecialchars($c['location']); ?>" class="w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary">
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" name="save_contact" class="bg-gradient-to-r from-accent-primary to-blue-500 text-white px-6 py-2 rounded-xl font-semibold shadow-md hover:shadow-xl transition-all flex items-center gap-2">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Form Sosial Media -->
                <div class="glass-card rounded-3xl p-6 sm:p-8 shadow-2xl border border-border-color mb-8">
                    <h2 class="text-2xl font-bold gradient-text mb-6 flex items-center gap-3">
                        <i class="fab fa-hashtag text-accent-primary"></i>
                        Kelola Sosial Media
                    </h2>

                    <!-- Form Tambah Sosial Media -->
                    <form method="post" class="mb-6 grid md:grid-cols-3 gap-4 items-end">
                        <div>
                            <label class="block text-sm font-medium mb-2">Platform</label>
                            <input type="text" name="platform" class="w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary" placeholder="Instagram, Github, LinkedIn..." required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium mb-2">URL</label>
                            <input type="url" name="url" class="w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary" placeholder="https://..." required>
                        </div>
                        <div>
                            <button type="submit" name="add_social" class="bg-gradient-to-r from-accent-primary to-blue-500 text-white px-6 py-2 rounded-xl font-semibold shadow-md hover:shadow-xl transition-all flex items-center gap-2 w-full"><i class="fas fa-plus"></i> Tambah</button>
                        </div>
                    </form>
                    <!-- Daftar Sosial Media -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left">
                            <thead>
                                <tr class="text-text-secondary border-b border-border-color">
                                    <th class="py-3 px-4">Platform</th>
                                    <th class="py-3 px-4">URL</th>
                                    <th class="py-3 px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($socials) > 0): foreach ($socials as $s): ?>
                                        <tr class="border-b border-border-color hover:bg-dark-surface/50">
                                            <td class="py-3 px-4 font-semibold text-text-primary flex items-center gap-2">
                                                <?php
                                                $icon = '<i class="fas fa-globe"></i>';
                                                $name = strtolower($s['platform']);
                                                if (strpos($name, 'instagram') !== false) {
                                                    $icon = '<i class="fab fa-instagram text-pink-400"></i>';
                                                } elseif (strpos($name, 'linkedin') !== false) {
                                                    $icon = '<i class="fab fa-linkedin text-blue-400"></i>';
                                                } elseif (strpos($name, 'github') !== false) {
                                                    $icon = '<i class="fab fa-github text-gray-300"></i>';
                                                } elseif (strpos($name, 'twitter') !== false || strpos($name, 'x.com') !== false) {
                                                    $icon = '<i class="fab fa-x-twitter text-blue-400"></i>';
                                                } elseif (strpos($name, 'facebook') !== false) {
                                                    $icon = '<i class="fab fa-facebook text-blue-600"></i>';
                                                } elseif (strpos($name, 'youtube') !== false) {
                                                    $icon = '<i class="fab fa-youtube text-red-500"></i>';
                                                }
                                                echo $icon . ' ' . htmlspecialchars($s['platform']);
                                                ?>
                                            </td>
                                            <td class="py-3 px-4 text-accent-primary"><a href="<?php echo htmlspecialchars($s['url']); ?>" target="_blank" class="underline hover:text-accent-success transition-colors"><?php echo htmlspecialchars($s['url']); ?></a></td>
                                            <td class="py-3 px-4 flex gap-2">
                                                <button type="button" class="text-accent-success hover:text-green-500 transition-colors" title="Edit" onclick="openEditSocialModal('<?php echo $s['id']; ?>', '<?php echo htmlspecialchars(addslashes($s['platform'])); ?>', '<?php echo htmlspecialchars(addslashes($s['url'])); ?>')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="?delete_social=<?php echo $s['id']; ?>" class="text-accent-danger hover:text-red-500 transition-colors" title="Hapus" onclick="return confirm('Hapus sosial media ini?')"><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach;
                                else: ?>
                                    <tr>
                                        <td colspan="3" class="py-6 text-center text-text-secondary">Belum ada sosial media.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Pesan Masuk -->
                <div class="glass-card rounded-3xl p-6 sm:p-8 shadow-2xl border border-border-color mb-8 mt-12">
                    <h2 class="text-2xl font-bold gradient-text mb-6 flex items-center gap-3">
                        <i class="fas fa-comments text-accent-primary"></i>
                        Pesan Masuk
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left">
                            <thead>
                                <tr class="text-text-secondary border-b border-border-color">
                                    <th class="py-3 px-4">Nama</th>
                                    <th class="py-3 px-4">Email</th>
                                    <th class="py-3 px-4">Pesan</th>
                                    <th class="py-3 px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $msgQ = $conn->query("SELECT * FROM contact_messages ORDER BY id DESC");
                                if ($msgQ && $msgQ->num_rows > 0):
                                    while ($m = $msgQ->fetch_assoc()): ?>
                                        <tr class="border-b border-border-color hover:bg-dark-surface/50">
                                            <td class="py-3 px-4 font-semibold text-text-primary"><?php echo htmlspecialchars($m['name']); ?></td>
                                            <td class="py-3 px-4 text-text-secondary"><?php echo htmlspecialchars($m['email']); ?></td>
                                            <td class="py-3 px-4 text-text-primary max-w-xs truncate" title="<?php echo htmlspecialchars($m['message']); ?>">
                                                <?php echo htmlspecialchars(mb_strimwidth($m['message'], 0, 60, '...')); ?>
                                            </td>
                                            <td class="py-3 px-4">
                                                <form method="post" class="inline">
                                                    <input type="hidden" name="delete_message_id" value="<?php echo $m['id']; ?>">
                                                    <button type="submit" name="delete_message" class="text-accent-danger hover:text-red-500 transition-colors" title="Hapus" onclick="return confirm('Hapus pesan ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endwhile;
                                else: ?>
                                    <tr>
                                        <td colspan="4" class="py-6 text-center text-text-secondary">Belum ada pesan masuk.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <!-- Modal Edit Sosial Media -->
    <div id="editSocialModal" class="fixed inset-0 z-50 hidden overflow-y-auto transition-all duration-300">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Overlay -->
            <div class="fixed inset-0 bg-dark-bg/80 backdrop-blur-sm transition-opacity" onclick="closeEditSocialModal()"></div>
            <!-- Modal Content -->
            <div class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden align-middle transition-all transform rounded-3xl shadow-2xl border-2 border-accent-primary bg-dark-bg/95 backdrop-blur-md glass-card relative animate-fade-in">
                <button onclick="closeEditSocialModal()" class="absolute top-3 right-3 p-1 text-gray-400 hover:text-accent-primary transition-colors duration-300 focus:outline-none rounded-full hover:bg-dark-bg/50">
                    <i class="fas fa-times text-2xl"></i>
                </button>
                <h2 class="text-2xl font-bold gradient-text mb-6 flex items-center gap-3 justify-center">
                    <i class="fab fa-hashtag text-accent-primary"></i> Edit Sosial Media
                </h2>
                <form method="post" id="editSocialForm" class="grid gap-6">
                    <input type="hidden" name="social_id" id="editSocialId">
                    <div>
                        <label class="block text-sm font-medium mb-2">Platform</label>
                        <input type="text" name="platform" id="editPlatform" class="w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">URL</label>
                        <input type="url" name="url" id="editUrl" class="w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary" required>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" name="edit_social" class="bg-gradient-to-r from-accent-primary to-blue-500 text-white px-6 py-2 rounded-xl font-semibold shadow-md hover:shadow-xl transition-all flex items-center gap-2">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEditSocialModal(id, platform, url) {
            document.getElementById('editSocialId').value = id;
            document.getElementById('editPlatform').value = platform;
            document.getElementById('editUrl').value = url;
            document.getElementById('editSocialModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                document.getElementById('editSocialModal').classList.add('opacity-100');
            }, 50);
        }

        function closeEditSocialModal() {
            document.getElementById('editSocialModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('editSocialModal');
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeEditSocialModal();
                }
            });
            if (modal.querySelector('.inline-block')) {
                modal.querySelector('.inline-block').addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        });
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