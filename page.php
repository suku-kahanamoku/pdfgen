<?php
$rawData  = $GLOBALS['pdfData'];

$intro    = $rawData['intro']    ?? [];
$user     = $rawData['user']     ?? [];
$balance  = $rawData['balance']  ?? [];
$property = $rawData['property'] ?? [];
$insurance = $rawData['insurance'] ?? [];
$goal      = $rawData['goal']      ?? [];

$curMap   = ['CZK' => 'Kč', 'EUR' => '€', 'USD' => '$'];

if (!empty($intro)) {
    include __DIR__ . '/components/intro1.php';
    include __DIR__ . '/components/intro2.php';
    include __DIR__ . '/components/intro3.php';
}

if (!empty($user)) {
    include __DIR__ . '/components/user1.php';
}

if (!empty($balance)) {
    include __DIR__ . '/components/balance1.php';
    include __DIR__ . '/components/balance2.php';
    include __DIR__ . '/components/balance3.php';
}

if (!empty($property)) {
    include __DIR__ . '/components/property1.php';
    include __DIR__ . '/components/property2.php';
    include __DIR__ . '/components/property3.php';
    include __DIR__ . '/components/property4.php';
}

if (!empty($insurance)) {
    include __DIR__ . '/components/insurance1.php';
}

if (!empty($goal)) {
    include __DIR__ . '/components/insurance2.php';
}

echo json_encode($rawData, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP);