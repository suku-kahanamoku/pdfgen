<?php
// ============================================================
// HEALTH DETAIL PAGE – DEATH + RESULT
// ============================================================
$health = $health ?? [];
$cur    = $curMap[$health['currency'] ?? 'CZK'] ?? 'Kč';

$death = $health['death'] ?? [];
$result = $health['result'] ?? [];

$deathSummaryRows = $death['rows'] ?? [];
$deathCoverage = (float)($death['coverage']['value'] ?? 0);
$deathCoverageTitle = $death['coverage']['title'] ?? 'Předané krytí';

$deathChart = $death['chart'] ?? [];
$deathChartLabel1 = $deathChart['label1'] ?? 'Výdaje';
$deathChartLabel2 = $deathChart['label2'] ?? 'Inv. I';
$deathChartValue1 = (float)($deathChart['value1'] ?? 35000);
$deathChartValue2 = (float)($deathChart['value2'] ?? 42000);

$resultGroups = $result['groups'] ?? [];
?>

<!-- ============================================================ -->
<!-- HEALTH DETAIL PAGE – Death + Result                          -->
<!-- ============================================================ -->
<div class="w-full box-border p-24 break-after-page box-decoration-clone overflow-visible flex flex-col gap-16">


    <!-- Death section -->
    <div class="flex flex-col gap-6">
        <h3 class="font-lora text-4xl font-semibold leading-none text-ink">
            Pojištění pro případ smrti
        </h3>

        <p class="max-w-5xl leading-relaxed text-ink/70">
            V případě úzmřtí zůstávají vašim nejbližším nejen vzpomínky, ale často také finanční závazky – hypotéka, spotřebitelské úvěry nebo náklady na běžný chod domácnosti. Správně nastavené životní pojištění zajistí, že vaše rodina nebude muset tyto závazky řešit sama a bude mít dostatek času se vyrovnat se situací bez finĊlního tlaku.
        </p>

        <div class="grid grid-cols-2 gap-6 items-end">
            <div class="bg-paper rounded-3xl p-6 h-[230px]">
                <canvas id="chart-health-death"></canvas>
            </div>

            <div class="flex flex-col gap-3 text-sm">
                <?php foreach ($deathSummaryRows as $row): ?>
                    <div class="flex items-center justify-between border-b border-mist pb-2 text-ink/75">
                        <span><?= htmlspecialchars($row['title'] ?? '') ?></span>
                        <span><?= number_format((float)($row['value'] ?? 0), 0, ',', ' ') ?> <?= $cur ?></span>
                    </div>
                <?php endforeach; ?>

                <div class="flex items-center justify-between rounded-lg bg-primary px-4 py-3 font-lora font-semibold text-white">
                    <span><?= htmlspecialchars($deathCoverageTitle) ?></span>
                    <span><?= number_format($deathCoverage, 0, ',', ' ') ?> <?= $cur ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Result section -->
    <div class="flex flex-col gap-10 mt-10">
        <!-- Header -->
        <div class="flex flex-col gap-8">
            <div class="flex items-center justify-between gap-4">
                <h2 class="font-lora text-4xl font-semibold leading-none text-ink">
                    Celkový přehled Vašeho životního<br> pojištění.
                </h2>
                <i class="text-secondary fa-solid fa-shield-halved text-5xl"></i>
            </div>

            <div class="leading-relaxed text-ink/70">
                Hlavní pojištěný
            </div>
        </div>

        <!-- Table header -->
        <div class="grid grid-cols-[46px_220px_1fr_1fr_1fr] gap-4 items-center">
            <div></div>
            <div></div>

            <div class="bg-ink/5 text-ink rounded-md px-3 py-2 text-center text-sm font-semibold">Pojistná rizika</div>
            <div class="bg-chestnut text-white rounded-md px-3 py-2 text-center text-sm font-semibold">Pojistná rizika</div>
            <div class="bg-primary text-white rounded-md px-3 py-2 text-center text-sm font-semibold">Pojistná rizika</div>
        </div>

        <!-- Table groups -->
        <div class="flex flex-col gap-6">
            <?php foreach ($resultGroups as $group):
                $groupTitle     = $group['title'] ?? '';
                $groupColor     = $group['color'] ?? 'cream';
                $groupTextColor = $group['textColor'] ?? 'ink';
                $sections = $group['sections'] ?? [];
            ?>
                <div class="grid grid-cols-[30px_1fr] gap-4 items-stretch break-inside-avoid">
                    <!-- Vertical group label -->
                    <div class="rounded-md bg-<?= htmlspecialchars($groupColor) ?> flex items-center justify-center">
                        <div class="rotate-[-90deg] whitespace-nowrap font-lora text-<?= htmlspecialchars($groupTextColor) ?>">
                            <?= htmlspecialchars($groupTitle) ?>
                        </div>
                    </div>

                    <div class="flex flex-col gap-5">
                        <?php foreach ($sections as $section): ?>
                            <div class="flex flex-col gap-3 break-inside-avoid">
                                <?php if (!empty($section['title'])): ?>
                                    <div class="font-lora text-lg font-semibold text-ink">
                                        <?= htmlspecialchars($section['title']) ?>
                                    </div>
                                <?php endif; ?>

                                <?php foreach (($section['rows'] ?? []) as $row): ?>
                                    <div class="grid grid-cols-[220px_1fr_1fr_1fr] gap-4 items-center break-inside-avoid">
                                        <div class="rounded-md border border-ink/15 px-3 py-2 text-sm text-ink/80">
                                            <?= htmlspecialchars($row['title'] ?? '') ?>
                                        </div>

                                        <?php foreach (($row['values'] ?? []) as $val): ?>
                                            <div class="rounded-md border border-ink/15 px-3 py-2 text-sm text-ink/80 text-center">
                                                <?php if (is_numeric($val)): ?>
                                                    <?= number_format((float)$val, 0, ',', ' ') ?> <?= $cur ?>
                                                <?php else: ?>
                                                    <?= htmlspecialchars((string)$val) ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
    new Chart(document.getElementById('chart-health-death'), {
        type: 'bar',
        data: {
            labels: ['<?= htmlspecialchars($deathChartLabel1) ?>', '<?= htmlspecialchars($deathChartLabel2) ?>'],
            datasets: [{
                data: [<?= $deathChartValue1 ?>, <?= $deathChartValue2 ?>],
                backgroundColor: ['#e7e4e4', '#936746'],
                borderRadius: 8,
                borderWidth: 0,
                borderSkipped: false,
                barThickness: 70
            }]
        },
        options: {
            animation: false,
            responsive: true,
            maintainAspectRatio: false,
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
                    grid: {
                        color: '#dfdddd'
                    },
                    border: {
                        display: false
                    },
                    ticks: {
                        color: '#b8b2ae',
                        font: {
                            size: 8
                        },
                        callback: value => new Intl.NumberFormat('cs-CZ').format(value)
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
                            size: 8
                        }
                    }
                }
            }
        }
    });
</script>