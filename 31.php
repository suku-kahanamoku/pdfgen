<?php
require_once __DIR__ . '/includes/helpers.php';
$dataRaw = $GLOBALS['pdfData'] ?? json_decode(file_get_contents(__DIR__ . '/data.json'), true);

$client  = $dataRaw['health']['health_client'] ?? [];
$actives = $dataRaw['property']['property_active'] ?? [];
$pasives = $dataRaw['property']['property_pasive'] ?? [];

$clientRow = $client['rows'][0] ?? [];

// Spočítáme celková aktiva a pasiva ze souhrnných dat
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
<style>
    :root {
        --clr-primary: #927355;
        --clr-gray: #8c8c8c;
    }

    .page {
        width: 210mm;
        height: 297mm;
        padding: 20mm;
        box-sizing: border-box;
        background: white;
        font-family: 'Plus Jakarta Sans', sans-serif;
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
        ['label' => 'Aktiva',  'val' => $totalAktiva,  'color' => '#D6B89E'],
        ['label' => 'Pasiva',  'val' => $totalPasiva,  'color' => '#927355'],
        ['label' => 'Čistý',   'val' => max($cistyMajetek, 0), 'color' => '#2ecc71'],
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