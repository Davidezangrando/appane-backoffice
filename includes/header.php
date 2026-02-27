<?php
require_once __DIR__ . '/../config/database.php';

// DA FARE: controlla che l'admin sia loggato
// if (!isset($_SESSION['admin'])) {
//     header('Location: /appane-backoffice/login.php');
//     exit;
// }

$pagina = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — ' : '' ?>AppPane Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= SITE_URL ?>/assets/css/admin.css" rel="stylesheet">
</head>
<body>

<!-- ── SIDEBAR ── -->
<nav class="sidebar">
    <a class="sidebar-logo" href="<?= SITE_URL ?>/menu.php">
        <i class="bi bi-shop-window me-2"></i>AppPane Admin
    </a>

    <div class="sidebar-nav">
        <a href="<?= SITE_URL ?>/menu.php"         class="<?= $pagina === 'menu.php'         ? 'active' : '' ?>">
            <i class="bi bi-calendar-week"></i> Menu
        </a>
        <a href="<?= SITE_URL ?>/ordini.php"        class="<?= $pagina === 'ordini.php'        ? 'active' : '' ?>">
            <i class="bi bi-bag"></i> Ordini
        </a>
        <a href="<?= SITE_URL ?>/ingredienti.php"   class="<?= in_array($pagina, ['ingredienti.php','ingrediente_form.php']) ? 'active' : '' ?>">
            <i class="bi bi-box-seam"></i> Ingredienti
        </a>
        <a href="<?= SITE_URL ?>/prodotti.php"      class="<?= in_array($pagina, ['prodotti.php','prodotto_form.php'])     ? 'active' : '' ?>">
            <i class="bi bi-basket2"></i> Prodotti
        </a>
        <a href="<?= SITE_URL ?>/consegne.php"      class="<?= $pagina === 'consegne.php'      ? 'active' : '' ?>">
            <i class="bi bi-truck"></i> Consegne
        </a>
    </div>

    <div class="sidebar-bottom">
        <a href="<?= SITE_URL ?>/logout.php">
            <i class="bi bi-box-arrow-left"></i> Esci
        </a>
    </div>
</nav>

<!-- ── CONTENUTO ── -->
<div class="wrapper">
    <div class="topbar">
        <h5><i class="bi bi-grid me-2 text-muted"></i><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : '' ?></h5>
        <span class="text-muted small"><i class="bi bi-person-circle me-1"></i>Admin</span>
    </div>

    <?php if (isset($_SESSION['flash_msg'])): ?>
        <div class="mx-3 mt-3 alert alert-<?= $_SESSION['flash_tipo'] ?? 'info' ?> alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['flash_msg']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['flash_msg'], $_SESSION['flash_tipo']); ?>
    <?php endif; ?>

    <div class="content">
