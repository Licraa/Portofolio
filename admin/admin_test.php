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

// --- Handle update education ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_education'])) {
    $id = intval($_POST['edu_id']);
    $institution = $conn->real_escape_string($_POST['edu_institution']);
    $program = $conn->real_escape_string($_POST['edu_program']);
    $description = $conn->real_escape_string($_POST['edu_description']);
    $conn->query("UPDATE education SET institution='$institution', program='$program', description='$description' WHERE id=$id");
    header("Location: " . $_SERVER['PHP_SELF'] . "#about-tab");
    exit;
}
// --- Handle delete education ---
if (isset($_GET['delete_education']) && is_numeric($_GET['delete_education'])) {
    $id = intval($_GET['delete_education']);
    $conn->query("DELETE FROM education WHERE id=$id");
    header("Location: " . strtok($_SERVER['REQUEST_URI'], '?') . "#about-tab");
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

// --- Handle update organization ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_organization'])) {
    $id = intval($_POST['org_id']);
    $name = $conn->real_escape_string($_POST['org_name']);
    $position = $conn->real_escape_string($_POST['org_position']);
    $description = $conn->real_escape_string($_POST['org_description']);
    $conn->query("UPDATE organization SET name='$name', position='$position', description='$description' WHERE id=$id");
    header("Location: " . $_SERVER['PHP_SELF'] . "#about-tab");
    exit;
}
// --- Handle delete organization ---
if (isset($_GET['delete_organization']) && is_numeric($_GET['delete_organization'])) {
    $id = intval($_GET['delete_organization']);
    $conn->query("DELETE FROM organization WHERE id=$id");
    header("Location: " . strtok($_SERVER['REQUEST_URI'], '?') . "#about-tab");
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

// --- Handle update skill ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_skill'])) {
    $id = intval($_POST['skill_id']);
    $name = $conn->real_escape_string($_POST['skill_name']);
    $category = $conn->real_escape_string($_POST['skill_category']);
    $level = intval($_POST['skill_level']);
    $iconPath = $_POST['skill_icon_old'];
    // Jika upload icon baru
    if (isset($_FILES['skill_icon']) && $_FILES['skill_icon']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['skill_icon'];
        if ($file['size'] <= 500 * 1024) {
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['png', 'svg'])) {
                $iconName = 'skill_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                $iconTarget = $profileImagesDir . $iconName;
                if (move_uploaded_file($file['tmp_name'], $iconTarget)) {
                    $iconPath = $iconTarget;
                }
            }
        }
    }
    $conn->query("UPDATE skills SET name='$name', category='$category', level=$level, path_icon='$iconPath' WHERE id=$id");
    header("Location: " . $_SERVER['PHP_SELF'] . "#skills-tab");
    exit;
}
// --- Handle delete skill ---
if (isset($_GET['delete_skill']) && is_numeric($_GET['delete_skill'])) {
    $id = intval($_GET['delete_skill']);
    $conn->query("DELETE FROM skills WHERE id=$id");
    header("Location: " . strtok($_SERVER['REQUEST_URI'], '?') . "#skills-tab");
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
    $github = $conn->real_escape_string($_POST['project_github']);
    $demo = $conn->real_escape_string($_POST['project_demo']);
    $imagePath = '';
    if (isset($_FILES['project_image']) && $_FILES['project_image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['project_image'];
        if ($file['size'] <= 2 * 1024 * 1024) { // max 2MB
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                $imgName = 'project_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                $imgTarget = '../images/' . $imgName;
                if (move_uploaded_file($file['tmp_name'], $imgTarget)) {
                    $imagePath = 'images/' . $imgName;
                }
            }
        }
    }
    $conn->query("INSERT INTO projects (title, description, technologies, image, github, demo) VALUES ('$title', '$desc', '$tech', '$imagePath', '$github', '$demo')");
    header("Location: " . $_SERVER['PHP_SELF'] . "#projects-tab");
    exit;
}

// --- Handle update project ---
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
                $imgTarget = '../images/' . $imgName;
                if (move_uploaded_file($file['tmp_name'], $imgTarget)) {
                    $imagePath = 'images/' . $imgName;
                }
            }
        }
    }
    $conn->query("UPDATE projects SET title='$title', description='$desc', technologies='$tech', image='$imagePath', github='$github', demo='$demo' WHERE id=$id");
    header("Location: " . $_SERVER['PHP_SELF'] . "#projects-tab");
    exit;
}
// --- Handle delete project ---
if (isset($_GET['delete_project']) && is_numeric($_GET['delete_project'])) {
    $id = intval($_GET['delete_project']);
    $conn->query("DELETE FROM projects WHERE id=$id");
    header("Location: " . strtok($_SERVER['REQUEST_URI'], '?') . "#projects-tab");
    exit;
}

// --- Handle save contact & social media ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_contact'])) {
    $email = $conn->real_escape_string($_POST['contact_email']);
    $phone = intval($_POST['contact_phone']);
    $location = $conn->real_escape_string($_POST['contact_location']);
    // $twitter = $conn->real_escape_string($_POST['social_twitter']);
    // $linkedin = $conn->real_escape_string($_POST['social_linkedin']);
    // $github = $conn->real_escape_string($_POST['social_github']);
    $check = $conn->query("SELECT id FROM contact LIMIT 1");
    if ($check && $check->num_rows > 0) {
        $conn->query("UPDATE contact SET email='$email', phone='$phone', location='$location' LIMIT 1");
    } else {
        $conn->query("INSERT INTO contact (email, phone, location) VALUES ('$email', '$phone', '$location')");
    }
    header("Location: " . $_SERVER['PHP_SELF'] . "#contact-tab");
    exit;
}

// --- Handle add activity ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_activity'])) {
    $activity = $conn->real_escape_string($_POST['activity_name']);
    $description = $conn->real_escape_string($_POST['activity_description']);
    $conn->query("INSERT INTO activity (name, description) VALUES ('$activity', '$description')");
    header("Location: " . $_SERVER['PHP_SELF'] . "#about-tab");
    exit;
}
// --- Handle delete activity ---
if (isset($_GET['delete_activity']) && is_numeric($_GET['delete_activity'])) {
    $id = intval($_GET['delete_activity']);
    $conn->query("DELETE FROM activity WHERE id=$id");
    header("Location: " . strtok($_SERVER['REQUEST_URI'], '?') . "#about-tab");
    exit;
}
// --- Handle edit activity ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_activity'])) {
    $id = intval($_POST['activity_id']);
    $activity = $conn->real_escape_string($_POST['activity_name']);
    $description = $conn->real_escape_string($_POST['activity_description']);
    $conn->query("UPDATE activity SET name='$activity', description='$description' WHERE id=$id");
    header("Location: " . $_SERVER['PHP_SELF'] . "#about-tab");
    exit;
}

// Handle delete message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_message'])) {
    $id = intval($_POST['delete_message_id']);
    $conn->query("DELETE FROM contact_messages WHERE id=$id");
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
                    <button class="tab-btn py-3 px-5 md:px-6 text-gray-300 hover:text-warm-wood font-semibold border-b-2 border-transparent hover:border-warm-wood transition-colors duration-200 whitespace-nowrap focus:outline-none"
                        data-tab="contact-messages">
                        <span class="mr-2">📬</span>Pesan Masuk
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
                        ?>
                                <li class="bg-gray-900 rounded p-3 border border-gray-700 flex flex-col md:flex-row md:items-center md:gap-4">
                                    <div class="flex-1">
                                        <b><?php echo htmlspecialchars($edu['institution']); ?></b> - <?php echo htmlspecialchars($edu['program']); ?><br>
                                        <span class="text-xs text-gray-400"><?php echo htmlspecialchars($edu['description']); ?></span>
                                    </div>
                                    <div class="flex gap-2 mt-2 md:mt-0">
                                        <button type="button" onclick="openEduModal(<?php echo $edu['id']; ?>, '<?php echo htmlspecialchars(addslashes($edu['institution'])); ?>', '<?php echo htmlspecialchars(addslashes($edu['program'])); ?>', '<?php echo htmlspecialchars(addslashes($edu['description'])); ?>')" class="bg-green-600 text-white px-3 py-1 rounded text-xs">Edit</button>
                                        <a href="?delete_education=<?php echo $edu['id']; ?>#about-tab" onclick="return confirm('Hapus data pendidikan ini?')" class="bg-red-600 text-white px-3 py-1 rounded text-xs">Hapus</a>
                                    </div>
                                </li>
                        <?php
                            }
                        } else {
                            echo '<li class="text-gray-400">Belum ada data pendidikan.</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>

            <!-- Organization Section -->
            <div class="glass-effect rounded-xl p-8 mb-8">
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
                        ?>
                                <li class="bg-gray-900 rounded p-3 border border-gray-700 flex flex-col md:flex-row md:items-center md:gap-4">
                                    <div class="flex-1">
                                        <b><?php echo htmlspecialchars($org['name']); ?></b> - <?php echo htmlspecialchars($org['position']); ?><br>
                                        <span class="text-xs text-gray-400"><?php echo htmlspecialchars($org['description']); ?></span>
                                    </div>
                                    <div class="flex gap-2 mt-2 md:mt-0">
                                        <button type="button" onclick="openOrgModal(<?php echo $org['id']; ?>, '<?php echo htmlspecialchars(addslashes($org['name'])); ?>', '<?php echo htmlspecialchars(addslashes($org['position'])); ?>', '<?php echo htmlspecialchars(addslashes($org['description'])); ?>')" class="bg-green-600 text-white px-3 py-1 rounded text-xs">Edit</button>
                                        <a href="?delete_organization=<?php echo $org['id']; ?>#about-tab" onclick="return confirm('Hapus data organisasi ini?')" class="bg-red-600 text-white px-3 py-1 rounded text-xs">Hapus</a>
                                    </div>
                                </li>
                        <?php
                            }
                        } else {
                            echo '<li class="text-gray-400">Belum ada data organisasi.</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>

            <!-- Activity Section -->
            <div class="glass-effect rounded-xl p-8 mb-8">
                <h3 class="text-2xl font-bold text-warm-wood mb-6">Aktivitas</h3>
                <form method="post">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Nama Aktivitas</label>
                            <input type="text" name="activity_name" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Deskripsi</label>
                            <textarea name="activity_description" rows="2" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none" required></textarea>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" name="add_activity" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2 shadow">
                            + Tambah Aktivitas
                        </button>
                    </div>
                </form>
                <div class="mt-6">
                    <h4 class="text-lg font-semibold mb-2 text-warm-wood">Daftar Aktivitas</h4>
                    <ul class="space-y-2">
                        <?php
                        $activityQ = $conn->query("SELECT * FROM activity ORDER BY id DESC");
                        if ($activityQ && $activityQ->num_rows > 0) {
                            while ($act = $activityQ->fetch_assoc()) {
                                echo '<li class="bg-gray-900 rounded p-3 border border-gray-700 flex flex-col md:flex-row md:items-center md:gap-4">';
                                echo '<div class="flex-1"><b>' . htmlspecialchars($act['name']) . '</b><br><span class="text-xs text-gray-400">' . htmlspecialchars($act['description']) . '</span></div>';
                                echo '<div class="flex gap-2 mt-2 md:mt-0">';
                                echo '<form method="post" style="display:inline;">'
                                    . '<input type="hidden" name="activity_id" value="' . $act['id'] . '">'
                                    . '<input type="text" name="activity_name" value="' . htmlspecialchars($act['name']) . '" class="bg-gray-800 border border-gray-600 rounded px-2 py-1 text-xs w-32">'
                                    . '<input type="text" name="activity_description" value="' . htmlspecialchars($act['description']) . '" class="bg-gray-800 border border-gray-600 rounded px-2 py-1 text-xs w-48">'
                                    . '<button type="submit" name="edit_activity" class="bg-green-600 text-white px-2 py-1 rounded text-xs">Simpan</button>'
                                    . '</form>';
                                echo '<a href="?delete_activity=' . $act['id'] . '#about-tab" onclick="return confirm(\'Hapus aktivitas ini?\')" class="bg-red-600 text-white px-2 py-1 rounded text-xs">Hapus</a>';
                                echo '</div>';
                                echo '</li>';
                            }
                        } else {
                            echo '<li class="text-gray-400">Belum ada data aktivitas.</li>';
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
                        ?>
                                <li class="bg-gray-900 rounded p-3 border border-gray-700 flex items-center gap-3">
                                    <?php if (!empty($skill['path_icon'])): ?>
                                        <img src="<?php echo htmlspecialchars($skill['path_icon']); ?>" alt="icon" class="w-8 h-8 object-contain rounded">
                                    <?php endif; ?>
                                    <div class="flex-1">
                                        <b><?php echo htmlspecialchars($skill['name']); ?></b> - <?php echo htmlspecialchars($skill['category']); ?> (<?php echo intval($skill['level']); ?>%)
                                    </div>
                                    <button type="button" onclick="openSkillModal(<?php echo $skill['id']; ?>, '<?php echo htmlspecialchars(addslashes($skill['name'])); ?>', '<?php echo htmlspecialchars(addslashes($skill['category'])); ?>', '<?php echo intval($skill['level']); ?>', '<?php echo htmlspecialchars(addslashes($skill['path_icon'])); ?>')" class="bg-green-600 text-white px-3 py-1 rounded text-xs">Edit</button>
                                    <a href="?delete_skill=<?php echo $skill['id']; ?>#skills-tab" onclick="return confirm('Hapus skill ini?')" class="bg-red-600 text-white px-3 py-1 rounded text-xs">Hapus</a>
                                </li>
                        <?php
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
                <form method="post" enctype="multipart/form-data" class="mb-6">
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
                            <label class="block text-sm font-medium mb-2">Gambar Proyek (Upload JPG/PNG, max 2MB)</label>
                            <input type="file" name="project_image" accept=".jpg,.jpeg,.png" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 focus:border-warm-wood focus:outline-none">
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
                                echo '<li class="bg-gray-900 rounded p-3 border border-gray-700 flex items-center gap-4">';
                                if (!empty($prj['image'])) {
                                    echo '<img src="../' . htmlspecialchars($prj['image']) . '" alt="img" class="w-24 h-24 md:w-32 md:h-32 object-cover rounded-lg shadow border-2 border-warm-wood">';
                                }
                                echo '<div><b>' . htmlspecialchars($prj['title']) . '</b> <span class="text-xs text-gray-400">[' . htmlspecialchars($prj['technologies']) . ']</span><br>'
                                    . '<span class="text-xs text-gray-400">' . htmlspecialchars($prj['description']) . '</span>';
                                if (!empty($prj['github'])) echo '<br><a href="' . htmlspecialchars($prj['github']) . '" class="text-blue-400 underline" target="_blank">Github</a>';
                                if (!empty($prj['demo'])) echo ' | <a href="' . htmlspecialchars($prj['demo']) . '" class="text-green-400 underline" target="_blank">Demo</a>';
                                echo '</div>';
                                $js = 'openProjectModal(' . $prj['id'] . ', ' .
                                    '\'' . addslashes($prj['title']) . '\', ' .
                                    '\'' . addslashes($prj['description']) . '\', ' .
                                    '\'' . addslashes($prj['technologies']) . '\', ' .
                                    '\'' . addslashes($prj['github']) . '\', ' .
                                    '\'' . addslashes($prj['demo']) . '\', ' .
                                    '\'' . addslashes($prj['image']) . '\'' .
                                    ')';
                                echo '<button type="button" onclick="' . $js . '" class="bg-green-600 text-white px-3 py-1 rounded text-xs ml-2">Edit</button>';
                                echo '<a href="?delete_project=' . $prj['id'] . '#projects-tab" onclick="return confirm(\'Hapus proyek ini?\')" class="bg-red-600 text-white px-3 py-1 rounded text-xs ml-2">Hapus</a>';
                                echo '</li>';
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

        <!-- Contact Messages Admin Section (tambahkan di admin_test.php pada tab baru atau tab kontak) -->
        <div id="contact-messages-tab" class="tab-content hidden">
            <div class="glass-effect rounded-xl p-8">
                <h2 class="text-3xl font-bold text-warm-wood mb-6">Pesan Masuk</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-gray-900 rounded-lg overflow-hidden">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-warm-wood">Nama</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-warm-wood">Email</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-warm-wood">Pesan</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-warm-wood">Waktu</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-warm-wood">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $msgQ = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
                            if ($msgQ && $msgQ->num_rows > 0) {
                                while ($msg = $msgQ->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td class="px-4 py-2 text-light-surface">' . htmlspecialchars($msg['name']) . '</td>';
                                    echo '<td class="px-4 py-2 text-light-surface">' . htmlspecialchars($msg['email']) . '</td>';
                                    echo '<td class="px-4 py-2 text-light-surface">' . nl2br(htmlspecialchars($msg['message'])) . '</td>';
                                    echo '<td class="px-4 py-2 text-gray-400">' . htmlspecialchars($msg['created_at']) . '</td>';
                                    echo '<td class="px-4 py-2">'
                                        . '<form method="post" style="display:inline;">'
                                        . '<input type="hidden" name="delete_message_id" value="' . $msg['id'] . '">'
                                        . '<button type="submit" name="delete_message" class="bg-red-600 text-white px-3 py-1 rounded text-xs" onclick="return confirm(\'Hapus pesan ini?\')">Hapus</button>'
                                        . '</form>'
                                        . '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="5" class="text-center text-gray-400 py-4">Belum ada pesan masuk.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Success/Error Messages -->
    <div id="messageContainer" class="fixed top-4 right-4 z-50"></div>

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
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Deskripsi</label>
                    <textarea name="org_description" id="org_description" rows="2" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2" required></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" name="edit_organization" class="bg-warm-wood text-dark-bg px-6 py-2 rounded-lg font-semibold hover:bg-opacity-90">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Skill -->
    <div id="skillModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-gray-900 rounded-xl p-8 w-full max-w-lg relative">
            <button onclick="closeSkillModal()" class="absolute top-2 right-2 text-gray-400 hover:text-warm-wood text-2xl">&times;</button>
            <h3 class="text-xl font-bold mb-4 text-warm-wood">Edit Skill</h3>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="skill_id" id="skill_id">
                <input type="hidden" name="skill_icon_old" id="skill_icon_old">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Nama Skill</label>
                    <input type="text" name="skill_name" id="skill_name" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Kategori</label>
                    <input type="text" name="skill_category" id="skill_category" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Level (%)</label>
                    <input type="number" name="skill_level" id="skill_level" min="0" max="100" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Icon (PNG/SVG, max 500KB)</label>
                    <input type="file" name="skill_icon" accept=".png,.svg" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2">
                    <input type="hidden" name="skill_icon_old" id="skill_icon_old">
                    <div class="mt-2 text-xs text-gray-400">Biarkan kosong jika tidak ingin mengganti icon.</div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" name="edit_skill" class="bg-warm-wood text-dark-bg px-6 py-2 rounded-lg font-semibold hover:bg-opacity-90">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Proyek -->
    <div id="projectModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-gray-900 rounded-xl p-8 w-full max-w-lg relative">
            <button onclick="closeProjectModal()" class="absolute top-2 right-2 text-gray-400 hover:text-warm-wood text-2xl">&times;</button>
            <h3 class="text-xl font-bold mb-4 text-warm-wood">Edit Proyek</h3>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="project_id" id="project_id">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Judul Proyek</label>
                    <input type="text" name="project_title" id="project_title" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Deskripsi</label>
                    <textarea name="project_desc" id="project_desc" rows="3" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Teknologi</label>
                    <input type="text" name="project_tech" id="project_tech" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2">
                </div>
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">GitHub URL</label>
                        <input type="text" name="project_github" id="project_github" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Demo URL</label>
                        <input type="text" name="project_demo" id="project_demo" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Gambar Proyek (Upload JPG/PNG, max 2MB)</label>
                    <input type="file" name="project_image" accept=".jpg,.jpeg,.png" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2">
                    <input type="hidden" name="project_image_old" id="project_image_old">
                    <div class="mt-2 text-xs text-gray-400">Biarkan kosong jika tidak ingin mengganti gambar.</div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" name="edit_project" class="bg-warm-wood text-dark-bg px-6 py-2 rounded-lg font-semibold hover:bg-opacity-90">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

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

        // Modal Edit Pendidikan
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

        // Modal Edit Organisasi
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

        // Modal Edit Skill
        function openSkillModal(id, name, category, level, icon) {
            document.getElementById('skill_id').value = id;
            document.getElementById('skill_name').value = name;
            document.getElementById('skill_category').value = category;
            document.getElementById('skill_level').value = level;
            document.getElementById('skill_icon_old').value = icon;
            document.getElementById('skillModal').classList.remove('hidden');
        }

        function closeSkillModal() {
            document.getElementById('skillModal').classList.add('hidden');
        }

        // Modal Edit Proyek
        function openProjectModal(id, title, desc, tech, github, demo, image) {
            document.getElementById('project_id').value = id;
            document.getElementById('project_title').value = title;
            document.getElementById('project_desc').value = desc;
            document.getElementById('project_tech').value = tech;
            document.getElementById('project_github').value = github;
            document.getElementById('project_demo').value = demo;
            document.getElementById('project_image_old').value = image;
            document.getElementById('projectModal').classList.remove('hidden');
        }

        function closeProjectModal() {
            document.getElementById('projectModal').classList.add('hidden');
        }
    </script>
</body>

</html>