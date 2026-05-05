<?php
// ============================================================
// HEALTH PAGE 3 – CONTROLLER
// ============================================================
$cur            = $curMap[$health['currency'] ?? 'CZK'] ?? 'Kč';
$invalidity     = $health['invalidity'] ?? [];
$invalidityRows = $invalidity['rows'] ?? [];
?>

<!-- ============================================================ -->
<!-- HEALTH PAGE 3                                                -->
<!-- ============================================================ -->
<div class="w-full box-border p-24 break-after-page overflow-visible flex flex-col gap-10 box-decoration-clone">

    <?php emitMarker(); ?>

    <!-- Long term -->
    <div class="flex flex-col gap-4">
        <h3 class="font-lora text-4xl font-semibold leading-none text-ink">
            Dlouhodobý výpadek příjmu
        </h3>
        <p class="leading-relaxed text-ink/70">
            Dlouhodobá invalidita nebo ztráta soběstačnosti patří mezi nejzávažnější finanční rizika. Státní dávky zpravidla nestačí pokrýt běžné výdaje domácnosti, proto je klíčové mít nastaveno pojištění, které rozdíl dorovná a zachová váš životní standard.
        </p>
    </div>

    <!-- Invalidity columns -->
    <div>
        <h3 class="mb-6 font-lora text-3xl font-semibold text-ink">
            Invalidita
        </h3>

        <div class="grid grid-cols-3 gap-8">
            <?php foreach ($invalidityRows as $i => $col):
                $chartId = 'chart-health-invalidity-' . $i;
            ?>
                <div class="flex flex-col gap-6 break-inside-avoid">
                    <h4 class="font-lora text-2xl font-semibold text-ink">
                        <?= htmlspecialchars($col['title'] ?? '') ?>
                    </h4>

                    <div class="flex flex-col gap-3">
                        <div class="flex items-center justify-between rounded-lg border border-primary/40 px-4 py-2 font-lora font-semibold">
                            <span class="text-primary">Vaše pojištění</span>
                            <span><?= number_format((float)($col['insured'] ?? 0), 0, ',', ' ') ?> <?= $cur ?></span>
                        </div>

                        <?php foreach (($col['rows'] ?? []) as $row): ?>
                            <div class="flex items-center justify-between border-b border-mist px-4 pb-3 text-ink/75">
                                <span><?= htmlspecialchars($row['title'] ?? '') ?></span>
                                <span><?= number_format((float)($row['value'] ?? 0), 0, ',', ' ') ?> <?= $cur ?></span>
                            </div>
                        <?php endforeach; ?>

                        <div class="mt-2 flex items-center justify-between rounded-lg bg-primary px-4 py-3 font-lora font-semibold text-white">
                            <span>Měsíčně zbývá</span>
                            <span><?= number_format((float)($col['monthly_left'] ?? 0), 0, ',', ' ') ?> <?= $cur ?></span>
                        </div>
                    </div>

                    <div class="bg-paper rounded-3xl p-6 h-72">
                        <canvas id="<?= $chartId ?>"></canvas>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
    <?php foreach ($invalidityRows as $i => $col):
        $chartId = 'chart-health-invalidity-' . $i;
        $expense = (float)($col['chart']['expense'] ?? 35000);
        $income  = (float)($col['chart']['income'] ?? 30000);
    ?>
        new Chart(document.getElementById('<?= $chartId ?>'), {
            type: 'bar',
            data: {
                labels: ['Výdaje', 'Inv. I'],
                datasets: [{
                    data: [<?= $expense ?>, <?= $income ?>],
                    backgroundColor: ['#e7e4e4', '#936746'],
                    borderRadius: 8,
                    borderWidth: 0,
                    borderSkipped: false,
                    barThickness: 46
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
                        suggestedMax: 40000,
                        grid: {
                            color: '#dfdddd'
                        },
                        border: {
                            display: false
                        },
                        ticks: {
                            stepSize: 5000,
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
    <?php endforeach; ?>
</script>
