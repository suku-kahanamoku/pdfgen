<?php
$rawData  = $GLOBALS['pdfData'];

$curMap   = ['CZK' => 'Kč', 'EUR' => '€', 'USD' => '$'];
$user     = $rawData['user']     ?? [];
$balance  = $rawData['balance']  ?? [];
$property = $rawData['property'] ?? [];

include __DIR__ . '/components/finanalys1.php';
include __DIR__ . '/components/finanalys2.php';
include __DIR__ . '/components/finanalys3.php';

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
