<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineRate — Avalie e Descubra</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'php/navbar.php'; ?>

<section class="hero">
    <div class="container">
        <h1>CINE<span>RATE</span></h1>
        <p>Registre, avalie e descubra filmes e séries com a comunidade.</p>
        <div class="search-wrapper" id="hero-search">
            <input type="text" class="search-input" id="search-input" placeholder="Buscar filmes, séries, documentários...">
            <button class="search-btn" id="search-btn">
                <svg width="16" height="16" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
            </button>
        </div>
    </div>
</section>

<main class="container py-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h2 class="section-title" id="section-heading">TODOS OS <span>TÍTULOS</span></h2>
            <div class="section-divider"></div>
        </div>
    </div>

    <div class="filter-tabs" id="filter-tabs">
        <button class="filter-tab active" data-tipo="">Todos</button>
        <button class="filter-tab" data-tipo="filme">Filmes</button>
        <button class="filter-tab" data-tipo="serie">Séries</button>
        <button class="filter-tab" data-tipo="documentario">Documentários</button>
    </div>

    <div id="content-area">
        <div class="loader"><div class="spinner"></div> Carregando...</div>
    </div>
</main>

<?php include 'php/footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js?v=<?= time() ?>"></script><script src="js/index.js"></script>
</body>
</html>
