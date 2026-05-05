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

function renderHtml(
    array $inputData,
    ?array $tocPageMap = null,
    bool $displayMarker = false
): string {
    $GLOBALS['pdfData']        = $inputData;
    $GLOBALS['pdfTocPageMap']  = $tocPageMap ?? [];
    $GLOBALS['display_marker'] = $displayMarker;
    ob_start();
    include __DIR__ . '/generate_html.php';
    return ob_get_clean();
}

function renderBrowsershot(string $html)
{
    return Browsershot::html($html)
        ->setChromePath(getenv('CHROME_PATH') ?: '/usr/bin/google-chrome')
        ->windowSize(794, 1123)
        ->noSandbox()
        ->addChromiumArguments([
            '--disable-gpu',
            '--disable-dev-shm-usage',
            '--disable-extensions',
            '--disable-sync',
            '--metrics-recording-only',
            '--mute-audio',
            '--no-first-run',
            '--force-device-scale-factor=1',
        ])
        ->showBackground()
        ->format('A4')
        ->margins(0, 0, 0, 0)
        ->waitUntilNetworkIdle();
};

// ============================================================
// Pass 1 – render s markery, detekce čísel stránek
// ============================================================
$htmlPass1 = renderHtml($inputData, null, true);
$pdfPass1  = renderBrowsershot($htmlPass1)->pdf();

$tocPageMap = [];
$tmpFile    = tempnam(sys_get_temp_dir(), 'pdfgen_');
file_put_contents($tmpFile, $pdfPass1);
try {
    $parser = new \Smalot\PdfParser\Parser();
    $pdf    = $parser->parseFile($tmpFile);
    foreach ($pdf->getPages() as $i => $page) {
        $text = $page->getText();
        for ($id = 1; $id <= 20; $id++) {
            if (!isset($tocPageMap[$id]) && strpos($text, 'TOCMARKER_' . $id) !== false) {
                $tocPageMap[$id] = $i + 1;
            }
        }
    }
} finally {
    @unlink($tmpFile);
}

// ============================================================
// Pass 2 – finální PDF se správnými čísly stránek
// ============================================================
try {
    $pdfContent = renderBrowsershot(renderHtml($inputData, $tocPageMap))->pdf();
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
