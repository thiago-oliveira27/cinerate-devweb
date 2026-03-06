<?php
session_start();
require_once 'db.php';
require_once 'auth.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

$db = getDB();

if ($method === 'GET') {
    $tipo   = $_GET['tipo'] ?? '';
    $busca  = $_GET['busca'] ?? '';
    $id     = $_GET['id'] ?? '';

    if ($id) {
        $stmt = $db->prepare(
            'SELECT t.*, u.nome AS cadastrado_por,
             ROUND(AVG(a.nota), 1) AS nota_media,
             COUNT(a.id) AS total_avaliacoes
             FROM titulos t
             LEFT JOIN usuarios u ON u.id = t.usuario_id
             LEFT JOIN avaliacoes a ON a.titulo_id = t.id
             WHERE t.id = ?
             GROUP BY t.id'
        );
        $stmt->execute([$id]);
        $item = $stmt->fetch();
        if (!$item) { echo json_encode(['error' => 'Não encontrado.']); exit; }

        $cmt = $db->prepare(
            'SELECT a.id, a.nota, a.comentario, a.criado_em, u.nome AS autor, a.usuario_id
             FROM avaliacoes a JOIN usuarios u ON u.id = a.usuario_id
             WHERE a.titulo_id = ? ORDER BY a.criado_em DESC'
        );
        $cmt->execute([$id]);
        $item['avaliacoes'] = $cmt->fetchAll();

        echo json_encode($item);
        exit;
    }

    $sql    = 'SELECT t.id, t.titulo, t.genero, t.ano, t.tipo, t.poster_url,
               ROUND(AVG(a.nota), 1) AS nota_media,
               COUNT(a.id) AS total_avaliacoes
               FROM titulos t
               LEFT JOIN avaliacoes a ON a.titulo_id = t.id
               WHERE 1=1';
    $params = [];

    if ($tipo && in_array($tipo, ['filme','serie','documentario'])) {
        $sql    .= ' AND t.tipo = ?';
        $params[] = $tipo;
    }
    if ($busca) {
        $sql    .= ' AND t.titulo LIKE ?';
        $params[] = "%$busca%";
    }

    $sql .= ' GROUP BY t.id ORDER BY nota_media DESC, t.criado_em DESC';
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    echo json_encode($stmt->fetchAll());
    exit;
}

if ($method === 'POST') {
    requireLogin('../login.php');
    $data = json_decode(file_get_contents('php://input'), true) ?: $_POST;

    $titulo    = trim($data['titulo'] ?? '');
    $genero    = trim($data['genero'] ?? '');
    $ano       = (int)($data['ano'] ?? 0);
    $tipo      = $data['tipo'] ?? '';
    $sinopse   = trim($data['sinopse'] ?? '');
    $poster    = trim($data['poster_url'] ?? '');

    if (!$titulo || !$genero || !$ano || !in_array($tipo, ['filme','serie','documentario'])) {
        echo json_encode(['success' => false, 'msg' => 'Dados inválidos.']);
        exit;
    }

    $stmt = $db->prepare(
        'INSERT INTO titulos (titulo, genero, ano, tipo, sinopse, poster_url, usuario_id, criado_em)
         VALUES (?, ?, ?, ?, ?, ?, ?, NOW())'
    );
    $stmt->execute([$titulo, $genero, $ano, $tipo, $sinopse, $poster, $_SESSION['user_id']]);
    echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
    exit;
}

if ($method === 'PUT') {
    requireLogin('../login.php');
    $data = json_decode(file_get_contents('php://input'), true);
    $id   = (int)($data['id'] ?? 0);

    $row = $db->prepare('SELECT usuario_id FROM titulos WHERE id = ?');
    $row->execute([$id]);
    $t = $row->fetch();
    if (!$t) { echo json_encode(['success' => false, 'msg' => 'Não encontrado.']); exit; }
    if (!isAdmin() && $t['usuario_id'] != $_SESSION['user_id']) {
        echo json_encode(['success' => false, 'msg' => 'Sem permissão.']); exit;
    }

    $titulo  = trim($data['titulo'] ?? '');
    $genero  = trim($data['genero'] ?? '');
    $ano     = (int)($data['ano'] ?? 0);
    $tipo    = $data['tipo'] ?? '';
    $sinopse = trim($data['sinopse'] ?? '');
    $poster  = trim($data['poster_url'] ?? '');

    $stmt = $db->prepare(
        'UPDATE titulos SET titulo=?, genero=?, ano=?, tipo=?, sinopse=?, poster_url=? WHERE id=?'
    );
    $stmt->execute([$titulo, $genero, $ano, $tipo, $sinopse, $poster, $id]);
    echo json_encode(['success' => true]);
    exit;
}

if ($method === 'DELETE') {
    requireAdmin('../index.php');
    $id = (int)($_GET['id'] ?? 0);
    if (!$id) { echo json_encode(['success' => false, 'msg' => 'ID inválido.']); exit; }

    $db->prepare('DELETE FROM avaliacoes WHERE titulo_id = ?')->execute([$id]);
    $db->prepare('DELETE FROM titulos WHERE id = ?')->execute([$id]);
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['error' => 'Ação não reconhecida.']);
