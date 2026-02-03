<?php
declare(strict_types=1);

require __DIR__ . '/lib/bootstrap.php';

$pageTitle = 'Renewal History';
$activeNav = 'renewals';

// We join on renewals -> licenses -> scales AND customers (via license)
$rows = $pdo->query(
    'SELECT
        r.renewal_date,
        r.new_expiry_date,
        l.id AS license_id,
        s.model, s.serial_no,
        c.full_name, c.location
     FROM license_renewals r
     JOIN licenses l ON l.id = r.license_id
     JOIN scales s ON s.id = l.scale_id
     JOIN customers c ON c.id = l.customer_id
     ORDER BY r.renewal_date DESC, r.id DESC'
)->fetchAll();

require __DIR__ . '/partials/header.php';
?>

<div class="mt-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h4 class="mb-1"><i class="fa-solid fa-clock-rotate-left me-2"></i>Renewal History</h4>
            <div class="wl-muted">Log of all renewals performed.</div>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-primary" href="expiring"><i class="fa-solid fa-calendar-check me-2"></i>Due Monitor</a>
        </div>
    </div>

    <div class="card wl-card mt-3">
        <div class="card-body">
            <div class="row g-2 align-items-center mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent text-primary border-0"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input class="form-control" placeholder="Search customer / scale..." data-wl-table-filter="tbl-renewals">
                    </div>
                </div>
                <div class="col-md-6 text-md-end wl-muted small">
                    Total <?= count($rows) ?> record(s)
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tbl-renewals">
                    <thead>
                    <tr>
                        <th>Date Renewed</th>
                        <th>Customer</th>
                        <th>Scale</th>
                        <th class="text-end">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td class="wl-mono"><?= h($row['renewal_date']) ?></td>
                            <td>
                                <div class="fw-bold"><?= h($row['full_name']) ?></div>
                                <div class="wl-muted small"><?= h($row['location']) ?></div>
                            </td>
                            <td>
                                <div><?= h($row['model']) ?></div>
                                <div class="wl-muted small wl-mono"><?= h($row['serial_no']) ?></div>
                            </td>
                            <td class="wl-mono fw-bold text-success"><?= h($row['new_expiry_date']) ?></td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="license_renew?id=<?= (int)$row['license_id'] ?>">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (count($rows) === 0): ?>
                        <tr>
                            <td colspan="6" class="text-center wl-muted py-4">No history records found.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
