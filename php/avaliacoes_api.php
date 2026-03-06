<?php
session_start();
require_once 'db.php';
require_once 'auth.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$db = getDB();

if ($method === 'POST') {
    requireLogin('../login.php');
    $data = json_decode(file_get_contents('php://input'), true) ?: $_POST;

    $titulo_id  = (int)($data['titulo_id'] ?? 0);
    $nota       = (int)($data['nota'] ?? 0);
    $comentario = trim($data['comentario'] ?? '');

    if (!$titulo_id || $nota < 1 || $nota > 5 || strlen($comentario) < 5) {
        echo json_encode(['success' => false, 'msg' => 'Dados inválidos.']);
        exit;
    }

    $check = $db->prepare('SELECT id FROM avaliacoes WHERE titulo_id = ? AND usuario_id = ?');
    $check->execute([$titulo_id, $_SESSION['user_id']]);
    if ($check->fetch()) {
        $stmt = $db->prepare('UPDATE avaliacoes SET nota=?, comentario=?, criado_em=NOW() WHERE titulo_id=? AND usuario_id=?');
        $stmt->execute([$nota, $comentario, $titulo_id, $_SESSION['user_id']]);
    } else {
        $stmt = $db->prepare('INSERT INTO avaliacoes (titulo_id, usuario_id, nota, comentario, criado_em) VALUES (?,?,?,?,NOW())');
        $stmt->execute([$titulo_id, $_SESSION['user_id'], $nota, $comentario]);
    }

    echo json_encode(['success' => true]);
    exit;
}

if ($method === 'DELETE') {
    requireLogin('../login.php');
    $id = (int)($_GET['id'] ?? 0);

    $row = $db->prepare('SELECT usuario_id FROM avaliacoes WHERE id = ?');
    $row->execute([$id]);
    $av = $row->fetch();

    if (!$av) { echo json_encode(['success' => false, 'msg' => 'Não encontrado.']); exit; }
    if (!isAdmin() && $av['usuario_id'] != $_SESSION['user_id']) {
        echo json_encode(['success' => false, 'msg' => 'Sem permissão.']); exit;
    }

    $db->prepare('DELETE FROM avaliacoes WHERE id = ?')->execute([$id]);
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['error' => 'Ação inválida.']);
