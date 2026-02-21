<?php
// php/login_action.php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'msg' => 'Método inválido.']);
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if (empty($email) || empty($senha)) {
    echo json_encode(['success' => false, 'msg' => 'Preencha todos os campos.']);
    exit;
}

$db  = getDB();
$stmt = $db->prepare('SELECT id, nome, senha_hash, admin FROM usuarios WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($senha, $user['senha_hash'])) {
    echo json_encode(['success' => false, 'msg' => 'E-mail ou senha incorretos.']);
    exit;
}

$_SESSION['user_id']    = $user['id'];
$_SESSION['user_nome']  = $user['nome'];
$_SESSION['user_admin'] = (bool)$user['admin'];

echo json_encode(['success' => true, 'admin' => (bool)$user['admin']]);
