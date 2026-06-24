<?php
header('Content-Type: application/json; charset=UTF-8');
require_once '../db.php';
require_once '../auth.php';

requireLogin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonResponse(405, 'error', 'Metode tidak didukung.');

$userId      = (int) $_SESSION['user_id'];
$confirmPass = $_POST['confirm_password'] ?? '';

if (empty($confirmPass)) jsonResponse(400, 'error', 'Password konfirmasi wajib diisi.');

try {
  $stmt = $pdo->prepare("SELECT password FROM users WHERE id = :id");
  $stmt->execute([':id' => $userId]);
  $user = $stmt->fetch();

  if (!$user || !password_verify($confirmPass, $user['password'])) {
    jsonResponse(401, 'error', 'Password konfirmasi salah. Penghapusan dibatalkan.');
  }

  $pdo->prepare("DELETE FROM users WHERE id = :id")->execute([':id' => $userId]);
  session_destroy();
  jsonResponse(200, 'success', 'Akun berhasil dihapus.', ['redirect' => 'index.html']);

} catch (PDOException $e) {
  error_log('DB Error [delete]: ' . $e->getMessage());
  jsonResponse(500, 'error', 'Kesalahan sistem saat menghapus akun.');
}
?>