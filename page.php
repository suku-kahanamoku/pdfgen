<?php
require_once __DIR__ . '/includes/helpers.php';
include_once __DIR__ . '/components/card-styles.php';

$dataRaw   = $GLOBALS['pdfData'] ?? json_decode(file_get_contents(__DIR__ . '/data.json'), true);

$client    = $dataRaw['health']['health_client'] ?? [];
$actives   = $dataRaw['property']['property_active'] ?? [];
$pasives   = $dataRaw['property']['property_pasive'] ?? [];
$targets   = $dataRaw['target']['targets'] ?? [];
$solutions = $dataRaw['target']['solutions'] ?? [];
$propertySummary = $dataRaw['property']['property_summary'] ?? [];

$clientRow = $client['rows'][0] ?? [];

$clientHeaderMap = [];
foreach ($client['header'] ?? [] as $h) {
    $clientHeaderMap[$h['key']] = $h['label'] ?? '';
}

$activeHeaderMap = [];
foreach ($actives['header'] ?? [] as $h) {
    $activeHeaderMap[$h['key']] = $h['label'] ?? '';
}

$pasiveHeaderMap = [];
foreach ($pasives['header'] ?? [] as $h) {
    $pasiveHeaderMap[$h['key']] = $h['label'] ?? '';
}

$targetsHeaderMap = [];
foreach ($targets['header'] ?? [] as $h) {
    $targetsHeaderMap[$h['key']] = $h['label'] ?? '';
}

$solutionsHeaderMap = [];
foreach ($solutions['header'] ?? [] as $h) {
    $solutionsHeaderMap[$h['key']] = $h['label'] ?? '';
}

$total_active  = (float)($dataRaw['property']['property_summary']['total_active']['value']  ?? 0);
$total_pasive  = (float)($dataRaw['property']['property_summary']['total_pasive']['value']  ?? 0);
$total = (float)($dataRaw['property']['property_summary']['total']['value'] ?? 0);

$currencySuffix = ' Kč';
$percentSuffix  = ' %';
?>

<!-- ============================================================ -->
<!-- PAGE 1 – Přehled majetku                                     -->
<!-- ============================================================ -->
<div class="page">
    <h1 class="main-title">
        <span class="gray">Přehled</span><br>vašeho majetku
    </h1>

    <div class="section-with-chart">
        <div class="section-text">
            <p class="page-subtitle">
                <?= safe_text($client['text'] ?? '') ?>
            </p>
        </div>
        <div class="donut-container">
            <canvas id="assetChart" width="160" height="160"></canvas>
            <div style="text-align: center; margin-top: 15px; font-size: 18px; font-weight: bold; color: <?= $total >= 0 ? '#2ecc71' : '#c0392b' ?>;">
                <?= format_czk($total) ?> Kč
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('assetChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Aktiva', 'Pasiva', 'Čistý majetek'],
                        datasets: [{
                            data: [<?= $total_active ?>, <?= $total_pasive ?>, <?= max($total, 0) ?>],
                            backgroundColor: ['#D6B89E', '#927355', '#2ecc71'],
                            borderColor: ['#fff', '#fff', '#fff'],
                            borderWidth: 2,
                            spacing: 2
                        }]
                    },
                    options: {
                        responsive: false,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        });
    </script>

    <!-- KPI -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-label"><?= safe_text($client['kpi_labels']['monthly_income'] ?? ($clientHeaderMap['monthly_income'] ?? '')) ?></div>
            <div class="kpi-value"><?= format_czk((float)($clientRow['monthly_income']['value'] ?? 0)) . $currencySuffix ?></div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label"><?= safe_text($client['kpi_labels']['monthly_expenses'] ?? ($clientHeaderMap['monthly_expenses'] ?? '')) ?></div>
            <div class="kpi-value"><?= format_czk((float)($clientRow['monthly_expenses']['value'] ?? 0)) . $currencySuffix ?></div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label"><?= safe_text($client['kpi_labels']['monthly_balance'] ?? ($clientHeaderMap['monthly_buffer'] ?? '')) ?></div>
            <div class="kpi-value <?= ((float)($clientRow['monthly_buffer']['value'] ?? 0)) >= 0 ? 'pos' : 'neg' ?>">
                <?= format_czk((float)($clientRow['monthly_buffer']['value'] ?? 0)) . $currencySuffix ?>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label"><?= safe_text($client['kpi_labels']['total'] ?? '') ?></div>
            <div class="kpi-value <?= $total >= 0 ? 'pos' : 'neg' ?>"><?= format_czk($total) . $currencySuffix ?></div>
        </div>
    </div>

    <!-- Info klienta -->
    <div class="section-title"><?= safe_text($client['title'] ?? '') ?></div>
    <div class="info-grid">
        <?php
        $headerMap = [];
        foreach ($client['header'] ?? [] as $h) $headerMap[$h['key']] = $h['label'];
        foreach ($clientRow as $key => $field):
            if (!isset($headerMap[$key])) continue;
            $val = $field['value'] ?? '';
            if ($field['type'] === 'currency') $val = format_czk((float)$val) . $currencySuffix;
        ?>
            <div class="info-row">
                <span class="info-label"><?= htmlspecialchars($headerMap[$key]) ?></span>
                <span class="info-val"><?= htmlspecialchars((string)$val) ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Bilance -->
    <div class="section-title"><?= safe_text($propertySummary['title'] ?? '') ?></div>
    <p class="page-subtitle"><?= safe_text($propertySummary['text'] ?? '') ?></p>
    <div style="width: 100%; margin: 0;">
        <canvas id="bilanceChart"></canvas>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctxBilance = document.getElementById('bilanceChart');
            if (ctxBilance) {
                new Chart(ctxBilance, {
                    type: 'bar',
                    data: {
                        labels: [
                            '<?= htmlspecialchars($propertySummary['labels'][0] ?? 'Aktiva') ?>',
                            '<?= htmlspecialchars($propertySummary['labels'][1] ?? 'Pasiva') ?>',
                            '<?= htmlspecialchars($propertySummary['labels'][2] ?? 'Čistý majetek') ?>'
                        ],
                        datasets: [{
                            label: 'Hodnota',
                            data: [<?= $total_active ?>, <?= $total_pasive ?>, <?= max($total, 0) ?>],
                            backgroundColor: ['#D6B89E', '#927355', '#2ecc71'],
                            borderColor: ['#bca087', '#7a5f48', '#27ae60'],
                            borderWidth: 0,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return new Intl.NumberFormat('cs-CZ', {
                                            style: 'currency',
                                            currency: 'CZK'
                                        }).format(context.raw);
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                display: false,
                                grid: {
                                    display: false,
                                    drawBorder: false
                                },
                            },
                            y: {
                                display: true,
                                ticks: {
                                    color: '#666',
                                    font: {
                                        size: 12
                                    }
                                },
                                grid: {
                                    display: false,
                                    drawBorder: false
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
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
            'bar_left_lbl'  => $actives['progress_labels']['left'] ?? ($activeHeaderMap['invested'] ?? ''),
            'bar_right_key' => 'aum',
            'bar_right_lbl' => $actives['progress_labels']['right'] ?? ($activeHeaderMap['aum'] ?? ''),
        ],
        [
            'data'          => $pasives,
            'icon'          => 'fa-file-invoice-dollar',
            'color'         => '#e67e22',
            'bar_pct_key'   => 'paid',
            'bar_left_key'  => null,
            'bar_left_lbl'  => $pasives['progress_labels']['left'] ?? ($pasiveHeaderMap['paid'] ?? ''),
            'bar_right_key' => 'aum',
            'bar_right_lbl' => $pasives['progress_labels']['right'] ?? ($pasiveHeaderMap['aum'] ?? ''),
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
            <p class="page-subtitle"><?= safe_text($sec['data']['text'] ?? '') ?></p>

            <?php foreach ($sec['data']['rows'] ?? [] as $cardRow):
                $cardName   = $cardRow[$cardNameKey]['value'] ?? '';
                $cardLabels = $cardRow['labels'] ?? [];

                if ($sec['bar_pct_key'] !== null) {
                    $pct      = min(100, max(0, (float)($cardRow[$sec['bar_pct_key']]['value'] ?? 0)));
                    $leftVal  = $pct . $percentSuffix;
                    $rightVal = format_czk((float)($cardRow[$sec['bar_right_key']]['value'] ?? 0)) . $currencySuffix;
                } else {
                    $invested = (float)($cardRow[$sec['bar_left_key']]['value']  ?? 0);
                    $aum      = (float)($cardRow[$sec['bar_right_key']]['value'] ?? 0);
                    $pct      = $invested > 0 ? min(100, round($aum / $invested * 100)) : 0;
                    $leftVal  = format_czk($invested) . $currencySuffix;
                    $rightVal = format_czk($aum) . $currencySuffix;
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
        <?= safe_text($targets['title'] ?? '') ?>
    </div>
    <p class="page-subtitle"><?= safe_text($targets['text'] ?? '') ?></p>

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
            'left_lbl'  => $targets['progress_labels']['saved'] ?? ($targetsHeaderMap['currentAmount'] ?? ''),
            'left_val'  => format_czk($currentAmt) . $currencySuffix,
            'right_lbl' => $targets['progress_labels']['goal'] ?? ($targetsHeaderMap['targetAmount'] ?? ''),
            'right_val' => format_czk($targetAmt) . $currencySuffix,
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
        <?= safe_text($solutions['title'] ?? '') ?>
    </div>
    <p class="page-subtitle"><?= safe_text($solutions['text'] ?? '') ?></p>

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
            'left_lbl'  => $solutions['progress_labels']['start'] ?? ($solutionsHeaderMap['start'] ?? ''),
            'left_val'  => format_date($startRaw),
            'right_lbl' => $solutions['progress_labels']['target_date'] ?? ($solutionsHeaderMap['targetDate'] ?? ''),
            'right_val' => format_date($endRaw),
            'pct'       => $pct,
            'pct_label' => $solutions['progress_labels']['elapsed'] ?? '',
        ];
        include __DIR__ . '/components/sol-card.php';
    endforeach; ?>

    <div class="diverz-box">
        <div class="diverz-icon"><i class="fa-solid fa-seedling"></i></div>
        <div class="diverz-text">
            <h3><?= safe_text($solutions['tip']['title'] ?? '') ?></h3>
            <p><?= safe_text($solutions['tip']['text'] ?? '') ?></p>
        </div>
    </div>
</div>