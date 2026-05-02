<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('e')) {
    function e($value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

function isLoggedIn(): bool {
    return isset($_SESSION['user']) && is_array($_SESSION['user']);
}

function isAdmin(): bool {
    return isLoggedIn() && (($_SESSION['user']['role'] ?? '') === 'admin');
}

function currentUserId(): ?int {
    return isLoggedIn() ? (int)($_SESSION['user']['id'] ?? 0) : null;
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: auth.php');
        exit;
    }
}

function requireAdmin(): void {
    if (!isAdmin()) {
        header('Location: auth.php');
        exit;
    }
}
?>
