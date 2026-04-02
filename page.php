<?php
require_once __DIR__ . '/includes/helpers.php';

if (!function_exists('safe_text')) {
    function safe_text($text)
    {
        if (empty($text) || is_array($text)) return '';
        return htmlspecialchars(preg_replace('/\[cite.*?\]/', '', (string)$text));
    }
}

$dataRaw   = $GLOBALS['pdfData'] ?? json_decode(file_get_contents(__DIR__ . '/data.json'), true);

$client    = $dataRaw['health']['health_client'] ?? [];
$actives   = $dataRaw['property']['property_active'] ?? [];
$pasives   = $dataRaw['property']['property_pasive'] ?? [];
$targets   = $dataRaw['target']['targets'] ?? [];
$solutions = $dataRaw['target']['solutions'] ?? [];

$clientRow = $client['rows'][0] ?? [];

$totalAktiva = 0;
foreach ($actives['rows'] ?? [] as $r) {
    $v = $r['aum']['value'] ?? $r['invested']['value'] ?? 0;
    if (is_numeric($v)) $totalAktiva += (float)$v;
}
$totalPasiva = 0;
foreach ($pasives['rows'] ?? [] as $r) {
    $v = $r['aum']['value'] ?? 0;
    if (is_numeric($v)) $totalPasiva += (float)$v;
}
$cistyMajetek = $totalAktiva - $totalPasiva;
?>
<?php include __DIR__ . '/components/card-styles.php'; ?>
<style>
    :root {
        --clr-primary: #927355;
        --clr-gray: #8c8c8c;
    }

    .page {
        width: 100%;
        min-height: 257mm;
        padding: 0;
        box-sizing: border-box;
        background: white;
        font-family: 'Plus Jakarta Sans', sans-serif;
        page-break-after: always;
        break-after: page;
        overflow: visible;
    }

    .main-title {
        font-family: 'Lora', serif;
        font-size: 42px;
        line-height: 1.1;
        margin: 0 0 8px 0;
    }

    .gray {
        color: var(--clr-gray);
    }

    .section-title {
        font-family: 'Lora', serif;
        font-size: 20px;
        color: var(--clr-primary);
        margin: 30px 0 15px 0;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 8px;
    }

    .kpi-grid {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
    }

    .kpi-card {
        flex: 1;
        background: #fcfaf8;
        border: 1px solid #f0ebe5;
        border-radius: 14px;
        padding: 18px;
    }

    .kpi-label {
        font-size: 11px;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 6px;
    }

    .kpi-value {
        font-family: 'Lora', serif;
        font-size: 22px;
        color: var(--clr-primary);
        font-weight: bold;
    }

    .kpi-value.neg {
        color: #c0392b;
    }

    .kpi-value.pos {
        color: #27ae60;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
        border: 1px solid #f0f0f0;
        border-radius: 10px;
        padding: 12px 16px;
    }

    .info-label {
        font-size: 12px;
        color: #888;
    }

    .info-val {
        font-size: 14px;
        font-weight: bold;
        color: #333;
    }

    .bilance-bar-wrap {
        margin-top: 30px;
    }

    .bilance-bar-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
    }

    .bilance-bar-label {
        width: 80px;
        font-size: 12px;
        color: #666;
    }

    .bilance-bar-outer {
        flex: 1;
        background: #f3f3f3;
        height: 16px;
        border-radius: 8px;
        overflow: hidden;
    }

    .bilance-bar-inner {
        height: 100%;
        border-radius: 8px;
    }

    .bilance-bar-amount {
        width: 110px;
        text-align: right;
        font-size: 12px;
        font-weight: bold;
        color: #444;
    }
</style>

<!-- ============================================================ -->
<!-- PAGE 1 – Přehled majetku                                     -->
<!-- ============================================================ -->
<div class="page">
    <h1 class="main-title">
        <span class="gray">Přehled</span><br>vašeho majetku
    </h1>
    <p style="color:#888; font-size:13px; margin: 0 0 25px 0;">Aktuální přehled finanční situace klienta</p>

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
            <div class="kpi-value <?= $cistyMajetek >= 0 ? 'pos' : 'neg' ?>"><?= format_czk($cistyMajetek) ?> Kč</div>
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
    $maxB = max($totalAktiva, $totalPasiva, 1);
    $bars = [
        ['label' => 'Aktiva',  'val' => $totalAktiva,              'color' => '#D6B89E'],
        ['label' => 'Pasiva',  'val' => $totalPasiva,              'color' => '#927355'],
        ['label' => 'Čistý',   'val' => max($cistyMajetek, 0),     'color' => '#2ecc71'],
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
                <span style="background:<?= $sec['color'] ?>22; color:<?= $sec['color'] ?>; width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center;">
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
        <span style="background:#927355; color:white; width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center;">
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
        <span style="background:#927355; color:white; width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center;">
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