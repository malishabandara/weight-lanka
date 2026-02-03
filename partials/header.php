<?php
/** @var array $config */
/** @var string $pageTitle */
/** @var string $activeNav */

$appName = $config['app']['name'] ?? 'Weight Lanka';
$pageTitle = $pageTitle ?? 'Home';
$activeNav = $activeNav ?? 'home';
$flash = wl_flash_get();
$showNav = $showNav ?? true;
$showTopbar = $showTopbar ?? true;
$bodyClass = trim('wl-bg ' . ($bodyClass ?? ''));
$mainClass = $mainClass ?? 'container-fluid px-3 px-md-4 pb-5';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($pageTitle) ?> · <?= h($appName) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" referrerpolicy="no-referrer">
    <link href="assets/css/app.css" rel="stylesheet">
    <?php foreach (($extraCss ?? []) as $href): ?>
        <link href="<?= h((string)$href) ?>" rel="stylesheet">
    <?php endforeach; ?>
</head>
<body class="<?= h($bodyClass) ?>">
<div class="wl-app">
    <?php if ($showTopbar): ?>
        <header class="wl-topbar mb-4">
            <div class="container-fluid px-3 px-md-4">
                <div class="d-flex align-items-center justify-content-between py-3">
                    <div class="d-flex align-items-center gap-3">
                        <?php if ($activeNav !== 'home'): ?>
                            <a href="./" class="btn btn-outline-secondary border-0"><i class="fa-solid fa-arrow-left me-2"></i>Dashboard</a>
                            <div class="vr opacity-25"></div>
                        <?php endif; ?>

                        <div class="wl-logo">
                            <i class="fa-solid fa-scale-balanced"></i>
                        </div>
                        <div>
                            <div class="wl-brand"><?= h($appName) ?></div>
                        </div>
                    </div>
                    <div class="d-none d-md-flex align-items-center gap-2">
                        <span class="wl-pill"><i class="fa-regular fa-calendar"></i> <?= date('Y-m-d') ?></span>
                    </div>
                </div>
            </div>
        </header>
    <?php endif; ?>

    <main class="<?= h($mainClass) ?>">
        <?php if ($flash): ?>
            <div class="alert alert-<?= h($flash['type']) ?> wl-alert mt-3" role="alert">
                <i class="fa-solid fa-circle-info me-2"></i><?= h($flash['message']) ?>
            </div>
        <?php endif; ?>

