<?php
// ============================================================
// SUMMARY PAGE – CONTROLLER
// ============================================================
$sumData   = $finance['summary'] ?? [];
$sumFooter = $sumData['footer'] ?? [];

$sumReserve     = $sumData['reserve'] ?? [];
$sumReserveRows = $sumReserve['rows'] ?? [];
$sumCur         = $curMap[$sumReserve['currency'] ?? 'CZK'] ?? 'Kč';
$sumTotal       = (float)($sumReserve['value'] ?? 0);

$sumFooterPercent = (float)($sumFooter['percent'] ?? 0);
$sumFooterStatus  = $sumFooter['status'] ?? 'success';

$sumChartRows   = array_filter($sumReserveRows, fn($r) => ($r['disable_grap'] ?? true) !== false);
$sumChartLabels = array_map(fn($r) => htmlspecialchars($r['title'] ?? ''), $sumChartRows);
$sumChartValues = array_map(fn($r) => (float)($r['value'] ?? 0), $sumChartRows);
$sumBarColors   = ['#e7e4e4', '#936746'];
?>

<!-- ============================================================ -->
<!-- SUMMARY PAGE – ... ale stejně tak důležité je                -->
<!-- myslet na budoucnost                                         -->
<!-- ============================================================ -->
<div class="w-full box-border p-24 flex flex-col [page-break-after:always] [break-after:page] overflow-visible gap-16 [box-decoration-break:clone]">

    <!-- Nadpis -->
    <h2 class="font-lora text-6xl font-semibold leading-none tracking-tight">
        <span class="text-pebble">... ale stejně tak důležité je</span><br>
        <span class="text-primary">myslet na budoucnost</span>
    </h2>

    <!-- Intro -->
    <div class="max-w-5xl">
        <p class="leading-relaxed text-ink/70">
            Řízení výdajů začíná jejich sledováním a kategorizací. Když víme, kolik a za co utrácíme, můžeme snadněji
            identifikovat oblasti, kde můžeme ušetřit. To zahrnuje nejen velké, jednorázové náklady, ale i pravidelné menší
            výdaje, které se mohou rychle nasčítat.
        </p>
    </div>

    <!-- Hlavní obsah -->
    <div class="flex-1 flex items-center [page-break-inside:avoid] [break-inside:avoid]">
        <div class="flex gap-8 items-stretch w-full">
            <!-- Chart -->
            <div class="flex-1 flex items-stretch bg-paper rounded-3xl p-6 min-h-[500px]">
                <div class="rounded-2xl px-6 py-6 flex-1">
                    <canvas id="chart-summary-p4"></canvas>
                </div>
            </div>

            <!-- Pravý panel -->
            <div class="w-80 flex flex-col gap-8 justify-end">
                <div class="flex flex-col gap-3">
                    <div class="rounded-lg border border-primary/40 px-4 py-2 font-lora font-semibold text-primary">
                        <?= htmlspecialchars($sumReserve['title'] ?? 'Vaše rezerva') ?>
                    </div>
                    <?php foreach ($sumReserveRows as $row): ?>
                        <div class="flex items-start justify-between gap-4 border-b border-mist pb-3 text-ink/75">
                            <div><?= htmlspecialchars($row['title'] ?? '') ?></div>
                            <div class="whitespace-nowrap"><?= number_format((float)($row['value'] ?? 0), 0, ',', ' ') ?> <?= $curMap[$row['currency'] ?? 'CZK'] ?? 'Kč' ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-2 flex items-center justify-between rounded-lg bg-primary px-4 py-3 font-lora font-semibold text-white">
                    <div><?= htmlspecialchars($sumReserve['title'] ?? 'Vydží na') ?></div>
                    <div><?= number_format($sumTotal, 2, ',', ' ') ?> <?= $sumCur ?> měsíců</div>
                </div>
            </div><!-- /Pravý panel -->
        </div><!-- /vnitřní wrapper -->
    </div><!-- /Hlavní obsah -->

    <!-- Footer row -->
    <div class="mt-10 grid grid-cols-[1fr_220px] gap-8 items-end">
        <?php if ($sumFooterStatus === 'success'): ?>
            <div class="bg-green-50 border border-success -ml-24 pl-24 max-w-2xl rounded-r-xl px-5 py-4 flex flex-col gap-4">
                <div class="flex items-center justify-between gap-4">
                    <div class="font-semibold">
                        <?= htmlspecialchars($sumFooter['headline'] ?? 'Gratulujeme! Vaše rezerva je dostatečná.') ?>
                    </div>
                    <div class="rounded-xl px-3 py-3 font-semibold flex-shrink-0 text-white bg-success">
                        <?= number_format($sumFooterPercent, 0, ',', ' ') ?>%
                    </div>
                </div>
                <div class="text-ink/70">
                    <?= htmlspecialchars($sumFooter['text'] ?? 'S vaší aktuální rezervou pravděpodobně zvládnete překlenout nenadálé negativní události.') ?>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-red-50 border border-error -ml-24 pl-24 max-w-2xl rounded-r-xl px-5 py-4 flex flex-col gap-4">
                <div class="flex items-center justify-between gap-4">
                    <div class="font-semibold">
                        <?= htmlspecialchars($sumFooter['headline'] ?? 'Pozor! Vaše rezerva není dostatečná.') ?>
                    </div>
                    <div class="rounded-xl px-3 py-3 font-semibold flex-shrink-0 text-white bg-error">
                        <?= number_format($sumFooterPercent, 0, ',', ' ') ?>%
                    </div>
                </div>
                <div class="text-ink/70">
                    <?= htmlspecialchars($sumFooter['text'] ?? 'Doporučujeme posílit rezervu, aby lépe pokryla neočekávané životní situace.') ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Ikona -->
        <div class="flex items-center justify-end text-primary/90">
            <?php include __DIR__ . '/trophy.php'; ?>
        </div>
    </div>
</div>

<script>
    new Chart(document.getElementById('chart-summary-p4'), {
        type: 'bar',
        data: {
            labels: [<?= implode(',', array_map(fn($l) => "'$l'", $sumChartLabels)) ?>],
            datasets: [{
                data: [<?= implode(',', $sumChartValues) ?>],
                backgroundColor: [<?= implode(',', array_map(fn($c) => "'$c'", $sumBarColors)) ?>],
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
                    suggestedMax: <?= ($sumChartValues ? max($sumChartValues) : 0) < 160000 ? 160000 : ceil((($sumChartValues ? max($sumChartValues) : 0)) / 20000) * 20000 ?>,
                    grid: {
                        display: true,
                        color: '#dfdddd'
                    },
                    border: {
                        display: false
                    },
                    ticks: {
                        stepSize: 20000,
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