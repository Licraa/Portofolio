<?php
// login_process.php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($email === '' || $password === '') {
        echo json_encode(['success' => false, 'message' => 'Email dan password wajib diisi.']);
        exit;
    }

    $stmt = $conn->prepare('SELECT id, email, password FROM users WHERE email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        // Ganti dengan password_verify jika password di-hash
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_email'] = $row['email'];
            echo json_encode(['success' => true, 'message' => 'Login berhasil!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Password salah.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Email tidak ditemukan.']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Metode tidak valid.']);
}
