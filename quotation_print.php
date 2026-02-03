<?php
// No declare(strict_types) to avoid some casting headers
// This page renders the pure HTML for printing

$date = $_POST['date'] ?? date('Y.m.d');
$recipient = $_POST['recipient'] ?? '';
// Ensure items is an array
$items = $_POST['items'] ?? [];
if (!is_array($items)) {
    $items = [];
}

// Helper to nl2br
function format_address($text) {
    return nl2br(htmlspecialchars((string)$text));
}
?>
<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="utf-8">
    <title>Quotation</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Sinhala:wght@400;700&display=swap');

        body {
            font-family: "Noto Sans Sinhala", "Iskoola Pota", "Nirmala UI", sans-serif;
            margin: 0;
            padding: 40px;
            font-size: 14px;
            line-height: 1.6;
        }
        @media print {
            body { padding: 0mm; margin: 20mm; }
            .no-print { display: none; }
            @page { size: A4; margin: 20mm; }
        }
        
        .header {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }
        .recipient-block {
            margin-bottom: 30px;
            font-weight: bold;
        }
        .title {
            text-decoration: underline;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .intro {
            margin-bottom: 20px;
            text-align: justify;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        table th, table td {
            border: 1px solid #000;
            padding: 8px 12px;
            text-align: center;
        }
        table th {
            background-color: #f0f0f0;
        }
        
        .features {
            margin-top: 20px;
        }
        .features-title {
            font-weight: bold;
            text-decoration: underline;
            display: inline-block;
            margin-bottom: 10px;
        }
        .features-list {
            list-style: none;
            padding-left: 20px;
            margin: 0;
        }
        .features-list li {
            margin-bottom: 5px;
        }
        
        .footer {
            margin-top: 60px;
        }
        .signature-line {
            margin-top: 60px;
        }
        .validity {
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- Header Date -->
    <div class="header">
        <div><?= htmlspecialchars((string)$date) ?></div>
    </div>

    <!-- Recipient -->
    <div class="recipient-block">
        <?= format_address($recipient) ?>
    </div>

    <!-- Title -->
    <div class="title">
        ඉලෙක්ට්‍රොනික් තරාදි මිල ගණන් සම්බන්ධයෙන්.
    </div>

    <!-- Intro -->
    <div class="intro">
        පහත සදහන් මිල ගණන් යටතේ අප ආයතනයෙන් ඉලෙක්ට්‍රොනික් තරාදි මිල දී ගත හැකිය. ඒවාට අදාළ සේවාවන් සහ විශේෂාංග පහතින් සදහන්කර ඇත.
    </div>

    <!-- Table -->
    <table>
        <thead>
            <tr>
                <th>උපකරණයේ වර්ගය</th>
                <th>මාදිලිය</th>
                <th>උපරිම බර</th>
                <th>පරාසය</th>
                <th>මිල (රු)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($items) > 0): ?>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars((string)($item['brand'] ?? '')) ?></td>
                    <td><?= htmlspecialchars((string)($item['model'] ?? '')) ?></td>
                    <td><?= htmlspecialchars((string)($item['capacity'] ?? '')) ?></td>
                    <td><?= htmlspecialchars((string)($item['division'] ?? '')) ?></td>
                    <td style="text-align: right;"><?= htmlspecialchars((string)($item['price'] ?? '')) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No items selected</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Features -->
    <div class="features">
        <div class="features-title">විශේෂාංග:-</div>
        <ul class="features-list">
            <li>කිරුම් මිනුම් හා සේවා දෙපාර්තමේන්තුවේ අනුමත සහතිකය.</li>
            <li>වසර දෙකක වගකීම් සහතිකය.</li>
            <li>කොරියානු තාක්ෂණයෙන් මනා නිෂ්පාදනයකි.</li>
            <li>සියළු සේවා හා අලුත්වැඩියාවන් අදාළ ස්ථානයටම ගොස් සිදුකරදීම.</li>
            <li>කිරුම් මිනුම් පරීකෂක මහතෙකු ලවා සෑම වසරකම මුද්‍රා කටයුතු අදාළ ස්ථානයටම ගොස් සිදුකරදීම.</li>
            <li>කඩිනම් සුහදශීලී සේවාව.</li>
        </ul>
    </div>

    <!-- Footer Signature -->
    <div class="footer">
        <div>කළමණාකරු,</div>
        <div class="signature-line">...........................................</div>
        <div>(W.T.U.C.ද සිල්වා)</div>
        
        <div class="validity" style="margin-top: 10px; margin-left: 20px;">
            (Valid for 30 days)
        </div>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>
