<?php
$dataRaw = $GLOBALS['pdfData'];

$bilance       = $dataRaw['property']['bilance'] ?? [];
$total_active  = (float)($bilance['active']['value'] ?? 0);
$total_pasive  = (float)($bilance['pasive']['value'] ?? 0);

$summary       = $dataRaw['property']['summary'] ?? [];
$cisty_majetek = (float)($summary['netto']['value'] ?? 0);
$total = (float)($summary['netto']['total'] ?? 0);

$curMap = ['CZK' => 'Kč', 'EUR' => '€', 'USD' => '$'];
$cur    = $curMap[$summary['netto']['currency'] ?? $bilance['active']['currency'] ?? 'CZK'] ?? 'Kč';

// Donut: netto.value vs remainder from netto.total
$donut_pct_value     = $total > 0 ? round($cisty_majetek / $total * 100) : 0;
$donut_pct_remainder = 100 - $donut_pct_value;

$property = $dataRaw['property'] ?? [];
?>

<?php include __DIR__ . '/components/page1.php'; ?>
<?php include __DIR__ . '/components/page2.php'; ?>
<?php include __DIR__ . '/components/page3.php'; ?>

