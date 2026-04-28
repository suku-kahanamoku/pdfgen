<?php
// ============================================================
// HEALTH PAGE – CONTROLLER
// ============================================================
$cur    = $curMap[$health['currency'] ?? 'CZK'] ?? 'Kč';

$shortfall      = $health['shortfall'] ?? [];
$shortRows      = $shortfall['rows'] ?? [];
$shortBoxes     = $shortfall['boxes'] ?? [];
$shortFooter    = $shortfall['footer'] ?? [];

$invalidity     = $health['invalidity'] ?? [];
$invalidityRows = $invalidity['rows'] ?? [];

$chartIncomeLossId = 'chart-health-income-loss';
?>

<!-- ============================================================ -->
<!-- HEALTH PAGE                                                  -->
<!-- ============================================================ -->
<div class="w-full box-border p-24 break-after-page overflow-visible flex flex-col gap-10 box-decoration-clone">

    <!-- Top section -->
    <div class="grid grid-cols-[1fr_1.45fr] gap-12 items-start">
        <!-- Left warning + chart -->
        <div class="flex flex-col gap-8">
            <div class="bg-primary/10 border border-primary rounded-xl px-5 py-7 min-h-28 flex flex-col gap-2">
                <div class="font-lora text-2xl font-semibold text-ink">
                    <?= htmlspecialchars($shortFooter['title'] ?? 'Nedostatečné') ?>
                </div>
                <div class="text-ink/70">
                    <?= htmlspecialchars($shortFooter['text'] ?? 'Aktuální nastavení vašeho zabezpečení') ?>
                </div>
            </div>

            <div class="bg-paper rounded-3xl p-6 min-h-[230px]">
                <canvas id="<?= $chartIncomeLossId ?>"></canvas>
            </div>
        </div>

        <!-- Right calculation -->
        <div class="flex flex-col gap-6">
            <div>
                <h3 class="font-lora text-4xl font-semibold leading-none text-ink">
                    Krátkodobý výpadek příjmu – do jednoho roku
                </h3>
                <p class="mt-4 leading-relaxed text-ink/70">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Itaque earum rerum hic tenetur sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores.
                </p>
            </div>

            <div class="grid grid-cols-[1fr_120px] gap-4 items-start">
                <div class="flex flex-col gap-3">
                    <?php foreach ($shortRows as $row): ?>
                        <div class="flex items-center justify-between border-b border-mist pb-2 text-ink/75">
                            <span><?= htmlspecialchars($row['title'] ?? '') ?></span>
                            <span><?= number_format((float)($row['value'] ?? 0), 0, ',', ' ') ?> <?= $cur ?></span>
                        </div>
                    <?php endforeach; ?>

                    <div class="flex items-center justify-between rounded-lg border border-primary/40 px-3 py-2 text-ink">
                        <span>Výdaje</span>
                        <span><?= number_format((float)($shortfall['expense'] ?? -32000), 0, ',', ' ') ?> <?= $cur ?></span>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-lg border border-primary/40 px-3 py-2 text-ink">
                            Rezerva 120 000 vydrží na
                        </div>
                        <div class="rounded-lg border border-primary/40 px-3 py-2 text-right text-ink">
                            <?= htmlspecialchars($shortfall['reserve_months'] ?? '3,24 měsíce') ?>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <?php foreach ($shortBoxes as $box): ?>
                        <div class="<?= !empty($box['dark']) ? 'bg-primary text-white' : 'bg-answer text-ink' ?> rounded-lg px-3 py-3">
                            <div class="text-xs"><?= htmlspecialchars($box['title'] ?? '') ?></div>
                            <div class="font-lora font-semibold text-right">
                                <?= htmlspecialchars($box['value'] ?? '') ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Long term -->
    <div class="flex flex-col gap-4">
        <h3 class="font-lora text-4xl font-semibold leading-none text-ink">
            Dlouhodobý výpadek příjmu
        </h3>
        <p class="leading-relaxed text-ink/70">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Itaque earum rerum hic tenetur sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores.
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

                    <div class="bg-paper rounded-3xl p-6 h-48">
                        <canvas id="<?= $chartId ?>"></canvas>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
    new Chart(document.getElementById('<?= $chartIncomeLossId ?>'), {
        type: 'bar',
        data: {
            labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
            datasets: [{
                    data: [17500, 17500, 17500, 17500, 17500, 17500, 17500, 17500, 17500, 17500, 17500, 17500],
                    backgroundColor: '#e7e4e4',
                    borderWidth: 0,
                    stack: 'stack',
                    barThickness: 18
                },
                {
                    data: [12500, 12500, 12500, 12500, 6500, 12500, 12500, 12500, 12500, 12500, 12500, 12500],
                    backgroundColor: '#6E4525',
                    borderWidth: 0,
                    stack: 'stack',
                    barThickness: 18
                },
                {
                    data: [0, 0, 0, 0, 6000, 0, 0, 0, 0, 0, 0, 0],
                    backgroundColor: '#F0D3BC',
                    borderWidth: 0,
                    stack: 'stack',
                    barThickness: 18
                }
            ]
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
                x: {
                    stacked: true,
                    grid: {
                        display: false
                    },
                    border: {
                        display: false
                    },
                    ticks: {
                        color: '#b8b2ae',
                        font: {
                            size: 9
                        }
                    }
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    suggestedMax: 30000,
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
                            size: 9
                        },
                        callback: value => new Intl.NumberFormat('cs-CZ').format(value)
                    }
                }
            }
        }
    });

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