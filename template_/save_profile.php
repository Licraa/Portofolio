<?php
include 'db.php';
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

$fullName = $conn->real_escape_string($data['fullName'] ?? '');
$profession = $conn->real_escape_string($data['profession'] ?? '');
$birthPlace = $conn->real_escape_string($data['birthPlace'] ?? '');

// Cek apakah sudah ada data profil (asumsi hanya 1 profil)
$res = $conn->query("SELECT id FROM profile LIMIT 1");
if ($res && $res->num_rows > 0) {
    // Update
    $row = $res->fetch_assoc();
    $id = $row['id'];
    $sql = "UPDATE profile SET full_name='$fullName', profession='$profession', birth_place='$birthPlace' WHERE id=$id";
} else {
    // Insert
    $sql = "INSERT INTO profile (full_name, profession, birth_place) VALUES ('$fullName', '$profession', '$birthPlace')";
}

if ($conn->query($sql)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
}
$conn->close();
