<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineRate — Adicionar Título</title>
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
    <div class="form-card" style="max-width:600px">
        <h2 id="form-heading">ADICIONAR TÍTULO</h2>

        <div class="alert-cinerate alert-error" id="alert-error"></div>

        <form id="titulo-form" novalidate>
            <input type="hidden" id="titulo-edit-id" name="id">

            <label class="form-label-cr" for="titulo">Título</label>
            <input class="form-control-cr" type="text" id="titulo" name="titulo" placeholder="Nome do filme/série">
            <span class="form-error"></span>

            <label class="form-label-cr" for="tipo">Tipo</label>
            <select class="form-control-cr" id="tipo" name="tipo">
                <option value="">— Selecione —</option>
                <option value="filme">Filme</option>
                <option value="serie">Série</option>
                <option value="documentario">Documentário</option>
            </select>
            <span class="form-error"></span>

            <label class="form-label-cr" for="genero">Gênero</label>
            <select class="form-control-cr" id="genero" name="genero">
                <option value="">— Selecione —</option>
                <option>Ação</option><option>Aventura</option><option>Animação</option>
                <option>Comédia</option><option>Crime</option><option>Drama</option>
                <option>Ficção Científica</option><option>Horror</option><option>Romance</option>
                <option>Suspense</option><option>Terror</option><option>Outro</option>
            </select>
            <span class="form-error"></span>

            <label class="form-label-cr" for="ano">Ano de lançamento</label>
            <input class="form-control-cr" type="number" id="ano" name="ano"
                   placeholder="Ex: 2023" min="1888" max="2030">
            <span class="form-error"></span>

            <label class="form-label-cr" for="sinopse">Sinopse</label>
            <textarea class="form-control-cr" id="sinopse" name="sinopse"
                      placeholder="Breve descrição do enredo..."></textarea>

            <label class="form-label-cr" for="poster_url">URL do Poster (opcional)</label>
            <input class="form-control-cr" type="url" id="poster_url" name="poster_url"
                   placeholder="https://...">

            <button type="submit" class="btn-cinerate w-100 mt-2" id="btn-submit">Adicionar</button>
        </form>
    </div>
</main>

<?php include 'php/footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js?v=<?= time() ?>"></script><script src="js/validate.js"></script>
<script src="js/adicionar.js"></script>
</body>
</html>
