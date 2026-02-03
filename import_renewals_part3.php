<?php
require __DIR__ . '/lib/bootstrap.php';

// Data Rows (First ~30 rows from the long list)
$data = [
    ['2025-10-15', '15918', 'C.Liyanage,Food & Bio Tech,Kandedola', 'Rathanapala', '0773630652', 'Zepper BZ', '20701', '15kg', '33-14065', '732361815V', 'Chandika'],
    ['2025-10-15', '15923', 'H.A.Saman,Galgamuwa St,Maraba Rd', 'Rathanapala', '0775607348', 'Alpha ACS-C-002', '31804', '15kg', '33-16474', '750611385V', 'Chandika'],
    ['2025-10-15', '15923', 'H.A.Saman,Galgamuwa St,Maraba Rd', 'Rathanapala', '0775607348', 'Canter KF A1', '3586', '30kg', '33-6623', '750611385V', 'Chandika'],
    ['2025-10-15', '15921', 'M.A.Sunil,Fruit Shop,Old Audugama Rd', 'Rathanapala', '0774618790', 'Hana DR25TS', 'B 11142', '15kg', '33-20450', '651263328V', 'Chandika'],
    ['2025-10-15', '15922', 'P.R.Wijesinghe,Shalika HW', 'Rathanapala', '0718693080', 'Royal ACS-C-KP1', '3553', '15kg', '33-6118', '832822003V', 'Chandika'],
    ['2025-10-15', null,    'P.R.Wijesinghe,Shalika HW', 'Rathanapala', '0718693080', 'Aloha ACS-C-999', '29555', '30kg', '33-6117', '832822003V', 'Chandika'],
    ['2025-10-15', '15924', 'H.M.Dayananda,Thulana,Siyabalagaha Junction', 'Rathanapala', '0724588840', 'Aqua T-30', '722', '30kg', '33-6523', '701511868V', 'Chandika'],
    ['2025-10-15', '15020', 'M.A.S.R.Gamage,Double Cool Spot', 'Rathanapala', '0702110025', 'Alpha T-01', '20981', '15kg', '33-22124', '812632559V', 'Chandika'],
    ['2025-10-15', '15019', 'A.H.U.S.Susantha,Win set St,Udakandawala', 'Rathanapala', '0716634342', 'Apex ACS-A', '26645', '15kg', '33-6814', '842240248V', 'Chandika'],
    ['2025-10-15', '15910', 'S.D.Somawathie,Mudalirama St,Kalukanda', 'Rathanapala', '0777938338', 'Apex ACS-A', '433', '15kg', '33-14711', '636671842V', 'Chandika'],
    ['2025-10-15', '15930', 'H.W.Jayamang,Jayakody St,Rammalgoda', 'Rathanapala', '0779262064', 'Royal ACS-C-KP1', '5886', '15kg', '33-9095', null, 'Chandika'],
    ['2025-10-15', '15929', 'K.P.P.Silva,Silva Stores,Udakandawala', 'Rathanapala', '0718228228', 'Sumsung ACS-30', '009012', '30kg', '33-14125', '843152600V', 'Chandika'],
    ['2025-10-15', '15928', 'S.Nyangama,Sarasi Stores,Walanagala', 'Embilipitiya', '0768224564', 'Apex ACS-A', '14885', '15kg', '33-21146', '920812425V', 'Malindu'],
    ['2025-10-16', '15908', 'I.Chamika Iswan Kumanayake,Iswan St,Weeraketiya Rd', 'Kekanadura', '0702812586', 'Aloha T-01', '1720', '15kg', null, '700862260V', 'Malindu'],
    ['2025-10-16', '15905', 'M.A.Dimuthu,Imalsha Produce', 'Walasmulla', '0716187515', 'Solid KP-N77-150', '5460', '150kg', '33-16282', '198614400969', 'Tharindu'],
    ['2025-10-17', '15934', 'H.G.Premarathne,Isuru St,Galdola', 'Rathanapala', '0711685890', 'Seculite BT 208', '51100062', '15kg', '33-9137', '531343171V', 'Chandika'],
    ['2025-10-17', '15933', 'G.W.H.N.Lakmali,Lakmali Hotel,Galdola', 'Rathanapala', '0766347949', 'Sumsung ACS-30', '9888', '30kg', '33-8745', '935510694V', 'Chandika'],
    ['2025-10-19', '15307', 'Saman Bandara,Damini Fruit,Dambuldeniya', 'Walasmulla', '0774119438', 'SeryAll Class-III', 'WT 30/3000/0152', '15kg', '33-2554', '0774119438', 'Tharindu'],
    ['2025-10-19', '15339', 'R.Chaminda,Chaminda St,BookKey,Near the Bridge', 'Walasmulla', '0771330828', 'Budry WP15', '15/2208036983', '15kg', '33-18992', '891942534V', 'Tharindu'],
    ['2025-10-19', null,    'R.Chaminda,Chaminda St,BookKey,Near the Bridge', 'Walasmulla', '0771330828', 'Apex ACS-C-KP2', '1005', '15kg', '33-18995', '891942534V', 'Chandindu'],
    ['2025-10-19', '15309', 'P.Abesinghe,P.P.M.Distributors', 'Walasmulla', '0702660050', 'Aloha ACS-A', '29193', '15kg', '33-9730', null, 'Tharindu'],
    ['2025-10-19', '15306', 'J.Indrasena,Gammadda St,Pasmeliyaya Factory', 'Walasmulla', '0713898089', 'Royal ACS-C-KP1', '33084', '15kg', '33-14338', '622020291V', 'Tharindu'],
    ['2025-10-19', '15304', 'T.Priyantha,Priyantha St', 'Walasmulla', '0713439408', 'Camry ACS-K', '31089', '15kg', '33-10444', '196807800164', 'Tharindu'],
    ['2025-10-19', '15305', 'A.T.Priyanthe,Priyantha St', 'Walasmulla', '0713439408', 'Dagar PC-100', '20245', '15kg', null, '196807800164', 'Tharindu'],
    ['2025-10-19', '15303', 'U.G.Kanthi,Nimsara Tea Room,Kanu Mamwatha', 'Walasmulla', '0764713065', 'Royal ACS-C-KP1', '28068', '15kg', '33-14337', '745811590V', 'Malindu'],
    ['2025-10-19', '15302', 'G.Sudth Sena,Tea Room', 'Walasmulla', '0763955610', 'Avery Berkel SL 6405', 'B 41019', '15kg', '33-8869', '795744722V', 'Chandika'],
    ['2025-10-19', '15372', 'K.Geethal,Mihira St,Near Hospital,Mawattha Junction', 'Walasmulla', '0713506941', 'Royal ACS-C-KP2', '2244', '15kg', '33-7204', '198931900049', 'Chandika'],
    ['2025-10-19', '15311', 'B.G.Buddhika,Ayesha St,Parapaliya', 'Walasmulla', '0718507042', 'alpha ACS-30', '3682', '30kg', '33-9130', '197317601977', 'Chandika'],
    ['2025-10-19', '15310', 'K.G.Hemachandra,Sandagiri St,Rathmaldeniya,Juleampitiya', 'Walasmulla', '0713917409', 'Camry ACS-K', '3163', '15kg', '33-14229', '632603826V', 'Chandika'],
];

$pdo->beginTransaction();
try {
    foreach ($data as $i => $row) {
        [$date, $billNo, $name, $loc, $tel, $model, $serial, $cap, $reg, $nid, $tech] = $row;
        
        $parts = explode(',', $name, 2);
        $fullName = trim($parts[0]);
        $address = trim($parts[1] ?? '');

        // 1. Customer
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
    echo "Import Part 3 Successful!";

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}
