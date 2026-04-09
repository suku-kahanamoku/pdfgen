<!-- ============================================================ -->
<!-- PAGE 1 – Přehled majetku                                     -->
<!-- ============================================================ -->
<div class="w-full px-3 py-2 box-border bg-white [page-break-after:always] [break-after:page] overflow-visible flex flex-col gap-16">
    <div class="flex justify-between items-start gap-12">
        <div class="flex-1 flex flex-col gap-6">
            <h1 class="font-lora text-5xl font-semibold leading-[1.1] m-0">
                <span class="text-[#8c8c8c]">Přehled</span><br>vašeho majetku
            </h1>
            <p class="text-[#666] leading-relaxed m-0">
                Diverzifikace příjmů, například prostřednictvím vedlejších příjmů
                nebo investic, může zvýšit naši finanční bezpečnost. Když
                přemýšlíme o budoucnosti a strategicky investujeme, zajišťujeme
                si lepší životní úroveň a klidnou mysl. Důležité je také udržovat
                si přehled o svých příjmech a pravidelně přehodnocovat své
                finanční cíle.
            </p>
        </div>
        <div class="w-80 text-center flex flex-col items-center flex-shrink-0 gap-8 self-center">
            <div class="w-48 h-48 flex-shrink-0">
                <canvas id="chart-donut-p1"></canvas>
            </div>
            <script>
                new Chart(document.getElementById('chart-donut-p1'), {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [<?= $donut_pct_active ?>, <?= $donut_pct_estate ?>, <?= $donut_pct_properties ?>],
                            backgroundColor: ['#936746', '#BD8D66', '#eeeeee'],
                            borderWidth: 0
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
                        }
                    }
                });
            </script>
            <div class="w-full box-border px-3 py-2.5 rounded-lg font-semibold text-white bg-primary font-lora flex justify-between items-center gap-2">
                <span>Čistá hodnota majetku</span>
                <span class="whitespace-nowrap"><?= number_format($cisty_majetek, 0, ',', ' ') ?> <?= $cur ?></span>
            </div>
        </div>
    </div>

    <!-- 3-column property overview -->
    <?php
    $summary = $dataRaw['summary'] ?? [];

    $statusIconMap = [
        'success' => ['cls' => 'fa-solid fa-check',       'tw' => 'text-success border-success'],
        'warning' => ['cls' => 'fa-solid fa-exclamation', 'tw' => 'text-warning border-warning'],
        'danger'  => ['cls' => 'fa-solid fa-xmark',       'tw' => 'text-danger border-danger'],
    ];

    $propertyColumns = [
        [
            'title' => 'Finanční aktiva',
            'icon'  => 'fa-solid fa-money-bill-1',
            'total' => (float)($summary['active']['value'] ?? 0),
            'rows'  => $summary['active']['rows'] ?? [],
        ],
        [
            'title' => 'Nemovitosti',
            'icon'  => 'fa-solid fa-house',
            'total' => (float)($summary['estate']['value'] ?? 0),
            'rows'  => $summary['estate']['rows'] ?? [],
        ],
        [
            'title' => 'Movitý majetek',
            'icon'  => 'fa-solid fa-car',
            'total' => (float)($summary['properties']['value'] ?? 0),
            'rows'  => $summary['properties']['rows'] ?? [],
        ],
    ];
    ?>
    <div class="flex gap-8">
        <?php foreach ($propertyColumns as $col): ?>
            <div class="flex-1 min-w-0 flex flex-col gap-8">
                <div class="border border-primary rounded-md py-1 px-2 flex items-center">
                    <div class="text-primary text-3xl w-10 text-center">
                        <i class="<?= $col['icon'] ?>"></i>
                    </div>
                    <div class="ml-auto text-right">
                        <div class="font-bold text-primary font-lora"><?= htmlspecialchars($col['title']) ?></div>
                        <div class="text-sm text-[#666]"><?= format_czk($col['total']) ?> <?= $cur ?></div>
                    </div>
                </div>

                <div class="flex flex-col gap-6">
                    <?php foreach ($col['rows'] as $row):
                        $val     = (float)($row['value'] ?? 0);
                        $name    = $row['title'] ?? '';
                        $status  = $row['status'] ?? 'success';
                        $iconCls = $statusIconMap[$status]['cls'] ?? 'fa-solid fa-check';
                        $iconTw  = $statusIconMap[$status]['tw']  ?? 'text-success border-success';
                    ?>
                        <div class="bg-white border border-[#f0f0f0] p-3 rounded-lg flex items-center gap-4 shadow-sm [page-break-inside:avoid] [break-inside:avoid]">
                            <div class="rounded-full w-5 h-5 flex justify-center items-center flex-shrink-0 border <?= $iconTw ?>">
                                <i class="<?= $iconCls ?> text-[10px] leading-none"></i>
                            </div>
                            <div class="overflow-hidden">
                                <div class="text-[#888] whitespace-nowrap overflow-hidden text-ellipsis"><?= htmlspecialchars($name) ?></div>
                                <div class="font-normal"><?= format_czk($val) ?> <?= $cur ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php if (empty($col['rows'])): ?>
                        <div class="text-[#aaa] text-center py-5">Žádné položky</div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>