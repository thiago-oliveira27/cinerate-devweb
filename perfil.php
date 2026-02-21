<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineRate — Meu Perfil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
include 'php/navbar.php';
?>

<main class="container py-5">
    <div class="row g-4">
        <!-- Profile Info -->
        <div class="col-lg-5">
            <div class="form-card">
                <div class="d-flex align-items-center mb-4">
                    <div class="profile-avatar" id="avatar-initials">?</div>
                    <div>
                        <h2 style="font-family:'Bebas Neue',sans-serif;font-size:1.8rem;letter-spacing:2px;margin:0">
                            <span id="profile-nome">—</span>
                        </h2>
                        <p id="profile-email" style="color:var(--text-muted);font-size:0.85rem;margin:0"></p>
                        <span id="profile-badge" style="font-size:0.75rem"></span>
                    </div>
                </div>

                <h3 style="font-family:'Bebas Neue',sans-serif;letter-spacing:1px;color:var(--accent);margin-bottom:16px">
                    EDITAR PERFIL
                </h3>

                <div class="alert-cinerate alert-error" id="alert-error"></div>
                <div class="alert-cinerate alert-success" id="alert-success"></div>

                <form id="perfil-form" novalidate>
                    <label class="form-label-cr" for="nome">Nome</label>
                    <input class="form-control-cr" type="text" id="nome" name="nome">
                    <span class="form-error"></span>

                    <label class="form-label-cr" for="email">E-mail</label>
                    <input class="form-control-cr" type="email" id="email" name="email">
                    <span class="form-error"></span>

                    <label class="form-label-cr" for="senha">Nova Senha <small style="color:var(--text-muted)">(deixe em branco para manter)</small></label>
                    <input class="form-control-cr" type="password" id="senha" name="senha" placeholder="Mín. 6 caracteres">
                    <span class="form-error"></span>

                    <label class="form-label-cr" for="confirmar">Confirmar Nova Senha</label>
                    <input class="form-control-cr" type="password" id="confirmar" name="confirmar">
                    <span class="form-error"></span>

                    <button type="submit" class="btn-cinerate w-100 mt-2" id="btn-salvar">Salvar Alterações</button>
                </form>

                <hr style="border-color:var(--border);margin:24px 0">

                <button id="btn-excluir" class="btn-outline-cinerate w-100"
                    style="border-color:var(--danger);color:var(--danger)">
                    🗑 Excluir Minha Conta
                </button>
            </div>
        </div>

        <!-- User's reviews -->
        <div class="col-lg-7">
            <h2 class="section-title">MINHAS <span>AVALIAÇÕES</span></h2>
            <div class="section-divider"></div>
            <div id="my-reviews-area">
                <div class="loader"><div class="spinner"></div> Carregando...</div>
            </div>
        </div>
    </div>
</main>

<?php include 'php/footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js?v=<?= time() ?>"></script><script src="js/validate.js"></script>
<script src="js/perfil.js"></script>
</body>
</html>
