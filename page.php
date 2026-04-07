<?php
require_once __DIR__ . '/includes/helpers.php';

$dataRaw = $GLOBALS['pdfData'];

$bilance       = $dataRaw['property']['bilance'] ?? [];
$total_active  = (float)($bilance['active']['value'] ?? 0);
$total_pasive  = (float)($bilance['pasive']['value'] ?? 0);
$cisty_majetek = (float)($bilance['netto']['value'] ?? 0);

$ratio_active = ($total_active > 0) ? round(($total_active / ($total_active + abs($total_pasive))) * 100) : 0;
$ratio_pasive = 100 - $ratio_active;

$cisty_majetek_color = ($cisty_majetek >= 0) ? '#927355' : '#e74c3c';

$curMap = ['CZK' => 'Kč', 'EUR' => '€', 'USD' => '$'];
$cur    = $curMap[$bilance['active']['currency'] ?? 'CZK'] ?? 'Kč';

$property = $dataRaw['property'] ?? [];
?>

<?php include __DIR__ . '/components/page1.php'; ?>
<?php include __DIR__ . '/components/page2.php'; ?>
<?php include __DIR__ . '/components/page3.php'; ?>
