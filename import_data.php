<?php
require __DIR__ . '/lib/bootstrap.php';

$data = [
    [
        'date' => '2026-01-05',
        'inv' => '15030-2',
        'name' => 'M.A.Ajith',
        'address' => '"Virul Niwasa", Devalegama, Radawela',
        'location' => 'Radawela',
        'tel' => '071-2432392',
        'nid' => '683061533V',
        'scale_brand' => 'Aqua',
        'model' => 'T-15',
        'serial' => '5141',
        'capacity' => '15kg',
        'colour' => 'Green',
        'price' => 29500.00,
        'discount' => 1500.00,
        'type' => 'Cash',
        'paid' => 28000.00,
        'pay1' => 0,
        'next_lic' => '2027-01-05',
    ],
    [
        'date' => '2026-01-05',
        'inv' => '15031-2',
        'name' => 'S.M.R.Prasanna',
        'address' => '"Manju", Katuwana',
        'location' => 'Katuwana',
        'tel' => '071-1807646',
        'nid' => '883503310V',
        'scale_brand' => 'Aqua',
        'model' => 'ACS-C-KPS',
        'serial' => '35211',
        'capacity' => '15kg',
        'colour' => 'Green',
        'price' => 19500.00,
        'discount' => 0.00,
        'type' => 'Cash',
        'paid' => 19500.00,
        'pay1' => 0,
        'next_lic' => '2027-01-05',
    ],
    // Skipping Hemantha chandika row as it lacks critical data (serial, price, date)
    [
        'date' => '2026-01-09',
        'inv' => '15040-2',
        'name' => 'K.Roopa Limali',
        'address' => '221/A, Palawaththera, Beliatta',
        'location' => 'Wierawila',
        'tel' => '0740065050',
        'nid' => '776062626V',
        'scale_brand' => 'Royal',
        'model' => 'KP-2',
        'serial' => '41869',
        'capacity' => '15kg',
        'colour' => 'Green',
        'price' => 19500.00,
        'discount' => 500.00,
        'type' => 'Cash',
        'paid' => 19000.00,
        'pay1' => 0,
        'next_lic' => '2027-01-09',
    ],
    [
        'date' => '2026-01-12',
        'inv' => '15045-2',
        'name' => 'L.Saman',
        'address' => 'Saman Waduwedeniya, Egodabedde',
        'location' => 'Kirama',
        'tel' => '0715366980',
        'nid' => '790222233V',
        'scale_brand' => 'Solid',
        'model' => 'KP-W77-150',
        'serial' => '8591',
        'capacity' => '150kg',
        'colour' => 'Green',
        'price' => 105000.00,
        'discount' => 15000.00,
        'type' => 'Cash/Credit',
        'paid' => 30000.00,
        'pay1' => 0,
        'next_lic' => '2027-01-12',
    ],
    [
        'date' => '2026-01-15',
        'inv' => '15050-2',
        'name' => 'Sarana Bank',
        'address' => 'Poholathagoda, Kottegoda',
        'location' => 'Kottegoda',
        'tel' => '0713633657',
        'nid' => null,
        'scale_brand' => 'Seldia',
        'model' => 'A08',
        'serial' => '0204013',
        'capacity' => '300g',
        'colour' => 'Green',
        'price' => 85000.00,
        'discount' => 0.00,
        'type' => 'Cash',
        'paid' => 85000.00,
        'pay1' => 0,
        'next_lic' => '2027-01-15',
    ],
    [
        'date' => '2026-01-18',
        'inv' => null,
        'name' => 'Upali',
        'address' => 'No 109, "Sriniwasa", In front of the School, Walgammulla',
        'location' => 'Kirama',
        'tel' => '0715159623',
        'nid' => '943481717V',
        'scale_brand' => 'Royal',
        'model' => 'ACS-C-KPS',
        'serial' => '35567',
        'capacity' => '15kg',
        'colour' => 'Red',
        'price' => 19500.00,
        'discount' => 500.00,
        'type' => 'Cash/Credit',
        'paid' => 5000.00,
        'pay1' => 3000.00,
        'next_lic' => '2027-01-18',
    ]
];

$pdo->beginTransaction();

try {
    foreach ($data as $row) {
        // 1. Find or Create Customer
        $stmt = $pdo->prepare("SELECT id FROM customers WHERE full_name = ? AND tel = ?");
        $stmt->execute([$row['name'], $row['tel']]);
        $custId = $stmt->fetchColumn();

        if (!$custId) {
            $stmt = $pdo->prepare("INSERT INTO customers (full_name, address, location, tel, id_no) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $row['name'], 
                $row['address'], 
                $row['location'], 
                $row['tel'],
                $row['nid']
            ]);
            $custId = $pdo->lastInsertId();
            echo "Created customer: {$row['name']}\n";
        } else {
            echo "Found customer: {$row['name']}\n";
        }

        // 2. Find or Create Scale
        // We match by serial_no. If exists, we use it. If not, create.
        $stmt = $pdo->prepare("SELECT id FROM scales WHERE serial_no = ?");
        $stmt->execute([$row['serial']]);
        $scaleId = $stmt->fetchColumn();

        if (!$scaleId) {
            $stmt = $pdo->prepare("INSERT INTO scales (customer_id, model, serial_no, capacity) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $custId,
                $row['model'],
                $row['serial'],
                $row['capacity']
            ]);
            $scaleId = $pdo->lastInsertId();
            echo "Created scale: {$row['model']} ({$row['serial']})\n";
        } else {
            echo "Found scale: {$row['model']} ({$row['serial']})\n";
        }

        // 3. Create Sale
        $netPrice = $row['price'] - $row['discount'];
        $balance = $netPrice - $row['paid'];
        $netBalance = $balance - $row['pay1'];

        // Check duplicates for sale? (Same scale, same date)
        $stmt = $pdo->prepare("SELECT id FROM sales WHERE scale_id = ? AND sale_date = ?");
        $stmt->execute([$scaleId, $row['date']]);
        $exists = $stmt->fetchColumn();

        if (!$exists) {
            $stmt = $pdo->prepare(
                'INSERT INTO sales 
                (customer_id, scale_id, invoice_no, sale_date, scale_brand, model, serial_no, capacity, display_colour, 
                 scale_price, discount, net_price, payment_type, payment_amount, balance_amount, payment_1, net_balance, next_license_date)
                 VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?, ?, 
                 ?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([
                $custId,
                $scaleId,
                $row['inv'],
                $row['date'],
                $row['scale_brand'],
                $row['model'],
                $row['serial'],
                $row['capacity'],
                $row['colour'],
                $row['price'],
                $row['discount'],
                $netPrice,
                $row['type'],
                $row['paid'],
                $balance,
                $row['pay1'],
                $netBalance,
                $row['next_lic']
            ]);
            echo "Recorded sale for invoice: {$row['inv']}\n";
        } else {
            echo "Sale already exists for invoice: {$row['inv']}\n";
        }

        // 4. Create License (Upsert based on scale_id)
        $stmt = $pdo->prepare("SELECT id FROM licenses WHERE scale_id = ?");
        $stmt->execute([$scaleId]);
        $licExists = $stmt->fetchColumn();

        if (!$licExists) {
            $stmt = $pdo->prepare("INSERT INTO licenses (scale_id, bill_no, last_service_date, expiry_date) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $scaleId,
                $row['inv'],
                $row['date'],
                $row['next_lic']
            ]);
            echo "Created license expiring: {$row['next_lic']}\n";
        }
    }
    $pdo->commit();
    echo "Import completed successfully.\n";

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}
