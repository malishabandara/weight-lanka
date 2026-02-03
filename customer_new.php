<?php
declare(strict_types=1);

require __DIR__ . '/lib/bootstrap.php';

$pageTitle = 'Add Customer';
$activeNav = 'customers';

$errors = [];
$values = [
    'full_name' => '',
    'address' => '',
    'location' => '',
    'tel' => '',
    'id_no' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($values as $k => $_) {
        $values[$k] = trim((string)($_POST[$k] ?? ''));
    }

    if ($values['full_name'] === '') {
        $errors['full_name'] = 'Customer name is required.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare(
            'INSERT INTO customers (full_name, address, location, tel, id_no)
             VALUES (:full_name, :address, :location, :tel, :id_no)'
        );
        $stmt->execute([
            ':full_name' => $values['full_name'],
            ':address' => $values['address'] !== '' ? $values['address'] : null,
            ':location' => $values['location'] !== '' ? $values['location'] : null,
            ':tel' => $values['tel'] !== '' ? $values['tel'] : null,
            ':id_no' => $values['id_no'] !== '' ? $values['id_no'] : null,
        ]);

        wl_flash_set('success', 'Customer added successfully.');
        wl_redirect('customers');
    }
}

require __DIR__ . '/partials/header.php';
?>

<div class="mt-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h4 class="mb-1"><i class="fa-solid fa-user-plus me-2"></i>Add Customer</h4>
            <div class="wl-muted">Enter the customer details. You can add scales after this.</div>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-primary" href="customers"><i class="fa-solid fa-arrow-left me-2"></i>Back</a>
        </div>
    </div>

    <div class="card wl-card mt-3">
        <div class="card-body">
            <form method="post" class="row g-3">
                <div class="col-12">
                    <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                    <input class="form-control <?= isset($errors['full_name']) ? 'is-invalid' : '' ?>" name="full_name" value="<?= h($values['full_name']) ?>" placeholder="Ex: T.Banda" required>
                    <?php if (isset($errors['full_name'])): ?>
                        <div class="invalid-feedback"><?= h($errors['full_name']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-12">
                    <label class="form-label">Address</label>
                    <input class="form-control" name="address" value="<?= h($values['address']) ?>" placeholder="Ex: Bus Stand">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Location</label>
                    <input class="form-control" name="location" value="<?= h($values['location']) ?>" placeholder="Ex: Hambanthota">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Telephone</label>
                    <input class="form-control" name="tel" value="<?= h($values['tel']) ?>" placeholder="Ex: 0760511507">
                </div>
                <div class="col-md-4">
                    <label class="form-label">ID No</label>
                    <input class="form-control" name="id_no" value="<?= h($values['id_no']) ?>" placeholder="Ex: 695200544V">
                </div>

                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary" type="submit"><i class="fa-solid fa-check me-2"></i>Save</button>
                    <a class="btn btn-outline-primary" href="scale_new"><i class="fa-solid fa-scale-balanced me-2"></i>Add Scale</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>

