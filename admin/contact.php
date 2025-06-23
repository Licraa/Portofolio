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
                        <img src="../images/Logo.png" alt="Logo" class="w-8 h-8 rounded-lg">
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
            <div class="mt-8 p-6 glass-card rounded-2xl">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 gradient-primary rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white text-xl"></i>
                    </div>
                    <div>
                        <span class="font-semibold text-lg text-text-primary">Admin</span>
                    </div>
                </div>
                <form method="post" action="logout.php">
                    <button type="submit" class="w-full bg-gradient-to-r from-accent-danger to-red-600 text-white py-3 rounded-xl font-semibold hover:shadow-2xl transition-all duration-300 flex items-center gap-3 justify-center group">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </button>
                </form>
            </div>
        </aside>
        <!-- Main Content -->
        <main class="flex-1 p-8 md:p-12 bg-dark-bg min-h-screen">
            <div class="max-w-4xl mx-auto">
                <div class="glass-card rounded-3xl p-8 shadow-2xl border border-border-color mb-8">
                    <h2 class="text-3xl font-bold gradient-text mb-8 flex items-center gap-3">
                        <i class="fas fa-address-book text-accent-primary"></i>
                        Kelola Kontak & Sosial Media
                    </h2>
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
                    <?php if (isset($notif)): ?>
                        <div class="mt-4 p-4 rounded-xl <?php echo $notif['type'] === 'success' ? 'notif-success' : 'notif-danger'; ?> shadow-md flex items-center gap-3">
                            <i class="fas fa-info-circle"></i>
                            <span><?php echo htmlspecialchars($notif['msg']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- Pesan Masuk -->
                <div class="glass-card rounded-3xl p-8 shadow-2xl border border-border-color mb-8 mt-12">
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
</body>

</html>