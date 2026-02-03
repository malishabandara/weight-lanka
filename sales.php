<?php
declare(strict_types=1);

require __DIR__ . '/lib/bootstrap.php';

$pageTitle = 'Sales';
$activeNav = 'sales';

$rows = $pdo->query(
    'SELECT
        sl.*,
        c.full_name AS customer_name,
        c.tel AS customer_tel,
        s.model AS scale_model,
        s.serial_no AS scale_serial
     FROM sales sl
     JOIN customers c ON c.id = sl.customer_id
     JOIN scales s ON s.id = sl.scale_id
     ORDER BY sl.sale_date DESC, sl.id DESC'
)->fetchAll();

require __DIR__ . '/partials/header.php';
?>

<div class="mt-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h4 class="mb-1"><i class="fa-solid fa-cart-shopping me-2"></i>Sales</h4>
            <div class="wl-muted">Track scale sales and payments.</div>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-primary" href="sale_new"><i class="fa-solid fa-circle-plus me-2"></i>New Sale</a>
        </div>
    </div>

    <div class="card wl-card mt-3">
        <div class="card-body">
            <div class="row g-2 align-items-center mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent text-primary border-0"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input class="form-control" placeholder="Search invoice / customer / serial..." data-wl-table-filter="tbl-sales">
                    </div>
                </div>
                <div class="col-md-6 text-md-end wl-muted small">
                    Total <?= count($rows) ?> record(s)
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tbl-sales">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Inv No</th>
                        <th>Customer</th>
                        <th>Scale</th>
                        <th>Price</th>
                        <th>Paid</th>
                        <th>Balance</th>
                        <th class="text-end">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td class="wl-mono"><?= h($row['sale_date']) ?></td>
                            <td class="wl-mono"><?= h($row['invoice_no'] ?: '-') ?></td>
                            <td>
                                <div class="fw-bold"><?= h($row['customer_name']) ?></div>
                                <div class="wl-muted small"><?= h($row['customer_tel']) ?></div>
                            </td>
                            <td>
                                <div><?= h($row['scale_brand']) ?> <?= h($row['model']) ?></div>
                                <div class="wl-muted small wl-mono"><?= h($row['serial_no']) ?></div>
                            </td>
                            <td class="text-end number-font"><?= number_format((float)$row['net_price'], 2) ?></td>
                            <td class="text-end number-font"><?= number_format((float)$row['payment_amount'] + (float)$row['payment_1'] + (float)$row['payment_2'] + (float)$row['payment_3'], 2) ?></td>
                            <td class="text-end number-font fw-bold <?= $row['net_balance'] > 0 ? 'text-danger' : 'text-success' ?>">
                                <?= number_format((float)$row['net_balance'], 2) ?>
                            </td>
                            <td class="text-end">
                                <?php if ($row['net_balance'] > 0): ?>
                                    <span class="badge text-bg-warning">Pending</span>
                                <?php else: ?>
                                    <span class="badge text-bg-success">Paid</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (count($rows) === 0): ?>
                        <tr>
                            <td colspan="8" class="text-center wl-muted py-4">No sales records found.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
