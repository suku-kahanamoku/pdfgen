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
            <div class="w-36 h-36 flex-shrink-0">
                <canvas id="chart-donut-p1" width="140" height="140"></canvas>
            </div>
            <script>
                new Chart(document.getElementById('chart-donut-p1'), {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [<?= $donut_pct_active ?>, <?= $donut_pct_estate ?>, <?= $donut_pct_properties ?>],
                            backgroundColor: ['#927355', '#b89a7a', '#eeeeee'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        animation: false,
                        cutout: '80%',
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
            <div class="w-full box-border px-3 py-2.5 rounded-xl font-bold text-white whitespace-nowrap text-center" style="background: <?= $cisty_majetek_color ?>;">
                Čistá hodnota majetku <?= number_format($cisty_majetek, 0, ',', ' ') ?> <?= $cur ?>
            </div>
        </div>
    </div>

    <!-- 3-column property overview -->
    <?php
    $summary = $dataRaw['summary'] ?? [];

    $statusIconMap = [
        'success' => ['cls' => 'fa-solid fa-check',       'clr' => '#2ecc71'],
        'warning' => ['cls' => 'fa-solid fa-exclamation', 'clr' => '#e67e22'],
        'danger'  => ['cls' => 'fa-solid fa-xmark',       'clr' => '#042444'],
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
                <div class="border border-[#927355] rounded-xl p-3 bg-[#fcfaf8] flex items-center gap-6">
                    <div class="text-[#927355] text-3xl w-10 text-center">
                        <i class="<?= $col['icon'] ?>"></i>
                    </div>
                    <div class="ml-auto text-right">
                        <div class="font-bold text-[#927355] font-lora"><?= htmlspecialchars($col['title']) ?></div>
                        <div class="text-sm text-[#666]"><?= format_czk($col['total']) ?> <?= $cur ?></div>
                    </div>
                </div>

                <div class="flex flex-col gap-6">
                    <?php foreach ($col['rows'] as $row):
                        $val     = (float)($row['value'] ?? 0);
                        $name    = $row['title'] ?? '';
                        $status  = $row['status'] ?? 'success';
                        $iconCls = $statusIconMap[$status]['cls'] ?? 'fa-solid fa-check';
                        $iconClr = $statusIconMap[$status]['clr'] ?? '#2ecc71';
                    ?>
                        <div class="bg-white border border-[#f0f0f0] p-3 rounded-xl flex items-center gap-4 [page-break-inside:avoid] [break-inside:avoid]">
                            <div class="rounded-full w-6 h-6 flex justify-center items-center flex-shrink-0 border"
                                style="color: <?= $iconClr ?>; border-color: <?= $iconClr ?>;">
                                <i class="<?= $iconCls ?>"></i>
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