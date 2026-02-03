<?php
declare(strict_types=1);

require __DIR__ . '/lib/bootstrap.php';

$pageTitle = 'Add Scale';
$activeNav = 'scales';

$errors = [];
$values = [
    'model' => '',
    'serial_no' => '',
    'capacity' => '',
    'reg_no' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($values as $k => $_) {
        $values[$k] = trim((string)($_POST[$k] ?? ''));
    }

    if ($values['model'] === '') {
        $errors['model'] = 'Model is required.';
    }
    if ($values['serial_no'] === '') {
        $errors['serial_no'] = 'Serial number is required.';
    }

    // Check duplicate serial
    $stmt = $pdo->prepare('SELECT id FROM scales WHERE serial_no = ?');
    $stmt->execute([$values['serial_no']]);
    if ($stmt->fetch()) {
        $errors['serial_no'] = 'This serial number already exists.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare(
            'INSERT INTO scales (model, serial_no, capacity, reg_no)
             VALUES (:model, :serial_no, :capacity, :reg_no)'
        );
        $stmt->execute([
            ':model' => $values['model'],
            ':serial_no' => $values['serial_no'],
            ':capacity' => $values['capacity'] !== '' ? $values['capacity'] : null,
            ':reg_no' => $values['reg_no'] !== '' ? $values['reg_no'] : null,
        ]);

        wl_flash_set('success', 'Scale added successfully.');
        wl_redirect('scale_new');
    }
}

require __DIR__ . '/partials/header.php';
?>

<div class="mt-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h4 class="mb-1"><i class="fa-solid fa-circle-plus me-2"></i>Add Scale</h4>
            <div class="wl-muted">Add a new scale to the inventory.</div>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-primary" href="./"><i class="fa-solid fa-arrow-left me-2"></i>Back</a>
        </div>
    </div>

    <div class="card wl-card mt-3">
        <div class="card-body">
            <form method="post" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Model <span class="text-danger">*</span></label>
                    <input class="form-control <?= isset($errors['model']) ? 'is-invalid' : '' ?>" name="model" value="<?= h($values['model']) ?>" placeholder="Ex: T-15" required>
                    <?php if (isset($errors['model'])): ?>
                        <div class="invalid-feedback"><?= h($errors['model']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Serial No <span class="text-danger">*</span></label>
                    <input class="form-control <?= isset($errors['serial_no']) ? 'is-invalid' : '' ?>" name="serial_no" value="<?= h($values['serial_no']) ?>" placeholder="Ex: 5141" required>
                    <?php if (isset($errors['serial_no'])): ?>
                        <div class="invalid-feedback"><?= h($errors['serial_no']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Capacity</label>
                    <input class="form-control" name="capacity" value="<?= h($values['capacity']) ?>" placeholder="Ex: 15kg">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Reg No</label>
                    <input class="form-control" name="reg_no" value="<?= h($values['reg_no']) ?>" placeholder="Ex: 33-8020">
                </div>

                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary" type="submit"><i class="fa-solid fa-check me-2"></i>Save Scale</button>
                    <!-- After saving, user goes to scales list. From there they can go to License New -->
                </div>
            </form>
        </div>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
