<?php
// ============================================================
// SUMMARY PAGE – CONTROLLER
// ============================================================
$balanceReserve = $balance['reserve'] ?? [];

$reserveClient       = $balanceReserve['client'] ?? [];
$clientRows   = $reserveClient['rows'] ?? [];
$clientMonths = (float)($reserveClient['months'] ?? 0);

$reserveFooter        = $balanceReserve['footer'] ?? [];
$footerPercent = (float)($reserveFooter['percent'] ?? 0);
$footerStatus  = $reserveFooter['status']  ?? 'success';

$chartLabel1 = $clientRows[0]['title'] ?? '';
$chartLabel2 = $clientRows[1]['title'] ?? '';
$chartValue1 = (float)($clientRows[0]['value'] ?? 0);
$chartValue2 = (float)($clientRows[1]['value'] ?? 0);
$barColors   = ['#e7e4e4', '#936746'];
?>

<!-- ============================================================ -->
<!-- SUMMARY PAGE – ... ale stejně tak důležité je                -->
<!-- myslet na budoucnost                                         -->
<!-- ============================================================ -->
<div class="w-full box-border p-24 flex flex-col break-after-page overflow-visible gap-16 box-decoration-clone">

    <!-- Nadpis -->
    <h2 class="font-lora text-5xl font-semibold tracking-wide">
        <span class="text-pebble">... ale stejně tak důležité je</span><br>
        <span class="text-secondary">myslet na budoucnost</span>
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
    <div class="flex-1 flex items-center break-inside-avoid">
        <div class="flex gap-8 items-stretch w-full">
            <!-- Chart -->
            <div class="bg-paper rounded-3xl p-6 h-[500px]">
                <canvas id="chart-summary-p4"></canvas>
            </div>

            <!-- Pravý panel -->
            <div class="w-80 flex flex-col gap-8 justify-end">
                <div class="flex flex-col gap-3">
                    <div class="rounded-lg border border-primary/40 px-4 py-2 font-lora font-semibold text-primary">
                        Vaše rezerva
                    </div>
                    <?php foreach ($clientRows as $row): ?>
                        <div class="flex items-start justify-between gap-4 border-b border-mist px-4 pb-3 text-ink/75">
                            <div><?= htmlspecialchars($row['title'] ?? '') ?></div>
                            <div class="whitespace-nowrap"><?= number_format((float)($row['value'] ?? 0), 0, ',', ' ') ?> <?= $curMap[$row['currency'] ?? 'CZK'] ?? 'Kč' ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-2 flex items-center justify-between rounded-lg bg-secondary px-4 py-3 font-lora font-semibold text-white">
                    <div><?= htmlspecialchars($reserveClient['title'] ?? 'Vydží na') ?></div>
                    <div><?= number_format($clientMonths, 1, ',', ' ') ?> měsíců</div>
                </div>
            </div><!-- /Pravý panel -->
        </div><!-- /vnitřní wrapper -->
    </div><!-- /Hlavní obsah -->

    <!-- Footer row -->
    <div class="mt-10 flex gap-8 items-center">
        <?php if ($footerStatus === 'success'): ?>
            <div class="flex-1 bg-secondary/10 border border-secondary -ml-24 pl-24 max-w-2xl rounded-r-xl px-5 py-4 flex flex-col gap-4">
                <div class="flex items-center justify-between gap-4">
                    <div class="font-semibold">
                        Gratulujeme! Vaše rezerva je dostatečná.
                    </div>
                    <div class="rounded-xl px-3 py-3 font-semibold flex-shrink-0 text-white bg-secondary">
                        <?= number_format($footerPercent, 0, ',', ' ') ?>%
                    </div>
                </div>
                <div>
                    S vaší aktuální rezervou pravděpodobně zvládnete překlenout nenadálé negativní události.
                </div>
            </div>
        <?php else: ?>
            <div class="flex-1 bg-primary/10 border border-primary -ml-24 pl-24 max-w-2xl rounded-r-xl px-5 py-4 flex flex-col gap-4">
                <div class="flex items-center justify-between gap-4">
                    <div class="font-semibold">
                        Pozor! Vaše rezerva není dostatečná.
                    </div>
                    <div class="rounded-xl px-3 py-3 font-semibold flex-shrink-0 text-white bg-primary">
                        <?= number_format($footerPercent, 0, ',', ' ') ?>%
                    </div>
                </div>
                <div>
                    Doporučujeme posílit rezervu, aby lépe pokryla neočekávané životní situace.
                </div>
            </div>
        <?php endif; ?>

        <!-- Ikona -->
        <div class="w-56 flex-shrink-0 flex items-center justify-end text-primary/90">
            <?php include __DIR__ . '/trophy.php'; ?>
        </div>
    </div>
</div>

<script>
    new Chart(document.getElementById('chart-summary-p4'), {
        type: 'bar',
        data: {
            labels: ['<?= $chartLabel1 ?>', '<?= $chartLabel2 ?>'],
            datasets: [{
                data: [<?= $chartValue1 ?>, <?= $chartValue2 ?>],
                backgroundColor: [<?= implode(',', array_map(fn($c) => "'$c'", $barColors)) ?>],
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
                    suggestedMax: <?=
                                    max($chartValue1, $chartValue2) < 160000
                                        ? 160000
                                        : ceil(max($chartValue1, $chartValue2) / 20000) * 20000
                                    ?>,
                    grid: {
                        display: true,
                        color: '#dfdddd'
                    },
                    border: {
                        display: false
                    },
                    ticks: {
                        stepSize: 10000,
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