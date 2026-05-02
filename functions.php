<?php
require_once __DIR__ . '/../includes/auth.php';

function formatDate(?string $date): string {
    if (!$date) return '';
    return date('Y-m-d', strtotime($date));
}

function flash(string $key, ?string $value = null) {
    if ($value !== null) {
        $_SESSION['flash'][$key] = $value;
        return null;
    }

    if (isset($_SESSION['flash'][$key])) {
        $message = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $message;
    }

    return null;
}
?>
