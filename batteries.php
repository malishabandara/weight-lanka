<?php
declare(strict_types=1);

require __DIR__ . '/lib/bootstrap.php';

$pageTitle = 'Battery Management';
$activeNav = 'batteries';

// Handle adding GRN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_grn') {
    $date = $_POST['date'] ?? date('Y-m-d');
    $q4 = (int)($_POST['qty_4v'] ?? 0);
    $b4 = trim($_POST['batch_4v'] ?? '');
    $c4 = (float)($_POST['cost_4v'] ?? 0);
    $q6 = (int)($_POST['qty_6v'] ?? 0);
    $b6 = trim($_POST['batch_6v'] ?? '');
    $c6 = (float)($_POST['cost_6v'] ?? 0);

    $stmt = $pdo->prepare("INSERT INTO battery_grn (grn_date, qty_4v, batch_4v, cost_4v, qty_6v, batch_6v, cost_6v) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$date, $q4, $b4, $c4, $q6, $b6, $c6]);
    wl_flash_set('success', 'GRN Entry Added');
    wl_redirect('batteries?tab=grn');
}

// Handle adding Sales (Issuing)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_sale') {
    $date = $_POST['date'] ?? date('Y-m-d');
    $q4 = (int)($_POST['qty_4v'] ?? 0);
    $b4 = trim($_POST['batch_4v'] ?? '');
    $q6 = (int)($_POST['qty_6v'] ?? 0);
    $b6 = trim($_POST['batch_6v'] ?? '');
    $inv = trim($_POST['invoice_no'] ?? '');
    $cust = trim($_POST['customer_details'] ?? '');
    $price = (float)($_POST['sale_price'] ?? 0);
    $remarks = trim($_POST['remarks'] ?? '');

    $stmt = $pdo->prepare("INSERT INTO battery_sales (sale_date, qty_4v, batch_4v, qty_6v, batch_6v, invoice_no, customer_details, sale_price, remarks) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$date, $q4, $b4, $q6, $b6, $inv, $cust, $price, $remarks]);
    wl_flash_set('success', 'Battery Sale Added');
    wl_redirect('batteries?tab=sales');
}

$tab = $_GET['tab'] ?? 'sales';

// Fetch Data
$grnRows = $pdo->query('SELECT * FROM battery_grn ORDER BY grn_date DESC, id DESC')->fetchAll();
$saleRows = $pdo->query('SELECT * FROM battery_sales ORDER BY sale_date DESC, id DESC')->fetchAll();

// Calculate Summary (Stock)
$stock4v = 0; $stock6v = 0;
foreach ($grnRows as $r) { $stock4v += $r['qty_4v']; $stock6v += $r['qty_6v']; }
foreach ($saleRows as $r) { $stock4v -= $r['qty_4v']; $stock6v -= $r['qty_6v']; }

require __DIR__ . '/partials/header.php';
?>

<div class="mt-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h4 class="mb-1"><i class="fa-solid fa-car-battery me-2"></i>Battery Management</h4>
            <div class="wl-muted">Manage stock (GRN) and issuing (Sales) of batteries.</div>
        </div>
        <div class="d-flex align-items-center gap-3">
             <div class="badge bg-light text-dark border p-2">
                <div class="small text-muted text-uppercase mb-1">4V Stock</div>
                <div class="fs-5 fw-bold <?= $stock4v < 5 ? 'text-danger' : 'text-primary' ?>"><?= $stock4v ?></div>
             </div>
             <div class="badge bg-light text-dark border p-2">
                <div class="small text-muted text-uppercase mb-1">6V Stock</div>
                <div class="fs-5 fw-bold <?= $stock6v < 5 ? 'text-danger' : 'text-primary' ?>"><?= $stock6v ?></div>
             </div>
        </div>
    </div>

    <ul class="nav nav-tabs mt-4">
        <li class="nav-item">
            <a class="nav-link <?= $tab === 'sales' ? 'active' : '' ?>" href="?tab=sales"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i>Issuing (Sales)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $tab === 'grn' ? 'active' : '' ?>" href="?tab=grn"><i class="fa-solid fa-truck-ramp-box me-2"></i>Stock In (GRN)</a>
        </li>
    </ul>

    <div class="card wl-card border-top-0 rounded-top-0">
        <div class="card-body">
            
            <?php if ($tab === 'grn'): ?>
                <!-- GRN Section -->
                 <div class="d-flex justify-content-between mb-3">
                    <h5 class="text-primary">Stock In Records</h5>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddGRN"><i class="fa-solid fa-plus me-2"></i>Add Stock</button>
                 </div>
                 
                 <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th rowspan="2" class="align-middle">Date</th>
                                <th colspan="3" class="text-center">4V Battery</th>
                                <th colspan="3" class="text-center">6V Battery</th>
                            </tr>
                            <tr>
                                <th>Qty</th>
                                <th>Batch</th>
                                <th>Cost (Rs)</th>
                                <th>Qty</th>
                                <th>Batch</th>
                                <th>Cost (Rs)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($grnRows as $row): ?>
                            <tr>
                                <td class="wl-mono"><?= h($row['grn_date']) ?></td>
                                <td class="fw-bold bg-warning bg-opacity-10"><?= $row['qty_4v'] ?></td>
                                <td class="wl-mono bg-warning bg-opacity-10"><?= h($row['batch_4v']) ?></td>
                                <td class="text-end bg-warning bg-opacity-10"><?= number_format((float)$row['cost_4v'], 2) ?></td>
                                <td class="fw-bold bg-info bg-opacity-10"><?= $row['qty_6v'] ?></td>
                                <td class="wl-mono bg-info bg-opacity-10"><?= h($row['batch_6v']) ?></td>
                                <td class="text-end bg-info bg-opacity-10"><?= number_format((float)$row['cost_6v'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                 </div>

            <?php else: ?>
                <!-- Sales Section -->
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="text-primary">Issuing Details</h5>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddSale"><i class="fa-solid fa-plus me-2"></i>New Issue</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th rowspan="2" class="align-middle">Date</th>
                                <th colspan="2" class="text-center">4V</th>
                                <th colspan="2" class="text-center">6V</th>
                                <th rowspan="2" class="align-middle">Inv No</th>
                                <th rowspan="2" class="align-middle">Customer</th>
                                <th rowspan="2" class="align-middle text-end">Price</th>
                                <th rowspan="2" class="align-middle">Remarks</th>
                            </tr>
                            <tr>
                                <th>Qty</th>
                                <th>Batch</th>
                                <th>Qty</th>
                                <th>Batch</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($saleRows as $row): ?>
                            <tr>
                                <td class="wl-mono"><?= h($row['sale_date']) ?></td>
                                <td class="bg-warning bg-opacity-10"><?= $row['qty_4v'] ?: '-' ?></td>
                                <td class="wl-mono small bg-warning bg-opacity-10"><?= h($row['batch_4v']) ?></td>
                                <td class="bg-info bg-opacity-10"><?= $row['qty_6v'] ?: '-' ?></td>
                                <td class="wl-mono small bg-info bg-opacity-10"><?= h($row['batch_6v']) ?></td>
                                <td class="wl-mono"><?= h($row['invoice_no']) ?></td>
                                <td><?= h($row['customer_details']) ?></td>
                                <td class="text-end fw-bold"><?= number_format((float)$row['sale_price'], 2) ?></td>
                                <td class="small text-muted"><?= h($row['remarks']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<!-- Modal Add GRN -->
<div class="modal fade" id="modalAddGRN" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" method="post">
            <input type="hidden" name="action" value="add_grn">
            <div class="modal-header">
                <h5 class="modal-title">Add New Stock (GRN)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input type="date" class="form-control" name="date" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="row">
                    <div class="col-md-6 border-end">
                        <h6 class="text-primary mb-3">4V Battery details</h6>
                        <div class="mb-2">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control" name="qty_4v">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Batch No</label>
                            <input type="text" class="form-control" name="batch_4v">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Cost Price (Each)</label>
                            <input type="number" step="0.01" class="form-control" name="cost_4v">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">6V Battery details</h6>
                        <div class="mb-2">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control" name="qty_6v">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Batch No</label>
                            <input type="text" class="form-control" name="batch_6v">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Cost Price (Each)</label>
                            <input type="number" step="0.01" class="form-control" name="cost_6v">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Stock</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Add Sale -->
<div class="modal fade" id="modalAddSale" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" method="post">
            <input type="hidden" name="action" value="add_sale">
            <div class="modal-header">
                <h5 class="modal-title">New Issue (Sale)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" name="date" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Invoice No</label>
                        <input type="text" class="form-control" name="invoice_no">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Customer Details</label>
                    <input type="text" class="form-control" name="customer_details" placeholder="Name, Location" required>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 border-end">
                        <h6 class="text-muted">4V Batteries</h6>
                        <div class="row g-2">
                            <div class="col-6"><input type="number" class="form-control form-control-sm" name="qty_4v" placeholder="Qty"></div>
                            <div class="col-6"><input type="text" class="form-control form-control-sm" name="batch_4v" placeholder="Batch"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">6V Batteries</h6>
                        <div class="row g-2">
                            <div class="col-6"><input type="number" class="form-control form-control-sm" name="qty_6v" placeholder="Qty"></div>
                            <div class="col-6"><input type="text" class="form-control form-control-sm" name="batch_6v" placeholder="Batch"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Total Price (Rs)</label>
                        <input type="number" step="0.01" class="form-control" name="sale_price">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Remarks</label>
                        <input type="text" class="form-control" name="remarks" placeholder="Shop, Malindu, etc">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Issue</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
