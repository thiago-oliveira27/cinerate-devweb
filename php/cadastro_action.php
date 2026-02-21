<?php
// php/cadastro_action.php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'msg' => 'Método inválido.']);
    exit;
}

$nome     = trim($_POST['nome'] ?? '');
$email    = trim($_POST['email'] ?? '');
$senha    = $_POST['senha'] ?? '';
$confirmar = $_POST['confirmar'] ?? '';

if (strlen($nome) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($senha) < 6) {
    echo json_encode(['success' => false, 'msg' => 'Dados inválidos.']);
    exit;
}

if ($senha !== $confirmar) {
    echo json_encode(['success' => false, 'msg' => 'As senhas não coincidem.']);
    exit;
}

$db = getDB();
$check = $db->prepare('SELECT id FROM usuarios WHERE email = ?');
$check->execute([$email]);
if ($check->fetch()) {
    echo json_encode(['success' => false, 'msg' => 'E-mail já cadastrado.']);
    exit;
}

$hash = password_hash($senha, PASSWORD_DEFAULT);
$stmt = $db->prepare('INSERT INTO usuarios (nome, email, senha_hash, admin) VALUES (?, ?, ?, 0)');
$stmt->execute([$nome, $email, $hash]);

$id = $db->lastInsertId();
$_SESSION['user_id']    = $id;
$_SESSION['user_nome']  = $nome;
$_SESSION['user_admin'] = false;

echo json_encode(['success' => true]);
