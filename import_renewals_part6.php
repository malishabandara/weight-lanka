<?php
require __DIR__ . '/lib/bootstrap.php';

$defaultDate = '2025-10-15'; 

// Data Part 6
$data = [
    ['Morawaka', '0763723581', 'Unique ACS-A1', '5212', '15kg', '32-21326', '196110304520', 'Buddhika'],
    ['Deiyandara', '0412208855', 'Acom JW-1', '1601420', '300g', '-', '-', 'Malindu'],
    ['Hakmana', '0761151432', 'Bdury mari 300', '197430019', '300g', '32-15777', '905243659V', 'Malindu'],
    ['Hakmana', '0414036208', 'Select GSP+30', '2020941', '30kg', '-', '856134687V', 'Malindu'],
    ['Gangodagama', '0775329324', 'Excell ACS-A', 'ACS-9P0127', '15kg', '32-2469', '696220077V', 'Malindu'],
    ['Kubalgoda', '0778639931', 'Budry ZAR WP PB15', '4041', '15kg', '32-2601', '846352015V', 'Malindu'],
    ['Hakmana', '0704160840', 'Cristal JW-618', '0540', '15kg', '32-2600', '937031483V', 'Malindu'],
    ['Ellewela', '0743389312', 'Cristal JW-618', '0703', '15kg', '32-14807', '725172214V', 'Malindu'],
    ['Hakmana', '0772997508', 'Excell ACS-A', 'ACS-9P0128', '15kg', '32-15853', '-', 'Malindu'],
    ['Deiyandara', '0771713080', 'Budry ZAR GR157', 'T-15/8158', '15kg', '32-21478', '8009500584', 'Malindu'],
    ['Bumunugama', '0778540388', 'Meero ACS-GE', 'GE-5848', '15kg', '32-0979', '-', 'Malindu'],
    ['Kamburupitiya', '0774081844', 'Royal ACS-C-KP2', '35318', '15kg', '33-3317', '685252686V', 'Malindu'],
    ['Deiyandara', '0772098236', 'Sunway ACS-CR', '8298', '15kg', '32-6872', '198275800430', 'Malindu'],
    ['Deiyandara', '0772098236', 'Royal ACS-C-KP2', '4257', '15kg', '32-7460', '198275800430', 'Malindu'],
    ['Deiyandara', '0412268067', 'Sunway ACS-CR', '2007-CR1139', '30kg', '32-14810', '-', 'Malindu'],
    ['Deiyandara', '0412268067', 'Aqua ACS-C-KP5', '9441', '15kg', '32-6697', '-', 'Malindu'],
    ['Radawela', '0776083122', 'Aqua T30', '2072', '30kg', '32-6433', '670442646V', 'Malindu'],
    ['Radawela', '0711495073', 'Excell RW 300', '120907', '300kg', '32-6837', '7310835117V', 'Malindu'],
    ['Deiyandara', '0711469063', 'Avery LT21', 'A10425125', '300kg', '32-3059', '912892727V', 'Malindu'],
    ['Deiyandara', '0710806667', 'Royal ACS-C-KP2', '27031', '15kg', '32-10736', '196317410149', 'Malindu'],
    ['Deiyandara', '0710806667', 'Budry mari-300', '2402-3001-20', '300kg', '32-10737', '196317410149', 'Malindu'],
    ['Handugala', '0705174849', 'Sunway KF 268', '14870', '15kg', '32-21513', '-', 'Buddhika'],
    ['Handugala', '0702803731', 'Canter KF A1', '478', '15kg', '-', '198005101925', 'Shop'],
    ['Deiyandara', '0710789944', 'Apex ACS-A', '2684', '15kg', '32-9164', '-', 'Malindu'],
    ['Deiyandara', '0776638225', 'Hana 640SL', 'B 5599', '15kg', '32-3111', '911313650V', 'Malindu'],
    ['Deiyandara', '0779913885', 'alpha TEC', '0127', '15kg', '32-18424', '733121866V', 'Buddhika'],
];

$pdo->beginTransaction();
try {
    foreach ($data as $i => $row) {
        [$loc, $tel, $model, $serial, $cap, $reg, $nid, $tech] = $row;
        
        $fullName = "Customer ($loc)"; 

        // 1. Customer
        $stmt = $pdo->prepare("SELECT id, full_name FROM customers WHERE tel = ? OR id_no = ?");
        $stmt->execute([$tel, $nid]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            $custId = $existing['id'];
            $fullName = $existing['full_name'];
            echo "Matched Customer: $fullName\n";
        } else {
            $stmt = $pdo->prepare("INSERT INTO customers (full_name, address, location, tel, id_no) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$fullName, $loc, $loc, $tel, $nid]);
            $custId = $pdo->lastInsertId();
            echo "Created Customer: $fullName\n";
        }

        // 2. Scale
        $stmt = $pdo->prepare("SELECT id FROM scales WHERE serial_no = ? AND model = ?");
        $stmt->execute([$serial, $model]);
        $scaleId = $stmt->fetchColumn();

        if (!$scaleId) {
            $stmt = $pdo->prepare("INSERT INTO scales (model, serial_no, capacity, reg_no) VALUES (?, ?, ?, ?)");
            $stmt->execute([$model, $serial, $cap, $reg !== '-' ? $reg : null]);
            $scaleId = $pdo->lastInsertId();
            echo "Created Scale: $model ($serial)\n";
        }

        // 3. License
        $expiry = date('Y-m-d', strtotime($defaultDate . ' +1 year'));
        
        $stmt = $pdo->prepare("SELECT id FROM licenses WHERE scale_id = ?");
        $stmt->execute([$scaleId]);
        if (!$stmt->fetch()) {
             $stmt = $pdo->prepare("INSERT INTO licenses (customer_id, scale_id, last_service_date, expiry_date, serviced_by) VALUES (?, ?, ?, ?, ?)");
             $stmt->execute([$custId, $scaleId, $defaultDate, $expiry, $tech]);
             echo "Created License for $fullName\n";
        }
    }

    $pdo->commit();
    echo "Import Part 6 Successful!";

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}
