<?php
header('Content-Type: application/json; charset=UTF-8');
require_once '../db.php';
require_once '../auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(405, 'error', 'Metode request tidak didukung.');
}

$identifier = isset($_POST['identifier']) ? trim($_POST['identifier']) : '';
$password   = isset($_POST['password'])   ? $_POST['password']        : '';

if (empty($identifier) || empty($password)) {
    jsonResponse(400, 'error', 'Username/email dan password wajib diisi.');
}

try {
    // Cari user berdasarkan username ATAU email
    $stmt = $pdo->prepare(
        "SELECT id, username, email, password, full_name
           FROM users
          WHERE username = :identifier OR email = :identifier
          LIMIT 1"
    );
    $stmt->execute([':identifier' => $identifier]);
    $user = $stmt->fetch();

    // Verifikasi password
    if (!$user || !password_verify($password, $user['password'])) {
        jsonResponse(401, 'error', 'Username/email atau password salah.');
    }

    // Buat sesi aman
    session_regenerate_id(true);
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['username']  = $user['username'];
    $_SESSION['full_name'] = $user['full_name'];

    jsonResponse(200, 'success', 'Login berhasil! Mengalihkan ke dashboard...', [
        'redirect' => 'dashboard.php'
    ]);

} catch (PDOException $e) {
    error_log('DB Error [login]: ' . $e->getMessage());
    jsonResponse(500, 'error', 'Kesalahan sistem saat autentikasi.');
}
?>