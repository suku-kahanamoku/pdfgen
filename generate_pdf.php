<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'vendor/autoload.php';

use Spatie\Browsershot\Browsershot;

// 1) POST or GET field named 'data'
$inputData = null;
if (!empty($_REQUEST['data'])) {
    $decoded = json_decode($_REQUEST['data'], true);
    if (json_last_error() === JSON_ERROR_NONE) {
        $inputData = $decoded;
    }
}

// 2) Raw JSON body (Content-Type: application/json)
if ($inputData === null) {
    $rawInput = file_get_contents('php://input');
    if (!empty($rawInput)) {
        $decoded = json_decode($rawInput, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            // Unwrap {"data": "json_string"} or {"data": {...}}
            if (isset($decoded['data']) && !isset($decoded['property'])) {
                $inner = is_string($decoded['data']) ? json_decode($decoded['data'], true) : $decoded['data'];
                if (json_last_error() === JSON_ERROR_NONE && is_array($inner)) {
                    $inputData = $inner;
                }
            } else {
                $inputData = $decoded;
            }
        }
    }
}

// 3) Fallback: read from data.json file
if ($inputData === null) {
    $jsonFile = __DIR__ . '/data.json';
    if (!file_exists($jsonFile)) {
        die("Chyba: Soubor data.json nebyl nalezen ve složce " . __DIR__);
    }
    $inputData = json_decode(file_get_contents($jsonFile), true);
}

$GLOBALS['pdfData'] = $inputData;
$aktiva = $inputData['property']['property_summary']['total_active']['value'] ?? 0;
$pasiva = $inputData['property']['property_summary']['total_pasive']['value'] ?? 0;
$cisty_majetek = $inputData['property']['property_summary']['total']['value'] ?? 0;
$GLOBALS['protection'] = $inputData['health']['protection'] ?? [];
$GLOBALS['dreams'] = $inputData['health']['dreams'] ?? [];
$GLOBALS['investment_plan'] = $inputData['health']['investment_plan'] ?? [];
$chart_url = "https://chart.googleapis.com/chart?cht=pd&chs=500x500&chd=t:$aktiva,$pasiva&chco=b38b5d,e74c3c&chp=0.1";
$GLOBALS['chartUrl'] = $chart_url;
$GLOBALS['cistyMajetek'] = $cisty_majetek;

ob_start();
?>
<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <title>Majetkový Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    </style>
</head>

<body>
    <?php include 'page.php'; ?>
</body>

</html>
<?php
$html = ob_get_clean();
try {
    $pdfContent = Browsershot::html($html)
        ->setChromePath('/usr/bin/google-chrome')
        ->showBackground()
        ->format('A4')
        ->margins(20, 20, 20, 20)
        ->waitUntilNetworkIdle()
        ->pdf();
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="Finalni_Majetkovy_Report.pdf"');
    header('Content-Length: ' . strlen($pdfContent));
    echo $pdfContent;
    exit;
} catch (\Exception $e) {
    header('Content-Type: text/html; charset=utf-8');
    echo "<h1>Chyba při generování</h1>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}
