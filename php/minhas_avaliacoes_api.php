<?php
session_start();
require_once 'db.php';
require_once 'auth.php';
header('Content-Type: application/json');

requireLogin('../login.php');
$db = getDB();

$stmt = $db->prepare(
    'SELECT a.id, a.nota, a.comentario, a.criado_em, t.id AS titulo_id, t.titulo
     FROM avaliacoes a
     JOIN titulos t ON t.id = a.titulo_id
     WHERE a.usuario_id = ?
     ORDER BY a.criado_em DESC'
);
$stmt->execute([$_SESSION['user_id']]);
echo json_encode($stmt->fetchAll());
