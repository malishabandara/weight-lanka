<?php
declare(strict_types=1);

require __DIR__ . '/lib/bootstrap.php';

$pageTitle = 'New License';
$activeNav = 'new';

// Allow pre-selection via query param
$scaleId = (int)($_GET['scale_id'] ?? 0);
$customerId = (int)($_GET['customer_id'] ?? 0);

$errors = [];
$values = [
    'customer_id' => $customerId ?: '',
    'scale_id' => $scaleId ?: '',
    'bill_no' => '',
    'serviced_by' => 'Chandika',
    'last_service_date' => date('Y-m-d'),
    'expiry_date' => date('Y-m-d', strtotime('+1 year')),
];

// Fetch Customers
$customers = $pdo->query('SELECT id, full_name, location FROM customers ORDER BY full_name ASC')->fetchAll();

// Fetch scales that DON'T have an active license? 
// Or just all scales? Since we decoupled owner, technically any scale can be licensed to anyone.
// But we probably want to filter out scales that already have a license record to avoid duplicates if ID is unique per scale in license table.
// In DB schema `uniq_licenses_scale_id` exists. So one scale = one license record (which is updated on renewal).
// So fetch scales NOT present in licenses table.
$scales = $pdo->query(
    'SELECT s.id, s.model, s.serial_no 
     FROM scales s 
     LEFT JOIN licenses l ON l.scale_id = s.id 
     WHERE l.id IS NULL 
     ORDER BY s.model, s.serial_no'
)->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($values as $k => $_) {
        if (isset($_POST[$k])) {
            $values[$k] = trim((string)$_POST[$k]);
        }
    }

    if ($values['customer_id'] === '') {
        $errors['customer_id'] = 'Please select a customer.';
    }
    if ($values['scale_id'] === '') {
        $errors['scale_id'] = 'Please select a scale.';
    }
    if ($values['last_service_date'] === '') {
        $errors['last_service_date'] = 'Service date is required.';
    }

    if (!$errors) {
        // Calculate expiry if not set (though fields are there)
        if ($values['expiry_date'] === '') {
            $values['expiry_date'] = date('Y-m-d', strtotime($values['last_service_date'] . ' +1 year'));
        }

        $stmt = $pdo->prepare(
            'INSERT INTO licenses (customer_id, scale_id, bill_no, last_service_date, expiry_date, serviced_by)
             VALUES (:customer_id, :scale_id, :bill_no, :last_service_date, :expiry_date, :serviced_by)'
        );
        
        try {
            $stmt->execute([
                ':customer_id' => $values['customer_id'],
                ':scale_id' => $values['scale_id'],
                ':bill_no' => $values['bill_no'] ?: null,
                ':last_service_date' => $values['last_service_date'],
                ':expiry_date' => $values['expiry_date'],
                ':serviced_by' => $values['serviced_by'],
            ]);

            wl_flash_set('success', 'License created. Expiry set to ' . $values['expiry_date'] . '.');
            wl_redirect('licenses');
        } catch (PDOException $e) {
            $errors['global'] = 'Error saving license: ' . $e->getMessage();
        }
    }
}

require __DIR__ . '/partials/header.php';
?>

<div class="mt-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h4 class="mb-1"><i class="fa-solid fa-circle-plus me-2"></i>New License</h4>
            <div class="wl-muted">Issue a license for a customer's scale.</div>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-primary" href="licenses"><i class="fa-solid fa-arrow-left me-2"></i>Back</a>
        </div>
    </div>

    <?php if (count($scales) === 0): ?>
        <div class="alert alert-warning wl-alert mt-3">
            No unlicensed scales available. 
            <a class="btn btn-sm btn-primary ms-2" href="scale_new"><i class="fa-solid fa-circle-plus me-1"></i>Add Scale</a>
        </div>
    <?php endif; ?>

    <?php if (isset($errors['global'])): ?>
        <div class="alert alert-danger wl-alert mt-3"><?= h($errors['global']) ?></div>
    <?php endif; ?>

    <div class="card wl-card mt-3">
        <div class="card-body">
            <form method="post" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Customer <span class="text-danger">*</span></label>
                    <select class="form-select <?= isset($errors['customer_id']) ? 'is-invalid' : '' ?>" name="customer_id">
                        <option value="">-- Select Customer --</option>
                        <?php foreach ($customers as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= (int)$values['customer_id'] === $c['id'] ? 'selected' : '' ?>>
                                <?= h($c['full_name']) ?> (<?= h($c['location']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['customer_id'])): ?>
                        <div class="invalid-feedback"><?= h($errors['customer_id']) ?></div>
                    <?php endif; ?>
                    <div class="form-text text-muted">Customer not here? <a href="customer_new" class="link-primary">Add Customer</a>.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Scale <span class="text-danger">*</span></label>
                    <select class="form-select <?= isset($errors['scale_id']) ? 'is-invalid' : '' ?>" name="scale_id">
                        <option value="">-- Select Scale --</option>
                        <?php foreach ($scales as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= (int)$values['scale_id'] === $s['id'] ? 'selected' : '' ?>>
                                <?= h($s['model']) ?> - <?= h($s['serial_no']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['scale_id'])): ?>
                        <div class="invalid-feedback"><?= h($errors['scale_id']) ?></div>
                    <?php endif; ?>
                    <div class="form-text text-muted">Need a new scale? <a href="scale_new" class="link-primary">Add scale</a>.</div>
                </div>

                <div class="col-12"><hr class="my-2"></div>

                <div class="col-md-6">
                    <label class="form-label">Bill No</label>
                    <input class="form-control" name="bill_no" value="<?= h($values['bill_no']) ?>" placeholder="Ex: 15734">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Technician</label>
                    <input class="form-control" name="serviced_by" value="<?= h($values['serviced_by']) ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Last Service Date</label>
                    <input class="form-control" type="date" name="last_service_date" value="<?= h($values['last_service_date']) ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Expiry Date</label>
                    <input class="form-control" type="date" name="expiry_date" value="<?= h($values['expiry_date']) ?>">
                    <div class="form-text">Auto-calculated as +1 year from service date</div>
                </div>

                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary" type="submit" <?= count($scales) === 0 ? 'disabled' : '' ?>><i class="fa-solid fa-check me-2"></i>Create License</button>
                    <a class="btn btn-outline-primary" href="expiring"><i class="fa-solid fa-rotate me-2"></i>Due Monitor</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
