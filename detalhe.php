<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineRate — Detalhe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'php/navbar.php'; ?>

<div id="page-loader" class="loader" style="padding:80px 0"><div class="spinner"></div> Carregando...</div>

<div id="page-content" style="display:none">
    <!-- Detail hero -->
    <section class="detail-hero">
        <div class="container">
            <div class="row align-items-start g-4">
                <div class="col-md-3 text-center">
                    <div id="poster-area"></div>
                </div>
                <div class="col-md-9">
                    <div id="detail-badges" class="detail-badges"></div>
                    <h1 class="detail-title" id="detail-title"></h1>
                    <p class="detail-subtitle" id="detail-sub"></p>
                    <div class="d-flex align-items-center gap-4 flex-wrap">
                        <div>
                            <div class="big-rating" id="big-rating">—</div>
                            <div class="big-rating-label">nota média</div>
                        </div>
                        <div>
                            <div class="stars-display" id="big-stars" style="font-size:1.6rem"></div>
                            <div class="big-rating-label" id="review-count">0 avaliações</div>
                        </div>
                    </div>
                    <p class="synopsis" id="synopsis"></p>
                    <div class="mt-3 d-flex gap-2 flex-wrap" id="action-btns"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Review form (for logged users) -->
    <section class="container py-4" id="review-section" style="display:none">
        <h3 class="section-title">DEIXAR <span>AVALIAÇÃO</span></h3>
        <div class="section-divider"></div>
        <div class="form-card" style="max-width:600px;margin:0">
            <div class="alert-cinerate alert-error" id="review-alert-error"></div>
            <div class="alert-cinerate alert-success" id="review-alert-success"></div>
            <form id="review-form" novalidate>
                <label class="form-label-cr">Sua nota</label>
                <div class="star-rating-input">
                    <input type="radio" id="s5" name="nota" value="5"><label for="s5">★</label>
                    <input type="radio" id="s4" name="nota" value="4"><label for="s4">★</label>
                    <input type="radio" id="s3" name="nota" value="3"><label for="s3">★</label>
                    <input type="radio" id="s2" name="nota" value="2"><label for="s2">★</label>
                    <input type="radio" id="s1" name="nota" value="1"><label for="s1">★</label>
                </div>
                <span class="form-error" id="nota-error" style="margin-bottom:12px"></span>

                <label class="form-label-cr mt-2" for="comentario">Comentário</label>
                <textarea class="form-control-cr" id="comentario" name="comentario" placeholder="Escreva sua opinião..."></textarea>
                <span class="form-error"></span>

                <input type="hidden" id="titulo-id" name="titulo_id">
                <button type="submit" class="btn-cinerate mt-2" id="btn-review">Enviar Avaliação</button>
            </form>
        </div>
    </section>

    <!-- Comments -->
    <section class="container comments-section">
        <h3 class="section-title">AVALIAÇÕES <span>DA COMUNIDADE</span></h3>
        <div class="section-divider"></div>
        <div id="comments-area"></div>
    </section>
</div>

<?php include 'php/footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js?v=<?= time() ?>"></script><script src="js/validate.js"></script>
<script src="js/detalhe.js"></script>
</body>
</html>
