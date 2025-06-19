<?php

include 'db.php';


// --- Handle Profile Image Upload ---
$profileImages = [];
$profileImagesDir = '../images/';
$profileImagesDb = []; // Will hold up to 3 image filenames from DB

// Fetch current profile images from DB (assuming a table 'profile_images' with columns: id, filename, is_active)
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
    $activeProfileImage = 'Profile.jpg'; // default
}

// Handle upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profileImage'])) {
    $file = $_FILES['profileImage'];
    if ($file['error'] === UPLOAD_ERR_OK && $file['size'] <= 2 * 1024 * 1024) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
            $newName = 'profile_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
            $target = $profileImagesDir . $newName;
            if (move_uploaded_file($file['tmp_name'], $target)) {
                // Insert new image to DB, set as active, others as inactive
                $conn->query("UPDATE profile_images SET is_active=0");
                $conn->query("INSERT INTO profile_images (filename, is_active) VALUES ('{$conn->real_escape_string($newName)}', 1)");
                // If more than 3, delete the oldest (but do not delete file)
                $result = $conn->query("SELECT id, filename FROM profile_images ORDER BY id DESC");
                $images = [];
                while ($row = $result->fetch_assoc()) $images[] = $row;
                if (count($images) > 3) {
                    $toKeep = array_slice($images, 0, 3);
                    $toDelete = array_slice($images, 3);
                    foreach ($toDelete as $img) {
                        $conn->query("DELETE FROM profile_images WHERE id=" . intval($img['id']));
                        // Do NOT unlink($profileImagesDir . $img['filename']); // keep file
                    }
                }
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            }
        }
    }
}

// Handle switch to old image
if (isset($_GET['use_profile']) && is_numeric($_GET['use_profile'])) {
    $id = intval($_GET['use_profile']);
    $conn->query("UPDATE profile_images SET is_active=0");
    $conn->query("UPDATE profile_images SET is_active=1 WHERE id=$id");
    header("Location: " . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}


// --- Fetch Profile Data ---

// --- Ambil data profil dari database (tabel: profile, kolom: full_name, profession, birth_place, birth_date) ---
$profileData = [
    'full_name' => '',
    'profession' => '',
    'birth_place' => '',
    'birth_date' => ''
];
$profileSql = "SELECT * FROM profile LIMIT 1";
$profileResult = $conn->query($profileSql);
if ($profileResult && $profileResult->num_rows > 0) {
    $profileData = $profileResult->fetch_assoc();
}

// --- Handle update profile (AJAX/POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_profile'])) {
    $fullName = $conn->real_escape_string($_POST['fullName']);
    $profession = $conn->real_escape_string($_POST['profession']);
    $birthPlace = $conn->real_escape_string($_POST['birthPlace']);
    $birthDate = $conn->real_escape_string($_POST['birthDate']);

    // Cek apakah sudah ada data
    $check = $conn->query("SELECT id FROM profile LIMIT 1");
    if ($check && $check->num_rows > 0) {
        $conn->query("UPDATE profile SET full_name='$fullName', profession='$profession', birth_place='$birthPlace', birth_date='$birthDate' LIMIT 1");
    } else {
        $conn->query("INSERT INTO profile (full_name, profession, birth_place, birth_date) VALUES ('$fullName', '$profession', '$birthPlace', '$birthDate')");
    }
    // Refresh data
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}


// --- Fetch About Data from DB (table: about, columns: main_description, additional_description) ---
$aboutData = [
    'main_description' => '',
    'additional_description' => ''
];
$aboutSql = "SELECT * FROM about LIMIT 1";
$aboutResult = $conn->query($aboutSql);
if ($aboutResult && $aboutResult->num_rows > 0) {
    $aboutData = $aboutResult->fetch_assoc();
}

// --- Handle update about (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_about'])) {
    $mainDesc = $conn->real_escape_string($_POST['mainDescription']);
    $addDesc = $conn->real_escape_string($_POST['additionalDescription']);
    $check = $conn->query("SELECT id FROM about LIMIT 1");
    if ($check && $check->num_rows > 0) {
        $conn->query("UPDATE about SET main_description='$mainDesc', additional_description='$addDesc' LIMIT 1");
    } else {
        $conn->query("INSERT INTO about (main_description, additional_description) VALUES ('$mainDesc', '$addDesc')");
    }
    header("Location: " . $_SERVER['PHP_SELF'] . "#about-tab");
    exit;
}

// --- Handle add education ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_education'])) {
    $institution = $conn->real_escape_string($_POST['edu_institution']);
    $program = $conn->real_escape_string($_POST['edu_program']);
    $description = $conn->real_escape_string($_POST['edu_description']);
    $conn->query("INSERT INTO education (institution, program, description) VALUES ('$institution', '$program', '$description')");
    header("Location: " . $_SERVER['PHP_SELF'] . "#about-tab");
    exit;
}

// --- Handle add organization ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_organization'])) {
    $name = $conn->real_escape_string($_POST['org_name']);
    $position = $conn->real_escape_string($_POST['org_position']);
    $description = $conn->real_escape_string($_POST['org_description']);
    $conn->query("INSERT INTO organization (name, position, description) VALUES ('$name', '$position', '$description')");
    header("Location: " . $_SERVER['PHP_SELF'] . "#about-tab");
    exit;
}

// --- Handle add skill ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_skill'])) {
    $name = $conn->real_escape_string($_POST['skill_name']);
    $category = $conn->real_escape_string($_POST['skill_category']);
    $level = intval($_POST['skill_level']);
    $iconPath = null;

    // Handle file upload for skill icon
    if (isset($_FILES['skill_icon']) && $_FILES['skill_icon']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['skill_icon'];
        if ($file['size'] <= 500 * 1024) { // Max 500KB
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['png', 'svg'])) {
                $iconName = 'skill_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                $iconTarget = $profileImagesDir . $iconName;
                if (move_uploaded_file($file['tmp_name'], $iconTarget)) {
                    $iconPath = $conn->real_escape_string($iconTarget);
                }
            }
        }
    }

    $conn->query("INSERT INTO skills (name, category, level, path_icon) VALUES ('$name', '$category', $level, '$iconPath')");
    header("Location: " . $_SERVER['PHP_SELF'] . "#skills-tab");
    exit;
}

// --- Handle add article ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_article'])) {
    $title = $conn->real_escape_string($_POST['article_title']);
    $date = $conn->real_escape_string($_POST['article_date']);
    $excerpt = $conn->real_escape_string($_POST['article_excerpt']);
    $content = $conn->real_escape_string($_POST['article_content']);
    $image = $conn->real_escape_string($_POST['article_image']);
    $conn->query("INSERT INTO articles (title, excerpt, content, image, publish_date) VALUES ('$title', '$excerpt', '$content', '$image', '$date')");
    header("Location: " . $_SERVER['PHP_SELF'] . "#articles-tab");
    exit;
}

// --- Handle add project ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_project'])) {
    $title = $conn->real_escape_string($_POST['project_title']);
    $desc = $conn->real_escape_string($_POST['project_desc']);
    $tech = $conn->real_escape_string($_POST['project_tech']);
    $image = $conn->real_escape_string($_POST['project_image']);
    $github = $conn->real_escape_string($_POST['project_github']);
    $demo = $conn->real_escape_string($_POST['project_demo']);
    $conn->query("INSERT INTO projects (title, description, technologies, image, github, demo) VALUES ('$title', '$desc', '$tech', '$image', '$github', '$demo')");
    header("Location: " . $_SERVER['PHP_SELF'] . "#projects-tab");
    exit;
}

// --- Handle save contact & social media ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_contact'])) {
    $email = $conn->real_escape_string($_POST['contact_email']);
    $phone = $conn->real_escape_string($_POST['contact_phone']);
    $location = $conn->real_escape_string($_POST['contact_location']);
    $twitter = $conn->real_escape_string($_POST['social_twitter']);
    $linkedin = $conn->real_escape_string($_POST['social_linkedin']);
    $github = $conn->real_escape_string($_POST['social_github']);
    $check = $conn->query("SELECT id FROM contact LIMIT 1");
    if ($check && $check->num_rows > 0) {
        $conn->query("UPDATE contact SET email='$email', phone='$phone', location='$location', twitter='$twitter', linkedin='$linkedin', github='$github' LIMIT 1");
    } else {
        $conn->query("INSERT INTO contact (email, phone, location, twitter, linkedin, github) VALUES ('$email', '$phone', '$location', '$twitter', '$linkedin', '$github')");
    }
    header("Location: " . $_SERVER['PHP_SELF'] . "#contact-tab");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Portfolio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'dark-bg': '#2D2D2D',
                        'light-surface': '#F5F5F5',
                        'accent-green': '#1D4ED8',
                        'warm-wood': '#D4A574'
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
    </style>
</head>

<body class="bg-dark-bg text-light-surface min-h-screen">
    <!-- Admin Header -->
    <header class="bg-gradient-to-r from-warm-wood to-accent-green shadow-lg">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">Admin Panel Portfolio</h1>
            </div>
        </div>
    </header>

    <!-- Navigation Tabs -->
    <nav class="bg-gray-900 border-b border-gray-800 shadow-md">
        <div class="container mx-auto px-6">
            <div class="flex flex-wrap items-center justify-between">
                <div class="flex space-x-2 md:space-x-6 overflow-x-auto py-2 w-full md:w-auto">
                    <button class="tab-btn active py-3 px-5 md:px-6 text-warm-wood font-semibold border-b-2 border-warm-wood transition-colors duration-200 whitespace-nowrap focus:outline-none"
                        data-tab="profile">
                        <span class="mr-2">👤</span>Profil
                    </button>
                    <button class="tab-btn py-3 px-5 md:px-6 text-gray-300 hover:text-warm-wood font-semibold border-b-2 border-transparent hover:border-warm-wood transition-colors duration-200 whitespace-nowrap focus:outline-none"
                        data-tab="about">
                        <span class="mr-2">📝</span>Tentang
                    </button>
                    <button class="tab-btn py-3 px-5 md:px-6 text-gray-300 hover:text-warm-wood font-semibold border-b-2 border-transparent hover:border-warm-wood transition-colors duration-200 whitespace-nowrap focus:outline-none"
                        data-tab="skills">
                        <span class="mr-2">🛠️</span>Skills
                    </button>
                    <button class="tab-btn py-3 px-5 md:px-6 text-gray-300 hover:text-warm-wood font-semibold border-b-2 border-transparent hover:border-warm-wood transition-colors duration-200 whitespace-nowrap focus:outline-none"
                        data-tab="projects">
                        <span class="mr-2">🚀</span>Proyek
                    </button>
                    <button class="tab-btn py-3 px-5 md:px-6 text-gray-300 hover:text-warm-wood font-semibold border-b-2 border-transparent hover:border-warm-wood transition-colors duration-200 whitespace-nowrap focus:outline-none"
                        data-tab="articles">
                        <span class="mr-2">📰</span>Artikel
                    </button>
                    <button class="tab-btn py-3 px-5 md:px-6 text-gray-300 hover:text-warm-wood font-semibold border-b-2 border-transparent hover:border-warm-wood transition-colors duration-200 whitespace-nowrap focus:outline-none"
                        data-tab="contact">
                        <span class="mr-2">📞</span>Kontak
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-8">
        <!-- Profile Tab -->
        <div id="profile-tab" class="tab-content">
            <div class="glass-effect rounded-xl p-8 mb-8">
                <h2 class="text-3xl font-bold text-warm-wood mb-6">Informasi Profil</h2>

                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Profile Picture -->
                    <div>
                        <form method="post" enctype="multipart/form-data">
                            <label class="block text-sm font-medium mb-2">Foto Profil</label>
                            <div class="flex items-center space-x-4">
                                <img id="profilePreview" src="<?php echo htmlspecialchars($profileImagesDir . $activeProfileImage); ?>" alt="Profile" class="w-32 h-32 rounded-full object-cover border-4 border-warm-wood">
                                <div>
                                    <input type="file" id="profileImage" name="profileImage" accept="image/*" class="hidden" onchange="this.form.submit()">
                                    <button type="button" onclick="document.getElementById('profileImage').click()" class="bg-warm-wood text-dark-bg px-4 py-2 rounded-lg hover:bg-opacity-90">
                                        Ubah Foto
                                    </button>
                                    <p class="text-xs text-gray-400 mt-2">JPG, PNG max 2MB</p>
                                </div>
                            </div>
                        </form>
                        <?php if (!empty($profileImagesDb) && count($profileImagesDb) > 1): ?>
                            <div class="mt-6">
                                <label class="block text-xs font-semibold mb-2 text-gray-400">Pilih Foto Sebelumnya:</label>
                                <div class="flex flex-wrap gap-3">
                                    <?php foreach ($profileImagesDb as $img): ?>
                                        <form method="get" style="display:inline;">
                                            <input type="hidden" name="use_profile" value="<?php echo $img['id']; ?>">
                                            <button type="submit"
                                                class="relative group focus:outline-none transition-shadow duration-200 <?php echo $img['is_active'] ? 'ring-4 ring-warm-wood' : 'hover:ring-2 hover:ring-warm-wood'; ?>">
                                                <img src="<?php echo htmlspecialchars($profileImagesDir . $img['filename']); ?>"
                                                    alt="Old Profile"
                                                    class="w-14 h-14 rounded-full object-cover border-2 <?php echo $img['is_active'] ? 'border-warm-wood' : 'border-gray-500'; ?> shadow-md transition-transform duration-200 group-hover:scale-105">
                                                <?php if ($img['is_active']): ?>
                                                    <span class="absolute -top-2 -right-2 bg-warm-wood text-xs text-white rounded-full px-2 py-0.5 shadow">Aktif</span>
                                                <?php endif; ?>
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
                            <div>
                                <label class="block text-sm font-medium mb-2">Nama Lengkap</label>
                                <input type="text" name="fullName" value="<?php echo htmlspecialchars($profileData['full_name']); ?>" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Profesi</label>
                                <input type="text" name="profession" value="<?php echo htmlspecialchars($profileData['profession']); ?>" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Tempat Lahir</label>
                                <input type="text" name="birthPlace" value="<?php echo htmlspecialchars($profileData['birth_place']); ?>" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none mb-2" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Tanggal Lahir</label>
                                <input type="date" name="birthDate" value="<?php echo htmlspecialchars($profileData['birth_date']); ?>" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none" required>
                            </div>
                            <!-- Tombol Simpan khusus Profile -->
                            <div class="mt-8 flex justify-end">
                                <button type="submit" name="save_profile" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2 shadow">
                                    💾 Simpan Profil
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- About Tab -->
        <div id="about-tab" class="tab-content hidden">
            <div class="glass-effect rounded-xl p-8 mb-8">
                <h2 class="text-3xl font-bold text-warm-wood mb-6">Tentang Saya</h2>

                <form method="post">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">Deskripsi Utama</label>
                            <textarea name="mainDescription" rows="4" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none" required><?php echo htmlspecialchars($aboutData['main_description']); ?></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Deskripsi Tambahan</label>
                            <textarea name="additionalDescription" rows="4" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none" required><?php echo htmlspecialchars($aboutData['additional_description']); ?></textarea>
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end">
                        <button type="submit" name="save_about" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2 shadow">
                            💾 Simpan Tentang
                        </button>
                    </div>
                </form>
            </div>

            <!-- Education Section -->
            <div class="glass-effect rounded-xl p-8 mb-8">
                <h3 class="text-2xl font-bold text-warm-wood mb-6">Pendidikan</h3>
                <form method="post">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Nama Institusi</label>
                            <input type="text" name="edu_institution" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Program Studi & Semester</label>
                            <input type="text" name="edu_program" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Deskripsi</label>
                            <textarea name="edu_description" rows="2" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none" required></textarea>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" name="add_education" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2 shadow">
                            + Tambah Pendidikan
                        </button>
                    </div>
                </form>
                <div class="mt-6">
                    <h4 class="text-lg font-semibold mb-2 text-warm-wood">Daftar Pendidikan</h4>
                    <ul class="space-y-2">
                        <?php
                        $eduQ = $conn->query("SELECT * FROM education ORDER BY id DESC");
                        if ($eduQ && $eduQ->num_rows > 0) {
                            while ($edu = $eduQ->fetch_assoc()) {
                                echo '<li class="bg-gray-900 rounded p-3 border border-gray-700">'
                                    . '<b>' . htmlspecialchars($edu['institution']) . '</b> - ' . htmlspecialchars($edu['program']) . '<br>'
                                    . '<span class="text-xs text-gray-400">' . htmlspecialchars($edu['description']) . '</span>'
                                    . '</li>';
                            }
                        } else {
                            echo '<li class="text-gray-400">Belum ada data pendidikan.</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>

            <!-- Organization Section -->
            <div class="glass-effect rounded-xl p-8">
                <h3 class="text-2xl font-bold text-warm-wood mb-6">Organisasi</h3>
                <form method="post">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Nama Organisasi</label>
                            <input type="text" name="org_name" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Posisi & Angkatan</label>
                            <input type="text" name="org_position" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Deskripsi</label>
                            <textarea name="org_description" rows="2" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none" required></textarea>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" name="add_organization" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2 shadow">
                            + Tambah Organisasi
                        </button>
                    </div>
                </form>
                <div class="mt-6">
                    <h4 class="text-lg font-semibold mb-2 text-warm-wood">Daftar Organisasi</h4>
                    <ul class="space-y-2">
                        <?php
                        $orgQ = $conn->query("SELECT * FROM organization ORDER BY id DESC");
                        if ($orgQ && $orgQ->num_rows > 0) {
                            while ($org = $orgQ->fetch_assoc()) {
                                echo '<li class="bg-gray-900 rounded p-3 border border-gray-700">'
                                    . '<b>' . htmlspecialchars($org['name']) . '</b> - ' . htmlspecialchars($org['position']) . '<br>'
                                    . '<span class="text-xs text-gray-400">' . htmlspecialchars($org['description']) . '</span>'
                                    . '</li>';
                            }
                        } else {
                            echo '<li class="text-gray-400">Belum ada data organisasi.</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Skills Tab -->
        <div id="skills-tab" class="tab-content hidden">
            <div class="glass-effect rounded-xl p-8">
                <h2 class="text-3xl font-bold text-warm-wood mb-6">Skills & Keahlian</h2>
                <form method="post" enctype="multipart/form-data" class="mb-6">
                    <div class="grid md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Nama Skill</label>
                            <input type="text" name="skill_name" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Kategori</label>
                            <input type="text" name="skill_category" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Level (%)</label>
                            <input type="number" name="skill_level" min="0" max="100" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Icon (PNG/SVG, max 500KB)</label>
                            <input type="file" name="skill_icon" accept=".png,.svg" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none">
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" name="add_skill" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2 shadow">
                            + Tambah Skill
                        </button>
                    </div>
                </form>
                <div class="mt-6">
                    <h4 class="text-lg font-semibold mb-2 text-warm-wood">Daftar Skills</h4>
                    <ul class="space-y-2">
                        <?php
                        $skillQ = $conn->query("SELECT * FROM skills ORDER BY id DESC");
                        if ($skillQ && $skillQ->num_rows > 0) {
                            while ($skill = $skillQ->fetch_assoc()) {
                                echo '<li class="bg-gray-900 rounded p-3 border border-gray-700 flex items-center gap-3">';
                                if (!empty($skill['icon_path'])) {
                                    echo '<img src="' . htmlspecialchars($skill['icon_path']) . '" alt="icon" class="w-8 h-8 object-contain rounded">';
                                }
                                echo '<div><b>' . htmlspecialchars($skill['name']) . '</b> - ' . htmlspecialchars($skill['category']) . ' (' . intval($skill['level']) . '%)</div>';
                                echo '</li>';
                            }
                        } else {
                            echo '<li class="text-gray-400">Belum ada data skill.</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Projects Tab -->
        <div id="projects-tab" class="tab-content hidden">
            <div class="glass-effect rounded-xl p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-warm-wood">Proyek</h2>
                </div>
                <form method="post" class="mb-6">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Judul Proyek</label>
                            <input type="text" name="project_title" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Teknologi (pisahkan dengan koma)</label>
                            <input type="text" name="project_tech" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium mb-2">Deskripsi</label>
                        <textarea name="project_desc" rows="3" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none" required></textarea>
                    </div>
                    <div class="grid md:grid-cols-3 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Gambar (URL/Path)</label>
                            <input type="text" name="project_image" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">GitHub URL</label>
                            <input type="text" name="project_github" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Demo URL</label>
                            <input type="text" name="project_demo" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" name="add_project" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2 shadow">
                            + Tambah Proyek
                        </button>
                    </div>
                </form>
                <div class="mt-6">
                    <h4 class="text-lg font-semibold mb-2 text-warm-wood">Daftar Proyek</h4>
                    <ul class="space-y-2">
                        <?php
                        $projectQ = $conn->query("SELECT * FROM projects ORDER BY id DESC");
                        if ($projectQ && $projectQ->num_rows > 0) {
                            while ($prj = $projectQ->fetch_assoc()) {
                                echo '<li class="bg-gray-900 rounded p-3 border border-gray-700">'
                                    . '<b>' . htmlspecialchars($prj['title']) . '</b> <span class="text-xs text-gray-400">[' . htmlspecialchars($prj['technologies']) . ']</span><br>'
                                    . '<span class="text-xs text-gray-400">' . htmlspecialchars($prj['description']) . '</span>'
                                    . '</li>';
                            }
                        } else {
                            echo '<li class="text-gray-400">Belum ada data proyek.</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Articles Tab -->
        <div id="articles-tab" class="tab-content hidden">
            <div class="glass-effect rounded-xl p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-warm-wood">Artikel</h2>
                </div>
                <form method="post" class="mb-6">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Judul Artikel</label>
                            <input type="text" name="article_title" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Tanggal Publish</label>
                            <input type="date" name="article_date" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none" required>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium mb-2">Ringkasan/Excerpt</label>
                        <textarea name="article_excerpt" rows="2" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none" required></textarea>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium mb-2">Konten</label>
                        <textarea name="article_content" rows="5" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none" required></textarea>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium mb-2">Gambar (URL/Path)</label>
                        <input type="text" name="article_image" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none">
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" name="add_article" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2 shadow">
                            + Tambah Artikel
                        </button>
                    </div>
                </form>
                <div class="mt-6">
                    <h4 class="text-lg font-semibold mb-2 text-warm-wood">Daftar Artikel</h4>
                    <ul class="space-y-2">
                        <?php
                        $articleQ = $conn->query("SELECT * FROM articles ORDER BY id DESC");
                        if ($articleQ && $articleQ->num_rows > 0) {
                            while ($art = $articleQ->fetch_assoc()) {
                                echo '<li class="bg-gray-900 rounded p-3 border border-gray-700">'
                                    . '<b>' . htmlspecialchars($art['title']) . '</b> <span class="text-xs text-gray-400">(' . htmlspecialchars($art['publish_date']) . ')</span><br>'
                                    . '<span class="text-xs text-gray-400">' . htmlspecialchars($art['excerpt']) . '</span>'
                                    . '</li>';
                            }
                        } else {
                            echo '<li class="text-gray-400">Belum ada data artikel.</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Contact Tab -->
        <div id="contact-tab" class="tab-content hidden">
            <div class="glass-effect rounded-xl p-8">
                <h2 class="text-3xl font-bold text-warm-wood mb-6">Informasi Kontak & Sosial Media</h2>
                <form method="post">
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Email</label>
                                <input type="email" name="contact_email" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Telepon</label>
                                <input type="tel" name="contact_phone" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Lokasi</label>
                                <input type="text" name="contact_location" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none">
                            </div>
                        </div>
                        <div class="space-y-4">
                            <h3 class="text-xl font-semibold text-warm-wood">Social Media</h3>
                            <div>
                                <label class="block text-sm font-medium mb-2">Twitter</label>
                                <input type="url" name="social_twitter" placeholder="https://twitter.com/username" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">LinkedIn</label>
                                <input type="url" name="social_linkedin" placeholder="https://linkedin.com/in/username" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">GitHub</label>
                                <input type="url" name="social_github" placeholder="https://github.com/username" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none">
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end">
                        <button type="submit" name="save_contact" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2 shadow">
                            💾 Simpan Kontak & Sosial Media
                        </button>
                    </div>
                </form>
                <div class="mt-6">
                    <h4 class="text-lg font-semibold mb-2 text-warm-wood">Data Kontak & Sosial Media Saat Ini</h4>
                    <ul class="space-y-2">
                        <?php
                        $contactQ = $conn->query("SELECT * FROM contact ORDER BY id DESC LIMIT 1");
                        if ($contactQ && $contactQ->num_rows > 0) {
                            $c = $contactQ->fetch_assoc();
                            echo '<li class="bg-gray-900 rounded p-3 border border-gray-700">'
                                . '<b>Email:</b> ' . htmlspecialchars($c['email']) . '<br>'
                                . '<b>Telepon:</b> ' . htmlspecialchars($c['phone']) . '<br>'
                                . '<b>Lokasi:</b> ' . htmlspecialchars($c['location']) . '<br>'
                                . '<b>Twitter:</b> ' . htmlspecialchars($c['twitter']) . '<br>'
                                . '<b>LinkedIn:</b> ' . htmlspecialchars($c['linkedin']) . '<br>'
                                . '<b>GitHub:</b> ' . htmlspecialchars($c['github']) . '</li>';
                        } else {
                            echo '<li class="text-gray-400">Belum ada data kontak/sosial media.</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <!-- Success/Error Messages -->
    <div id="messageContainer" class="fixed top-4 right-4 z-50"></div>

    <script>
        // Tab Navigation
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');
        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active from all
                tabBtns.forEach(b => b.classList.remove('active', 'text-warm-wood', 'border-warm-wood'));
                tabContents.forEach(c => c.classList.add('hidden'));
                // Set active
                btn.classList.add('active', 'text-warm-wood', 'border-warm-wood');
                const tab = btn.getAttribute('data-tab');
                document.getElementById(tab + '-tab').classList.remove('hidden');
            });
        });
    </script>
</body>

</html>