<?php
/** @var string $activeNav */
?>
<nav class="wl-nav">
    <div class="wl-nav-wrap">
        <a class="wl-nav-item <?= $activeNav === 'home' ? 'active' : '' ?>" href="./">
            <div class="wl-nav-icon"><i class="fa-solid fa-house"></i></div>
            <div class="wl-nav-label">Home</div>
        </a>
        <a class="wl-nav-item <?= $activeNav === 'licenses' ? 'active' : '' ?>" href="licenses">
            <div class="wl-nav-icon"><i class="fa-solid fa-id-card-clip"></i></div>
            <div class="wl-nav-label">Licenses</div>
        </a>
        <a class="wl-nav-item <?= $activeNav === 'renew' ? 'active' : '' ?>" href="expiring">
            <div class="wl-nav-icon"><i class="fa-solid fa-rotate"></i></div>
            <div class="wl-nav-label">Due Monitor</div>
        </a>
        <a class="wl-nav-item <?= $activeNav === 'customers' ? 'active' : '' ?>" href="customers">
            <div class="wl-nav-icon"><i class="fa-solid fa-users"></i></div>
            <div class="wl-nav-label">Customers</div>
        </a>
        <a class="wl-nav-item <?= $activeNav === 'scales' ? 'active' : '' ?>" href="scale_new">
            <div class="wl-nav-icon"><i class="fa-solid fa-scale-balanced"></i></div>
            <div class="wl-nav-label">Add Scale</div>
        </a>
        <a class="wl-nav-item <?= $activeNav === 'sales' ? 'active' : '' ?>" href="sales">
            <div class="wl-nav-icon"><i class="fa-solid fa-cart-shopping"></i></div>
            <div class="wl-nav-label">Sales</div>
        </a>
        <a class="wl-nav-item <?= $activeNav === 'batteries' ? 'active' : '' ?>" href="batteries">
            <div class="wl-nav-icon"><i class="fa-solid fa-car-battery"></i></div>
            <div class="wl-nav-label">Batteries</div>
        </a>
        <a class="wl-nav-item <?= $activeNav === 'cash_book' ? 'active' : '' ?>" href="cash_book">
            <div class="wl-nav-icon"><i class="fa-solid fa-file-invoice-dollar"></i></div>
            <div class="wl-nav-label">Cash Book</div>
        </a>
        <a class="wl-nav-item <?= $activeNav === 'quotations' ? 'active' : '' ?>" href="quotations">
            <div class="wl-nav-icon"><i class="fa-solid fa-file-invoice"></i></div>
            <div class="wl-nav-label">Quotations</div>
        </a>
        <a class="wl-nav-item <?= $activeNav === 'new' ? 'active' : '' ?>" href="license_new">
            <div class="wl-nav-icon"><i class="fa-solid fa-circle-plus"></i></div>
            <div class="wl-nav-label">New</div>
        </a>
    </div>
</nav>

