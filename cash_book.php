<?php
declare(strict_types=1);

require __DIR__ . '/lib/bootstrap.php';

$pageTitle = 'Cash Book';
$activeNav = 'cash_book';

// Handle Adding
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'] ?? date('Y-m-d');
    $desc = trim($_POST['description'] ?? '');
    $type = $_POST['type'] ?? 'Expense';
    $amount = (float)($_POST['amount'] ?? 0);

    if ($desc === '') {
        wl_flash_set('danger', 'Description is required.');
    } else {
        $income = 0;
        $expense = 0;
        if ($type === 'Income') {
            $income = $amount;
        } else {
            $expense = $amount;
        }

        $stmt = $pdo->prepare("INSERT INTO cash_book (transaction_date, description, income, expense) VALUES (?, ?, ?, ?)");
        $stmt->execute([$date, $desc, $income, $expense]);
        wl_flash_set('success', 'Transaction added.');
        wl_redirect('cash_book');
    }
}

// Fetch all rows
$rows = $pdo->query('SELECT * FROM cash_book ORDER BY transaction_date DESC, id ASC')->fetchAll();

// Group by Date
$grouped = [];
foreach ($rows as $r) {
    $d = $r['transaction_date'];
    if (!isset($grouped[$d])) {
        $grouped[$d] = ['details' => [], 'total_income' => 0, 'total_expense' => 0];
    }
    $grouped[$d]['details'][] = $r;
    $grouped[$d]['total_income'] += $r['income'];
    $grouped[$d]['total_expense'] += $r['expense'];
}

require __DIR__ . '/partials/header.php';
?>

<div class="mt-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h4 class="mb-1"><i class="fa-solid fa-file-invoice-dollar me-2"></i>Cash Book</h4>
            <div class="wl-muted">Daily Income & Expenses Tracker</div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddTrans"><i class="fa-solid fa-plus me-2"></i>Add Entry</button>
        </div>
    </div>

    <div class="card wl-card mt-4 border-0">
        <div class="card-body p-0">
            <?php if (empty($grouped)): ?>
                <div class="p-5 text-center text-muted">No transactions found. Click "Add Entry" to start.</div>
            <?php else: ?>
                <?php foreach ($grouped as $date => $data): ?>
                    <?php 
                        $balance = $data['total_income'] - $data['total_expense'];
                    ?>
                    <div class="mb-4 shadow-sm rounded overflow-hidden">
                        <div class="bg-warning bg-opacity-25 p-2 px-3 fw-bold border-bottom d-flex justify-content-between align-items-center">
                            <span><i class="fa-regular fa-calendar me-2"></i><?= $date ?></span>
                            <span class="small text-muted">Daily Total Balance: <?= number_format($balance, 2) ?></span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0 bg-white align-middle">
                                <thead class="table-light small text-uppercase">
                                    <tr>
                                        <th style="width: 50%;">Description</th>
                                        <th class="text-end" style="width: 25%;">Income</th>
                                        <th class="text-end" style="width: 25%;">Expenses</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['details'] as $item): ?>
                                    <tr>
                                        <td><?= h($item['description']) ?></td>
                                        <td class="text-end"><?= $item['income'] > 0 ? number_format((float)$item['income'], 2) : '' ?></td>
                                        <td class="text-end"><?= $item['expense'] > 0 ? number_format((float)$item['expense'], 2) : '' ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="bg-warning bg-opacity-10 fw-bold">
                                    <tr>
                                        <td class="text-end">Total</td>
                                        <td class="text-end"><?= number_format($data['total_income'], 2) ?></td>
                                        <td class="text-end text-danger"><?= number_format($data['total_expense'], 2) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">Balance</td>
                                        <td colspan="2" class="text-end"><?= number_format($balance, 2) ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Add Transaction -->
<div class="modal fade" id="modalAddTrans" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="post">
            <div class="modal-header">
                <h5 class="modal-title">Add Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input type="date" class="form-control" name="date" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Type</label>
                    <select class="form-select" name="type">
                        <option value="Expense">Expense</option>
                        <option value="Income">Income</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <input type="text" class="form-control" name="description" placeholder="Ex: Lunch, Sales INV 101, O/B..." required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Amount (Rs)</label>
                    <input type="number" step="0.01" class="form-control" name="amount" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Entry</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
