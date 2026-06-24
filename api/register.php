<?php
header('Content-Type: application/json; charset=UTF-8');
require_once '../db.php';
require_once '../auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(405, 'error', 'Metode request tidak didukung.');
}

$username  = isset($_POST['username'])  ? trim($_POST['username'])  : '';
$email     = isset($_POST['email'])     ? trim($_POST['email'])     : '';
$full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
$password  = isset($_POST['password'])  ? $_POST['password']       : '';

// Validasi wajib isi
if (empty($username) || empty($email) || empty($password)) {
    jsonResponse(400, 'error', 'Username, email, dan password wajib diisi.');
}

// Validasi format username
if (!preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username)) {
    jsonResponse(400, 'error', 'Username hanya boleh huruf, angka, underscore (3-50 karakter).');
}

// Validasi format email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonResponse(400, 'error', 'Format email tidak valid.');
}

// Validasi kekuatan password
if (strlen($password) < 6) {
    jsonResponse(400, 'error', 'Password minimal 6 karakter.');
}
if (!preg_match('/[A-Z]/', $password)) {
    jsonResponse(400, 'error', 'Password harus mengandung minimal 1 huruf kapital.');
}
if (!preg_match('/[0-9]/', $password)) {
    jsonResponse(400, 'error', 'Password harus mengandung minimal 1 angka.');
}
if (!preg_match('/[\W_]/', $password)) {
    jsonResponse(400, 'error', 'Password harus mengandung minimal 1 karakter spesial.');
}

try {
    // Cek username & email sudah terdaftar belum
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR email = :email");
    $stmt->execute([':username' => $username, ':email' => $email]);

    if ($stmt->fetchColumn() > 0) {
        jsonResponse(409, 'error', 'Username atau email sudah terdaftar.');
    }

    // Hash password pakai Bcrypt
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Simpan ke database
    $stmtInsert = $pdo->prepare(
        "INSERT INTO users (username, email, password, full_name)
         VALUES (:username, :email, :password, :full_name)"
    );
    $stmtInsert->execute([
        ':username'  => $username,
        ':email'     => $email,
        ':password'  => $hashedPassword,
        ':full_name' => $full_name ?: null,
    ]);

    jsonResponse(201, 'success', 'Registrasi berhasil! Silakan login.');

} catch (PDOException $e) {
    error_log('DB Error [register]: ' . $e->getMessage());
    jsonResponse(500, 'error', 'Kesalahan sistem saat menyimpan data.');
}
?>