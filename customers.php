<?php
declare(strict_types=1);

require __DIR__ . '/lib/bootstrap.php';

$pageTitle = 'Customers';
$activeNav = 'customers';

$rows = $pdo->query(
    'SELECT
        c.*,
        (SELECT COUNT(*) FROM licenses l WHERE l.customer_id = c.id) AS scales_count
     FROM customers c
     ORDER BY c.full_name ASC'
)->fetchAll();

require __DIR__ . '/partials/header.php';
?>

<div class="mt-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h4 class="mb-1"><i class="fa-solid fa-users me-2"></i>Customers</h4>
            <div class="wl-muted">Manage customer details (name, location, telephone).</div>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-primary" href="customer_new"><i class="fa-solid fa-user-plus me-2"></i>Add Customer</a>
        </div>
    </div>

    <div class="card wl-card mt-3">
        <div class="card-body">
            <div class="row g-2 align-items-center mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent text-primary border-0"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input class="form-control" placeholder="Search name / tel / location..." data-wl-table-filter="tbl-customers">
                    </div>
                </div>
                <div class="col-md-6 text-md-end wl-muted small">
                    Total <?= count($rows) ?> record(s)
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tbl-customers">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Tel</th>
                        <th>ID No</th>
                        <th>Scales</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?= h($row['full_name']) ?></div>
                                <div class="wl-muted small"><?= h($row['address']) ?></div>
                            </td>
                            <td><?= h($row['location']) ?></td>
                            <td class="wl-mono"><?= h($row['tel']) ?></td>
                            <td class="wl-mono"><?= h($row['id_no']) ?></td>
                            <td><span class="badge text-bg-light"><?= (int)$row['scales_count'] ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (count($rows) === 0): ?>
                        <tr>
                            <td colspan="5" class="text-center wl-muted py-4">No customers found.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>

