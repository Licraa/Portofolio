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
// --- Handle Add Article ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_article'])) {
    $title = $conn->real_escape_string($_POST['article_title']);
    $date = $conn->real_escape_string($_POST['article_date']);
    $excerpt = $conn->real_escape_string($_POST['article_excerpt']);
    $content = $conn->real_escape_string($_POST['article_content']);
    $image = $conn->real_escape_string($_POST['article_image']);
    $conn->query("INSERT INTO articles (title, excerpt, content, image, publish_date) VALUES ('$title', '$excerpt', '$content', '$image', '$date')");
    $_SESSION['notif'] = ['type' => 'success', 'msg' => 'Artikel berhasil ditambah!'];
    header("Location: articles.php");
    exit;
}
// --- Handle Edit Article ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_article'])) {
    $id = intval($_POST['article_id']);
    $title = $conn->real_escape_string($_POST['article_title']);
    $date = $conn->real_escape_string($_POST['article_date']);
    $excerpt = $conn->real_escape_string($_POST['article_excerpt']);
    $content = $conn->real_escape_string($_POST['article_content']);
    $image = $conn->real_escape_string($_POST['article_image']);
    $conn->query("UPDATE articles SET title='$title', excerpt='$excerpt', content='$content', image='$image', publish_date='$date' WHERE id=$id");
    $_SESSION['notif'] = ['type' => 'success', 'msg' => 'Artikel berhasil diupdate!'];
    header("Location: articles.php");
    exit;
}
// --- Handle Delete Article ---
if (isset($_GET['delete_article']) && is_numeric($_GET['delete_article'])) {
    $id = intval($_GET['delete_article']);
    $conn->query("DELETE FROM articles WHERE id=$id");
    $_SESSION['notif'] = ['type' => 'success', 'msg' => 'Artikel berhasil dihapus!'];
    header("Location: articles.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Artikel</title>
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
                <a href="project.php" class="nav-item flex items-center gap-4 px-6 py-4 rounded-2xl font-semibold transition-all duration-300 hover:bg-accent-primary/10 hover:text-accent-primary text-text-secondary">
                    <i class="fas fa-rocket text-lg w-5"></i>
                    <span>Proyek</span>
                </a>
                <a href="articles.php" class="nav-item flex items-center gap-4 px-6 py-4 rounded-2xl font-semibold transition-all duration-300 bg-accent-primary/20 text-accent-primary border border-accent-primary/30">
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
        <!-- Main Content -->
        <main class="flex-1 p-8 md:p-12 bg-dark-bg min-h-screen">
            <div class="max-w-4xl mx-auto">
                <div class="glass-card rounded-3xl p-8 shadow-2xl border border-border-color mb-8">
                    <h2 class="text-3xl font-bold gradient-text mb-8 flex items-center gap-3"><i class="fas fa-edit"></i>Kelola Artikel</h2>
                    <form method="post" class="mb-8">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Judul Artikel</label>
                                <input type="text" name="article_title" class="w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Tanggal Publish</label>
                                <input type="date" name="article_date" class="w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary" required>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium mb-2">Ringkasan/Excerpt</label>
                            <textarea name="article_excerpt" rows="2" class="w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary" required></textarea>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium mb-2">Konten</label>
                            <textarea name="article_content" rows="5" class="w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary" required></textarea>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium mb-2">Gambar (URL/Path)</label>
                            <input type="text" name="article_image" class="w-full rounded-lg bg-dark-surface border border-border-color px-4 py-2 text-text-primary focus:ring-2 focus:ring-accent-primary">
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button type="submit" name="add_article" class="bg-accent-success text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2 shadow">
                                <i class="fas fa-plus"></i> Tambah Artikel
                            </button>
                        </div>
                    </form>
                    <div class="mt-6">
                        <h4 class="text-lg font-semibold mb-2 gradient-text flex items-center gap-2"><i class="fas fa-list"></i>Daftar Artikel</h4>
                        <ul class="space-y-2">
                            <?php
                            // Fungsi js_escape hanya dideklarasikan sekali
                            if (!function_exists('js_escape')) {
                                function js_escape($str)
                                {
                                    return str_replace(["\\", "'", "\n", "\r"], ["\\\\", "\\'", "\\n", "\\r"], $str);
                                }
                            }
                            $articleQ = $conn->query("SELECT * FROM articles ORDER BY id DESC");
                            if ($articleQ && $articleQ->num_rows > 0) {
                                while ($art = $articleQ->fetch_assoc()) {
                                    $js = "openArticleModal('" . $art['id'] . "', '"
                                        . js_escape($art['title']) . "', '"
                                        . js_escape($art['publish_date']) . "', '"
                                        . js_escape($art['excerpt']) . "', '"
                                        . js_escape($art['content']) . "', '"
                                        . js_escape($art['image']) . "')";
                                    echo '<li class="bg-dark-surface rounded p-3 border border-border-color flex flex-col md:flex-row md:items-center md:gap-4">';
                                    echo '<div class="flex-1">'
                                        . '<b class="text-text-primary">' . htmlspecialchars($art['title']) . '</b> <span class="text-xs text-accent-warning">(' . htmlspecialchars($art['publish_date']) . ')</span><br>'
                                        . '<span class="text-xs text-text-secondary">' . htmlspecialchars($art['excerpt']) . '</span>'
                                        . '</div>';
                                    echo '<div class="flex gap-2 mt-2 md:mt-0">';
                                    echo '<button type="button" onclick="' . $js . '" class="bg-accent-success text-white px-3 py-1 rounded text-xs flex items-center gap-1"><i class="fas fa-edit"></i>Edit</button>';
                                    echo '<a href="?delete_article=' . $art['id'] . '" onclick="return confirm(\'Hapus artikel ini?\')" class="bg-accent-danger text-white px-3 py-1 rounded text-xs flex items-center gap-1"><i class="fas fa-trash"></i>Hapus</a>';
                                    echo '</div>';
                                    echo '</li>';
                                }
                            } else {
                                echo '<li class="text-text-secondary">Belum ada data artikel.</li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <!-- Modal Edit Article -->
                <div id="articleModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                    <div class="glass-card rounded-2xl p-8 w-full max-w-lg relative border border-border-color">
                        <button onclick="closeArticleModal()" class="absolute top-2 right-2 text-gray-400 hover:text-accent-warning text-2xl">&times;</button>
                        <h3 class="text-xl font-bold mb-4 gradient-text flex items-center gap-2"><i class="fas fa-edit"></i>Edit Artikel</h3>
                        <form method="post">
                            <input type="hidden" name="article_id" id="modal_article_id">
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Judul Artikel</label>
                                <input type="text" name="article_title" id="modal_article_title" class="w-full bg-dark-surface border border-border-color rounded-lg px-4 py-2 text-text-primary" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Tanggal Publish</label>
                                <input type="date" name="article_date" id="modal_article_date" class="w-full bg-dark-surface border border-border-color rounded-lg px-4 py-2 text-text-primary" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Ringkasan/Excerpt</label>
                                <textarea name="article_excerpt" id="modal_article_excerpt" rows="2" class="w-full bg-dark-surface border border-border-color rounded-lg px-4 py-2 text-text-primary"></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Konten</label>
                                <textarea name="article_content" id="modal_article_content" rows="5" class="w-full bg-dark-surface border border-border-color rounded-lg px-4 py-2 text-text-primary"></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Gambar (URL/Path)</label>
                                <input type="text" name="article_image" id="modal_article_image" class="w-full bg-dark-surface border border-border-color rounded-lg px-4 py-2 text-text-primary">
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" name="edit_article" class="bg-accent-primary text-white px-6 py-2 rounded-lg font-semibold hover:bg-accent-secondary transition-colors flex items-center gap-2"><i class="fas fa-save"></i> Simpan Perubahan</button>
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
        function openArticleModal(id, title, date, excerpt, content, image) {
            document.getElementById('modal_article_id').value = id;
            document.getElementById('modal_article_title').value = title;
            document.getElementById('modal_article_date').value = date;
            document.getElementById('modal_article_excerpt').value = excerpt;
            document.getElementById('modal_article_content').value = content;
            document.getElementById('modal_article_image').value = image;
            document.getElementById('articleModal').classList.remove('hidden');
        }

        function closeArticleModal() {
            document.getElementById('articleModal').classList.add('hidden');
        }
    </script>
</body>

</html>