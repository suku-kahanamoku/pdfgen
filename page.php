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

// ============================================================
// Build TOC dynamically – order must match include order below
// ============================================================
$tocSectionsMap = [
    'intro'       => ['title' => 'O nás',               'items' => ['Představení společnosti', 'Hodnoty & očekávání', 'Finanční chování klienta']],
    'user'        => ['title' => 'Klient',              'items' => ['Profil klienta']],
    'balance'     => ['title' => 'Cashflow',            'items' => ['Příjmy', 'Výdaje', 'Rezerva']],
    'property'    => ['title' => 'Majetek',             'items' => ['Přehled majetku', 'Nemovitosti & aktiva', 'Detail majetku', 'Bilanční přehled']],
    'insurance'   => ['title' => 'Pojištění',           'items' => ['Přehled pojištění']],
    'goal'        => ['title' => 'Cíle',                'items' => ['Přehled cílů', 'Kroky k cílům']],
    'health'      => ['title' => 'Zdraví & zajištění',  'items' => ['Přehled zdraví', 'Pracovní neschopnost', 'Invalidita I. stupeň', 'Invalidita II. stupeň', 'Invalidita III. stupeň']],
    'action_plan' => ['title' => 'Akční plán',          'items' => ['Akční plán']],
];

// page 1 = introduction, page 2 = content/TOC, sections start at page 3
$tocPageCounter = (!empty($introduction) ? 1 : 0) + 1 + 1;
$tocSections = [];
foreach ($tocSectionsMap as $key => $def) {
    if (empty($rawData[$key])) continue;
    $items = [];
    foreach ($def['items'] as $itemTitle) {
        $items[] = ['title' => $itemTitle, 'page' => $tocPageCounter++];
    }
    $tocSections[] = ['title' => $def['title'], 'items' => $items];
}

if (!empty($introduction)) {
    include __DIR__ . '/components/introduction.php';
}

if (!empty($tocSections)) {
    include __DIR__ . '/components/content.php';
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
