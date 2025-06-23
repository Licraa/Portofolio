<?php
include 'db.php';
header('Content-Type: application/json');

// Ambil data profile dari database
$res = $conn->query("SELECT * FROM profile LIMIT 1");
if ($res && $res->num_rows > 0) {
    $row = $res->fetch_assoc();
    echo json_encode([
        'success' => true,
        'profile' => [
            'fullName' => $row['full_name'],
            'profession' => $row['profession'],
            'birthPlace' => $row['birth_place'],
            'profileImage' => $row['profile_image'] ?? 'images/Profile.jpg'
        ]
    ]);
} else {
    echo json_encode([
        'success' => false,
        'profile' => [
            'fullName' => '',
            'profession' => '',
            'birthPlace' => '',
            'profileImage' => 'images/Profile.jpg'
        ]
    ]);
}
$conn->close();
