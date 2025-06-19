<?php
// Koneksi database (edit sesuai konfigurasi Anda)
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'porto_db';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Koneksi gagal: ' . $conn->connect_error);
}
