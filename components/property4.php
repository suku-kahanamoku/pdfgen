<?php
// ============================================================
// PAGE – BONITA / DTI / DSTI / LTV
// ============================================================
$mortgage = $property['mortgage'] ?? [];
$cur      = $curMap[$mortgage['currency'] ?? 'CZK'] ?? 'Kč';

// Top summary
$mortgageSummary = $mortgage['summary'] ?? [];
$summaryValue    = (float)($mortgageSummary['value'] ?? 0);
$summaryStatus   = $mortgageSummary['status'] ?? '';
$summaryOk       = $summaryStatus === 'success';
$summaryTitle    = $summaryOk
    ? 'Dostupná výše finančních prostředků pro financování vašich nemovitostí.'
    : 'Vaše bonita nesplňuje požadavky pro financování nemovitostí.';
$summaryBadge    = $summaryOk ? 'BONITA' : 'NEDOSTATEČNÁ BONITA';

// DTI
$dti      = $mortgage['dti'] ?? [];
$dtiRows  = $dti['rows'] ?? [];
$dtiValue = (float)($dti['value'] ?? 0);
$dtiTitle = $dti['title'] ?? '';

// DSTI
$dsti      = $mortgage['dsti'] ?? [];
$dstiRows  = $dsti['rows'] ?? [];
$dstiValue = (float)($dsti['value'] ?? 0);
$dstiTitle = $dsti['title'] ?? '';

// LTV
$ltv                  = $mortgage['ltv'] ?? [];
$ltvTitle             = $ltv['title'] ?? '';
$ltvRows              = $ltv['rows'] ?? [];
$ltvOwnResourcesTitle = $ltv['own_resources']['title'] ?? '';
$ltvOwnResourcesValue = (float)($ltv['own_resources']['value'] ?? 0);
$ltvMaxLoanTitle      = $ltv['max_loan']['title'] ?? '';
$ltvMaxLoanValue      = (float)($ltv['max_loan']['value'] ?? 0);
$ltvChartLabel1       = htmlspecialchars($ltv['chart']['label1'] ?? '');
$ltvChartLabel2       = htmlspecialchars($ltv['chart']['label2'] ?? '');
$ltvChartValue1       = (float)($ltv['chart']['value1'] ?? 0);
$ltvChartValue2       = (float)($ltv['chart']['value2'] ?? 0);

// Footer
$mortgageFooter = $mortgage['footer'] ?? [];
$footerPercent  = (float)($mortgageFooter['percent'] ?? 0);
$footerStatus   = $mortgageFooter['status'] ?? $summaryStatus;

// Progress ring colors
$ringBaseColor = '#D9D9D9';
$ringMainColor = '#A97B53';

// status -> footer / badge styles
$footerOk = $footerStatus === 'success';
$badgeCls = $footerOk ? 'bg-success text-white' : 'bg-error text-white';

// helper
$formatPct = function ($value) {
    $out = number_format((float)$value, 1, ',', ' ');
    return str_replace(',0', '', $out);
};
?>

<!-- ============================================================ -->
<!-- PAGE – Bonita / DTI / DSTI / LTV                             -->
<!-- ============================================================ -->
<div class="w-full box-border p-24 overflow-visible flex flex-col gap-12 [page-break-after:always] [break-after:page] [box-decoration-break:clone]">

    <!-- Top summary row -->
    <div class="flex gap-8 items-center">
        <div class="flex-1 <?= $footerOk ? 'bg-green-50 border border-success' : 'bg-red-50 border border-error' ?> -ml-24 pl-24 max-w-3xl rounded-r-xl px-5 py-4 flex flex-col gap-2">
            <div class="flex items-center justify-between gap-4">
                <div class="font-lora text-3xl font-semibold text-ink">
                    <?= number_format($summaryValue, 0, ',', ' ') ?> <?= $cur ?>
                </div>
                <div class="rounded-xl px-4 py-2 font-semibold flex-shrink-0 <?= $badgeCls ?>">
                    <?= htmlspecialchars($summaryBadge) ?>
                </div>
            </div>
            <div class="max-w-xl text-ink/70">
                <?= htmlspecialchars($summaryTitle) ?>
            </div>
        </div>

        <div class="w-56 flex-shrink-0 flex items-center justify-end text-primary/90">
            <?php include __DIR__ . '/trophy.php'; ?>
        </div>
    </div>

    <!-- DTI -->
    <div class="[page-break-inside:avoid] [break-inside:avoid] flex flex-col gap-8">
        <h3 class="font-lora text-4xl font-semibold leading-none text-ink">
            <?= htmlspecialchars($dtiTitle) ?>
        </h3>

        <div class="flex gap-8 items-center">
            <div class="flex-1 flex flex-col gap-3">
                <?php foreach ($dtiRows as $i => $row):
                    $title = $row['title'] ?? '';
                    $value = (float)($row['value'] ?? 0);
                    $isPrimary = !empty($row['primary']) || $i === 0;
                    $rowCls = $isPrimary ? 'border-primary/40' : 'border-ink/15';
                ?>
                    <div class="flex justify-between items-center px-4 py-2 rounded-lg border text-lg <?= $rowCls ?>">
                        <span class="font-lora text-ink/85"><?= htmlspecialchars($title) ?></span>
                        <span class="text-ink/85"><?= number_format($value, 0, ',', ' ') ?> <?= $cur ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="w-52 h-52 relative flex-shrink-0">
                <canvas id="chart-credit-dti"></canvas>
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <span class="font-lora text-4xl text-pebble"><?= $formatPct($dtiValue) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- DSTI -->
    <div class="[page-break-inside:avoid] [break-inside:avoid] flex flex-col gap-8">
        <h3 class="font-lora text-4xl font-semibold leading-none text-ink">
            <?= htmlspecialchars($dstiTitle) ?>
        </h3>

        <div class="flex gap-8 items-center">
            <div class="flex-1 flex flex-col gap-3">
                <?php foreach ($dstiRows as $i => $row):
                    $title = $row['title'] ?? '';
                    $value = (float)($row['value'] ?? 0);
                    $isPrimary = !empty($row['primary']) || $i === 0;
                    $rowCls = $isPrimary ? 'border-primary/40' : 'border-ink/15';
                ?>
                    <div class="flex justify-between items-center px-4 py-2 rounded-lg border text-lg <?= $rowCls ?>">
                        <span class="font-lora text-ink/85"><?= htmlspecialchars($title) ?></span>
                        <span class="text-ink/85"><?= number_format($value, 0, ',', ' ') ?> <?= $cur ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="w-52 h-52 relative flex-shrink-0">
                <canvas id="chart-credit-dsti"></canvas>
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <span class="font-lora text-4xl text-pebble"><?= $formatPct($dstiValue) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- LTV -->
    <div class="[page-break-inside:avoid] [break-inside:avoid] flex gap-8 items-stretch w-full">
        <div class="w-80 flex flex-col gap-8 justify-start">
            <h3 class="font-lora text-3xl font-semibold leading-none text-ink">
                <?= htmlspecialchars($ltvTitle) ?>
            </h3>

            <div class="flex flex-col gap-3">
                <?php foreach ($ltvRows as $row): ?>
                    <div class="flex items-start justify-between gap-4 border-b border-mist pb-3 text-ink/75">
                        <div><?= htmlspecialchars($row['title'] ?? '') ?></div>
                        <div class="whitespace-nowrap"><?= number_format((float)($row['value'] ?? 0), 0, ',', ' ') ?> <?= $cur ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="mt-2 flex items-center justify-between rounded-lg border border-primary/40 px-4 py-3 font-lora font-semibold text-ink">
                <span><?= htmlspecialchars($ltvOwnResourcesTitle) ?></span>
                <span><?= number_format($ltvOwnResourcesValue, 0, ',', ' ') ?> <?= $cur ?></span>
            </div>

            <div class="flex items-center justify-between rounded-lg bg-primary px-4 py-3 font-lora font-semibold text-white">
                <span><?= htmlspecialchars($ltvMaxLoanTitle) ?></span>
                <span><?= number_format($ltvMaxLoanValue, 0, ',', ' ') ?> <?= $cur ?></span>
            </div>
        </div>

        <!-- Right chart, taken as much as possible from template -->
        <div class="flex-1 flex items-stretch bg-paper rounded-3xl p-6">
            <div class="rounded-2xl flex-1">
                <canvas id="chart-credit-ltv"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    // DTI ring
    new Chart(document.getElementById('chart-credit-dti'), {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [<?= max(0, min(100, $dtiValue * 10)) ?>, <?= max(0, 100 - min(100, $dtiValue * 10)) ?>],
                backgroundColor: ['<?= $ringMainColor ?>', '<?= $ringBaseColor ?>'],
                borderWidth: 0,
                spacing: 0
            }]
        },
        options: {
            animation: false,
            cutout: '90%',
            rotation: -90,
            circumference: 360,
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: false
                }
            }
        }
    });

    // DSTI ring
    new Chart(document.getElementById('chart-credit-dsti'), {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [<?= max(0, min(100, $dstiValue)) ?>, <?= max(0, 100 - min(100, $dstiValue)) ?>],
                backgroundColor: ['<?= $ringMainColor ?>', '<?= $ringBaseColor ?>'],
                borderWidth: 0,
                spacing: 0
            }]
        },
        options: {
            animation: false,
            cutout: '90%',
            rotation: -90,
            circumference: 360,
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: false
                }
            }
        }
    });

    // LTV bar chart
    new Chart(document.getElementById('chart-credit-ltv'), {
        type: 'bar',
        data: {
            labels: ['<?= $ltvChartLabel1 ?>', '<?= $ltvChartLabel2 ?>'],
            datasets: [{
                data: [<?= $ltvChartValue1 ?>, <?= $ltvChartValue2 ?>],
                backgroundColor: ['#e7e4e4', '#936746'],
                borderRadius: 8,
                borderWidth: 0,
                borderSkipped: false,
                barThickness: 86
            }]
        },
        options: {
            animation: false,
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    top: 10,
                    right: 10,
                    bottom: 0,
                    left: 0
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMax: <?= max($ltvChartValue1, $ltvChartValue2) < 16000000 ? 16000000 : ceil(max($ltvChartValue1, $ltvChartValue2) / 2000000) * 2000000 ?>,
                    grid: {
                        display: true,
                        color: '#dfdddd'
                    },
                    border: {
                        display: false
                    },
                    ticks: {
                        stepSize: 2000000,
                        color: '#b8b2ae',
                        padding: 8,
                        font: {
                            size: 10
                        },
                        callback: function(value) {
                            if (value === 0) return '0';
                            return new Intl.NumberFormat('cs-CZ').format(value);
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    border: {
                        color: '#b8b2ae'
                    },
                    ticks: {
                        color: '#4A4541',
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });
</script>