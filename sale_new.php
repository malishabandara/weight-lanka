<?php
declare(strict_types=1);

require __DIR__ . '/lib/bootstrap.php';

$pageTitle = 'New Sale';
$activeNav = 'sales';

// Fetch customers
$customers = $pdo->query('SELECT id, full_name, location FROM customers ORDER BY full_name ASC')->fetchAll();

$errors = [];
$today = date('Y-m-d');
$nextYear = date('Y-m-d', strtotime('+1 year'));

$values = [
    'customer_id' => '',
    'sale_date' => $today,
    'invoice_no' => '',
    'scale_brand' => '',
    'model' => '',
    'serial_no' => '',
    'capacity' => '15kg',
    'display_colour' => 'Green',
    'scale_price' => '0.00',
    'discount' => '0.00',
    'payment_type' => 'Cash',
    'payment_amount' => '0.00',
    'next_license_date' => $nextYear,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($values as $k => $_) {
        if (isset($_POST[$k])) {
            $values[$k] = trim((string)$_POST[$k]);
        }
    }

    if ($values['customer_id'] === '') $errors['customer_id'] = 'Please select a customer.';
    if ($values['sale_date'] === '') $errors['sale_date'] = 'Sale date is required.';
    if ($values['model'] === '') $errors['model'] = 'Model is required.';
    if ($values['serial_no'] === '') $errors['serial_no'] = 'Serial number is required.';

    if (!$errors) {
        $pdo->beginTransaction();
        try {
            // 1. Create Scale Record (NO customer_id now)
            $stmt = $pdo->prepare(
                'INSERT INTO scales (model, serial_no, capacity)
                 VALUES (:model, :serial_no, :capacity)'
            );
            $stmt->execute([
                ':model' => $values['model'],
                ':serial_no' => $values['serial_no'],
                ':capacity' => $values['capacity']
            ]);
            $scaleId = $pdo->lastInsertId();

            // 2. Calculations
            $scalePrice = (float)$values['scale_price'];
            $discount = (float)$values['discount'];
            $netPrice = $scalePrice - $discount;
            $paid = (float)$values['payment_amount'];
            $balance = $netPrice - $paid;

            // 3. Create Sale Record (This keeps history of who bought it)
            $stmt = $pdo->prepare(
                'INSERT INTO sales 
                (customer_id, scale_id, invoice_no, sale_date, scale_brand, model, serial_no, capacity, display_colour, 
                 scale_price, discount, net_price, payment_type, payment_amount, balance_amount, net_balance, next_license_date)
                 VALUES 
                (:cid, :sid, :inv, :sdate, :brand, :model, :serial, :cap, :col, 
                 :price, :disc, :net, :ptype, :paid, :bal, :bal, :next_lic)'
            );
            
            $stmt->execute([
                ':cid' => $values['customer_id'],
                ':sid' => $scaleId,
                ':inv' => $values['invoice_no'] ?: null,
                ':sdate' => $values['sale_date'],
                ':brand' => $values['scale_brand'],
                ':model' => $values['model'],
                ':serial' => $values['serial_no'],
                ':cap' => $values['capacity'],
                ':col' => $values['display_colour'],
                ':price' => $scalePrice,
                ':disc' => $discount,
                ':net' => $netPrice,
                ':ptype' => $values['payment_type'],
                ':paid' => $paid,
                ':bal' => $balance,
                ':next_lic' => $values['next_license_date'] ?: null
            ]);
            $saleId = $pdo->lastInsertId();

            // 4. Create Initial License (Must include customer_id now)
            if ($values['next_license_date']) {
                $stmt = $pdo->prepare(
                    'INSERT INTO licenses (customer_id, scale_id, last_service_date, expiry_date, bill_no)
                     VALUES (:customer_id, :scale_id, :service_date, :expiry_date, :bill_no)'
                );
                $stmt->execute([
                    ':customer_id' => $values['customer_id'],
                    ':scale_id' => $scaleId,
                    ':service_date' => $values['sale_date'],
                    ':expiry_date' => $values['next_license_date'],
                    ':bill_no' => $values['invoice_no'] ?: null
                ]);
            }

            $pdo->commit();
            wl_flash_set('success', 'Sale recorded successfully.');
            wl_redirect('sales');

        } catch (Throwable $e) {
            $pdo->rollBack();
            $errors['global'] = 'Database Error: ' . $e->getMessage();
        }
    }
}

require __DIR__ . '/partials/header.php';
?>

<div class="mt-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h4 class="mb-1"><i class="fa-solid fa-cart-shopping me-2"></i>New Sale</h4>
            <div class="wl-muted">Record a new scale sale. This creates a scale and license automatically.</div>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-primary" href="scale_new"><i class="fa-solid fa-arrow-left me-2"></i>Back</a>
        </div>
    </div>

    <?php if (isset($errors['global'])): ?>
        <div class="alert alert-danger wl-alert mt-3"><?= h($errors['global']) ?></div>
    <?php endif; ?>

    <div class="card wl-card mt-3">
        <div class="card-body">
            <form method="post" class="row g-3">
                
                <!-- Customer Section -->
                <div class="col-12"><h5 class="text-primary border-bottom pb-2">Customer</h5></div>
                <div class="col-md-6">
                    <label class="form-label">Select Customer <span class="text-danger">*</span></label>
                    <select class="form-select <?= isset($errors['customer_id']) ? 'is-invalid' : '' ?>" name="customer_id">
                        <option value="">-- Choose Customer --</option>
                        <?php foreach ($customers as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= (int)$values['customer_id'] === $c['id'] ? 'selected' : '' ?>>
                                <?= h($c['full_name']) ?> (<?= h($c['location']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['customer_id'])): ?>
                        <div class="invalid-feedback"><?= h($errors['customer_id']) ?></div>
                    <?php endif; ?>
                    <div class="form-text mt-2">
                        Customer not in list? <a href="customer_new" class="fw-bold text-decoration-none">Add Customer First</a>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Invoice No</label>
                    <input class="form-control" name="invoice_no" value="<?= h($values['invoice_no']) ?>" placeholder="Ex: 15030-2">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Sale Date <span class="text-danger">*</span></label>
                    <input class="form-control" type="date" name="sale_date" value="<?= h($values['sale_date']) ?>">
                </div>

                <!-- Scale Section -->
                <div class="col-12 mt-4"><h5 class="text-primary border-bottom pb-2">Scale Details</h5></div>
                
                <div class="col-md-4">
                    <label class="form-label">Brand / Scale Name</label>
                    <input class="form-control" name="scale_brand" value="<?= h($values['scale_brand']) ?>" placeholder="Ex: Aqua, Royal">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Model <span class="text-danger">*</span></label>
                    <input class="form-control <?= isset($errors['model']) ? 'is-invalid' : '' ?>" name="model" value="<?= h($values['model']) ?>" placeholder="Ex: T-15" required>
                    <?php if (isset($errors['model'])): ?>
                        <div class="invalid-feedback"><?= h($errors['model']) ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Serial No <span class="text-danger">*</span></label>
                    <input class="form-control <?= isset($errors['serial_no']) ? 'is-invalid' : '' ?>" name="serial_no" value="<?= h($values['serial_no']) ?>" placeholder="Ex: 5141" required>
                     <?php if (isset($errors['serial_no'])): ?>
                        <div class="invalid-feedback"><?= h($errors['serial_no']) ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Capacity</label>
                    <input class="form-control" name="capacity" value="<?= h($values['capacity']) ?>" placeholder="Ex: 15kg">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Display Colour</label>
                    <select class="form-select" name="display_colour">
                        <option value="Green" <?= $values['display_colour'] === 'Green' ? 'selected' : '' ?>>Green</option>
                        <option value="Red" <?= $values['display_colour'] === 'Red' ? 'selected' : '' ?>>Red</option>
                        <option value="White" <?= $values['display_colour'] === 'White' ? 'selected' : '' ?>>White</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Next Year License Day</label>
                    <input class="form-control bg-light" type="date" name="next_license_date" value="<?= h($values['next_license_date']) ?>">
                </div>

                <!-- Payment Section -->
                <div class="col-12 mt-4"><h5 class="text-primary border-bottom pb-2">Payment</h5></div>
                
                <div class="col-md-3">
                    <label class="form-label">Scale Price (Rs)</label>
                    <input class="form-control" type="number" step="0.01" name="scale_price" value="<?= h($values['scale_price']) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Discount (Rs)</label>
                    <input class="form-control" type="number" step="0.01" name="discount" value="<?= h($values['discount']) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Payment Type</label>
                    <select class="form-select" name="payment_type">
                        <option value="Cash" <?= $values['payment_type'] === 'Cash' ? 'selected' : '' ?>>Cash</option>
                        <option value="Credit" <?= $values['payment_type'] === 'Credit' ? 'selected' : '' ?>>Credit</option>
                        <option value="Cash/Credit" <?= $values['payment_type'] === 'Cash/Credit' ? 'selected' : '' ?>>Cash/Credit</option>
                        <option value="Cheque" <?= $values['payment_type'] === 'Cheque' ? 'selected' : '' ?>>Cheque</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Paid Amount (Rs)</label>
                    <input class="form-control" type="number" step="0.01" name="payment_amount" value="<?= h($values['payment_amount']) ?>">
                </div>

                <div class="col-12 d-flex gap-2 mt-4 border-top pt-3">
                    <button class="btn btn-primary" type="submit"><i class="fa-solid fa-check me-2"></i>Save Sale</button>
                    <a class="btn btn-outline-secondary" href="sales">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
