<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineRate — Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'php/navbar.php'; ?>

<main class="container py-5">
    <div class="form-card">
        <h2>ENTRAR</h2>

        <div class="alert-cinerate alert-error" id="alert-error"></div>

        <form id="login-form" novalidate>
            <label class="form-label-cr" for="email">E-mail</label>
            <input class="form-control-cr" type="email" id="email" name="email" placeholder="seu@email.com">
            <span class="form-error"></span>

            <label class="form-label-cr" for="senha">Senha</label>
            <input class="form-control-cr" type="password" id="senha" name="senha" placeholder="••••••">
            <span class="form-error"></span>

            <button type="submit" class="btn-cinerate w-100 mt-2" id="btn-login">Entrar</button>
        </form>

        <p style="color:var(--text-muted);font-size:0.85rem;text-align:center;margin-top:20px;">
            Ainda não tem conta? <a href="cadastro.php" style="color:var(--accent)">Cadastre-se</a>
        </p>
    </div>
</main>

<?php include 'php/footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js?v=<?= time() ?>"></script><script src="js/validate.js"></script>
<script src="js/login.js"></script>
</body>
</html>
