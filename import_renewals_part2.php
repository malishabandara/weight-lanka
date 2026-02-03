<?php
require __DIR__ . '/lib/bootstrap.php';

// Data Rows 22 to 43
$data = [
    ['2025-11-07', '15040', 'Keerthi Munasinghe,Singhe Pawing Center', 'Ambalanthota', '0472225064', 'Budry WP A-84', 'EPWS-495', '300g', '33-12740', '198160600532', 'Chandika'],
    ['2025-11-07', '15041', 'R.Kumara,Biththara kade,Walawa', 'Ambalanthota', '0775140830', 'Royal ACS-C-KP2', '23549', '15kg', '33-7694', '763132048V', 'Chandika'],
    ['2025-11-07', null, 'R.Kumara,Biththara kade,Walawa', 'Ambalanthota', '0775140830', 'Solid KP-N77-150', '5752', '150kg', '33-13743', '763132048V', 'Chandika'],
    ['2025-11-07', '15043', 'K.K.Premalal,Sriyani St,Sisilas gama', 'Hambanthota', '0777540328', 'Mortex R15P', '0505-1033', '15kg', '33-8784', '653490062V', 'Chandika'],
    ['2025-11-07', null, 'K.K.Premalal,Sriyani St,Sisilas gama', 'Hambanthota', '0777540328', 'Aqua T 30', '2231', '30kg', '33-12772', '653490062V', 'Chandika'],
    ['2025-11-07', '15044', 'Sujith Kumara,Green Island Bakers,No.26,Chithragala', 'Ambalanthota', '0766170009', 'Budry MFD-51', 'MFD-51 15891', '15kg', '33-23034', '197607601419', 'Chandika'],
    ['2025-11-07', '15046', 'H.T.Shanthasiri,Nilmini St,Mirijjawila', 'Hambanthota', '0716630937', 'Canter ST-918', '1427', '15kg', null, '197204403368', 'Chandika'],
    ['2025-11-07', '15167', 'K.H.A.Ishika,Indika Kalu Dodol,No.08,Siribopura', 'Hambanthota', '0719434367', 'Camry ACS-K', '3166', '15kg', '33-16412', '817501753V', 'Chandika'],
    ['2025-11-07', null, 'K.H.A.Ishika,Indika Kalu Dodol,No.08,Siribopura', 'Hambanthota', '0719434367', 'Camry ACS-K', 'UNKNOWN-R30', '15kg', '33-12758', '817501753V', 'Chandika'],
    ['2025-11-07', '15169', 'M.M.Harshana,Nilan Food City,Mayurapura', 'Hambanthota', '0760594957', 'Budry MFD-51', 'MFD-51 15805', '15kg', '33-23028', '818393636V', 'Chandika'],
    ['2025-11-07', '15170', 'P.K.Milan,Prayam Super,Siribopura', 'Hambanthota', '0766740083', 'alpha T-10', '1445', '15kg', null, '880220104V', 'Chandika'],
    ['2025-11-07', '15173', 'K.Munasinghe,Singhe Pawing Center,No.29/2', 'Hambanthota', '0472220372', 'Budry WPA 84', 'EPWS-0501', '300g', '33-12762', '196508800366', 'Chandika'],
    ['2025-11-07', '15172', 'J.M.Nayana Shyamali,Madu rasa St,10/2,Baddewela', 'Hambanthota', '0759132672', 'Hana 640SL', 'B 5706', '15kg', null, '196873102449', 'Chandika'],
    ['2025-11-07', '15171', 'R.H.M.Dinesh,Vegi Shop,Baddewela', 'Hambanthota', '0761711959', 'Aqua -ACS-KPS', '8600', '15kg', '33-12766', '891744994V', 'Chandika'],
    ['2025-11-07', '15036', 'M.A.M.Tasneem,Ruhunu Farm Shop,Melekolaniya', 'Ambalanthota', '0705782201', 'Tiger TIGE', 'R3P21000-6EM', '15kg', '33-7203', '931641492V', 'Chandika'],
    ['2025-11-07', null, 'M.A.M.Tasneem,Ruhunu Farm Shop,Melekolaniya', 'Ambalanthota', '0705782201', 'Hana 640SL', 'B 2784', '15kg', '33-7201', '931641492V', 'Chandika'],
    ['2025-11-07', '15042', 'Anura Bandara,Vegi Shop,Seetharama Pansala Asala,Sisiliyagama', 'Hambanthota', '0778117620', 'Royal ACS-C-KP1', '6331', '15kg', '33-12744', '733523620V', 'Chandika'],
    ['2025-11-07', '15711', 'Onari Vee Mola,No.11,Lewaya Egodaha', 'Hambanthota', '0706060620', 'Solid KP-N77-300', '486', '300kg', '33-11259', '863510279V', 'Malindu'],
    ['2025-11-07', '15554', 'Pasindu St,No.41,Sarodagawa', 'Hambanthota', '0771039438', 'Canter KF-268', '1134', '15kg', null, '793443196V', 'Malindu'],
    ['2025-11-07', '15770-S', 'Lanka Sathosa', 'Hambanthota', '0472222379', 'IM30A', '50077431', '15kg', null, '197025501517', 'Malindu'],
    ['2025-11-07', null, 'Lanka Sathosa', 'Hambanthota', '0472222379', 'IM30A', '50077489', '15kg', '33-74196', '197025501517', 'Malindu'],
    ['2025-11-07', null, 'Lanka Sathosa', 'Hambanthota', '0472222379', 'A-Camsl-1', '1701060', '600kg', '33-10733', '197025501517', 'Malindu'],
];

$pdo->beginTransaction();
try {
    foreach ($data as $i => $row) {
        [$date, $billNo, $name, $loc, $tel, $model, $serial, $cap, $reg, $nid, $tech] = $row;
        
        // 1. Customer
        $parts = explode(',', $name, 2);
        $fullName = trim($parts[0]);
        $address = trim($parts[1] ?? '');

        // Find existing customer (important if same customer appears multiple times)
        $stmt = $pdo->prepare("SELECT id FROM customers WHERE full_name = ? AND (tel = ? OR id_no = ?)");
        $stmt->execute([$fullName, $tel, $nid]);
        $custId = $stmt->fetchColumn();

        if (!$custId) {
            $stmt = $pdo->prepare("INSERT INTO customers (full_name, address, location, tel, id_no) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$fullName, $address, $loc, $tel, $nid]);
            $custId = $pdo->lastInsertId();
            echo "Created Customer: $fullName\n";
        }

        // 2. Scale
        $stmt = $pdo->prepare("SELECT id FROM scales WHERE serial_no = ? AND model = ?");
        $stmt->execute([$serial, $model]);
        $scaleId = $stmt->fetchColumn();

        if (!$scaleId) {
            $stmt = $pdo->prepare("INSERT INTO scales (model, serial_no, capacity, reg_no) VALUES (?, ?, ?, ?)");
            $stmt->execute([$model, $serial, $cap, $reg]);
            $scaleId = $pdo->lastInsertId();
            echo "Created Scale: $model ($serial)\n";
        }

        // 3. License
        $expiry = date('Y-m-d', strtotime($date . ' +1 year'));
        
        $stmt = $pdo->prepare("INSERT INTO licenses (customer_id, scale_id, bill_no, last_service_date, expiry_date, serviced_by) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$custId, $scaleId, $billNo, $date, $expiry, $tech]);
        echo "Created License for $fullName (Scale: $serial)\n";
    }

    $pdo->commit();
    echo "Import Part 2 Successful!";

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}
