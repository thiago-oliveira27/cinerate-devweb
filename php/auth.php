<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function isAdmin(): bool {
    return isLoggedIn() && !empty($_SESSION['user_admin']);
}

function requireLogin(string $redirect = '../login.php'): void {
    if (!isLoggedIn()) {
        header("Location: $redirect");
        exit;
    }
}

function requireAdmin(string $redirect = '../index.php'): void {
    if (!isAdmin()) {
        header("Location: $redirect");
        exit;
    }
}

function currentUser(): array {
    if (!isLoggedIn()) return [];
    return [
        'id'    => $_SESSION['user_id'],
        'nome'  => $_SESSION['user_nome'],
        'admin' => (bool)$_SESSION['user_admin']
    ];
}
