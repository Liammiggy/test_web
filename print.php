<?php
$id = $_GET['id'] ?? '';
$file = 'quotations.json';
$quotations = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

$quote = null;
foreach ($quotations as $q) {
    if ($q['id'] === $id) {
        $quote = $q;
        break;
    }
}

function renderItems($items)
{
    $html = '<table border="1" cellspacing="0" cellpadding="5" width="100%"><tr><th>Item</th><th>Description</th><th>Qty</th><th>Unit Price</th><th>Amount</th></tr>';
    $total = 0;
    foreach ($items as $item) {
        $amount = $item['qty'] * $item['unit_price'];
        $total += $amount;
        $html .= "<tr>
            <td>{$item['item']}</td>
            <td>{$item['description']}</td>
            <td align='right'>{$item['qty']}</td>
            <td align='right'>₱" . number_format($item['unit_price'], 2) . "</td>
            <td align='right'>₱" . number_format($amount, 2) . "</td>
        </tr>";
    }
    $html .= "<tr><td colspan='4' align='right'><strong>Total</strong></td><td align='right'><strong>₱" . number_format($total, 2) . "</strong></td></tr></table>";
    return $html;
}

if (!$quote) {
    echo "Quotation not found.";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Print Quotation</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <h2>Company Name</h2>
    <p><?= nl2br(htmlspecialchars($quote['customer_name'])) ?><br>
       <?= nl2br(htmlspecialchars($quote['customer_address'])) ?></p>
    <h3>Project: <?= htmlspecialchars($quote['project_name']) ?></h3>
    <?= renderItems($quote['items']) ?>
    <p>Prepared By: <?= htmlspecialchars($quote['prepared_by']) ?><br>
       Date: <?= htmlspecialchars($quote['date']) ?></p>
</body>
</html>
