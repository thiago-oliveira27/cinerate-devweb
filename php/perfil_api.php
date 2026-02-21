<?php
// php/perfil_api.php
session_start();
require_once 'db.php';
require_once 'auth.php';
header('Content-Type: application/json');

requireLogin('../login.php');
$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];

// GET: current user data
if ($method === 'GET') {
    $stmt = $db->prepare('SELECT id, nome, email, admin, criado_em FROM usuarios WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    echo json_encode($stmt->fetch());
    exit;
}

// PUT: update profile
if ($method === 'PUT') {
    $data  = json_decode(file_get_contents('php://input'), true);
    $nome  = trim($data['nome'] ?? '');
    $email = trim($data['email'] ?? '');
    $senha = $data['senha'] ?? '';

    if (strlen($nome) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'msg' => 'Dados inválidos.']);
        exit;
    }

    // Check email uniqueness (excluding current user)
    $check = $db->prepare('SELECT id FROM usuarios WHERE email = ? AND id != ?');
    $check->execute([$email, $_SESSION['user_id']]);
    if ($check->fetch()) {
        echo json_encode(['success' => false, 'msg' => 'E-mail já em uso.']);
        exit;
    }

    if ($senha && strlen($senha) >= 6) {
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $db->prepare('UPDATE usuarios SET nome=?, email=?, senha_hash=? WHERE id=?')
           ->execute([$nome, $email, $hash, $_SESSION['user_id']]);
    } else {
        $db->prepare('UPDATE usuarios SET nome=?, email=? WHERE id=?')
           ->execute([$nome, $email, $_SESSION['user_id']]);
    }

    $_SESSION['user_nome'] = $nome;
    echo json_encode(['success' => true]);
    exit;
}

// DELETE: delete own account
if ($method === 'DELETE') {
    $uid = $_SESSION['user_id'];
    $db->prepare('DELETE FROM avaliacoes WHERE usuario_id = ?')->execute([$uid]);
    // Titles registered by user: keep but nullify user reference or delete
    $db->prepare('UPDATE titulos SET usuario_id = NULL WHERE usuario_id = ?')->execute([$uid]);
    $db->prepare('DELETE FROM usuarios WHERE id = ?')->execute([$uid]);
    session_destroy();
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['error' => 'Ação inválida.']);
