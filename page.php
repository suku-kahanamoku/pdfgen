<?php
$rawData  = $GLOBALS['pdfData'];

$introduction = $rawData['introduction'] ?? [];
$intro    = $rawData['intro']    ?? [];
$user     = $rawData['user']     ?? [];
$balance  = $rawData['balance']  ?? [];
$property = $rawData['property'] ?? [];
$insurance = $rawData['insurance'] ?? [];
$goal      = $rawData['goal']      ?? [];
$health   = $rawData['health']    ?? [];

$footer     = $rawData['footer']     ?? [];
$actionPlan = $rawData['action_plan'] ?? [];

$curMap   = ['CZK' => 'Kč', 'EUR' => '€', 'USD' => '$'];

if (!empty($introduction)) {
    include __DIR__ . '/components/introduction.php';
}

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
    include __DIR__ . '/components/insurance.php';
}

if (!empty($goal)) {
    include __DIR__ . '/components/goal1.php';
    include __DIR__ . '/components/goal2.php';
}

if (!empty($health)) {
    include __DIR__ . '/components/health1.php';
    include __DIR__ . '/components/health2.php';
    include __DIR__ . '/components/health3.php';
    include __DIR__ . '/components/health4.php';
    include __DIR__ . '/components/health5.php';
}

if (!empty($actionPlan)) {
    include __DIR__ . '/components/action-plan.php';
}

if (!empty($footer)) {
    include __DIR__ . '/components/footer.php';
}
