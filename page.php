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
        'title' => 'O nás',
        'items' => [
            1 => ['title' => 'Představení společnosti', 'page' => $tocPageMap[1] ?? 0],
            2 => ['title' => 'Hodnoty & očekávání', 'page' => $tocPageMap[2] ?? 0],
            3 => ['title' => 'Finanční chování klienta', 'page' => $tocPageMap[3] ?? 0],
        ],
    ];
}
if (!empty($user)) {
    $pageDefinition['user'] = [
        'title' => 'Klient',
        'items' => [
            4 => ['title' => 'Profil klienta', 'page' => $tocPageMap[4] ?? 0],
        ],
    ];
}
if (!empty($balance)) {
    $pageDefinition['balance'] = [
        'title' => 'Cashflow',
        'items' => [
            5 => ['title' => 'Výdaje', 'page' => $tocPageMap[5] ?? 0],
            6 => ['title' => 'Příjmy', 'page' => $tocPageMap[6] ?? 0],
            7 => ['title' => 'Rezerva', 'page' => $tocPageMap[7] ?? 0],
        ],
    ];
}
if (!empty($property)) {
    $pageDefinition['property'] = [
        'title' => 'Majetek',
        'items' => [
            8 => ['title' => 'Přehled majetku', 'page' => $tocPageMap[8] ?? 0],
            9 => ['title' => 'Nemovitosti & aktiva', 'page' => $tocPageMap[9] ?? 0],
            10 => ['title' => 'Detail majetku', 'page' => $tocPageMap[10] ?? 0],
            11 => ['title' => 'Bilanční přehled', 'page' => $tocPageMap[11] ?? 0],
        ],
    ];
}
if (!empty($insurance)) {
    $pageDefinition['insurance'] = [
        'title' => 'Pojištění',
        'items' => [
            12 => ['title' => 'Přehled pojištění', 'page' => $tocPageMap[12] ?? 0],
        ],
    ];
}
if (!empty($goal)) {
    $pageDefinition['goal'] = [
        'title' => 'Cíle',
        'items' => [
            13 => ['title' => 'Přehled cílů', 'page' => $tocPageMap[13] ?? 0],
            14 => ['title' => 'Kroky k cílům', 'page' => $tocPageMap[14] ?? 0],
        ],
    ];
}
if (!empty($health)) {
    $pageDefinition['health'] = [
        'title' => 'Zdraví & zajištění',
        'items' => [
            15 => ['title' => 'Přehled zdraví', 'page' => $tocPageMap[15] ?? 0],
            16 => ['title' => 'Pracovní neschopnost', 'page' => $tocPageMap[16] ?? 0],
            17 => ['title' => 'Invalidita I. stupeň', 'page' => $tocPageMap[17] ?? 0],
            18 => ['title' => 'Invalidita II. stupeň', 'page' => $tocPageMap[18] ?? 0],
            19 => ['title' => 'Invalidita III. stupeň', 'page' => $tocPageMap[19] ?? 0],
        ],
    ];
}
if (!empty($actionPlan)) {
    $pageDefinition['action_plan'] = [
        'title' => 'Akční plán',
        'items' => [
            20 => ['title' => 'Akční plán', 'page' => $tocPageMap[20] ?? 0],
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
    $GLOBALS['_marker'] = 4;
    include __DIR__ . '/components/user1.php';
}
if (!empty($balance)) {
    $GLOBALS['_marker'] = 5;
    include __DIR__ . '/components/balance1.php';
    $GLOBALS['_marker'] = 6;
    include __DIR__ . '/components/balance2.php';
    $GLOBALS['_marker'] = 7;
    include __DIR__ . '/components/balance3.php';
}
if (!empty($property)) {
    $GLOBALS['_marker'] = 8;
    include __DIR__ . '/components/property1.php';
    $GLOBALS['_marker'] = 9;
    include __DIR__ . '/components/property2.php';
    $GLOBALS['_marker'] = 10;
    include __DIR__ . '/components/property3.php';
    $GLOBALS['_marker'] = 11;
    include __DIR__ . '/components/property4.php';
}
if (!empty($insurance)) {
    $GLOBALS['_marker'] = 12;
    include __DIR__ . '/components/insurance.php';
}
if (!empty($goal)) {
    $GLOBALS['_marker'] = 13;
    include __DIR__ . '/components/goal1.php';
    $GLOBALS['_marker'] = 14;
    include __DIR__ . '/components/goal2.php';
}
if (!empty($health)) {
    $GLOBALS['_marker'] = 15;
    include __DIR__ . '/components/health1.php';
    $GLOBALS['_marker'] = 16;
    include __DIR__ . '/components/health2.php';
    $GLOBALS['_marker'] = 17;
    include __DIR__ . '/components/health3.php';
    $GLOBALS['_marker'] = 18;
    include __DIR__ . '/components/health4.php';
    $GLOBALS['_marker'] = 19;
    include __DIR__ . '/components/health5.php';
}
if (!empty($actionPlan)) {
    $GLOBALS['_marker'] = 20;
    include __DIR__ . '/components/action-plan.php';
}
if (!empty($footer)) {
    include __DIR__ . '/components/footer.php';
}
