<?php
require __DIR__ . '/lib/bootstrap.php';

// Data from the provided image (Top 21 rows)
$data = [
    ['2025-11-07', '15734', 'T.Banda,Bus Stand', 'Hambanthota', '0760511507', 'Canter KF A1', '1017', '15kg', null, '695200544V', 'Chandika'],
    ['2025-11-07', '15734', 'T.Banda,Bus Stand', 'Hambanthota', '0760511507', 'Carna 1', '5200', '15kg', null, '695200544V', 'Chandika'],
    ['2025-11-07', '15163', 'M.P.Asanka,Vegi shop,Market', 'Hungama', '0719440926', 'Apex ACS-A', '34317', '15kg', null, '761907242V', 'Chandika'],
    ['2025-11-07', '15162', 'P.K.A.Priyangika Wasanthi,Madushanka St,Mulana', 'Hungama', '0712465698', 'Budry ZAR WPPR15', '3847', '15kg', '33-8020', '896332732V', 'Chandika'],
    ['2025-11-07', '15027', 'C.Gunasekara,Gunasekara St,Mulana', 'Hungama', '0717820530', 'Soft Cash', 'SC 002 363', '15kg', '33-8057', '596110673V', 'Chandika'],
    ['2025-11-07', '15029', 'J.A.Suranga Pradeep,Nelumpura', 'Mamadala', '0779557372', 'Camry ACS-K', '9058', '15kg', null, '863314496V', 'Chandika'],
    ['2025-11-07', '15029', 'J.A.Suranga Pradeep,Nelumpura', 'Mamadala', '0779557372', 'Avery Berkel SL 6405', 'B 3573', '15kg', null, '863314496V', 'Chandika'],
    ['2025-11-07', '15028', 'A.P.Neel Jayantha,Abeysooriya Welanda Sala,Yaya 09', 'Mamadala', '0774905159', 'Tiger TIGE', 'RSP00040-6JQ', '15kg', '33-7689', '196230003264', 'Chandika'],
    ['2025-11-07', '15026', 'I.R.Rajapaksha,Rajapaksha HW & St,Hathagala', 'Hungama', '0703182216', 'Royal ACS-C-KP1', '7201', '15kg', '33-10459', '942021259V', 'Chandika'],
    ['2025-11-07', '15026', 'I.R.Rajapaksha,Rajapaksha HW & St,Hathagala', 'Hungama', '0703182216', 'Rainbow ACS-A', 'RGS 0014', '15kg', '33-14155', '942021259V', 'Chandika'],
    ['2025-11-07', '15025', 'J.O.A.Nuwan,Kesel waththe Kade,Deniya,Hathagala', 'Hungama', '0711773926', 'Hana 640SL', 'DSC 011', '15kg', '33-14043', '19822952780', 'Chandika'],
    ['2025-11-07', '15030', 'Premadasa Witharana,Gamage St,Mandagala Rd,Lunama', 'Ambalanthota', '0776569343', 'Sunway KF 268', '8603', '15kg', '33-6597', '570050263V', 'Chandika'],
    ['2025-11-07', '15031', 'Wilshan Sri Warnasinghe,Wiyath St,Dalugahahena Rd,Lunama', 'Ambalanthota', '0712624425', 'Camry ACS-K', '7925', '15kg', null, '921490283V', 'Chandika'],
    ['2025-11-07', '15033', 'A.R.Wagarchchi,Wagarchchi Oil Center,Lunama', 'Ambalanthota', '0717394979', 'alpha ACS-C-999', '31266', '15kg', null, '951070661V', 'Chandika'],
    ['2025-11-07', '15024', 'U.P.Premalal,Lal St,No.15,Jana Udanagama,Lunama', 'Ambalanthota', '0713142413', 'Hana DR25TS', 'B 6055', '15kg', '33-15954', '511100607V', 'Chandika'],
    ['2025-11-07', '15035', 'Sub Post Office,Nonagama Junction', 'Nonagama', '0472223286', 'Hana DR25TS', 'ADA 174', '15kg', '33-10445', null, 'Chandika'],
    ['2025-11-07', '15035', 'Sub Post Office,Nonagama Junction', 'Nonagama', '0472223286', 'Solid KP-N77-150', '6893', '150kg', '33-23035', null, 'Chandika'],
    ['2025-11-07', '15034', 'H.H.Mahesh,Sithurasa St,Wawa Rd,Nonagama', 'Ambalanthota', '0774636992', 'Budry WP15', '15/220880572', '15kg', null, '843624553V', 'Chandika'],
    ['2025-11-07', '15037', 'Matara Feeland,Ambalanthota Branch', 'Ambalanthota', '0742642620', 'Crystal JW-618', '0639', '15kg', null, null, 'Chandika'],
    ['2025-11-07', '15039', 'W.A.K.Bandula Dayawansha,Wanniarachchi St,Baragama Rd', 'Ambalanthota', null, 'Apex ACS A', '29260', '15kg', '33-13735', null, 'Chandika'],
    ['2025-11-07', '15038', 'C.H.A.C.Samantha,Chandana hotel & Grocery,Baragama Rd', 'Ambalanthota', '0713377516', 'Hana DR25HLS', 'B0192', '15kg', '33-7207', '722603271V', 'Chandika'],
];

$pdo->beginTransaction();
try {
    foreach ($data as $i => $row) {
        [$date, $billNo, $name, $loc, $tel, $model, $serial, $cap, $reg, $nid, $tech] = $row;
        
        // 1. Find or Create Customer
        // Split name -> address if comma exists to be safer? 
        // Image format is 'Name, Address'. 
        // Let's assume first part is name, rest is address.
        $parts = explode(',', $name, 2);
        $fullName = trim($parts[0]);
        $address = trim($parts[1] ?? '');

        // Match by Name and Tel to be safe, or just Name if Tel is null
        // Creating a logic to find existing customer
        $stmt = $pdo->prepare("SELECT id FROM customers WHERE full_name = ? AND (tel = ? OR id_no = ?)");
        $stmt->execute([$fullName, $tel, $nid]);
        $custId = $stmt->fetchColumn();

        if (!$custId) {
            $stmt = $pdo->prepare("INSERT INTO customers (full_name, address, location, tel, id_no) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$fullName, $address, $loc, $tel, $nid]);
            $custId = $pdo->lastInsertId();
            echo "Created Customer: $fullName\n";
        }

        // 2. Find or Create Scale
        // Match by serial number
        $stmt = $pdo->prepare("SELECT id FROM scales WHERE serial_no = ? AND model = ?");
        $stmt->execute([$serial, $model]);
        $scaleId = $stmt->fetchColumn();

        if (!$scaleId) {
            $stmt = $pdo->prepare("INSERT INTO scales (model, serial_no, capacity, reg_no) VALUES (?, ?, ?, ?)");
            $stmt->execute([$model, $serial, $cap, $reg]);
            $scaleId = $pdo->lastInsertId();
            echo "Created Scale: $model ($serial)\n";
        }

        // 3. Create License (Check if exists for this scale first?)
        // Since user deleted data and this is import, we assume fresh.
        $expiry = date('Y-m-d', strtotime($date . ' +1 year'));
        
        $stmt = $pdo->prepare("INSERT INTO licenses (customer_id, scale_id, bill_no, last_service_date, expiry_date, serviced_by) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$custId, $scaleId, $billNo, $date, $expiry, $tech]);
        echo "Created License for $fullName (Scale: $serial)\n";
    }

    $pdo->commit();
    echo "Import Successful!";

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}
