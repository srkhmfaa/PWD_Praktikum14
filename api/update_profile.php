<?php
header('Content-Type: application/json; charset=UTF-8');
require_once '../db.php';
require_once '../auth.php';

requireLogin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonResponse(405, 'error', 'Metode tidak didukung.');

$userId    = (int) $_SESSION['user_id'];
$full_name = trim($_POST['full_name']        ?? '');
$email     = trim($_POST['email']            ?? '');
$currPass  = $_POST['current_password']      ?? '';
$newPass   = $_POST['new_password']          ?? '';

if (empty($email)) jsonResponse(400, 'error', 'Email tidak boleh kosong.');
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) jsonResponse(400, 'error', 'Format email tidak valid.');

try {
  $stmtUser = $pdo->prepare("SELECT email, password FROM users WHERE id = :id");
  $stmtUser->execute([':id' => $userId]);
  $user = $stmtUser->fetch();

  // Cek email unik jika berubah
  if ($email !== $user['email']) {
    $stmtCek = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email AND id != :id");
    $stmtCek->execute([':email' => $email, ':id' => $userId]);
    if ($stmtCek->fetchColumn() > 0) jsonResponse(409, 'error', 'Email sudah digunakan akun lain.');
  }

  $hashedPass = $user['password'];

  // Proses ganti password jika diisi
  if (!empty($newPass)) {
    if (!password_verify($currPass, $user['password'])) jsonResponse(401, 'error', 'Password saat ini tidak sesuai.');
    if (strlen($newPass) < 6)          jsonResponse(400, 'error', 'Password baru minimal 6 karakter.');
    if (!preg_match('/[A-Z]/', $newPass)) jsonResponse(400, 'error', 'Password baru harus ada huruf kapital.');
    if (!preg_match('/[0-9]/', $newPass)) jsonResponse(400, 'error', 'Password baru harus ada angka.');
    if (!preg_match('/[\W_]/', $newPass)) jsonResponse(400, 'error', 'Password baru harus ada karakter spesial.');
    $hashedPass = password_hash($newPass, PASSWORD_DEFAULT);
  }

  $stmt = $pdo->prepare(
    "UPDATE users SET full_name=:full_name, email=:email, password=:password WHERE id=:id"
  );
  $stmt->execute([
    ':full_name' => $full_name ?: null,
    ':email'     => $email,
    ':password'  => $hashedPass,
    ':id'        => $userId,
  ]);

  $_SESSION['full_name'] = $full_name;
  jsonResponse(200, 'success', 'Profil berhasil diperbarui!');

} catch (PDOException $e) {
  error_log('DB Error [update]: ' . $e->getMessage());
  jsonResponse(500, 'error', 'Kesalahan sistem saat update profil.');
}
?>