<?php
$rawData  = $GLOBALS['pdfData'];

$curMap   = ['CZK' => 'Kč', 'EUR' => '€', 'USD' => '$'];
$user     = $rawData['user']     ?? [];
$balance  = $rawData['balance']  ?? [];
$property = $rawData['property'] ?? [];

include __DIR__ . '/components/intro1.php';
include __DIR__ . '/components/intro2.php';
include __DIR__ . '/components/intro3.php';

if (!empty($user)) {
    include __DIR__ . '/components/user1.php';
}

if (!empty($balance)) {
    include __DIR__ . '/components/finance1.php';
    include __DIR__ . '/components/finance2.php';
    include __DIR__ . '/components/finance3.php';
}

if (!empty($property)) {
    include __DIR__ . '/components/property1.php';
    include __DIR__ . '/components/property2.php';
    include __DIR__ . '/components/property3.php';
}
