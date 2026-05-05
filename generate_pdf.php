<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'vendor/autoload.php';
require_once __DIR__ . '/helper.php';

use Spatie\Browsershot\Browsershot;

$inputData = RESOLVE_INPUT_DATA();

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
function extractMarkerPageNumbers(array $inputData): array
{
    $pdfBinary  = renderBrowsershot(renderHtml($inputData, null, true))->pdf();
    $tocPageMap = [];
    $tmpFile    = tempnam(sys_get_temp_dir(), 'pdfgen_');
    file_put_contents($tmpFile, $pdfBinary);
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
    return $tocPageMap;
}
function renderFinalPdf(array $inputData, array $tocPageMap): string
{
    return renderBrowsershot(renderHtml($inputData, $tocPageMap))->pdf();
}

try {

    // ============================================================
    // Pass 1 – render s markery, vrátí tocPageMap
    // ============================================================
    $tocPageMap = extractMarkerPageNumbers($inputData);

    // ============================================================
    // Pass 2 – finální PDF se správnými čísly stránek
    // ============================================================
    $pdfContent = renderFinalPdf($inputData, $tocPageMap);

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
