<?php
declare(strict_types=1);

require __DIR__ . '/lib/bootstrap.php';

$pageTitle = 'Home';
$activeNav = 'home';

// Home screen only (no stats widgets)
$showTopbar = false;
$showNav = false;
$bodyClass = 'wl-home-body';
$mainClass = 'wl-home-main';
$extraCss = ['assets/css/home.css'];
require __DIR__ . '/partials/header.php';
?>

<div class="wl-home-wrap">
    <div class="wl-home-top">
        <div class="wl-home-quick" aria-label="Quick actions">
            <a href="license_new" title="New License"><i class="fa-solid fa-circle-plus"></i></a>
            <a href="expiring" title="Due Monitor"><i class="fa-solid fa-calendar-check"></i></a>
        </div>

        <div>
            <div class="wl-home-title">Weight Lanka</div>
            <div class="wl-home-subtitle">Scale License & Renewal Management System</div>
        </div>
    </div>

    <div class="wl-home-grid">
        <a class="wl-home-tile" href="license_new">
            <div class="wl-home-icon"><i class="fa-solid fa-circle-plus"></i></div>
            <div class="wl-home-label">Create New License</div>
        </a>
        <a class="wl-home-tile" href="licenses">
            <div class="wl-home-icon"><i class="fa-solid fa-id-card-clip"></i></div>
            <div class="wl-home-label">Manage Licenses</div>
        </a>
        <a class="wl-home-tile" href="expiring">
            <div class="wl-home-icon"><i class="fa-solid fa-calendar-check"></i></div>
            <div class="wl-home-label">Due Monitor</div>
        </a>
        <a class="wl-home-tile" href="customers">
            <div class="wl-home-icon"><i class="fa-solid fa-users"></i></div>
            <div class="wl-home-label">Customers</div>
        </a>
        <a class="wl-home-tile" href="scale_new">
            <div class="wl-home-icon"><i class="fa-solid fa-scale-balanced"></i></div>
            <div class="wl-home-label">Add Scale</div>
        </a>
        <a class="wl-home-tile" href="sales">
            <div class="wl-home-icon"><i class="fa-solid fa-cart-shopping"></i></div>
            <div class="wl-home-label">Sales</div>
        </a>
        <a class="wl-home-tile" href="batteries">
            <div class="wl-home-icon"><i class="fa-solid fa-car-battery"></i></div>
            <div class="wl-home-label">Batteries</div>
        </a>
        <a class="wl-home-tile" href="cash_book">
            <div class="wl-home-icon"><i class="fa-solid fa-file-invoice-dollar"></i></div>
            <div class="wl-home-label">Cash Book</div>
        </a>
        <a class="wl-home-tile" href="quotations">
            <div class="wl-home-icon"><i class="fa-solid fa-file-invoice"></i></div>
            <div class="wl-home-label">Quotations</div>
        </a>
        <a class="wl-home-tile" href="renewals">
            <div class="wl-home-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
            <div class="wl-home-label">Renewal History</div>
        </a>
    </div>

    <div class="wl-home-hint">
        Tip: Use <strong>Due Monitor</strong> to find expiring licenses and renew them in one click.
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
