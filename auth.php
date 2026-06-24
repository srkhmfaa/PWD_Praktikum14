<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login, kalau belum redirect ke index.html
function requireLogin(): void
{
    if (empty($_SESSION['user_id'])) {
        header("Location: ../index.html");
        exit;
    }
}

// Kirim response JSON + HTTP status code sekaligus
function jsonResponse(int $code, string $status, string $message, array $extra = []): void
{
    http_response_code($code);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array_merge(
        ['status' => $status, 'message' => $message],
        $extra
    ));
    exit;
}
?>