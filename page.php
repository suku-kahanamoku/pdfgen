<?php
require_once __DIR__ . '/includes/helpers.php';

$dataRaw = $GLOBALS['pdfData'];

$bilance       = $dataRaw['property']['bilance'] ?? [];
$total_active  = (float)($bilance['active']['value'] ?? 0);
$total_pasive  = (float)($bilance['pasive']['value'] ?? 0);

$summary       = $dataRaw['summary'] ?? [];
$cisty_majetek = (float)($summary['netto']['value'] ?? 0);

$curMap = ['CZK' => 'Kč', 'EUR' => '€', 'USD' => '$'];
$cur    = $curMap[$summary['netto']['currency'] ?? $bilance['active']['currency'] ?? 'CZK'] ?? 'Kč';

// Donut segments from summary (positive values only)
$donut_active     = max(0, (float)($summary['active']['value']     ?? 0));
$donut_estate     = max(0, (float)($summary['estate']['value']     ?? 0));
$donut_properties = max(0, (float)($summary['properties']['value'] ?? 0));
$donut_total      = $donut_active + $donut_estate + $donut_properties;
$donut_pct_active     = $donut_total > 0 ? round($donut_active     / $donut_total * 100) : 0;
$donut_pct_estate     = $donut_total > 0 ? round($donut_estate     / $donut_total * 100) : 0;
$donut_pct_properties = $donut_total > 0 ? (100 - $donut_pct_active - $donut_pct_estate) : 0;

$property = $dataRaw['property'] ?? [];
?>

<?php include __DIR__ . '/components/page1.php'; ?>
<?php include __DIR__ . '/components/page2.php'; ?>
<?php include __DIR__ . '/components/page3.php'; ?>
