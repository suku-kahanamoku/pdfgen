<?php
// ============================================================
// CASH-FLOW PAGE – CONTROLLER
// ============================================================
$balanceIncome = $balance['income'] ?? [];
$incomeTotal   = (float)($balanceIncome['total'] ?? 0);
$cur           = $curMap[$balanceIncome['currency'] ?? 'CZK'] ?? 'Kč';

$incomeClient  = $balanceIncome['client'] ?? [];
$clientRows  = $incomeClient['rows'] ?? [];

$incomePartner = $balanceIncome['partner'] ?? [];
$partnerRows = $incomePartner['rows'] ?? [];

$incomeFooter             = $balanceIncome['footer'] ?? [];
$footerPercent      = (float)($incomeFooter['percent']       ?? 0);
$footerStatus       = $incomeFooter['status']                ?? 'success';
$footerCur          = $curMap[$incomeFooter['currency']      ?? 'CZK'] ?? 'Kč';
$footerYear         = (int)($incomeFooter['year']            ?? 0);
$footerAvgPerson    = (float)($incomeFooter['avg_person']    ?? 0);
$footerAvgHousehold = (float)($incomeFooter['avg_household'] ?? 0);


$incomeSummary     = $balanceIncome['summary'] ?? [];
$summaryRows = $incomeSummary['rows'] ?? [];

$chartLabel1 = $summaryRows[0]['title'] ?? 'Příjmy';
$chartLabel2 = $summaryRows[1]['title'] ?? 'Výdaje';
$chartValue1 = (float)($summaryRows[0]['value'] ?? 0);
$chartValue2 = (float)($summaryRows[1]['value'] ?? 0);
$barColors   = ['#e7e4e4', '#936746'];
?>

<!-- ============================================================ -->
<!-- CASH-FLOW PAGE – Kde končí zisk, začíná svoboda              -->
<!-- ============================================================ -->
<div class="w-full box-border p-24 flex flex-col [page-break-after:always] [break-after:page] overflow-visible gap-16 [box-decoration-break:clone]">

    <!-- Nadpis -->
    <h2 class="font-lora text-6xl font-semibold leading-none tracking-tight">
        <span class="text-pebble">Kde končí zisk,</span>
        <span class="text-primary">začíná</span><br>
        <span class="text-primary">svoboda</span>
    </h2>

    <!-- Intro -->
    <div class="max-w-5xl">
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
                    <canvas id="chart-balance-p3"></canvas>
                </div>
            </div>

            <!-- Pravý panel -->
            <div class="w-80 flex flex-col gap-8 justify-end">
                <!-- Vaše příjmy -->
                <div class="flex flex-col gap-3">
                    <div class="rounded-lg border border-primary/40 px-4 py-2 font-lora font-semibold text-primary">
                        <?= htmlspecialchars($incomeClient['title'] ?? 'Vaše příjmy') ?>
                    </div>
                    <?php foreach ($clientRows as $row): ?>
                        <div class="flex items-start justify-between gap-4 border-b border-mist px-4 pb-3 text-ink/75">
                            <div><?= htmlspecialchars($row['title'] ?? '') ?></div>
                            <div class="whitespace-nowrap"><?= number_format((float)($row['value'] ?? 0), 0, ',', ' ') ?> <?= $cur ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Příjmy partnera -->
                <div class="flex flex-col gap-3">
                    <div class="rounded-lg border border-primary/40 px-4 py-2 font-lora font-semibold text-primary">
                        <?= htmlspecialchars($incomePartner['title'] ?? 'Příjmy partnera') ?>
                    </div>
                    <?php foreach ($partnerRows as $row): ?>
                        <div class="flex items-start justify-between gap-4 border-b border-mist px-4 pb-3 text-ink/75">
                            <div><?= nl2br(htmlspecialchars($row['title'] ?? '')) ?></div>
                            <div class="whitespace-nowrap"><?= number_format((float)($row['value'] ?? 0), 0, ',', ' ') ?> <?= $cur ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Celková bilance -->
                <div class="flex flex-col gap-3">
                    <div class="rounded-lg border border-primary/40 px-4 py-2 font-lora font-semibold text-primary">
                        <?= htmlspecialchars($incomeSummary['title'] ?? '') ?>
                    </div>
                    <?php foreach ($summaryRows as $row): ?>
                        <div class="flex items-start justify-between gap-4 border-b border-mist px-4 pb-3 text-ink/75">
                            <div><?= htmlspecialchars($row['title'] ?? '') ?></div>
                            <div class="whitespace-nowrap"><?= number_format((float)($row['value'] ?? 0), 0, ',', ' ') ?> <?= $cur ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-2 flex items-center justify-between rounded-lg bg-primary px-4 py-3 font-lora font-semibold text-white">
                    <div>Měsíčně zbývá</div>
                    <div><?= number_format($incomeTotal, 0, ',', ' ') ?> <?= $cur ?></div>
                </div>
            </div><!-- /Pravý panel -->
        </div><!-- /vnitřní wrapper -->
    </div><!-- /Hlavní obsah -->

    <!-- Footer row -->
    <div class="mt-10 grid grid-cols-[1fr_220px] gap-8 items-end">
        <?php if ($footerStatus === 'success'): ?>
            <div class="bg-green-50 border border-success -ml-24 pl-24 max-w-2xl rounded-r-xl px-5 py-4 flex flex-col gap-4">
                <div class="flex items-center justify-between gap-4">
                    <div class="font-semibold">Vaše příjmy jsou o <?= number_format(58, 0, ',', ' ') ?> % vyšší než průměr.</div>
                    <div class="rounded-xl px-3 py-3 font-semibold flex-shrink-0 text-white bg-success"><?= number_format($footerPercent, 0, ',', ' ') ?>%</div>
                </div>
                <div class="text-ink/70">Průměrný příjem jedince v ČR dosahoval v roce <?= $footerYear ?> hodnoty <?= number_format($footerAvgPerson, 0, ',', ' ') ?> <?= $footerCur ?>. Průměrný příjem domácnosti byl <?= number_format($footerAvgHousehold, 0, ',', ' ') ?> <?= $footerCur ?>.</div>
            </div>
        <?php else: ?>
            <div class="bg-red-50 border border-error -ml-24 pl-24 max-w-2xl rounded-r-xl px-5 py-4 flex flex-col gap-4">
                <div class="flex items-center justify-between gap-4">
                    <div class="font-semibold">Pozor! Vaše příjmy jsou pod průměrem.</div>
                    <div class="rounded-xl px-3 py-3 font-semibold flex-shrink-0 text-white bg-error"><?= number_format($footerPercent, 0, ',', ' ') ?>%</div>
                </div>
                <div class="text-ink/70">Vaše příjmy jsou nižší než referenční průměr. Doporučujeme zaměřit se na posílení příjmové stránky a práci s rezervou.</div>
            </div>
        <?php endif; ?>

        <!-- Ikona -->
        <div class="flex items-center justify-end text-primary/90">
            <?php include __DIR__ . '/trophy.php'; ?>
        </div>
    </div>
</div>

<script>
    new Chart(document.getElementById('chart-balance-p3'), {
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
                    suggestedMax: <?= max($chartValue1, $chartValue2) < 80000 ? 80000 : ceil(max($chartValue1, $chartValue2) / 10000) * 10000 ?>,
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