<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$base = $base ?? '';
?>
<nav class="navbar navbar-expand-lg navbar-cinerate">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= $base ?>index.php">CINE<span>RATE</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto ms-3">
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>"
                       href="<?= $base ?>index.php">Início</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $base ?>index.php?tipo=serie">Séries</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $base ?>index.php?tipo=filme">Filmes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $base ?>index.php?tipo=documentario">Documentários</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto align-items-center" id="nav-right">
                <!-- Filled by main.js -->
            </ul>
        </div>
    </div>
</nav>
