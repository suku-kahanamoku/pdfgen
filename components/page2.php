<!-- ============================================================ -->
<!-- PAGE 2 – Aktiva & Pasiva                                     -->
<!-- ============================================================ -->
<div class="w-full px-3 py-2 box-border bg-white [page-break-after:always] [break-after:page] overflow-visible flex flex-col gap-16">
    <?php
    $p2StatusMap = [
        'success' => ['cls' => 'fa-solid fa-check',       'tw' => 'text-success border-success'],
        'warning' => ['cls' => 'fa-solid fa-exclamation', 'tw' => 'text-warning border-warning'],
        'danger'  => ['cls' => 'fa-solid fa-xmark',       'tw' => 'text-danger  border-danger'],
    ];
    $p2Sections = [
        ['key' => 'estate',     'title' => 'Nemovitosti',     'icon' => 'fa-solid fa-house',        'desc' => 'Přehled vašich nemovitostí – vlastní bydlení, investiční objekty a související závazky jako hypotéky.'],
        ['key' => 'active',     'title' => 'Finanční aktiva', 'icon' => 'fa-solid fa-money-bill-1', 'desc' => 'Finanční nástroje a investice – hotovost, cenné papíry, deriváty, kryptoměny a další likvidní aktiva.'],
        ['key' => 'properties', 'title' => 'Movitý majetek',  'icon' => 'fa-solid fa-car',          'desc' => 'Hmotný majetek movité povahy – vozidla, stroje, vybavení a případné spotřebitelské úvěry s nimi spojené.'],
    ];
    ?>
    <?php foreach ($p2Sections as $sec):
        $rows = $property[$sec['key']]['rows'] ?? [];
        if (empty($rows)) continue;
    ?>
        <div class="[page-break-inside:avoid] [break-inside:avoid] flex flex-col gap-8">
            <div class="flex items-center gap-4 font-lora text-4xl font-semibold">
                <?= htmlspecialchars($sec['title']) ?>
                <i class="text-primary <?= $sec['icon'] ?>"></i>
            </div>
            <div class="text-[#666]"><?= htmlspecialchars($sec['desc']) ?></div>
            <div class="flex flex-col gap-8">
                <?php foreach ($rows as $row):
                    $val     = (float)($row['value'] ?? 0);
                    $title   = $row['title'] ?? '';
                    $status  = $row['status'] ?? 'success';
                    $note    = $row['note'] ?? '';
                    $desc    = $row['description'] ?? '';
                    $labels  = $row['labels'] ?? [];
                    $iconCls = $p2StatusMap[$status]['cls'] ?? 'fa-check';
                    $iconTw  = $p2StatusMap[$status]['tw']  ?? 'text-success border-success';
                ?>
                    <div class="flex items-center gap-6 [page-break-inside:avoid] [break-inside:avoid]">
                        <div class="rounded-full w-8 h-8 flex justify-center items-center flex-shrink-0 border <?= $iconTw ?>">
                            <i class="<?= $iconCls ?>"></i>
                        </div>
                        <div class="flex-1 bg-white border border-[#f0f0f0] rounded-xl px-4 py-3 flex gap-4 items-center shadow-sm">
                            <div class="bg-[#f8f8f8] px-3 py-2 rounded-lg w-48 flex-shrink-0 flex flex-col gap-0.5">
                                <div class="text-base text-primary font-lora"><?= format_czk($val) ?> <?= $cur ?></div>
                                <div class="text-[#777] font-semibold"><?= htmlspecialchars($note) ?></div>
                            </div>
                            <div class="flex-1 min-w-0 flex flex-col gap-1">
                                <div class="font-bold text-[#333] font-lora whitespace-nowrap overflow-hidden text-ellipsis"><?= htmlspecialchars($title) ?></div>
                                <div class="text-[#888] font-lora"><?= htmlspecialchars($desc) ?></div>
                            </div>
                            <div class="flex flex-col gap-1.5 items-end w-44 flex-shrink-0">
                                <?php foreach ($labels as $lbl): ?>
                                    <div class="text-xs border border-primary px-2 py-1 rounded-md text-center w-full box-border whitespace-nowrap"><?= htmlspecialchars($lbl) ?></div>
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
                                backgroundColor: ['#eeeeee', '#936746'],
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
            <div class="flex-1 flex flex-col gap-2">
                <h2 class="font-lora text-3xl font-semibold mb-4">Bilance majetku</h2>
                <div class="flex justify-between px-3 py-2 border border-[#ddd] rounded-lg">
                    <span class="font-lora font-semibold text-primary"><?= htmlspecialchars($bilance['active']['title'] ?? 'Aktiva') ?></span>
                    <span class="font-bold"><?= format_czk($total_active) ?> <?= $cur ?></span>
                </div>
                <div class="flex justify-between px-3 pt-1 pb-2 text-[#888]">
                    <span><?= htmlspecialchars($bilance['active']['yeld']['title'] ?? '') ?></span>
                    <span><?= number_format($bilance['active']['yeld']['percent'] ?? 0, 2, ',', ' ') ?> %</span>
                </div>
                <div class="flex justify-between px-3 py-2 border border-[#ddd] rounded-lg">
                    <span class="font-lora font-semibold text-primary"><?= htmlspecialchars($bilance['pasive']['title'] ?? 'Pasiva') ?></span>
                    <span class="font-bold"><?= format_czk($total_pasive) ?> <?= $cur ?></span>
                </div>
                <div class="flex justify-between px-3 pt-1 pb-2 text-[#888]">
                    <span><?= htmlspecialchars($bilance['pasive']['yeld']['title'] ?? '') ?></span>
                    <span><?= number_format($bilance['pasive']['yeld']['percent'] ?? 0, 2, ',', ' ') ?> %</span>
                </div>
                <div class="flex justify-between px-3 py-2 bg-primary text-white rounded-lg font-bold font-lora">
                    <span><?= htmlspecialchars($bilance['netto']['title'] ?? 'Čistý majetek') ?></span>
                    <span><?= format_czk($cisty_majetek) ?> <?= $cur ?></span>
                </div>
            </div>
        </div>
        <!-- Footer row -->
        <?php if ($bilStatus === 'success'): ?>
            <div class="bg-[#f0faf4] border border-success rounded-xl px-5 py-4 flex items-center gap-4">
                <div class="w-16 h-14 rounded-xl flex items-center justify-center font-bold text-base flex-shrink-0 text-white bg-success">
                    <?= number_format($bilPercent, 2, ',', ' ') ?>%
                </div>
                <div class="flex flex-col gap-1">
                    <div class="font-bold text-base">Čistý výnos majetku</div>
                    <div class="text-[#666]">Rozdíl mezi ziskovostí aktiv a nákladovostí pasiv.</div>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-[#fbf2f2] border border-error rounded-xl px-5 py-4 flex items-center gap-4">
                <div class="w-16 h-14 rounded-xl flex items-center justify-center font-bold text-base flex-shrink-0 text-white bg-error">
                    <?= number_format($bilPercent, 2, ',', ' ') ?>%
                </div>
                <div class="flex flex-col gap-1">
                    <div class="font-bold text-base">Pozor! Vaše pasiva jsou vysoká.</div>
                    <div class="text-[#666]">Výše vašich aktiv se blíží hodnotě vašich pasiv. Kvůli tomu budete méně odolní v případě tržního výkyvu.</div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>