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

ob_start();
?>
<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <title>Majetkový Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&family=Host+Grotesk:wght@400;500;600;700&display=swap">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#936746',
                        cream: '#f6f1e8',
                        linen: '#e9e4de',
                        mist: '#ddd8d3',
                        sand: '#d8bf9b',
                        taupe: '#c7b299',
                        pebble: '#b9b0a8',
                        bronze: '#b68557',
                        success: '#59bf52',
                        warning: '#ebb081',
                        danger: '#042444',
                        error: '#C35252',
                        ink: '#4A4541',
                        surface: '#4F4742',
                        answer: '#FCDEC5',
                    }
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>

    <style>
        html {
            font-size: 12px;
        }

        body {
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            font-family: 'Host Grotesk', sans-serif;
            color: #4A4541;
        }

        .font-lora {
            font-family: 'Lora', serif;
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
        ->setChromePath(getenv('CHROME_PATH') ?: '/usr/bin/google-chrome')
        ->noSandbox()
        ->addChromiumArguments([
            '--disable-gpu',
            '--disable-dev-shm-usage',
            '--disable-extensions',
            '--disable-background-networking',
            '--disable-sync',
            '--metrics-recording-only',
            '--mute-audio',
            '--no-first-run',
        ])
        ->showBackground()
        ->format('A4')
        ->margins(0, 0, 0, 0)
        ->waitUntilNetworkIdle()
        ->pdf();
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="Finalni_Majetkovy_Report.pdf"');
    header('Content-Length: ' . strlen($pdfContent));
    echo $pdfContent;
    exit;
} catch (\Exception $e) {
    header('Content-Type: text/html; charset=utf-8');
    echo "<h2>Chyba při generování</h2>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}
