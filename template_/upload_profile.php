<?php
include 'db.php';
$profileImagesDir = '../images/';
$profileImagesDb = [];
$activeProfileImage = '';

// Ambil 3 gambar terakhir
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
                header("Location: admin_test.php");
                exit;
            }
        }
    }
}
