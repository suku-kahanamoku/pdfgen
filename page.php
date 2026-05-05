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

if (!empty($intro)) {
    $pageDefinition['intro'] = [
        'title' => 'Úvod',
        'items' => [
            1 => ['title' => 'O finanční analýze a společnosti', 'page' => $tocPageMap[1] ?? 0],
            2 => ['title' => 'Očekávání a hodnoty', 'page' => $tocPageMap[2] ?? 0],
            3 => ['title' => 'Účastníci finančního plánu', 'page' => $tocPageMap[3] ?? 0],
        ],
    ];
}
if (!empty($balance)) {
    $pageDefinition['balance'] = [
        'title' => 'Rozvaha',
        'items' => [
            4 => ['title' => 'Výdaje',  'page' => $tocPageMap[4] ?? 0],
            5 => ['title' => 'Příjmy',  'page' => $tocPageMap[5] ?? 0],
            6 => ['title' => 'Bilance', 'page' => $tocPageMap[6] ?? 0],
        ],
    ];
}
if (!empty($property)) {
    $pageDefinition['property'] = [
        'title' => 'Přehled vašeho majetku',
        'items' => [
            7 => ['title' => 'Přehled',         'page' => $tocPageMap[7]  ?? 0],
            8 => ['title' => 'Portfolio',        'page' => $tocPageMap[8]  ?? 0],
            9 => ['title' => 'Statistiky',       'page' => $tocPageMap[9]  ?? 0],
            10 => ['title' => 'Bonita',           'page' => $tocPageMap[10] ?? 0],
            11 => ['title' => 'Ochrana majetku',  'page' => $tocPageMap[11] ?? 0],
        ],
    ];
}
if (!empty($goal)) {
    $pageDefinition['goal'] = [
        'title' => 'Sny a finanční cíle',
        'items' => [
            12 => ['title' => 'Vaše sny',     'page' => $tocPageMap[12] ?? 0],
            13 => ['title' => 'Plán a řešení', 'page' => $tocPageMap[13] ?? 0],
        ],
    ];
}
if (!empty($health)) {
    $pageDefinition['health'] = [
        'title' => 'Pojištění zdraví',
        'items' => [
            14 => ['title' => 'Pojištění a pojitné částky', 'page' => $tocPageMap[14] ?? 0],
        ],
    ];
}
if (!empty($actionPlan)) {
    $pageDefinition['action_plan'] = [
        'title' => 'Vyhodnocení a akční plán',
        'items' => [
            15 => ['title' => 'Vyhodnocení', 'page' => $tocPageMap[15] ?? 0],
        ],
    ];
}

// ============================================================
// Pomocník – nastaví marker pro komponentu (emituje se uvnitř)
// ============================================================
if (!function_exists('emitMarker')) {
    function emitMarker(): void
    {
        if (!empty($GLOBALS['display_marker']) && isset($GLOBALS['_marker'])) {
            $id = $GLOBALS['_marker'];
            unset($GLOBALS['_marker']);
            echo '<div style="position:absolute;font-size:8px;color:#000;line-height:1;margin:0;padding:0;">TOCMARKER_' . $id . '</div>';
        }
    }
}

// ============================================================
// Renderování stránek
// ============================================================
if (!empty($introduction)) {
    include __DIR__ . '/components/introduction.php';
}
if (!empty($pageDefinition)) {
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
