<?php
require_once '../auth.php';
session_destroy();
header('Content-Type: application/json; charset=UTF-8');
echo json_encode([
    'status'   => 'success',
    'message'  => 'Logout berhasil.',
    'redirect' => 'index.html'
]);
exit;
?>