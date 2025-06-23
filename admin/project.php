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
$imgDir = '../images/';
// --- Handle Add Project ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_project'])) {
    $title = $conn->real_escape_string($_POST['project_title']);
    $desc = $conn->real_escape_string($_POST['project_desc']);
    $tech = $conn->real_escape_string($_POST['project_tech']);
    $github = $conn->real_escape_string($_POST['project_github']);
    $demo = $conn->real_escape_string($_POST['project_demo']);
    $imagePath = '';
    if (isset($_FILES['project_image']) && $_FILES['project_image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['project_image'];
        if ($file['size'] <= 2 * 1024 * 1024) {
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                $imgName = 'project_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                $imgTarget = $imgDir . $imgName;
                if (move_uploaded_file($file['tmp_name'], $imgTarget)) {
                    $imagePath = 'images/' . $imgName;
                }
            }
        }
    }
    $conn->query("INSERT INTO projects (title, description, technologies, image, github, demo) VALUES ('$title', '$desc', '$tech', '$imagePath', '$github', '$demo')");
    $_SESSION['notif'] = ['type' => 'success', 'msg' => 'Proyek berhasil ditambah!'];
    header("Location: project.php");
    exit;
}
// --- Handle Edit Project ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_project'])) {
    $id = intval($_POST['project_id']);
    $title = $conn->real_escape_string($_POST['project_title']);
    $desc = $conn->real_escape_string($_POST['project_desc']);
    $tech = $conn->real_escape_string($_POST['project_tech']);
    $github = $conn->real_escape_string($_POST['project_github']);
    $demo = $conn->real_escape_string($_POST['project_demo']);
    $imagePath = $_POST['project_image_old'];
    if (isset($_FILES['project_image']) && $_FILES['project_image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['project_image'];
        if ($file['size'] <= 2 * 1024 * 1024) {
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                $imgName = 'project_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                $imgTarget = $imgDir . $imgName;
                if (move_uploaded_file($file['tmp_name'], $imgTarget)) {
                    $imagePath = 'images/' . $imgName;
                }
            }
        }
    }
    $conn->query("UPDATE projects SET title='$title', description='$desc', technologies='$tech', image='$imagePath', github='$github', demo='$demo' WHERE id=$id");
    $_SESSION['notif'] = ['type' => 'success', 'msg' => 'Proyek berhasil diupdate!'];
    header("Location: project.php");
    exit;
}
// --- Handle Delete Project ---
if (isset($_GET['delete_project']) && is_numeric($_GET['delete_project'])) {
    $id = intval($_GET['delete_project']);
    $conn->query("DELETE FROM projects WHERE id=$id");
    $_SESSION['notif'] = ['type' => 'success', 'msg' => 'Proyek berhasil dihapus!'];
    header("Location: project.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Proyek</title>
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
        /* Animasi di-nonaktifkan agar tampilan lebih ringan */
        /* @keyframes fadeIn { ... } */
        /* .animate-fade-in { animation: none; } */
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
                <a href="skills.php" class="nav-item flex items-center gap-4 px-6 py-4 rounded-2xl font-semibold transition-all duration-300 hover:bg-accent-primary/10 hover:text-accent-primary text-text-secondary">
                    <i class="fas fa-bolt text-lg w-5"></i>
                    <span>Keahlian</span>
                </a>
                <a href="project.php" class="nav-item flex items-center gap-4 px-6 py-4 rounded-2xl font-semibold transition-all duration-300 bg-accent-primary/20 text-accent-primary border border-accent-primary/30">
                    <i class="fas fa-rocket text-lg w-5"></i>
                    <span>Proyek</span>
                    <div class="ml-auto w-2 h-2 bg-accent-primary rounded-full animate-bounce-subtle"></div>
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
            <div class="max-w-4xl mx-auto">
                <div class="glass-card rounded-3xl p-8 shadow-2xl border border-border-color mb-8">
                    <h2 class="text-3xl font-bold gradient-text mb-8 flex items-center gap-3"><i class="fas fa-rocket"></i>Kelola Proyek</h2>
                    <form method="post" enctype="multipart/form-data" class="mb-8">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Judul Proyek</label>
                                <input type="text" name="project_title" class="w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Teknologi</label>
                                <input type="text" name="project_tech" class="w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary" placeholder="Contoh: PHP, MySQL, Tailwind" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium mb-2">Deskripsi</label>
                                <textarea name="project_desc" rows="3" class="w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary" required></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Gambar/Poster</label>
                                <input type="file" name="project_image" accept="image/*" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-accent-primary/20 file:text-accent-primary hover:file:bg-accent-primary/40" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Link Github</label>
                                <input type="url" name="project_github" class="w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary" placeholder="https://github.com/username/repo">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Link Demo</label>
                                <input type="url" name="project_demo" class="w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary" placeholder="https://demo.com">
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button type="submit" name="add_project" class="bg-accent-success text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2 shadow"><i class="fas fa-plus"></i> Tambah Proyek</button>
                        </div>
                    </form>
                    <!-- Notifikasi session -->
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
                <!-- Daftar Proyek -->
                <div class="glass-card rounded-3xl p-8 shadow-2xl border border-border-color">
                    <h3 class="text-2xl font-bold gradient-text mb-6 flex items-center gap-2"><i class="fas fa-list"></i>Daftar Proyek</h3>
                    <ul class="space-y-4">
                        <?php
                        $projectQ = $conn->query("SELECT * FROM projects ORDER BY id DESC");
                        if ($projectQ && $projectQ->num_rows > 0) {
                            while ($project = $projectQ->fetch_assoc()) {
                        ?>
                                <li class="bg-dark-surface rounded-xl p-5 border border-border-color flex flex-col md:flex-row md:items-center md:gap-6 card-hover">
                                    <?php if (!empty($project['image'])): ?>
                                        <img src="../<?php echo htmlspecialchars($project['image']); ?>" alt="Poster" class="w-28 h-28 object-cover rounded-xl border-2 border-accent-primary mb-3 md:mb-0">
                                    <?php else: ?>
                                        <div class="w-28 h-28 rounded-xl bg-accent-primary flex items-center justify-center text-white text-3xl mb-3 md:mb-0"><i class="fas fa-rocket"></i></div>
                                    <?php endif; ?>
                                    <div class="flex-1">
                                        <b class="text-text-primary text-lg"><?php echo htmlspecialchars($project['title']); ?></b>
                                        <div class="text-xs text-accent-warning font-semibold mb-1">Teknologi: <?php echo htmlspecialchars($project['technologies']); ?></div>
                                        <div class="text-text-secondary text-sm mb-2"><?php echo nl2br(htmlspecialchars($project['description'])); ?></div>
                                        <div class="flex gap-2 flex-wrap mb-2">
                                            <?php if (!empty($project['github'])): ?>
                                                <a href="<?php echo htmlspecialchars($project['github']); ?>" target="_blank" class="inline-flex items-center gap-1 text-accent-primary hover:text-accent-secondary font-semibold text-xs"><i class="fab fa-github"></i> Github</a>
                                            <?php endif; ?>
                                            <?php if (!empty($project['demo'])): ?>
                                                <a href="<?php echo htmlspecialchars($project['demo']); ?>" target="_blank" class="inline-flex items-center gap-1 text-accent-success hover:text-green-400 font-semibold text-xs"><i class="fas fa-external-link-alt"></i> Demo</a>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex gap-2 mt-2">
                                            <button type="button" onclick="openProjectModal('<?php echo $project['id']; ?>', '<?php echo htmlspecialchars(addslashes($project['title'])); ?>', '<?php echo htmlspecialchars(addslashes($project['technologies'])); ?>', '<?php echo htmlspecialchars(addslashes($project['description'])); ?>', '<?php echo htmlspecialchars(addslashes($project['image'])); ?>', '<?php echo htmlspecialchars(addslashes($project['github'])); ?>', '<?php echo htmlspecialchars(addslashes($project['demo'])); ?>')" class="bg-accent-success text-white px-3 py-1 rounded text-xs flex items-center gap-1"><i class="fas fa-edit"></i>Edit</button>
                                            <a href="?delete_project=<?php echo $project['id']; ?>" onclick="return confirm('Hapus proyek ini?')" class="bg-accent-danger text-white px-3 py-1 rounded text-xs flex items-center gap-1"><i class="fas fa-trash"></i>Hapus</a>
                                        </div>
                                    </div>
                                </li>
                        <?php
                            }
                        } else {
                            echo '<li class="text-text-secondary">Belum ada data proyek.</li>';
                        }
                        ?>
                    </ul>
                </div>
                <!-- Modal Edit Project -->
                <div id="projectModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                    <div class="glass-card rounded-2xl p-8 w-full max-w-xl relative border border-border-color">
                        <button onclick="closeProjectModal()" class="absolute top-2 right-2 text-gray-400 hover:text-accent-warning text-2xl">&times;</button>
                        <h3 class="text-xl font-bold mb-4 gradient-text flex items-center gap-2"><i class="fas fa-edit"></i>Edit Proyek</h3>
                        <form method="post" enctype="multipart/form-data">
                            <input type="hidden" name="project_id" id="modal_project_id">
                            <input type="hidden" name="project_image_old" id="modal_project_image_old">
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Judul Proyek</label>
                                <input type="text" name="project_title" id="modal_project_title" class="w-full bg-dark-surface border border-border-color rounded-lg px-4 py-2 text-text-primary" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Teknologi</label>
                                <input type="text" name="project_tech" id="modal_project_tech" class="w-full bg-dark-surface border border-border-color rounded-lg px-4 py-2 text-text-primary" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Deskripsi</label>
                                <textarea name="project_desc" id="modal_project_desc" rows="3" class="w-full bg-dark-surface border border-border-color rounded-lg px-4 py-2 text-text-primary" required></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Gambar/Poster</label>
                                <input type="file" name="project_image" accept="image/*" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-accent-primary/20 file:text-accent-primary hover:file:bg-accent-primary/40" />
                                <div class="mt-2 text-xs text-gray-400">Biarkan kosong jika tidak ingin mengganti gambar.</div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Link Github</label>
                                <input type="url" name="project_github" id="modal_project_github" class="w-full bg-dark-surface border border-border-color rounded-lg px-4 py-2 text-text-primary" placeholder="https://github.com/username/repo">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Link Demo</label>
                                <input type="url" name="project_demo" id="modal_project_demo" class="w-full bg-dark-surface border border-border-color rounded-lg px-4 py-2 text-text-primary" placeholder="https://demo.com">
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" name="edit_project" class="bg-accent-primary text-white px-6 py-2 rounded-lg font-semibold hover:bg-accent-secondary transition-colors flex items-center gap-2"><i class="fas fa-save"></i> Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        function openProjectModal(id, title, tech, desc, image, github, demo) {
            document.getElementById('modal_project_id').value = id;
            document.getElementById('modal_project_title').value = title;
            document.getElementById('modal_project_tech').value = tech;
            document.getElementById('modal_project_desc').value = desc;
            document.getElementById('modal_project_image_old').value = image;
            document.getElementById('modal_project_github').value = github;
            document.getElementById('modal_project_demo').value = demo;
            document.getElementById('projectModal').classList.remove('hidden');
        }

        function closeProjectModal() {
            document.getElementById('projectModal').classList.add('hidden');
        }
    </script>
</body>

</html>