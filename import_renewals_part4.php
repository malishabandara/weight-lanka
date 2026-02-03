<?php
require __DIR__ . '/lib/bootstrap.php';

// Data from the clear image snippet provided
// Note: Name is missing in this view, so I will placeholder user names or skip customer creation 
// if I can't derive it. Wait, previously name was Column 3. Here Column 3 is Model.
// This image is DIFFERENT columns.
// Image Columns: LOCATION | TEL | MODEL | SERIAL NO | CAP | REG NO | ID NO | SERVICED BY
// Missing: DATE, BILL NO, NAME.
// I will set a default Name like "Customer - [Location]" if name is unknown, 
// and default date to today (or 2025-10-15 based on prev context).
// Let's use 2025-10-15 as default service date based on the batch context.

$defaultDate = '2025-10-15'; 

$data = [
    ['Kotapola', '0716454500', 'Budry Z 82', '21761', '15kg', '-', '750721621V', 'Chaminda'],
    ['Kotapola', '0776007348', 'Alpha ACS-C-000', '20184', '30kg', '-', '753661980V', 'Chaminda'],
    ['Kotapola', '0776007348', 'Canter KF Ai', '3866', '15kg', '32-6491', '753661980V', 'Chaminda'],
    ['Kotapola', '0710351280', 'Hana DR25TS', 'B 3243', '15kg', '32-20453', '652313329V', 'Chaminda'],
    ['Kotapola', '0726826830', 'Royal ACS-C-KP4', '2152', '15kg', '32-8119', '850821585V', 'Chaminda'],
    ['Kotapola', '0726826830', 'Alpha ACS-C-000', '25028', '30kg', '32-8117', '850821585V', 'Chaminda'],
    ['Kotapola', '0710540840', 'Aqua T 30', '722', '30kg', '32-8125', '703333968V', 'Chaminda'],
    ['Kotapola', '0760833075', 'Alpha T -01', '10901', '15kg', '32-21164', '823610130V', 'Chaminda'],
    ['Kotapola', '0775209292', 'Apex ACS-A', '18695', '15kg', '32-8114', '682280298V', 'Chaminda'],
    ['Kotapola', '0767866239', 'Apex ACS-A', '403', '15kg', '32-14711', '696030340V', 'Chaminda'],
    ['Kotapola', '0772042034', 'Royal ACS-C-KP2', '9696', '15kg', '32-5020', '-', 'Chaminda'],
    ['Kotapola', '0718250075', 'Apex ACS-A', '11659', '15kg', '32-13573', '860104806V', 'Chaminda'],
    ['Bengamuwa', '0705247281', 'Apex ACS-A', '33405', '15kg', '32-21189', '500552743V', 'Malindu'],
    ['Kosmodara', '0766932969', 'Alpha T -10', '1710', '15kg', '-', '788835450V', 'Malindu'],
    ['Mologgamuwa', '0766869386', 'Solid KP-N77-300', '5485', '300kg', '32-17252', '198621910060', 'Tharindu'],
    ['Kotapola', '0714370769', 'Sunway KF 268', '11901', '15kg', '32-8157', '571526575V', 'Chaminda'],
    ['Kotapola', '0772864859', 'Accura L/CF', '15668', '15kg', '32-6495', '0772864859', 'Chaminda'],
    ['Waralla', '0774243435', 'Sayaki Class iii', 'WT 2010/002/2652', '15kg', '32-2334', '0774243435', 'Tharindu'],
    ['Waralla', '0771330828', 'Budry WP15', '15/190638993', '15kg', '32-18902', '891982534V', 'Tharindu'],
    ['Waralla', '0771330828', 'Aqua ACS-C-KP5', '3875', '15kg', '32-20905', '891982534V', 'Chaminda'],
    ['Waralla', '0762598936', 'Apex ACS-A', '10553', '15kg', '32-16795', '-', 'Tharindu'],
    ['Waralla', '0755988670', 'Royal ACS-C-KP2', '33087', '15kg', '-', '541316750V', 'Chaminda'],
    ['Waralla', '0412271251', 'Hana DR25TS', '11908', '15kg', '32-5033', '-', 'Chaminda'],
    ['Waralla', '0773723251', 'Cogent PC-15P', '15245', '15kg', '32-14396', '830894187V', 'Tharindu'],
];

$pdo->beginTransaction();
try {
    foreach ($data as $i => $row) {
        [$loc, $tel, $model, $serial, $cap, $reg, $nid, $tech] = $row;
        
        // Since Name is missing, we use "Unknown Customer" or derive from context if possible.
        // I will use "Customer [TEL]" pattern if name not found.
        $fullName = "Customer ($loc)"; 

        // 1. Customer
        // Try to finding by Tel/ID first
        $stmt = $pdo->prepare("SELECT id, full_name FROM customers WHERE tel = ? OR id_no = ?");
        $stmt->execute([$tel, $nid]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            $custId = $existing['id'];
            $fullName = $existing['full_name']; // Use existing name
            echo "Matched Customer: $fullName\n";
        } else {
            // Create New Placeholder Customer
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
        
        // Check for duplicates
        $stmt = $pdo->prepare("SELECT id FROM licenses WHERE scale_id = ?");
        $stmt->execute([$scaleId]);
        if (!$stmt->fetch()) {
             $stmt = $pdo->prepare("INSERT INTO licenses (customer_id, scale_id, last_service_date, expiry_date, serviced_by) VALUES (?, ?, ?, ?, ?)");
             $stmt->execute([$custId, $scaleId, $defaultDate, $expiry, $tech]);
             echo "Created License for $fullName\n";
        }
    }

    $pdo->commit();
    echo "Import Part 4 Successful!";

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}
