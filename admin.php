<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineRate — Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
session_start();
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_admin'])) {
    header('Location: index.php');
    exit;
}
include 'php/navbar.php';
?>

<main class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="section-title">PAINEL <span>ADMIN</span></h1>
            <div class="section-divider"></div>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav" id="admin-tabs" style="gap:8px;margin-bottom:24px">
        <li class="nav-item">
            <button class="filter-tab active" data-tab="usuarios">Usuários</button>
        </li>
        <li class="nav-item">
            <button class="filter-tab" data-tab="titulos">Títulos</button>
        </li>
    </ul>

    <!-- Users tab -->
    <div id="tab-usuarios">
        <div class="loader" id="users-loader"><div class="spinner"></div> Carregando...</div>
        <div id="users-table-wrap" style="display:none">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th><th>Nome</th><th>E-mail</th><th>Admin</th>
                            <th>Títulos</th><th>Avaliações</th><th>Cadastro</th><th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="users-tbody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Titles tab -->
    <div id="tab-titulos" style="display:none">
        <div class="loader" id="titulos-loader"><div class="spinner"></div> Carregando...</div>
        <div id="titulos-table-wrap" style="display:none">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th><th>Título</th><th>Tipo</th><th>Gênero</th>
                            <th>Ano</th><th>Nota Média</th><th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="titulos-tbody"></tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include 'php/footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js?v=<?= time() ?>"></script><script src="js/admin.js"></script>
</body>
</html>
