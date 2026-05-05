<?php
$rawData = $GLOBALS['pdfData'];

$introduction = $rawData['introduction'] ?? [];
$intro        = $rawData['intro']        ?? [];
$user         = $rawData['user']         ?? [];
$balance      = $rawData['balance']      ?? [];
$property     = $rawData['property']     ?? [];
$insurance    = $rawData['insurance']    ?? [];
$goal         = $rawData['goal']         ?? [];
$health       = $rawData['health']       ?? [];
$actionPlan   = $rawData['action_plan']  ?? [];
$footer       = $rawData['footer']       ?? [];

$curMap = ['CZK' => 'Kč', 'EUR' => '€', 'USD' => '$'];

// ============================================================
// TOC – definice sekcí a položek
// ============================================================

$tocPageMap = $GLOBALS['pdfTocPageMap'] ?? [];
BUILD_PAGE_DEFINITION($rawData, $tocPageMap);

// ============================================================
// Renderování stránek
// ============================================================
if (!empty($introduction)) {
    include __DIR__ . '/components/introduction.php';
}

if (!empty($GLOBALS['page_definition'])) {
    include __DIR__ . '/components/content.php';
}

if (!empty($intro)) {
    $GLOBALS['_marker'] = 1;
    include __DIR__ . '/components/intro1.php';
    $GLOBALS['_marker'] = 2;
    include __DIR__ . '/components/intro2.php';
    $GLOBALS['_marker'] = 3;
    include __DIR__ . '/components/intro3.php';
}
if (!empty($user)) {
    include __DIR__ . '/components/user1.php';
}
if (!empty($balance)) {
    $GLOBALS['_marker'] = 4;
    include __DIR__ . '/components/balance1.php';
    $GLOBALS['_marker'] = 5;
    include __DIR__ . '/components/balance2.php';
    $GLOBALS['_marker'] = 6;
    include __DIR__ . '/components/balance3.php';
}
if (!empty($property)) {
    $GLOBALS['_marker'] = 7;
    include __DIR__ . '/components/property1.php';
    $GLOBALS['_marker'] = 8;
    include __DIR__ . '/components/property2.php';
    $GLOBALS['_marker'] = 9;
    include __DIR__ . '/components/property3.php';
    $GLOBALS['_marker'] = 10;
    include __DIR__ . '/components/property4.php';
}
if (!empty($insurance)) {
    $GLOBALS['_marker'] = 11;
    include __DIR__ . '/components/insurance.php';
}
if (!empty($goal)) {
    $GLOBALS['_marker'] = 12;
    include __DIR__ . '/components/goal1.php';
    $GLOBALS['_marker'] = 13;
    include __DIR__ . '/components/goal2.php';
}
if (!empty($health)) {
    $GLOBALS['_marker'] = 14;
    include __DIR__ . '/components/health1.php';
    include __DIR__ . '/components/health2.php';
    include __DIR__ . '/components/health3.php';
    include __DIR__ . '/components/health4.php';
    include __DIR__ . '/components/health5.php';
}
if (!empty($actionPlan)) {
    $GLOBALS['_marker'] = 15;
    include __DIR__ . '/components/action-plan.php';
}
if (!empty($footer)) {
    include __DIR__ . '/components/footer.php';
}
