<?php
require __DIR__ . '/lib/bootstrap.php';

$defaultDate = '2025-10-15'; 

// Data Part 5
$data = [
    ['Waralla', '0754719065', 'Royal ACS-C-KP1', '23364', '15kg', '-', '745973590V', 'Malindu'],
    ['Waralla', '0761584954', 'Avery Berkel SL 6405', 'B - 3529', '15kg', '32-19891', '791744725V', 'Chaminda'],
    ['Waralla', '0778617023', 'Royal ACS-C-KP2', '2518', '15kg', '32-7654', '196656900040', 'Chaminda'],
    ['Waralla', '0713507052', 'alpha ACS-A9', '366', '15kg', '32-4503', '197220625422', 'Tharindu'],
    ['Waralla', '0704818008', 'Rainbow ACS A', 'RGS 0160', '15kg', '32 13485', '197217401893', 'Chaminda'],
    ['Morawaka', '0740431468', 'Camry ACS-K', '9581', '15kg', '-', '763044246V', 'Chaminda'],
    ['Morawaka', '0718107960', 'Tiger', 'R3P08787-6LN', '15kg', '32-1101', '815501012V', 'Malindu'],
    ['Morawaka', '0775311232', 'Alfha Vision ACS-T1', '1727', '15kg', '32-8849', '197966503418', 'Malindu'],
    ['Morawaka', '0714000983', 'Rolex ZP 15B', 'NMB 102', '15kg', '32-3046', '815660617V', 'Chaminda'],
    ['Morawaka', '0768668771', 'Alpha T-1', '0087', '15kg', '32-16421', '621662511V', 'Chaminda'],
    ['Morawaka', '0710179955', 'Budry WP15', '15/200241462', '15kg', '32-8820', '821000842V', 'Chaminda'],
    ['Morawaka', '0774835651', 'Budry ZARGR-15T', '15/6455', '15kg', '32-10819', '723583519V', 'Malindu'],
    ['Morawaka', '0711126500', 'Budry WP15', '15/230551749', '15kg', '-', '503520044V', 'Chaminda'],
    ['Morawaka', '0412282964', 'Sunway ACS-CR3', '430', '15kg', '32-5120', '-', 'Tharindu'],
    ['Morawaka', '0412282964', 'Sunway ACS-CR3', '417', '15kg', '32-5119', '-', 'Tharindu'],
    ['Morawaka', '0412282964', 'Acom Sl-7', '1701066', '600kg', '32-5121', '-', 'Tharindu'],
    ['Waralla', '0742947577', 'Rainbow ACS-A', '0713', '15kg', '32-19913', '702760089V', 'Chaminda'],
    ['Kotapola', '0719026390', 'Unique ACS-A1', '6700', '15kg', '-', '890734170V', 'Malindu'],
    ['Kotapola', '0779049413', 'Apex ACS A', '33876', '15kg', '32 21060', '936292585V', 'Chaminda'],
    ['Kotapola', '0767517328', 'Alfa Vision ACS-T1', '4085', '15kg', '32-21013', '19746802625', 'Chaminda'],
    ['Kotapola', '0760936454', 'Royal ACS-C-KP2', '4541', '15kg', '32-12928', '860844311V', 'Chaminda'],
    ['Kotapola', '0760936454', 'Accura T 7E', '0319', '300kg', '32-10702', '860844311V', 'Chaminda'],
    ['Kotapola', '0719588833', 'Energy PC-1', '687', '15kg', '32-21046', '195311400443', 'Chaminda'],
    ['Kotapola', '0713480744', 'Solid KP-N77-60', '1363', '60kg', '-', '866523231V', 'Chaminda'],
    ['Waralla', '0771014110', 'Sayaki Class iii', 'WT208/002/311', '15kg', '32-14395', '8935891567V', 'Tharindu'],
    ['Waralla', '0704906471', 'Budry WP15', '15/171233015', '15kg', '-', '-', 'Chaminda'],
    ['Waralla', '0773686620', 'Budry WP15', '15/180334118', '15kg', '32-8507', '451440993V', 'Chaminda'],
    ['Waralla', '0770407208', 'Royal ACS-C-KP4', '36606', '15kg', '32-21038', '922540610V', 'Malindu'],
    ['Kotapola', '0771758990', 'Clever ACS-C', '100501093', '15kg', '32-6494', '195225201710', 'Chaminda'],
    ['Beralapanathare', '0717878837', 'Sunway KF 268', '20563', '15kg', '32-5901', '651021111V', 'Chaminda'],
    ['Beralapanathare', '0768708655', 'Aqua T 30', '6334', '30kg', '-', '-', 'Chaminda'],
    ['Kirilipana', '0769037105', 'Gold Bell IND 102/W', '17H 2G 252', '150kg', '-', '200300100901', 'Chaminda'],
    ['Kirilipana', '0772801290', 'Cogent PC-15P', '15167', '15kg', '32-5549', '631782078V', 'Chaminda'],
    ['Kirilipana', '0717952742', 'Alfa Vision ACS-T1', '4364', '15kg', '32-14095', '562271651V', 'Buddhika'],
    ['Kirilipana', '0719486705', 'Camry ACS-K', '5321', '15kg', '32-21427', '797591629V', 'Chaminda'],
    ['Waralla', '0778081326', 'Apex ACS-A', '3840', '15kg', '32-8506', '832634670V', 'Chaminda'],
    ['Urubokka', '0715216008', 'Budry WP15', '15/170230984', '15kg', '32-5630', '740240528V', 'Malindu'],
    ['Urubokka', '0717438645', 'Sayaki', 'WT/2008/000/324', '15kg', '32-5528', '968621564V', 'Buddhika'],
    ['Urubokka', '0710926411', 'Budry WP15', '15/220952252', '15kg', '32-21302', '658630920V', 'Buddhika'],
    ['Beralapanathare', '0778851371', 'Solid KP-N77-300', '6293', '300kg', '32-22001', '771880215V', 'Buddhika'],
    ['Beralapanathare', '0767780725', 'Royal ACS-KP2', '29927', '15kg', '-', '198352602200', 'Buddhika'],
    ['Kirilipana', '0763868726', 'Clever ACS-LM', '101101323', '15kg', '32-5836', '720233002V', 'Buddhika'],
    ['Kirilipana', '0761130458', 'Budry ZARGR-15T', 'T-15/8635', '15kg', '-', '721250051V', 'Buddhika'],
    ['Kirilipana', '0706290041', 'Apex ACS-A', '1072', '15kg', '32-13416', '802444462V', 'Buddhika'],
    ['Kirilipana', '0776085486', 'ALDA A15P', '0537', '15kg', '-', '731623988V', 'Buddhika'],
    ['Kirilipana', '0717100873', 'Apex ACS-A', '10748', '15kg', '32-7796', '871912475V', 'Buddhika'],
    ['Kirilipana', '0765285997', 'Crystal ACS-C-AAA', '3395', '15kg', '32-5532', '623052670V', 'Buddhika'],
    ['Pothdeniya', '0766226279', 'Butterfly ACS-C1', '14014', '15kg', '32-5890', '-', 'Buddhika'],
    ['Kirilipana', '0717247933', 'Apex ACS-A', '29778', '15kg', '32-19479', '911662957V', 'Buddhika'],
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
    echo "Import Part 5 Successful!";

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}
