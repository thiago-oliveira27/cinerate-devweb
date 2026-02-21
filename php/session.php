<?php
// php/session.php - Returns current session as JSON (used by JS)
header('Content-Type: application/json');
session_start();

if (isset($_SESSION['user_id'])) {
    echo json_encode([
        'logged' => true,
        'id'     => $_SESSION['user_id'],
        'nome'   => $_SESSION['user_nome'],
        'admin'  => (bool)$_SESSION['user_admin']
    ]);
} else {
    echo json_encode(['logged' => false]);
}
