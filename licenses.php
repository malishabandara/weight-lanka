<?php
declare(strict_types=1);

require __DIR__ . '/lib/bootstrap.php';

$pageTitle = 'Manage Licenses';
$activeNav = 'licenses';

// Update query to join customers table via licenses instead of scales
$rows = $pdo->query(
    'SELECT
        l.id AS license_id,
        l.bill_no,
        l.last_service_date,
        l.expiry_date,
        s.model, s.serial_no,
        c.full_name, c.location
     FROM licenses l
     JOIN scales s ON s.id = l.scale_id
     JOIN customers c ON c.id = l.customer_id
     ORDER BY l.expiry_date DESC'
)->fetchAll();

$today = new DateTimeImmutable('today');

require __DIR__ . '/partials/header.php';
?>

<div class="mt-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h4 class="mb-1"><i class="fa-solid fa-id-card-clip me-2"></i>Licenses</h4>
            <div class="wl-muted">Issue & renewal records (expiry is 1 year from last service date).</div>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-primary" href="license_new"><i class="fa-solid fa-circle-plus me-2"></i>New License</a>
        </div>
    </div>

    <div class="card wl-card mt-3">
        <div class="card-body">
            <div class="row g-2 align-items-center mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent text-primary border-0"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input class="form-control" placeholder="Search customer / scale / bill..." data-wl-table-filter="tbl-licenses">
                    </div>
                </div>
                <div class="col-md-6 text-md-end wl-muted small">
                    Total <?= count($rows) ?> record(s)
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tbl-licenses">
                    <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Scale</th>
                        <th>Bill No</th>
                        <th>Last Service</th>
                        <th>Expiry</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($rows as $row): ?>
                        <?php
                        $expiry = new DateTimeImmutable($row['expiry_date']);
                        $status = ($expiry < $today) ? 'expired' : 'active';
                        ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?= h($row['full_name']) ?></div>
                                <div class="wl-muted small"><?= h($row['location']) ?></div>
                            </td>
                            <td>
                                <div><?= h($row['model']) ?></div>
                                <div class="wl-muted small wl-mono"><?= h($row['serial_no']) ?></div>
                            </td>
                            <td class="wl-mono"><?= h($row['bill_no'] ?: '-') ?></td>
                            <td class="wl-mono"><?= h($row['last_service_date']) ?></td>
                            <td class="wl-mono"><?= h($row['expiry_date']) ?></td>
                            <td><?= wl_status_badge($status) ?></td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="license_renew?id=<?= (int)$row['license_id'] ?>">
                                    <i class="fa-solid fa-rotate me-1"></i> Renew
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (count($rows) === 0): ?>
                        <tr>
                            <td colspan="7" class="text-center wl-muted py-4">No licenses found.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
