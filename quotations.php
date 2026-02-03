<?php
declare(strict_types=1);

require __DIR__ . '/lib/bootstrap.php';

$pageTitle = 'Quotations';
$activeNav = 'quotations';

// Default values
$date = date('Y.m.d');
$recipient = "කළමණාකරු,\nවිවිධ සේවා සමූපකාර සමිතිය‚\nහක්මණ."; // Default from example
$items = [
    ['brand' => 'APEX', 'model' => 'ACS-A', 'capacity' => '15Kg', 'division' => '5g', 'price' => '23500.00']
];

require __DIR__ . '/partials/header.php';
?>

<div class="mt-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h4 class="mb-1"><i class="fa-solid fa-file-invoice me-2"></i>Quotations</h4>
            <div class="wl-muted">Create printable quotation.</div>
        </div>
    </div>

    <div class="card wl-card mt-3">
        <div class="card-body">
            <form action="quotation_print" method="post" target="_blank">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Date (දිනය)</label>
                        <input type="text" class="form-control" name="date" value="<?= h($date) ?>">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Recipient (ලිපිනය)</label>
                        <textarea class="form-control" name="recipient" rows="3"><?= h($recipient) ?></textarea>
                    </div>
                </div>

                <h5 class="mt-4 mb-3">Items (උපකරණ)</h5>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="tbl-items">
                        <thead>
                            <tr class="table-light">
                                <th>Brand (වර්ගය)</th>
                                <th>Model (මාදිලිය)</th>
                                <th>Capacity (බර)</th>
                                <th>Range (පරාසය)</th>
                                <th>Price (මිල)</th>
                                <th style="width: 50px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $i => $item): ?>
                            <tr>
                                <td><input class="form-control" name="items[<?= $i ?>][brand]" value="<?= h($item['brand']) ?>"></td>
                                <td><input class="form-control" name="items[<?= $i ?>][model]" value="<?= h($item['model']) ?>"></td>
                                <td><input class="form-control" name="items[<?= $i ?>][capacity]" value="<?= h($item['capacity']) ?>"></td>
                                <td><input class="form-control" name="items[<?= $i ?>][division]" value="<?= h($item['division']) ?>"></td>
                                <td><input class="form-control" name="items[<?= $i ?>][price]" value="<?= h($item['price']) ?>"></td>
                                <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(this)"><i class="fa-solid fa-trash"></i></button></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-sm btn-outline-success mb-3" onclick="addRow()"><i class="fa-solid fa-plus me-1"></i> Add Row</button>

                <div class="d-grid d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                        <i class="fa-solid fa-print"></i> Generate & Print
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let rowIdx = <?= count($items) ?>;
function addRow() {
    const tbody = document.querySelector('#tbl-items tbody');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td><input class="form-control" name="items[${rowIdx}][brand]"></td>
        <td><input class="form-control" name="items[${rowIdx}][model]"></td>
        <td><input class="form-control" name="items[${rowIdx}][capacity]"></td>
        <td><input class="form-control" name="items[${rowIdx}][division]"></td>
        <td><input class="form-control" name="items[${rowIdx}][price]"></td>
        <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(this)"><i class="fa-solid fa-trash"></i></button></td>
    `;
    tbody.appendChild(tr);
    rowIdx++;
}
function removeRow(btn) {
    if(document.querySelectorAll('#tbl-items tbody tr').length > 1) {
        btn.closest('tr').remove();
    }
}
</script>

<?php require __DIR__ . '/partials/footer.php'; ?>
