<?php
declare(strict_types=1);

require __DIR__ . '/lib/bootstrap.php';

$pageTitle = 'Renew License';
$activeNav = 'renew';

$licenseId = null;
if (isset($_GET['id']) && ctype_digit((string)$_GET['id'])) {
    $licenseId = (int)$_GET['id'];
}
if (isset($_POST['license_id']) && ctype_digit((string)$_POST['license_id'])) {
    $licenseId = (int)$_POST['license_id'];
}
if (!$licenseId) {
    wl_redirect('licenses');
}

$stmt = $pdo->prepare(
    'SELECT
        l.*,
        s.model, s.serial_no, s.capacity, s.reg_no,
        c.full_name, c.location, c.tel, c.id_no
     FROM licenses l
     JOIN scales s ON s.id = l.scale_id
     JOIN customers c ON c.id = s.customer_id
     WHERE l.id = :id'
);
$stmt->execute([':id' => $licenseId]);
$license = $stmt->fetch();

if (!$license) {
    http_response_code(404);
    $pageTitle = 'License Not Found';
    require __DIR__ . '/partials/header.php';
    ?>
    <div class="mt-4">
        <div class="alert alert-danger wl-alert">License not found.</div>
        <a class="btn btn-outline-primary" href="licenses"><i class="fa-solid fa-arrow-left me-2"></i>Back</a>
    </div>
    <?php
    require __DIR__ . '/partials/footer.php';
    exit;
}

$errors = [];
$values = [
    'license_id' => (string)$licenseId,
    'renewal_date' => (new DateTimeImmutable('today'))->format('Y-m-d'),
    'new_expiry_date' => '',
    'bill_no' => (string)($license['bill_no'] ?? ''),
    'serviced_by' => (string)($license['serviced_by'] ?? ''),
];
$values['new_expiry_date'] = wl_date_add_one_year($values['renewal_date']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values['renewal_date'] = trim((string)($_POST['renewal_date'] ?? ''));
    $values['bill_no'] = trim((string)($_POST['bill_no'] ?? ''));
    $values['serviced_by'] = trim((string)($_POST['serviced_by'] ?? ''));

    $dt = DateTimeImmutable::createFromFormat('Y-m-d', $values['renewal_date']);
    if (!$dt || $dt->format('Y-m-d') !== $values['renewal_date']) {
        $errors['renewal_date'] = 'Please enter a valid date.';
    }

    $values['new_expiry_date'] = wl_date_add_one_year($values['renewal_date']);

    if (!$errors) {
        try {
            $pdo->beginTransaction();

            $stmtIns = $pdo->prepare(
                'INSERT INTO license_renewals (license_id, renewal_date, new_expiry_date, bill_no, serviced_by)
                 VALUES (:license_id, :renewal_date, :new_expiry_date, :bill_no, :serviced_by)'
            );
            $stmtIns->execute([
                ':license_id' => $licenseId,
                ':renewal_date' => $values['renewal_date'],
                ':new_expiry_date' => $values['new_expiry_date'],
                ':bill_no' => $values['bill_no'] !== '' ? $values['bill_no'] : null,
                ':serviced_by' => $values['serviced_by'] !== '' ? $values['serviced_by'] : null,
            ]);

            $stmtUp = $pdo->prepare(
                'UPDATE licenses
                 SET bill_no = :bill_no,
                     last_service_date = :renewal_date,
                     expiry_date = :new_expiry_date,
                     serviced_by = :serviced_by
                 WHERE id = :id'
            );
            $stmtUp->execute([
                ':bill_no' => $values['bill_no'] !== '' ? $values['bill_no'] : null,
                ':renewal_date' => $values['renewal_date'],
                ':new_expiry_date' => $values['new_expiry_date'],
                ':serviced_by' => $values['serviced_by'] !== '' ? $values['serviced_by'] : null,
                ':id' => $licenseId,
            ]);

            $pdo->commit();

            wl_flash_set('success', 'License renewed. New expiry: ' . $values['new_expiry_date'] . '.');
            wl_redirect('licenses');
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $errors['form'] = 'Renewal failed. Please try again.';
        }
    }
}

require __DIR__ . '/partials/header.php';
?>

<div class="mt-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h4 class="mb-1"><i class="fa-solid fa-rotate me-2"></i>Renew License</h4>
            <div class="wl-muted">Renewal updates expiry to +1 year and stores a renewal history record.</div>
        </div>
        <div class="d-flex gap-2">
        <a class="btn btn-outline-primary" href="licenses"><i class="fa-solid fa-arrow-left me-2"></i>Back</a>
            <a class="btn btn-outline-primary" href="licenses.php"><i class="fa-solid fa-arrow-left me-2"></i>Back</a>
        </div>
    </div>

    <?php if (isset($errors['form'])): ?>
        <div class="alert alert-danger wl-alert mt-3"><?= h($errors['form']) ?></div>
    <?php endif; ?>

    <div class="row g-3 mt-1">
        <div class="col-lg-5">
            <div class="card wl-card h-100">
                <div class="card-header">
                    <div class="fw-bold">License Details</div>
                    <div class="small wl-muted">Current customer and scale info</div>
                </div>
                <div class="card-body">
                    <div class="fw-bold fs-5"><?= h($license['full_name']) ?></div>
                    <div class="wl-muted"><?= h($license['location']) ?><?= $license['tel'] ? ' · ' . h($license['tel']) : '' ?><?= $license['id_no'] ? ' · ' . h($license['id_no']) : '' ?></div>
                    <hr class="border-white border-opacity-10">
                    <div class="fw-bold"><?= h($license['model']) ?></div>
                    <div class="wl-muted">Serial: <span class="wl-mono"><?= h($license['serial_no']) ?></span><?= $license['capacity'] ? ' · ' . h($license['capacity']) : '' ?><?= $license['reg_no'] ? ' · Reg: ' . h($license['reg_no']) : '' ?></div>
                    <hr class="border-white border-opacity-10">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="wl-muted small">Last Service</div>
                            <div class="wl-mono"><?= h((string)$license['last_service_date']) ?></div>
                        </div>
                        <div class="col-6">
                            <div class="wl-muted small">Current Expiry</div>
                            <div class="wl-mono"><?= h((string)$license['expiry_date']) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card wl-card">
                <div class="card-header">
                    <div class="fw-bold">Renewal</div>
                    <div class="small wl-muted">Select renewal date and confirm bill / serviced by</div>
                </div>
                <div class="card-body">
                    <form method="post" class="row g-3">
                        <input type="hidden" name="license_id" value="<?= h($values['license_id']) ?>">

                        <div class="col-md-6">
                            <label class="form-label">Renewal Date <span class="text-danger">*</span></label>
                            <input class="form-control <?= isset($errors['renewal_date']) ? 'is-invalid' : '' ?>" type="date" name="renewal_date" value="<?= h($values['renewal_date']) ?>" data-wl-issue-date required>
                            <?php if (isset($errors['renewal_date'])): ?>
                                <div class="invalid-feedback"><?= h($errors['renewal_date']) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">New Expiry (Auto)</label>
                            <input class="form-control" type="date" name="new_expiry_date" value="<?= h($values['new_expiry_date']) ?>" data-wl-expiry-date readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Bill No</label>
                            <input class="form-control" name="bill_no" value="<?= h($values['bill_no']) ?>" placeholder="Ex: 15027">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Serviced By</label>
                            <input class="form-control" name="serviced_by" value="<?= h($values['serviced_by']) ?>" placeholder="Ex: Chandika">
                        </div>

                        <div class="col-12 d-flex gap-2">
                            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-rotate me-2"></i>Renew Now</button>
                            <a class="btn btn-outline-primary" href="expiring"><i class="fa-solid fa-calendar-check me-2"></i>Due Monitor</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>

