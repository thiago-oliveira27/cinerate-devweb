<?php

session_start();
require_once 'db.php';
require_once 'auth.php';
header('Content-Type: application/json');

requireAdmin('../index.php');
$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];


if ($method === 'GET') {
    $stmt = $db->query(
        'SELECT u.id, u.nome, u.email, u.admin, u.criado_em,
         COUNT(DISTINCT t.id) AS titulos_cadastrados,
         COUNT(DISTINCT a.id) AS avaliacoes
         FROM usuarios u
         LEFT JOIN titulos t ON t.usuario_id = u.id
         LEFT JOIN avaliacoes a ON a.usuario_id = u.id
         GROUP BY u.id ORDER BY u.criado_em DESC'
    );
    echo json_encode($stmt->fetchAll());
    exit;
}


if ($method === 'DELETE') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id === $_SESSION['user_id']) {
        echo json_encode(['success' => false, 'msg' => 'Não é possível excluir sua própria conta por aqui.']);
        exit;
    }
    $db->prepare('DELETE FROM avaliacoes WHERE usuario_id = ?')->execute([$id]);
    $db->prepare('UPDATE titulos SET usuario_id = NULL WHERE usuario_id = ?')->execute([$id]);
    $db->prepare('DELETE FROM usuarios WHERE id = ?')->execute([$id]);
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['error' => 'Ação inválida.']);
