<?php
header('Content-Type: application/json; charset=UTF-8');
require_once '../db.php';
require_once '../auth.php';

requireLogin();

try {
  $stmt = $pdo->prepare(
    "SELECT id, username, email, full_name, created_at, updated_at
       FROM users WHERE id = :id"
  );
  $stmt->execute([':id' => $_SESSION['user_id']]);
  $user = $stmt->fetch();

  if (!$user) jsonResponse(404, 'error', 'User tidak ditemukan.');
  jsonResponse(200, 'success', 'OK', ['user' => $user]);

} catch (PDOException $e) {
  error_log('DB Error [get_profile]: ' . $e->getMessage());
  jsonResponse(500, 'error', 'Gagal mengambil data profil.');
}
?>