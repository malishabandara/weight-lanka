<?php
declare(strict_types=1);

require __DIR__ . '/lib/bootstrap.php';

$pageTitle = 'Due Monitor';
$activeNav = 'renew';

$today = new DateTimeImmutable('today');
$defaultMonth = $today->modify('first day of next month')->format('Y-m');

$month = trim((string)($_GET['m'] ?? $defaultMonth));
if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
    $month = $defaultMonth;
}

$start = DateTimeImmutable::createFromFormat('Y-m-d', $month . '-01') ?: $today->modify('first day of next month');
$end = $start->modify('last day of this month');

$stmt = $pdo->prepare(
    'SELECT
        l.id AS license_id,
        l.bill_no,
        l.last_service_date,
        l.expiry_date,
        l.serviced_by,
        s.model, s.serial_no, s.capacity, s.reg_no,
        c.full_name, c.address, c.location, c.tel, c.id_no
     FROM licenses l
     JOIN scales s ON s.id = l.scale_id
     JOIN customers c ON c.id = l.customer_id
     WHERE l.expiry_date BETWEEN :start AND :end
     ORDER BY l.expiry_date ASC, c.full_name ASC'
);
$stmt->execute([
    ':start' => $start->format('Y-m-d'),
    ':end' => $end->format('Y-m-d'),
]);
$rows = $stmt->fetchAll();

require __DIR__ . '/partials/header.php';
?>

<div class="mt-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h4 class="mb-1"><i class="fa-solid fa-calendar-check me-2"></i>Due Monitor</h4>
            <div class="wl-muted">Quickly see which licenses expire in a selected month.</div>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-primary" href="license_new"><i class="fa-solid fa-circle-plus me-2"></i>New License</a>
        </div>
    </div>

    <div class="card wl-card mt-3">
        <div class="card-body">
            <form method="get" class="row g-2 align-items-end">
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <label class="form-label">Month</label>
                    <input class="form-control" type="month" name="m" value="<?= h($month) ?>">
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <button class="btn btn-outline-primary w-100" type="submit"><i class="fa-solid fa-filter me-2"></i>Show</button>
                </div>
                <div class="col-md-4 col-lg-6 text-md-end wl-muted small">
                    Range: <span class="wl-mono"><?= h($start->format('Y-m-d')) ?></span> → <span class="wl-mono"><?= h($end->format('Y-m-d')) ?></span><br>
                    Total: <?= count($rows) ?> record(s)
                </div>
            </form>
        </div>
    </div>

    <div class="card wl-card mt-3">
        <div class="card-body">
            <div class="row g-2 align-items-center mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent text-primary border-0"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input class="form-control" placeholder="Search customer / model / serial..." data-wl-table-filter="tbl-due">
                    </div>
                </div>
                <div class="col-md-6 text-md-end wl-muted small">
                    Tip: Click Renew to extend expiry by 1 year.
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tbl-due">
                    <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Location</th>
                        <th>Tel</th>
                        <th>Model</th>
                        <th>Serial</th>
                        <th>Expiry</th>
                        <th>Days Left</th>
                        <th class="text-end">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($rows as $row): ?>
                        <?php
                        $expiry = new DateTimeImmutable($row['expiry_date']);
                        $daysLeft = (int)$today->diff($expiry)->format('%r%a');
                        ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?= h($row['full_name']) ?></div>
                                <div class="wl-muted small"><?= h($row['address']) ?><?= $row['id_no'] ? ' · ' . h($row['id_no']) : '' ?></div>
                            </td>
                            <td><?= h($row['location']) ?></td>
                            <td class="wl-mono"><?= h($row['tel']) ?></td>
                            <td><?= h($row['model']) ?></td>
                            <td class="wl-mono"><?= h($row['serial_no']) ?></td>
                            <td class="wl-mono"><?= h($row['expiry_date']) ?></td>
                            <td>
                                <?php if ($daysLeft < 0): ?>
                                    <span class="badge text-bg-danger">Expired</span>
                                <?php elseif ($daysLeft <= 7): ?>
                                    <span class="badge text-bg-warning"><?= (int)$daysLeft ?> days</span>
                                <?php else: ?>
                                    <span class="badge text-bg-light"><?= (int)$daysLeft ?> days</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="license_renew?id=<?= (int)$row['license_id'] ?>">
                                    <i class="fa-solid fa-rotate me-1"></i> Renew
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (count($rows) === 0): ?>
                        <tr>
                            <td colspan="8" class="text-center wl-muted py-4">
                                No licenses expiring in this month.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
