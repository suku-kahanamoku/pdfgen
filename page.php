<?php
require_once __DIR__ . '/includes/helpers.php';
include_once __DIR__ . '/components/card-styles.php';

$dataRaw   = $GLOBALS['pdfData'] ?? json_decode(file_get_contents(__DIR__ . '/data.json'), true);

$client    = $dataRaw['health']['health_client'] ?? [];
$actives   = $dataRaw['property']['property_active'] ?? [];
$pasives   = $dataRaw['property']['property_pasive'] ?? [];
$targets   = $dataRaw['target']['targets'] ?? [];
$solutions = $dataRaw['target']['solutions'] ?? [];

$clientRow = $client['rows'][0] ?? [];

$total_active  = (float)($dataRaw['property']['property_summary']['total_active']['value']  ?? 0);
$total_pasive  = (float)($dataRaw['property']['property_summary']['total_pasive']['value']  ?? 0);
$total = (float)($dataRaw['property']['property_summary']['total']['value'] ?? 0);
?>

<!-- ============================================================ -->
<!-- PAGE 1 – Přehled majetku                                     -->
<!-- ============================================================ -->
<div class="page">
    <h1 class="main-title">
        <span class="gray">Přehled</span><br>vašeho majetku
    </h1>
    <p class="page-subtitle">
        Diverzifikace příjmů, například prostřednictvím vedlejších příjmů
        nebo investic, může zvýšit naši finanční bezpečnost. Když
        přemýšlíme o budoucnosti a strategicky investujeme, zajišťujeme
        si lepší životní úroveň a klidnou mysl. Důležité je také udržovat
        si přehled o svých příjmech a pravidelně přehodnocovat své
        finanční cíle. Tím můžeme efektivně plánovat a přizpůsobovat
        se měnícím se podmínkám.
    </p>

    <!-- KPI -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-label">Měsíční příjem</div>
            <div class="kpi-value"><?= format_czk((float)($clientRow['monthly_income']['value'] ?? 0)) ?> Kč</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Měsíční výdaje</div>
            <div class="kpi-value"><?= format_czk((float)($clientRow['monthly_expenses']['value'] ?? 0)) ?> Kč</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Měsíční zůstatek</div>
            <div class="kpi-value <?= ((float)($clientRow['monthly_buffer']['value'] ?? 0)) >= 0 ? 'pos' : 'neg' ?>">
                <?= format_czk((float)($clientRow['monthly_buffer']['value'] ?? 0)) ?> Kč
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Čistý majetek</div>
            <div class="kpi-value <?= $total >= 0 ? 'pos' : 'neg' ?>"><?= format_czk($total) ?> Kč</div>
        </div>
    </div>

    <!-- Info klienta -->
    <div class="section-title">Profil klienta</div>
    <div class="info-grid">
        <?php
        $headerMap = [];
        foreach ($client['header'] ?? [] as $h) $headerMap[$h['key']] = $h['label'];
        foreach ($clientRow as $key => $field):
            if (!isset($headerMap[$key])) continue;
            $val = $field['value'] ?? '';
            if ($field['type'] === 'currency') $val = format_czk((float)$val) . ' Kč';
        ?>
            <div class="info-row">
                <span class="info-label"><?= htmlspecialchars($headerMap[$key]) ?></span>
                <span class="info-val"><?= htmlspecialchars((string)$val) ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Bilance -->
    <div class="section-title">Bilance majetku</div>
    <?php
    $maxB = max($total_active, $total_pasive, 1);
    $bars = [
        ['label' => 'Aktiva',  'val' => $total_active,              'color' => '#D6B89E'],
        ['label' => 'Pasiva',  'val' => $total_pasive,              'color' => '#927355'],
        ['label' => 'Čistý',   'val' => max($total, 0),     'color' => '#2ecc71'],
    ];
    ?>
    <div class="bilance-bar-wrap">
        <?php foreach ($bars as $b): ?>
            <div class="bilance-bar-row">
                <span class="bilance-bar-label"><?= $b['label'] ?></span>
                <div class="bilance-bar-outer">
                    <div class="bilance-bar-inner" style="width:<?= round($b['val'] / $maxB * 100) ?>%; background:<?= $b['color'] ?>;"></div>
                </div>
                <span class="bilance-bar-amount"><?= format_czk($b['val']) ?> Kč</span>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ============================================================ -->
<!-- PAGE 2 – Aktiva & Pasiva                                     -->
<!-- ============================================================ -->
<div class="page">
    <?php
    $sections = [
        [
            'data'          => $actives,
            'icon'          => 'fa-chart-line',
            'color'         => '#2ecc71',
            'bar_pct_key'   => null,
            'bar_left_key'  => 'invested',
            'bar_left_lbl'  => 'Investováno',
            'bar_right_key' => 'aum',
            'bar_right_lbl' => 'Aktuální hodnota',
        ],
        [
            'data'          => $pasives,
            'icon'          => 'fa-file-invoice-dollar',
            'color'         => '#e67e22',
            'bar_pct_key'   => 'paid',
            'bar_left_key'  => null,
            'bar_left_lbl'  => 'Splaceno',
            'bar_right_key' => 'aum',
            'bar_right_lbl' => 'Zbývá umořit',
        ],
    ];
    foreach ($sections as $sec):
        $cardHeader  = $sec['data']['header'] ?? [];
        $cardNameKey = $cardHeader[0]['key'] ?? 'name';
    ?>
        <div class="section-block">
            <div class="section-heading">
                <span class="section-icon" style="background:<?= $sec['color'] ?>22; color:<?= $sec['color'] ?>;">
                    <i class="fa-solid <?= $sec['icon'] ?>"></i>
                </span>
                <?= safe_text($sec['data']['title'] ?? '') ?>
            </div>

            <?php foreach ($sec['data']['rows'] ?? [] as $cardRow):
                $cardName   = $cardRow[$cardNameKey]['value'] ?? '';
                $cardLabels = $cardRow['labels'] ?? [];

                if ($sec['bar_pct_key'] !== null) {
                    $pct      = min(100, max(0, (float)($cardRow[$sec['bar_pct_key']]['value'] ?? 0)));
                    $leftVal  = $pct . ' %';
                    $rightVal = format_czk((float)($cardRow[$sec['bar_right_key']]['value'] ?? 0)) . ' Kč';
                } else {
                    $invested = (float)($cardRow[$sec['bar_left_key']]['value']  ?? 0);
                    $aum      = (float)($cardRow[$sec['bar_right_key']]['value'] ?? 0);
                    $pct      = $invested > 0 ? min(100, round($aum / $invested * 100)) : 0;
                    $leftVal  = format_czk($invested) . ' Kč';
                    $rightVal = format_czk($aum) . ' Kč';
                }

                $cardProgress = [
                    'left_lbl'  => $sec['bar_left_lbl'],
                    'left_val'  => $leftVal,
                    'right_lbl' => $sec['bar_right_lbl'],
                    'right_val' => $rightVal,
                    'pct'       => $pct,
                    'pct_label' => '',
                ];
                include __DIR__ . '/components/sol-card.php';
            endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>

<!-- ============================================================ -->
<!-- PAGE 3 – Finanční cíle                                       -->
<!-- ============================================================ -->
<div class="page">
    <div class="section-heading">
        <span class="section-icon section-icon--primary">
            <i class="fa-solid fa-bullseye"></i>
        </span>
        <?= safe_text($targets['title'] ?? 'Finanční cíle a přání') ?>
    </div>

    <?php
    $cardHeader  = $targets['header'] ?? [];
    $cardNameKey = 'name';
    foreach ($targets['rows'] ?? [] as $cardRow):
        $targetAmt  = (float)($cardRow['targetAmount']['value']  ?? 0);
        $currentAmt = (float)($cardRow['currentAmount']['value'] ?? 0);
        $gap        = $targetAmt - $currentAmt;
        $pct        = $targetAmt > 0 ? min(100, round($currentAmt / $targetAmt * 100)) : 0;

        $cardRow['gap'] = ['value' => $gap, 'type' => 'currency'];

        $cardName     = $cardRow['name']['value'] ?? '';
        $cardLabels   = $cardRow['labels'] ?? [];
        $cardProgress = [
            'left_lbl'  => 'Naspořeno',
            'left_val'  => format_czk($currentAmt) . ' Kč',
            'right_lbl' => 'Cíl',
            'right_val' => format_czk($targetAmt) . ' Kč',
            'pct'       => $pct,
            'pct_label' => '',
        ];
        include __DIR__ . '/components/sol-card.php';
    endforeach; ?>
</div>

<!-- ============================================================ -->
<!-- PAGE 4 – Finanční plán                                       -->
<!-- ============================================================ -->
<div class="page">
    <div class="section-heading">
        <span class="section-icon section-icon--primary">
            <i class="fa-solid fa-seedling"></i>
        </span>
        <?= safe_text($solutions['title'] ?? 'Finanční plán') ?>
    </div>

    <?php
    $cardHeader  = $solutions['header'] ?? [];
    $cardNameKey = 'name';
    foreach ($solutions['rows'] ?? [] as $cardRow):
        $cardName   = $cardRow[$cardNameKey]['value'] ?? '';
        $cardLabels = $cardRow['labels'] ?? [];

        $startRaw = $cardRow['start']['value']      ?? '';
        $endRaw   = $cardRow['targetDate']['value']  ?? '';
        $pct      = 0;
        if ($startRaw && $endRaw) {
            try {
                $dtStart = new DateTime($startRaw);
                $dtEnd   = new DateTime($endRaw);
                $today   = new DateTime();
                $total   = $dtEnd->getTimestamp() - $dtStart->getTimestamp();
                $elapsed = $today->getTimestamp() - $dtStart->getTimestamp();
                $pct     = $total > 0 ? min(100, max(0, round($elapsed / $total * 100))) : 0;
            } catch (Exception $e) {
            }
        }

        $cardProgress = [
            'left_lbl'  => 'Začátek',
            'left_val'  => format_date($startRaw),
            'right_lbl' => 'Cílové datum',
            'right_val' => format_date($endRaw),
            'pct'       => $pct,
            'pct_label' => 'doby uplynulo',
        ];
        include __DIR__ . '/components/sol-card.php';
    endforeach; ?>

    <div class="diverz-box">
        <div class="diverz-icon"><i class="fa-solid fa-seedling"></i></div>
        <div class="diverz-text">
            <h3>Pravidelné investování buduje bohatství</h3>
            <p>Díky složenému úročení a pravidelným vkladům roste hodnota vašeho portfolia exponenciálně. Klíčem je konzistentnost a dlouhý investiční horizont.</p>
        </div>
    </div>
</div>