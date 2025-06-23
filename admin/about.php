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
// --- Fetch About Data ---
$aboutData = [
    'main_description' => '',
    'additional_description' => ''
];
$aboutSql = "SELECT * FROM about LIMIT 1";
$aboutResult = $conn->query($aboutSql);
if ($aboutResult && $aboutResult->num_rows > 0) {
    $aboutData = $aboutResult->fetch_assoc();
}
// --- Handle update about ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_about'])) {
    $mainDesc = $conn->real_escape_string($_POST['mainDescription']);
    $addDesc = $conn->real_escape_string($_POST['additionalDescription']);
    $check = $conn->query("SELECT id FROM about LIMIT 1");
    if ($check && $check->num_rows > 0) {
        $conn->query("UPDATE about SET main_description='$mainDesc', additional_description='$addDesc' LIMIT 1");
        $_SESSION['notif'] = ['type' => 'success', 'msg' => 'Tentang berhasil diupdate!'];
        header("Location: about.php");
        exit;
    } else {
        $conn->query("INSERT INTO about (main_description, additional_description) VALUES ('$mainDesc', '$addDesc')");
        $_SESSION['notif'] = ['type' => 'success', 'msg' => 'Tentang berhasil ditambah!'];
        header("Location: about.php");
        exit;
    }
}
// --- Handle add education ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_education'])) {
    $institution = $conn->real_escape_string($_POST['edu_institution']);
    $program = $conn->real_escape_string($_POST['edu_program']);
    $description = $conn->real_escape_string($_POST['edu_description']);
    $conn->query("INSERT INTO education (institution, program, description) VALUES ('$institution', '$program', '$description')");
    $_SESSION['notif'] = ['type' => 'success', 'msg' => 'Pendidikan berhasil ditambah!'];
    header("Location: about.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_education'])) {
    $id = intval($_POST['edu_id']);
    $institution = $conn->real_escape_string($_POST['edu_institution']);
    $program = $conn->real_escape_string($_POST['edu_program']);
    $description = $conn->real_escape_string($_POST['edu_description']);
    $conn->query("UPDATE education SET institution='$institution', program='$program', description='$description' WHERE id=$id");
    $_SESSION['notif'] = ['type' => 'success', 'msg' => 'Pendidikan berhasil diupdate!'];
    header("Location: about.php");
    exit;
}
if (isset($_GET['delete_education']) && is_numeric($_GET['delete_education'])) {
    $id = intval($_GET['delete_education']);
    $conn->query("DELETE FROM education WHERE id=$id");
    $_SESSION['notif'] = ['type' => 'success', 'msg' => 'Pendidikan berhasil dihapus!'];
    header("Location: about.php");
    exit;
}
// --- Handle add organization ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_organization'])) {
    $name = $conn->real_escape_string($_POST['org_name']);
    $position = $conn->real_escape_string($_POST['org_position']);
    $description = $conn->real_escape_string($_POST['org_description']);
    $conn->query("INSERT INTO organization (name, position, description) VALUES ('$name', '$position', '$description')");
    $_SESSION['notif'] = ['type' => 'success', 'msg' => 'Organisasi berhasil ditambah!'];
    header("Location: about.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_organization'])) {
    $id = intval($_POST['org_id']);
    $name = $conn->real_escape_string($_POST['org_name']);
    $position = $conn->real_escape_string($_POST['org_position']);
    $description = $conn->real_escape_string($_POST['org_description']);
    $conn->query("UPDATE organization SET name='$name', position='$position', description='$description' WHERE id=$id");
    $_SESSION['notif'] = ['type' => 'success', 'msg' => 'Organisasi berhasil diupdate!'];
    header("Location: about.php");
    exit;
}
if (isset($_GET['delete_organization']) && is_numeric($_GET['delete_organization'])) {
    $id = intval($_GET['delete_organization']);
    $conn->query("DELETE FROM organization WHERE id=$id");
    $_SESSION['notif'] = ['type' => 'success', 'msg' => 'Organisasi berhasil dihapus!'];
    header("Location: about.php");
    exit;
}
// --- Handle add activity ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_activity'])) {
    $activity = $conn->real_escape_string($_POST['activity_name']);
    $description = $conn->real_escape_string($_POST['activity_description']);
    $conn->query("INSERT INTO activity (name, description) VALUES ('$activity', '$description')");
    $_SESSION['notif'] = ['type' => 'success', 'msg' => 'Aktivitas berhasil ditambah!'];
    header("Location: about.php");
    exit;
}
if (isset($_GET['delete_activity']) && is_numeric($_GET['delete_activity'])) {
    $id = intval($_GET['delete_activity']);
    $conn->query("DELETE FROM activity WHERE id=$id");
    $_SESSION['notif'] = ['type' => 'success', 'msg' => 'Aktivitas berhasil dihapus!'];
    header("Location: about.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Tentang</title>
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
    </style>
</head>

<body class="bg-dark-bg text-text-primary min-h-screen font-sans overflow-x-hidden">
    <div class="flex min-h-screen relative z-10">
        <!-- Overlay (mobile only) -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden transition-opacity duration-300"></div>
        <!-- Sidebar -->
        <aside id="sidebar" class="w-72 md:w-80 bg-dark-surface glass-morphism border-r border-border-color flex flex-col py-8 px-6 fixed md:sticky top-0 h-full md:h-screen z-40 sidebar-scroll overflow-y-auto -left-80 md:left-0 transition-all duration-300">
            <!-- Logo Section -->
            <div class="flex items-center gap-4 mb-12">
                <div class="relative">
                    <div class="w-12 h-12 gradient-primary rounded-2xl flex items-center justify-center shadow-2xl">
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
                <a href="about.php" class="nav-item flex items-center gap-4 px-6 py-4 rounded-2xl font-semibold transition-all duration-300 bg-accent-primary/20 text-accent-primary border border-accent-primary/30">
                    <i class="fas fa-info-circle text-lg w-5"></i>
                    <span>Tentang</span>
                    <div class="ml-auto w-2 h-2 bg-accent-primary rounded-full animate-bounce-subtle"></div>
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
        <main class="flex-1 p-4 sm:p-8 md:p-12 bg-dark-bg min-h-screen overflow-x-auto">
            <!-- Hamburger (mobile only) -->
            <button id="hamburgerBtn" class="fixed top-4 left-4 z-50 md:hidden bg-dark-surface p-3 rounded-xl shadow-lg focus:outline-none focus:ring-2 focus:ring-accent-primary" aria-label="Buka sidebar">
                <span class="sr-only">Buka navigasi</span>
                <i class="fas fa-bars text-2xl text-white"></i>
            </button>
            <div class="max-w-3xl w-full mx-auto px-0 flex flex-col gap-8">
                <div class="glass-card rounded-3xl p-6 sm:p-8 shadow-2xl border border-border-color mb-8">
                    <h2 class="text-3xl font-bold gradient-text mb-8 flex items-center gap-3 pl-16 md:pl-0"><i class="fas fa-info-circle"></i>Kelola Tentang</h2>
                    <form method="post">
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Deskripsi Utama</label>
                            <textarea name="mainDescription" rows="4" class="profile-input w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary" required><?php echo htmlspecialchars($aboutData['main_description']); ?></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Deskripsi Tambahan</label>
                            <textarea name="additionalDescription" rows="4" class="profile-input w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary" required><?php echo htmlspecialchars($aboutData['additional_description']); ?></textarea>
                        </div>
                        <div class="mt-8 flex justify-end">
                            <button type="submit" name="save_about" class="bg-accent-success text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2 shadow"><i class="fas fa-save"></i> Simpan Tentang</button>
                        </div>
                    </form>
                </div>
                <!-- Pendidikan -->
                <div class="glass-card rounded-3xl p-6 sm:p-8 shadow-2xl border border-border-color mb-8">
                    <h3 class="text-2xl font-bold gradient-text mb-6 flex items-center gap-3"><i class="fas fa-graduation-cap"></i>Pendidikan</h3>
                    <form method="post">
                        <div class="grid md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Nama Institusi</label>
                                <input type="text" name="edu_institution" class="profile-input w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Program Studi & Semester</label>
                                <input type="text" name="edu_program" class="profile-input w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Deskripsi</label>
                                <textarea name="edu_description" rows="2" class="profile-input w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary" required></textarea>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button type="submit" name="add_education" class="bg-accent-primary text-white px-6 py-2 rounded-lg hover:bg-accent-secondary transition-colors flex items-center gap-2 shadow"><i class="fas fa-plus"></i> Tambah Pendidikan</button>
                        </div>
                    </form>
                    <div class="mt-6">
                        <h4 class="text-lg font-semibold mb-2 gradient-text flex items-center gap-2"><i class="fas fa-list"></i>Daftar Pendidikan</h4>
                        <!-- Daftar Pendidikan -->
                        <ul class="space-y-2">
                            <?php
                            $eduQ = $conn->query("SELECT * FROM education ORDER BY id DESC");
                            if ($eduQ && $eduQ->num_rows > 0) {
                                while ($edu = $eduQ->fetch_assoc()) {
                            ?>
                                    <li class="bg-dark-surface rounded p-3 border border-border-color flex flex-col md:flex-row md:items-center md:gap-4">
                                        <div class="flex-1">
                                            <b><?php echo htmlspecialchars($edu['institution']); ?></b> - <?php echo htmlspecialchars($edu['program']); ?><br>
                                            <span class="text-xs text-text-secondary"><?php echo htmlspecialchars($edu['description']); ?></span>
                                        </div>
                                        <div class="flex gap-2 mt-2 md:mt-0">
                                            <button type="button" onclick="openEduModal('<?php echo $edu['id']; ?>', '<?php echo htmlspecialchars(addslashes($edu['institution'])); ?>', '<?php echo htmlspecialchars(addslashes($edu['program'])); ?>', '<?php echo htmlspecialchars(addslashes($edu['description'])); ?>')" class="bg-accent-success text-white px-3 py-1 rounded text-xs flex items-center gap-1"><i class="fas fa-edit"></i>Edit</button>
                                            <a href="?delete_education=<?php echo $edu['id']; ?>" onclick="return confirm('Hapus data pendidikan ini?')" class="bg-accent-danger text-white px-3 py-1 rounded text-xs flex items-center gap-1"><i class="fas fa-trash"></i>Hapus</a>
                                        </div>
                                    </li>
                            <?php
                                }
                            } else {
                                echo '<li class="text-text-secondary">Belum ada data pendidikan.</li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <!-- Organisasi -->
                <div class="glass-card rounded-3xl p-6 sm:p-8 shadow-2xl border border-border-color mb-8">
                    <h3 class="text-2xl font-bold gradient-text mb-6 flex items-center gap-3"><i class="fas fa-users"></i>Organisasi</h3>
                    <form method="post">
                        <div class="grid md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Nama Organisasi</label>
                                <input type="text" name="org_name" class="profile-input w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Posisi & Angkatan</label>
                                <input type="text" name="org_position" class="profile-input w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Deskripsi</label>
                                <textarea name="org_description" rows="2" class="profile-input w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary" required></textarea>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button type="submit" name="add_organization" class="bg-accent-primary text-white px-6 py-2 rounded-lg hover:bg-accent-secondary transition-colors flex items-center gap-2 shadow"><i class="fas fa-plus"></i> Tambah Organisasi</button>
                        </div>
                    </form>
                    <div class="mt-6">
                        <h4 class="text-lg font-semibold mb-2 gradient-text flex items-center gap-2"><i class="fas fa-list"></i>Daftar Organisasi</h4>
                        <!-- Daftar Organisasi -->
                        <ul class="space-y-2">
                            <?php
                            $orgQ = $conn->query("SELECT * FROM organization ORDER BY id DESC");
                            if ($orgQ && $orgQ->num_rows > 0) {
                                while ($org = $orgQ->fetch_assoc()) {
                            ?>
                                    <li class="bg-dark-surface rounded p-3 border border-border-color flex flex-col md:flex-row md:items-center md:gap-4">
                                        <div class="flex-1">
                                            <b><?php echo htmlspecialchars($org['name']); ?></b> - <?php echo htmlspecialchars($org['position']); ?><br>
                                            <span class="text-xs text-text-secondary"><?php echo htmlspecialchars($org['description']); ?></span>
                                        </div>
                                        <div class="flex gap-2 mt-2 md:mt-0">
                                            <button type="button" onclick="openOrgModal('<?php echo $org['id']; ?>', '<?php echo htmlspecialchars(addslashes($org['name'])); ?>', '<?php echo htmlspecialchars(addslashes($org['position'])); ?>', '<?php echo htmlspecialchars(addslashes($org['description'])); ?>')" class="bg-accent-success text-white px-3 py-1 rounded text-xs flex items-center gap-1"><i class="fas fa-edit"></i>Edit</button>
                                            <a href="?delete_organization=<?php echo $org['id']; ?>" onclick="return confirm('Hapus data organisasi ini?')" class="bg-accent-danger text-white px-3 py-1 rounded text-xs flex items-center gap-1"><i class="fas fa-trash"></i>Hapus</a>
                                        </div>
                                    </li>
                            <?php
                                }
                            } else {
                                echo '<li class="text-text-secondary">Belum ada data organisasi.</li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <!-- Aktivitas -->
                <div class="glass-card rounded-3xl p-6 sm:p-8 shadow-2xl border border-border-color mb-8">
                    <h3 class="text-2xl font-bold gradient-text mb-6 flex items-center gap-3"><i class="fas fa-running"></i>Aktivitas</h3>
                    <form method="post">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Nama Aktivitas</label>
                                <input type="text" name="activity_name" class="profile-input w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Deskripsi</label>
                                <textarea name="activity_description" rows="2" class="profile-input w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary" required></textarea>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button type="submit" name="add_activity" class="bg-accent-primary text-white px-6 py-2 rounded-lg hover:bg-accent-secondary transition-colors flex items-center gap-2 shadow"><i class="fas fa-plus"></i> Tambah Aktivitas</button>
                        </div>
                    </form>
                    <div class="mt-6">
                        <h4 class="text-lg font-semibold mb-2 gradient-text flex items-center gap-2"><i class="fas fa-list"></i>Daftar Aktivitas</h4>
                        <!-- Daftar Aktivitas -->
                        <ul class="space-y-2">
                            <?php
                            $activityQ = $conn->query("SELECT * FROM activity ORDER BY id DESC");
                            if ($activityQ && $activityQ->num_rows > 0) {
                                while ($act = $activityQ->fetch_assoc()) {
                            ?>
                                    <li class="bg-dark-surface rounded p-3 border border-border-color flex flex-col md:flex-row md:items-center md:gap-4">
                                        <div class="flex-1"><b><?php echo htmlspecialchars($act['name']); ?></b><br><span class="text-xs text-text-secondary"><?php echo htmlspecialchars($act['description']); ?></span></div>
                                        <div class="flex gap-2 mt-2 md:mt-0">
                                            <button type="button" onclick="openActivityModal('<?php echo $act['id']; ?>', '<?php echo htmlspecialchars(addslashes($act['name'])); ?>', '<?php echo htmlspecialchars(addslashes($act['description'])); ?>')" class="bg-accent-success text-white px-3 py-1 rounded text-xs flex items-center gap-1"><i class="fas fa-edit"></i>Edit</button>
                                            <a href="?delete_activity=<?php echo $act['id']; ?>" onclick="return confirm('Hapus aktivitas ini?')" class="bg-accent-danger text-white px-3 py-1 rounded text-xs flex items-center gap-1"><i class="fas fa-trash"></i>Hapus</a>
                                        </div>
                                    </li>
                            <?php
                                }
                            } else {
                                echo '<li class="text-text-secondary">Belum ada data aktivitas.</li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <!-- Modal Edit Pendidikan -->
                <div id="eduModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                    <div class="bg-gray-900 rounded-xl p-8 w-full max-w-lg relative">
                        <button onclick="closeEduModal()" class="absolute top-2 right-2 text-gray-400 hover:text-warm-wood text-2xl">&times;</button>
                        <h3 class="text-xl font-bold mb-4 text-warm-wood">Edit Pendidikan</h3>
                        <form method="post">
                            <input type="hidden" name="edu_id" id="edu_id">
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Nama Institusi</label>
                                <input type="text" name="edu_institution" id="edu_institution" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Program Studi & Semester</label>
                                <input type="text" name="edu_program" id="edu_program" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Deskripsi</label>
                                <textarea name="edu_description" id="edu_description" rows="2" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2" required></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" name="edit_education" class="bg-warm-wood text-dark-bg px-6 py-2 rounded-lg font-semibold hover:bg-opacity-90">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Modal Edit Organisasi -->
                <div id="orgModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                    <div class="bg-gray-900 rounded-xl p-8 w-full max-w-lg relative">
                        <button onclick="closeOrgModal()" class="absolute top-2 right-2 text-gray-400 hover:text-warm-wood text-2xl">&times;</button>
                        <h3 class="text-xl font-bold mb-4 text-warm-wood">Edit Organisasi</h3>
                        <form method="post">
                            <input type="hidden" name="org_id" id="org_id">
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Nama Organisasi</label>
                                <input type="text" name="org_name" id="org_name" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Posisi & Angkatan</label>
                                <input type="text" name="org_position" id="org_position" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2" required>
                            </div>
                            <div class="mb-4"></div>
                            <label class="block text-sm font-medium mb-2">Deskripsi</label>
                            <textarea name="org_description" id="org_description" rows="2" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2" required></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" name="edit_organization" class="bg-warm-wood text-dark-bg px-6 py-2 rounded-lg font-semibold hover:bg-opacity-90">Simpan Perubahan</button>
                    </div>
                    </form>
                </div>
            </div>
            <!-- Modal Edit Aktivitas -->
            <div id="activityModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                <div class="bg-gray-900 rounded-xl p-8 w-full max-w-lg relative">
                    <button onclick="closeActivityModal()" class="absolute top-2 right-2 text-gray-400 hover:text-warm-wood text-2xl">&times;</button>
                    <h3 class="text-xl font-bold mb-4 text-warm-wood">Edit Aktivitas</h3>
                    <form method="post">
                        <input type="hidden" name="activity_id" id="activity_id">
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Nama Aktivitas</label>
                            <input type="text" name="activity_name" id="activity_name" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Deskripsi</label>
                            <textarea name="activity_description" id="activity_description" rows="2" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2" required></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" name="edit_activity" class="bg-warm-wood text-dark-bg px-6 py-2 rounded-lg font-semibold hover:bg-opacity-90">Simpan Perubahan</button>
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
                            notif.style.opacity = '0';
                            setTimeout(() => container.remove(), 300);
                        }, 2500);
                    });
                </script>
            <?php endif; ?>
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
        // --- Scroll position persistence after form submit (edit/tambah/hapus) ---
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                sessionStorage.setItem('about_scroll', window.scrollY);
            });
        });
        window.addEventListener('DOMContentLoaded', function() {
            const scrollY = sessionStorage.getItem('about_scroll');
            if (scrollY !== null) {
                window.scrollTo(0, parseInt(scrollY));
                sessionStorage.removeItem('about_scroll');
            }
        });

        function openEduModal(id, institution, program, description) {
            document.getElementById('edu_id').value = id;
            document.getElementById('edu_institution').value = institution;
            document.getElementById('edu_program').value = program;
            document.getElementById('edu_description').value = description;
            document.getElementById('eduModal').classList.remove('hidden');
        }

        function closeEduModal() {
            document.getElementById('eduModal').classList.add('hidden');
        }

        function openOrgModal(id, name, position, description) {
            document.getElementById('org_id').value = id;
            document.getElementById('org_name').value = name;
            document.getElementById('org_position').value = position;
            document.getElementById('org_description').value = description;
            document.getElementById('orgModal').classList.remove('hidden');
        }

        function closeOrgModal() {
            document.getElementById('orgModal').classList.add('hidden');
        }

        function openActivityModal(id, name, description) {
            document.getElementById('activity_id').value = id;
            document.getElementById('activity_name').value = name;
            document.getElementById('activity_description').value = description;
            document.getElementById('activityModal').classList.remove('hidden');
        }

        function closeActivityModal() {
            document.getElementById('activityModal').classList.add('hidden');
        }
    </script>
</body>

</html>