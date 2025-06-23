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
$logoDir = '../images/';
// --- Handle Add Skill ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_skill'])) {
    $name = $conn->real_escape_string($_POST['skill_name']);
    $level = isset($_POST['skill_level']) ? intval($_POST['skill_level']) : 3;
    $logo = '';
    if (isset($_FILES['skill_logo']) && $_FILES['skill_logo']['error'] === UPLOAD_ERR_OK && $_FILES['skill_logo']['size'] <= 2 * 1024 * 1024) {
        $ext = strtolower(pathinfo($_FILES['skill_logo']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
            $newName = 'skill_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
            $target = $logoDir . $newName;
            if (move_uploaded_file($_FILES['skill_logo']['tmp_name'], $target)) {
                $logo = $conn->real_escape_string($newName);
            }
        }
    }
    $conn->query("INSERT INTO skills (name, level, logo) VALUES ('$name', $level, '$logo')");
    $notif = ['type' => 'success', 'msg' => 'Skill berhasil ditambah!'];
}

// --- Handle Delete Skill ---
if (isset($_GET['delete_skill']) && is_numeric($_GET['delete_skill'])) {
    $id = intval($_GET['delete_skill']);
    $conn->query("DELETE FROM skills WHERE id=$id");
    $notif = ['type' => 'success', 'msg' => 'Skill berhasil dihapus!'];
}


// Backend: proses edit dari modal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_skill_modal'])) {
    $id = intval($_POST['skill_id']);
    $name = $conn->real_escape_string($_POST['skill_name']);
    $level = isset($_POST['modal_skill_level']) ? intval($_POST['modal_skill_level']) : 3;
    $logo = isset($_POST['old_logo']) ? $conn->real_escape_string($_POST['old_logo']) : '';
    if (isset($_FILES['skill_logo']) && $_FILES['skill_logo']['error'] === UPLOAD_ERR_OK && $_FILES['skill_logo']['size'] <= 2 * 1024 * 1024) {
        $ext = strtolower(pathinfo($_FILES['skill_logo']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
            $newName = 'skill_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
            $target = $logoDir . $newName;
            if (move_uploaded_file($_FILES['skill_logo']['tmp_name'], $target)) {
                $logo = $conn->real_escape_string($newName);
            }
        }
    }
    $conn->query("UPDATE skills SET name='$name', level=$level, logo='$logo' WHERE id=$id");
    $notif = ['type' => 'success', 'msg' => 'Skill berhasil diupdate!'];
}



?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Keahlian</title>
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
                    backdropBlur: {
                        'xs': '2px'
                    }
                }
            }
        }
    </script>
    <style>
        /* Animasi dan keyframes custom */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounceSubtle {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        .animate-bounce-subtle {
            animation: bounceSubtle 2s ease-in-out infinite;
        }

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

        .star-active {
            color: #f39c12 !important;
            text-shadow: 0 0 8px #f39c12cc;
        }
    </style>
</head>

<body class="bg-dark-bg text-text-primary min-h-screen font-sans overflow-x-hidden">
    <div class="flex min-h-screen relative z-10">
        <!-- Sidebar -->
        <aside class="w-80 bg-dark-surface glass-morphism border-r border-border-color flex flex-col py-8 px-6 sticky top-0 h-screen z-40 sidebar-scroll overflow-y-auto">
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
                <a href="dashboard.php" class="nav-item flex items-center gap-4 px-6 py-4 rounded-2xl font-semibold transition-all duration-300 hover:bg-accent-primary/10 hover:text-accent-primary text-text-secondary">
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
                <a href="skills.php" class="nav-item flex items-center gap-4 px-6 py-4 rounded-2xl font-semibold transition-all duration-300 bg-accent-primary/20 text-accent-primary border border-accent-primary/30">
                    <i class="fas fa-bolt text-lg w-5"></i>
                    <span>Keahlian</span>
                    <div class="ml-auto w-2 h-2 bg-accent-primary rounded-full animate-bounce-subtle"></div>
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
                    <button type="submit" class="w-full bg-gradient-to-r from-accent-danger to-red-600 text-white py-3 rounded-xl font-semibold hover:shadow-2xl transition-all duration-300 flex items-center gap-3 justify-center">
                        <i class="fas fa-sign-out-alt group-hover:translate-x-1 transition-transform duration-300"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>
        <!-- Main Content -->
        <main class="flex-1 p-8 md:p-12 bg-dark-bg min-h-screen">
            <div class="max-w-3xl mx-auto">
                <div class="glass-card rounded-3xl p-8 shadow-2xl border border-border-color mb-8">
                    <h2 class="text-3xl font-bold gradient-text mb-8 flex items-center gap-3"><i class="fas fa-bolt"></i>Kelola Keahlian</h2>
                    <form method="post" enctype="multipart/form-data" class="mb-8">
                        <div class="grid md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Nama Skill</label>
                                <input type="text" name="skill_name" class="w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Level</label>
                                <div class="flex items-center gap-1 mt-1 star-rating" id="star-input-group">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <input type="radio" id="star<?php echo $i; ?>" name="skill_level" value="<?php echo $i; ?>" class="hidden" <?php echo $i === 3 ? 'checked' : ''; ?> />
                                        <label for="star<?php echo $i; ?>" class="cursor-pointer text-2xl transition-colors">★</label>
                                    <?php endfor; ?>
                                    <span id="star-indicator" class="ml-2 text-accent-warning font-semibold">3/5</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Logo/Icon</label>
                                <input type="file" name="skill_logo" accept="image/*" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-accent-primary/20 file:text-accent-primary hover:file:bg-accent-primary/40" />
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button type="submit" name="add_skill" class="bg-accent-success text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2 shadow"><i class="fas fa-plus"></i> Tambah Keahlian</button>
                        </div>
                    </form>
                    <div class="mt-6">
                        <h4 class="text-lg font-semibold mb-2 gradient-text flex items-center gap-2"><i class="fas fa-list"></i>Daftar Keahlian</h4>
                        <ul class="space-y-2">
                            <?php
                            $skillQ = $conn->query("SELECT * FROM skills ORDER BY id DESC");
                            if ($skillQ && $skillQ->num_rows > 0) {
                                while ($skill = $skillQ->fetch_assoc()) {
                            ?>
                                    <li class="bg-dark-surface rounded p-3 border border-border-color flex flex-col md:flex-row md:items-center md:gap-4">
                                        <div class="flex items-center gap-4 flex-1">
                                            <?php if (!empty($skill['logo'])): ?>
                                                <img src="../images/<?php echo htmlspecialchars($skill['logo']); ?>" alt="Logo" class="w-10 h-10 rounded-full object-cover border-2 border-accent-primary">
                                            <?php else: ?>
                                                <div class="w-10 h-10 rounded-full bg-accent-primary flex items-center justify-center text-white text-xl"><i class="fas fa-bolt"></i></div>
                                            <?php endif; ?>
                                            <div>
                                                <b class="text-text-primary text-lg"><?php echo htmlspecialchars($skill['name']); ?></b><br>
                                                <span class="text-xs text-accent-warning font-semibold">Level: <?php echo intval($skill['level']); ?>/5</span>
                                            </div>
                                        </div>
                                        <div class="flex gap-2 mt-2 md:mt-0">
                                            <button type="button" onclick="openSkillModal('<?php echo $skill['id']; ?>', '<?php echo htmlspecialchars(addslashes($skill['name'])); ?>', '<?php echo intval($skill['level']); ?>', '<?php echo htmlspecialchars(addslashes($skill['logo'])); ?>')" class="bg-accent-success text-white px-3 py-1 rounded text-xs flex items-center gap-1"><i class="fas fa-edit"></i>Edit</button>
                                            <a href="?delete_skill=<?php echo $skill['id']; ?>" onclick="return confirm('Hapus skill ini?')" class="bg-accent-danger text-white px-3 py-1 rounded text-xs flex items-center gap-1"><i class="fas fa-trash"></i>Hapus</a>
                                        </div>
                                    </li>
                            <?php
                                }
                            } else {
                                echo '<li class="text-text-secondary">Belum ada data keahlian.</li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <!-- Modal Edit Skill -->
                <div id="skillModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                    <div class="glass-card rounded-2xl p-8 w-full max-w-lg relative border border-border-color">
                        <button onclick="closeSkillModal()" class="absolute top-2 right-2 text-gray-400 hover:text-accent-warning text-2xl">&times;</button>
                        <h3 class="text-xl font-bold mb-4 gradient-text flex items-center gap-2"><i class="fas fa-edit"></i>Edit Skill</h3>
                        <form method="post" enctype="multipart/form-data">
                            <input type="hidden" name="skill_id" id="modal_skill_id">
                            <input type="hidden" name="old_logo" id="modal_skill_logo">
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Nama Skill</label>
                                <input type="text" name="skill_name" id="modal_skill_name" class="w-full bg-dark-surface border border-border-color rounded-lg px-4 py-2 text-text-primary" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Level</label>
                                <div class="flex items-center gap-1 star-rating" id="modal_star_group">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <input type="radio" id="modal_star<?php echo $i; ?>" name="modal_skill_level" value="<?php echo $i; ?>" class="hidden" />
                                        <label for="modal_star<?php echo $i; ?>" class="cursor-pointer text-2xl transition-colors">★</label>
                                    <?php endfor; ?>
                                    <span id="modal_star_indicator" class="ml-2 text-accent-warning font-semibold">3/5</span>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Logo/Icon</label>
                                <input type="file" name="skill_logo" accept="image/*" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-accent-primary/20 file:text-accent-primary hover:file:bg-accent-primary/40" />
                                <div class="mt-2 text-xs text-gray-400">Biarkan kosong jika tidak ingin mengganti logo.</div>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" name="edit_skill_modal" class="bg-accent-primary text-white px-6 py-2 rounded-lg font-semibold hover:bg-accent-secondary transition-colors flex items-center gap-2"><i class="fas fa-save"></i> Simpan Perubahan</button>
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
                            notif.className = 'bg-accent-success text-white px-6 py-4 rounded-lg shadow-lg flex items-center transition-opacity duration-500';
                            notif.innerHTML = '<span><?php echo addslashes($notif['msg']); ?></span>';
                            container.appendChild(notif);
                            document.body.appendChild(container);
                            setTimeout(() => {
                                notif.classList.add('opacity-0');
                                setTimeout(() => container.remove(), 500);
                            }, 2500);
                        });
                    </script>
                <?php endif; ?>
            </div>
        </main>
    </div>
    <script>
        function openSkillModal(id, name, level, logo) {
            document.getElementById('modal_skill_id').value = id;
            document.getElementById('modal_skill_name').value = name;
            document.getElementById('modal_skill_logo').value = logo;
            // Set radio bintang
            let radios = document.querySelectorAll('#modal_star_group input[type=radio]');
            let labels = document.querySelectorAll('#modal_star_group label');
            let indicator = document.getElementById('modal_star_indicator');
            let val = parseInt(level) || 3;
            radios.forEach((r, idx) => {
                r.checked = (idx + 1) === val;
            });
            labels.forEach((lbl, idx) => {
                if (idx < val) lbl.classList.add('star-active');
                else lbl.classList.remove('star-active');
            });
            indicator.textContent = val + '/5';
            document.getElementById('skillModal').classList.remove('hidden');
        }

        function closeSkillModal() {
            document.getElementById('skillModal').classList.add('hidden');
        }
        // Pewarnaan bintang dan indikator modal
        (function() {
            let radios = document.querySelectorAll('#modal_star_group input[type=radio]');
            let labels = document.querySelectorAll('#modal_star_group label');
            let indicator = document.getElementById('modal_star_indicator');

            function updateModalStars() {
                let val = 3;
                radios.forEach((r, idx) => {
                    if (r.checked) val = parseInt(r.value);
                });
                labels.forEach((lbl, idx) => {
                    if (idx < val) lbl.classList.add('star-active');
                    else lbl.classList.remove('star-active');
                });
                indicator.textContent = val + '/5';
            }
            radios.forEach(r => r.addEventListener('change', updateModalStars));
            updateModalStars();
        })();
        // Pewarnaan bintang pada form tambah
        (function() {
            let radios = document.querySelectorAll('#star-input-group input[type=radio]');
            let labels = document.querySelectorAll('#star-input-group label');
            let indicator = document.getElementById('star-indicator');

            function updateStars() {
                let val = 3;
                radios.forEach((r, idx) => {
                    if (r.checked) val = parseInt(r.value);
                });
                labels.forEach((lbl, idx) => {
                    if (idx < val) lbl.classList.add('star-active');
                    else lbl.classList.remove('star-active');
                });
                indicator.textContent = val + '/5';
            }
            radios.forEach(r => r.addEventListener('change', updateStars));
            updateStars();
        })();
    </script>
</body>

</html>