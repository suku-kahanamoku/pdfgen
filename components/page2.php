<!-- ============================================================ -->
<!-- PAGE 2 – Aktiva & Pasiva                                     -->
<!-- ============================================================ -->
<div class="w-full min-h-[257mm] px-3 py-2 box-border bg-white [page-break-after:always] [break-after:page] overflow-visible flex flex-col gap-16">
    <?php
    $p2StatusMap = [
        'success' => ['cls' => 'fa-check',       'clr' => '#2ecc71'],
        'warning' => ['cls' => 'fa-exclamation', 'clr' => '#e67e22'],
        'danger'  => ['cls' => 'fa-exclamation', 'clr' => '#e74c3c'],
    ];
    $p2Sections = [
        ['key' => 'estate',     'title' => 'Nemovitosti',     'icon' => 'fa-house'],
        ['key' => 'active',     'title' => 'Finanční aktiva', 'icon' => 'fa-money-bill-1'],
        ['key' => 'properties', 'title' => 'Movitý majetek',  'icon' => 'fa-car'],
    ];
    ?>
    <?php foreach ($p2Sections as $sec):
        $rows = $property[$sec['key']]['rows'] ?? [];
        if (empty($rows)) continue;
    ?>
        <div class="[page-break-inside:avoid] [break-inside:avoid] flex flex-col gap-8">
            <div class="flex items-center gap-4 text-[#927355] font-lora text-3xl border-b border-[#eee] pb-2.5">
                <i class="fa-solid <?= $sec['icon'] ?>"></i>
                <?= htmlspecialchars($sec['title']) ?>
            </div>
            <div class="flex flex-col gap-6">
                <?php foreach ($rows as $row):
                    $val     = (float)($row['value'] ?? 0);
                    $title   = $row['title'] ?? '';
                    $status  = $row['status'] ?? 'success';
                    $note    = $row['note'] ?? '';
                    $desc    = $row['description'] ?? '';
                    $labels  = $row['labels'] ?? [];
                    $iconCls = $p2StatusMap[$status]['cls'] ?? 'fa-check';
                    $iconClr = $p2StatusMap[$status]['clr'] ?? '#2ecc71';
                ?>
                    <div class="flex items-center gap-6 [page-break-inside:avoid] [break-inside:avoid]">
                        <div class="rounded-full w-8 h-8 flex justify-center items-center flex-shrink-0 border"
                            style="color: <?= $iconClr ?>; border-color: <?= $iconClr ?>;">
                            <i class="fa-solid <?= $iconCls ?>"></i>
                        </div>
                        <div class="flex-1 bg-white border border-[#f0f0f0] rounded-xl px-4 py-3.5 flex gap-4 items-center">
                            <div class="bg-[#f8f8f8] px-3.5 py-2.5 rounded-lg min-w-36 flex-shrink-0 flex flex-col gap-0.5">
                                <div class="text-base text-[#927355] font-lora"><?= format_czk($val) ?> <?= $cur ?></div>
                                <div class="text-[#777] font-semibold uppercase"><?= htmlspecialchars($title) ?></div>
                            </div>
                            <div class="flex-1 min-w-0 flex flex-col gap-1">
                                <div class="font-bold text-[#333] whitespace-nowrap overflow-hidden text-ellipsis"><?= htmlspecialchars($note) ?></div>
                                <div class="text-[#888]"><?= htmlspecialchars($desc) ?></div>
                            </div>
                            <div class="flex flex-col gap-1.5 items-end w-44 flex-shrink-0">
                                <?php foreach ($labels as $lbl): ?>
                                    <div class="text-xs uppercase border border-[#d4c4b5] px-2 py-1 rounded-md text-[#927355] text-center w-full box-border whitespace-nowrap"><?= htmlspecialchars($lbl) ?></div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Bilance majetku -->
    <?php
    $bilFooter  = $bilance['footer'] ?? [];
    $bilPercent = $bilFooter['percent'] ?? 0;
    $bilStatus  = $bilFooter['status'] ?? 'success';
    $bilFootClr = ($bilStatus === 'success') ? '#2ecc71' : (($bilStatus === 'warning') ? '#e67e22' : '#e74c3c');
    ?>
    <div class="[page-break-inside:avoid] [break-inside:avoid] flex flex-col gap-6">
        <div class="flex gap-8 items-center">
            <!-- Bar chart -->
            <div class="flex-1 flex items-center justify-center">
                <div style="width:340px; height:340px; position:relative;">
                    <canvas id="chart-bilance-p2"></canvas>
                </div>
                <script>
                    new Chart(document.getElementById('chart-bilance-p2'), {
                        type: 'bar',
                        data: {
                            labels: [
                                '<?= htmlspecialchars($bilance['active']['title'] ?? 'Aktiva') ?>',
                                '<?= htmlspecialchars($bilance['pasive']['title'] ?? 'Pasiva') ?>'
                            ],
                            datasets: [{
                                data: [<?= $total_active ?>, <?= $total_pasive ?>],
                                backgroundColor: ['#ededed', '#8d6e53'],
                                borderRadius: 6,
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
                            },
                            scales: {
                                y: {
                                    display: false,
                                    beginAtZero: true
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        font: {
                                            size: 11
                                        }
                                    }
                                }
                            }
                        }
                    });
                </script>
            </div>
            <!-- Table -->
            <div class="flex-1 flex flex-col gap-4">
                <h2 class="font-lora text-2xl text-[#333] mb-4">Bilance majetku</h2>
                <div class="flex flex-col gap-2">
                    <div class="flex justify-between px-3.5 py-2.5 border border-[#ddd] rounded-lg">
                        <span><?= htmlspecialchars($bilance['active']['title'] ?? 'Aktiva') ?></span>
                        <span class="font-bold"><?= format_czk($total_active) ?> <?= $cur ?></span>
                    </div>
                    <div class="flex justify-between px-3.5 pt-1 pb-2.5 text-[#888]">
                        <span><?= htmlspecialchars($bilance['active']['yeld']['title'] ?? '') ?></span>
                        <span><?= number_format($bilance['active']['yeld']['percent'] ?? 0, 2, ',', ' ') ?> %</span>
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    <div class="flex justify-between px-3.5 py-2.5 border border-[#ddd] rounded-lg">
                        <span><?= htmlspecialchars($bilance['pasive']['title'] ?? 'Pasiva') ?></span>
                        <span class="font-bold"><?= format_czk($total_pasive) ?> <?= $cur ?></span>
                    </div>
                    <div class="flex justify-between px-3.5 pt-1 pb-2.5 text-[#888]">
                        <span><?= htmlspecialchars($bilance['pasive']['yeld']['title'] ?? '') ?></span>
                        <span><?= number_format($bilance['pasive']['yeld']['percent'] ?? 0, 2, ',', ' ') ?> %</span>
                    </div>
                </div>
                <div class="flex justify-between px-3.5 py-3 bg-[#8d6e53] text-white rounded-lg font-bold">
                    <span><?= htmlspecialchars($bilance['netto']['title'] ?? 'Čistý majetek') ?></span>
                    <span><?= format_czk($cisty_majetek) ?> <?= $cur ?></span>
                </div>
            </div>
        </div>
        <!-- Footer row -->
        <div class="flex gap-4 items-stretch">
            <div class="flex-[3] bg-[#fff5f5] border border-[#f8d7da] rounded-xl px-5 py-4 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center font-bold text-base flex-shrink-0"
                    style="color: <?= $bilFootClr ?>; background: <?= $bilFootClr ?>22; border: 1px solid <?= $bilFootClr ?>;">
                    <?= number_format($bilPercent, 2, ',', ' ') ?>%
                </div>
                <div class="flex flex-col gap-1">
                    <div class="font-bold text-base">Čistý výnos majetku</div>
                    <div class="text-[#666]">Rozdíl mezi ziskovostí aktiv a nákladovostí pasiv.</div>
                </div>
            </div>
        </div>
    </div>
</div>