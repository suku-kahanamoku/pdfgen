<?php
// ============================================================
// CASH-FLOW PAGE – CONTROLLER
// ============================================================
$cfIncome   = $finance['income']['income'] ?? [];
$cfPartner  = $finance['income']['partner'] ?? [];
$cfExpense  = $finance['income']['expense'] ?? [];
$cfFooter   = $finance['income']['footer'] ?? [];
$cfCur      = $curMap[$cfIncome['currency'] ?? 'CZK'] ?? 'Kč';

$cfIncomeRows  = $cfIncome['rows'] ?? [];
$cfPartnerRows = $cfPartner['rows'] ?? [];

$cfIncomeTotal  = (float)($cfIncome['value'] ?? 0) + (float)($cfPartner['value'] ?? 0);
$cfExpenseTotal = (float)($cfExpense['value'] ?? 0);

$cfRemaining = $cfIncomeTotal - $cfExpenseTotal;

$cfFooterPercent = (float)($cfFooter['percent'] ?? 0);
$cfFooterStatus  = $cfFooter['status'] ?? 'success';

$cfChartLabel1 = 'Příjmy';
$cfChartLabel2 = 'Výdaje';
$cfBarColors   = ['#e7e4e4', '#936746'];
?>

<!-- ============================================================ -->
<!-- CASH-FLOW PAGE – Kde končí zisk, začíná svoboda              -->
<!-- ============================================================ -->
<div class="w-full box-border p-24 h-screen flex flex-col [page-break-after:always] [break-after:page] overflow-visible [box-decoration-break:clone]">

    <!-- Nadpis -->
    <div class="mb-8">
        <h2 class="font-lora text-6xl font-semibold leading-none tracking-tight">
            <span class="text-pebble">Kde končí zisk,</span>
            <span class="text-primary">začíná</span><br>
            <span class="text-primary">svoboda</span>
        </h2>
    </div>

    <!-- Intro -->
    <div class="mb-10 max-w-5xl">
        <p class="leading-relaxed text-ink/70">
            Příjmy jsou základem naší finanční stability a jejich efektivní správa je klíčová pro dosažení dlouhodobé prosperity.
            Správné nakládání s příjmy začíná důkladným plánováním a rozumným investováním. Je důležité nejen vydělávat, ale také
            efektivně šetřit a investovat tak, aby naše peníze pracovaly pro nás.
        </p>
    </div>

    <!-- Hlavní obsah -->
    <div class="flex-1 flex items-center [page-break-inside:avoid] [break-inside:avoid]">
        <div class="flex gap-8 items-stretch w-full">
            <!-- Chart -->
            <div class="flex-1 flex items-stretch bg-paper rounded-3xl p-6 min-h-[500px]">
                <div class="rounded-2xl px-6 py-6 flex-1">
                    <canvas id="chart-finance-p3"></canvas>
                </div>
            </div>

            <!-- Pravý panel -->
            <div class="w-80 flex flex-col gap-8 justify-end">
                <!-- Vaše příjmy -->
                <div class="flex flex-col gap-3">
                    <div class="rounded-lg border border-primary/40 px-4 py-2 font-lora font-semibold text-primary">
                        Vaše příjmy
                    </div>
                    <?php foreach ($cfIncomeRows as $row): ?>
                        <div class="flex items-start justify-between gap-4 border-b border-mist pb-3 text-ink/75">
                            <div><?= htmlspecialchars($row['title'] ?? '') ?></div>
                            <div class="whitespace-nowrap"><?= number_format((float)($row['value'] ?? 0), 0, ',', ' ') ?> <?= $cfCur ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Příjmy partnera -->
                <div class="flex flex-col gap-3">
                    <div class="rounded-lg border border-primary/40 px-4 py-2 font-lora font-semibold text-primary">
                        <?= htmlspecialchars($cfPartner['title'] ?? 'Příjmy partnera') ?>
                    </div>
                    <?php foreach ($cfPartnerRows as $row): ?>
                        <div class="flex items-start justify-between gap-4 border-b border-mist pb-3 text-ink/75">
                            <div><?= nl2br(htmlspecialchars($row['title'] ?? '')) ?></div>
                            <div class="whitespace-nowrap"><?= number_format((float)($row['value'] ?? 0), 0, ',', ' ') ?> <?= $cfCur ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Celková bilance -->
                <div class="flex flex-col gap-3">
                    <div class="rounded-lg border border-primary/40 px-4 py-2 font-lora font-semibold text-primary">
                        Celková bilance cash-flow
                    </div>

                    <div class="flex items-start justify-between gap-4 border-b border-mist pb-3 text-ink/75">
                        <div><?= htmlspecialchars($cfIncome['title'] ?? 'Příjmy') ?></div>
                        <div class="whitespace-nowrap"><?= number_format($cfIncomeTotal, 0, ',', ' ') ?> <?= $cfCur ?></div>
                    </div>

                    <div class="flex items-start justify-between gap-4 border-b border-mist pb-3 text-ink/75">
                        <div><?= htmlspecialchars($cfExpense['title'] ?? 'Výdaje') ?></div>
                        <div class="whitespace-nowrap"><?= number_format($cfExpenseTotal, 0, ',', ' ') ?> <?= $cfCur ?></div>
                    </div>
                </div>

                <div class="mt-2 flex items-center justify-between rounded-lg bg-primary px-4 py-3 font-lora font-semibold text-white">
                    <div>Měsíčně zbývá</div>
                    <div><?= number_format($cfRemaining, 0, ',', ' ') ?> <?= $cfCur ?></div>
                </div>
            </div><!-- /Pravý panel -->
        </div><!-- /vnitřní wrapper -->
    </div><!-- /Hlavní obsah -->

    <!-- Footer row -->
    <div class="mt-10 grid grid-cols-[1fr_220px] gap-8 items-end">
        <?php if ($cfFooterStatus === 'success'): ?>
            <div class="bg-green-50 border border-success -ml-24 pl-24 max-w-2xl rounded-r-xl px-5 py-4 flex flex-col gap-4">
                <div class="flex items-center justify-between gap-4">
                    <div class="font-semibold">Vaše příjmy jsou o <?= number_format(58, 0, ',', ' ') ?> % vyšší než průměr.</div>
                    <div class="rounded-xl px-3 py-3 font-semibold flex-shrink-0 text-white bg-success"><?= number_format($cfFooterPercent, 0, ',', ' ') ?>%</div>
                </div>
                <div class="text-ink/70"><?= 'Průměrný příjem jedince v ČR dosahoval v roce 2024 hodnoty 37 000 Kč. Průměrný příjem domácnosti byl 52 000 Kč.' ?></div>
            </div>
        <?php else: ?>
            <div class="bg-red-50 border border-error -ml-24 pl-24 max-w-2xl rounded-r-xl px-5 py-4 flex flex-col gap-4">
                <div class="flex items-center justify-between gap-4">
                    <div class="font-semibold">Pozor! Vaše příjmy jsou pod průměrem.</div>
                    <div class="rounded-xl px-3 py-3 font-semibold flex-shrink-0 text-white bg-error"><?= number_format($cfFooterPercent, 0, ',', ' ') ?>%</div>
                </div>
                <div class="text-ink/70"><?= 'Vaše příjmy jsou nižší než referenční průměr. Doporučujeme zaměřit se na posílení příjmové stránky a práci s rezervou.' ?></div>
            </div>
        <?php endif; ?>

        <!-- Ikona -->
        <div class="flex items-center justify-end text-primary/90">
            <?php include __DIR__ . '/trophy.php'; ?>
        </div>
    </div>
</div>

<script>
    new Chart(document.getElementById('chart-finance-p3'), {
        type: 'bar',
        data: {
            labels: ['<?= $cfChartLabel1 ?>', '<?= $cfChartLabel2 ?>'],
            datasets: [{
                data: [<?= $cfIncomeTotal ?>, <?= $cfExpenseTotal ?>],
                backgroundColor: [<?= implode(',', array_map(fn($c) => "'$c'", $cfBarColors)) ?>],
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
                    suggestedMax: <?= max($cfIncomeTotal, $cfExpenseTotal) < 80000 ? 80000 : ceil(max($cfIncomeTotal, $cfExpenseTotal) / 10000) * 10000 ?>,
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