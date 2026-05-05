<?php
// ============================================================
// HEALTH PAGE – CONTROLLER
// ============================================================
$cur    = $curMap[$health['currency'] ?? 'CZK'] ?? 'Kč';

$shortfall      = $health['shortfall'] ?? [];
$shortRows      = $shortfall['rows'] ?? [];
$shortExpense   = $shortfall['expense'] ?? [];
$shortReserve   = $shortfall['reserve'] ?? [];
$shortFooter    = $shortfall['footer'] ?? [];
$shortMissing   = $shortfall['missing'] ?? [];
$shortDose      = $shortfall['dose'] ?? [];
$shortChart     = $shortfall['chart'] ?? [];
$shortDatasets  = $shortChart['datasets'] ?? [];

$chartIncomeLossId = 'chart-health-income-loss';
?>

<!-- ============================================================ -->
<!-- HEALTH PAGE                                                  -->
<!-- ============================================================ -->
<div class="w-full box-border p-24 break-after-page overflow-visible flex flex-col gap-10 box-decoration-clone">

    <?php emitMarker(); ?>

    <!-- Top section -->
    <div class="grid grid-cols-[1fr_1.45fr] gap-12 items-stretch">
        <!-- Left footer + right text -->
        <?php if (($shortFooter['status'] ?? '') === 'success'): ?>
            <div class="bg-secondary/10 border border-secondary -ml-24 pl-24 rounded-r-xl px-5 py-4 flex flex-col gap-1 justify-center">
                <div class="font-semibold text-ink">Dobře nastavené</div>
                <div class="text-ink/70">Vaše pojistná ochrana je na doporučené úrovni</div>
            </div>
        <?php else: ?>
            <div class="bg-primary/10 border border-primary -ml-24 pl-24 rounded-r-xl px-5 py-4 flex flex-col gap-1 justify-center">
                <div class="font-semibold text-ink">Nedostatečné</div>
                <div class="text-ink/70">Aktuální nastavení vašeho zabezpečení</div>
            </div>
        <?php endif; ?>

        <div>
            <h3 class="font-lora text-4xl font-semibold leading-none text-ink">
                Krátkodobý výpadek příjmu – do jednoho roku
            </h3>
            <p class="mt-4 leading-relaxed text-ink/70">
                Při krátkodobé pracovní neschopnosti nebo úrazu se váš příjem může výrazně snížit. Státní nemocenská pokrývá pouze část výdajů, a bez dostatečné rezervy či pojistné ochrany může domácnost rychle sklouznout do finanční tísně.
            </p>
        </div>
    </div>

    <!-- Graf + right info -->
    <div class="grid grid-cols-2 gap-6 items-end">
        <div class="bg-paper rounded-3xl p-6 h-72">
            <canvas id="<?= $chartIncomeLossId ?>"></canvas>
        </div>

        <div class="grid grid-cols-[1fr_160px] gap-4 items-end text-sm">
            <div class="flex flex-col gap-3">
                <?php foreach ($shortRows as $row): ?>
                    <div class="flex items-center justify-between border-b border-mist pb-2 text-ink/75">
                        <span><?= htmlspecialchars($row['title'] ?? '') ?></span>
                        <span><?= number_format((float)($row['value'] ?? 0), 0, ',', ' ') ?> <?= $cur ?></span>
                    </div>
                <?php endforeach; ?>

                <div class="flex items-center justify-between rounded-lg border border-primary/40 px-2 py-1 text-ink">
                    <span class="text-primary font-lora"><?= htmlspecialchars($shortExpense['title'] ?? '') ?></span>
                    <span class="text-right"><?= number_format((float)($shortExpense['value'] ?? 0), 0, ',', ' ') ?> <?= $cur ?></span>
                </div>

                <div class="grid grid-cols-2 items-center rounded-lg border border-primary/40 px-2 py-1 text-ink">
                    <span class="text-primary font-lora leading-none"><?= htmlspecialchars($shortReserve['title'] ?? '') ?></span>
                    <span class="text-right"><?= number_format((float)($shortReserve['value'] ?? 0), 2, ',', ' ') ?> měsíc</span>
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <?php foreach ($shortMissing as $missing): ?>
                    <div class="grid grid-cols-2 items-center rounded-lg border border-primary/40 px-2 py-1 text-white bg-primary">
                        <span class="font-lora leading-none"><?= htmlspecialchars($missing['title'] ?? '') ?></span>
                        <span class="text-right"><?= number_format((float)($missing['value'] ?? 0), 0, ',', ' ') ?> <?= $cur ?></span>
                    </div>
                <?php endforeach; ?>

                <?php foreach ($shortDose as $dose): ?>
                    <div class="grid grid-cols-2 items-center rounded-lg border px-2 py-1 bg-peach">
                        <span class="font-lora leading-none"><?= htmlspecialchars($dose['title'] ?? '') ?></span>
                        <span class="text-right"><?= number_format((float)($dose['value'] ?? 0), 0, ',', ' ') ?> <?= $cur ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
    new Chart(document.getElementById('<?= $chartIncomeLossId ?>'), {
        type: 'bar',
        data: {
            labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
            datasets: [{
                    data: <?= json_encode($shortDatasets[0]['data'] ?? []) ?>,
                    backgroundColor: '#e7e4e4',
                    borderWidth: 0,
                    stack: 'stack',
                    barThickness: 8
                },
                {
                    data: <?= json_encode($shortDatasets[1]['data'] ?? []) ?>,
                    backgroundColor: '#6E4525',
                    borderWidth: 0,
                    stack: 'stack',
                    barThickness: 8
                },
                {
                    data: <?= json_encode($shortDatasets[2]['data'] ?? []) ?>,
                    backgroundColor: '#F0D3BC',
                    borderWidth: 0,
                    stack: 'stack',
                    barThickness: 8
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
</script>