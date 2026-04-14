<?php
// ============================================================
// PAGE 2 – CONTROLLER
// ============================================================
$summary            = $property['summary'] ?? [];
$propBalance        = $property['bilance'] ?? [];
$propBalanceFooter  = $propBalance['footer'] ?? [];
$propBalancePercent = $propBalanceFooter['percent'] ?? 0;
$propBalanceStatus  = $propBalanceFooter['status'] ?? 'success';
$netWorth           = (float)($summary['netto']['value'] ?? 0);
$netTotal           = (float)($summary['netto']['total'] ?? 0);
$totalActive        = (float)($propBalance['active']['value'] ?? 0);
$totalPassive       = (float)($propBalance['pasive']['value'] ?? 0);
$cur                = $curMap[$summary['netto']['currency'] ?? 'CZK'] ?? 'Kč';

$p2StatusMap = [
    'success' => ['icon' => 'fa-solid fa-check',       'cls' => 'text-success border-success'],
    'warning' => ['icon' => 'fa-solid fa-exclamation', 'cls' => 'text-warning border-warning'],
    'danger'  => ['icon' => 'fa-solid fa-xmark',       'cls' => 'text-danger  border-danger'],
];

$p2Sections = [
    ['key' => 'estate',     'title' => 'Nemovitosti',     'icon' => 'fa-solid fa-house',        'desc' => 'Přehled vašich nemovitostí – vlastní bydlení, investiční objekty a související závazky jako hypotéky. Zahrnuje také tržní ocenění a míru zadluženosti.'],
    ['key' => 'active',     'title' => 'Finanční aktiva', 'icon' => 'fa-solid fa-money-bill-1', 'desc' => 'Finanční nástroje a investice – hotovost, cenné papíry, deriváty, kryptoměny a další likvidní aktiva. Zobrazuje celkovou hodnotu portfolia a jeho výnosnost.'],
    ['key' => 'properties', 'title' => 'Movitý majetek',  'icon' => 'fa-solid fa-car',          'desc' => 'Hmotný majetek movité povahy – vozidla, stroje, vybavení a případné spotřebitelské úvěry s nimi spojené. Reflektuje aktuální zůstatkovou hodnotu majetku.'],
];

$p2ChartLabel1 = htmlspecialchars($propBalance['active']['title'] ?? 'Aktiva');
$p2ChartLabel2 = htmlspecialchars($propBalance['pasive']['title'] ?? 'Pasiva');
$p2BarColors   = ['#e7e4e4', '#936746'];
?>

<!-- ============================================================ -->
<!-- PAGE 2 – Aktiva & Pasiva                                     -->
<!-- ============================================================ -->
<div class="w-full box-border p-24 [page-break-after:always] [break-after:page] overflow-visible flex flex-col gap-16 [box-decoration-break:clone]">
    <?php foreach ($p2Sections as $sec):
        $rows = $property[$sec['key']]['rows'] ?? [];
        if (empty($rows)) continue;
    ?>
        <div class="[page-break-inside:avoid] [break-inside:avoid] flex flex-col gap-8">
            <h3 class="flex items-center gap-4 font-lora text-4xl font-semibold">
                <?= htmlspecialchars($sec['title']) ?>
                <i class="text-primary <?= $sec['icon'] ?>"></i>
            </h3>
            <div class="text-ink/70"><?= htmlspecialchars($sec['desc']) ?></div>
            <div class="flex flex-col gap-7">
                <?php foreach ($rows as $row):
                    $val     = (float)($row['value'] ?? 0);
                    $title   = $row['title'] ?? '';
                    $status  = $row['status'] ?? 'success';
                    $note    = $row['note'] ?? '';
                    $desc    = $row['description'] ?? '';
                    $labels  = $row['labels'] ?? [];
                    $iconCls = $p2StatusMap[$status]['icon'] ?? 'fa-check';
                    $iconTw  = $p2StatusMap[$status]['cls']  ?? 'text-success border-success';
                ?>
                    <div class="flex items-center gap-6 [page-break-inside:avoid] [break-inside:avoid]">
                        <div class="rounded-full w-8 h-8 flex justify-center items-center flex-shrink-0 border <?= $iconTw ?>">
                            <i class="<?= $iconCls ?>"></i>
                        </div>
                        <div class="flex-1 border border-ink/15 rounded-xl px-4 py-3 flex gap-4 items-center shadow-sm">
                            <div class="bg-ink/5 px-3 py-2 rounded-lg w-48 flex-shrink-0 flex flex-col gap-1">
                                <div class="text-primary text-lg font-lora"><?= number_format($val, 0, ',', ' ') ?> <?= $cur ?></div>
                                <div class="text-xs"><?= htmlspecialchars($note) ?></div>
                            </div>
                            <div class="flex-1 min-w-0 flex flex-col gap-1">
                                <div class="font-semibold font-lora whitespace-nowrap overflow-hidden text-ellipsis"><?= htmlspecialchars($title) ?></div>
                                <div class="text-xs font-lora"><?= htmlspecialchars($desc) ?></div>
                            </div>
                            <?php $lblCount = count($labels); ?>
                            <div class="<?= $lblCount > 3 ? 'grid grid-cols-2' : 'flex flex-col' ?> gap-1 min-w-72 flex-shrink-0">
                                <?php foreach ($labels as $i => $lbl): ?>
                                    <div class="text-xs border border-primary/40 px-2 py-1.5 rounded-md text-center w-full box-border whitespace-nowrap <?= ($lblCount > 3 && $lblCount % 2 === 1 && $i === $lblCount - 1) ? 'col-span-2' : '' ?>"><?= htmlspecialchars($lbl) ?></div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Bilance majetku -->
    <div class="[page-break-inside:avoid] [break-inside:avoid] flex flex-col gap-6">
        <div class="flex gap-8 items-stretch w-full">
            <!-- Bar chart -->
            <div class="flex-1 flex items-stretch bg-paper rounded-3xl p-6 min-h-[340px]">
                <div class="rounded-2xl flex-1">
                    <canvas id="chart-bilance-p2"></canvas>
                </div>
            </div>

            <!-- Pravý panel -->
            <div class="w-80 flex flex-col gap-8 justify-end">
                <h3 class="font-lora text-3xl font-semibold">Bilance majetku</h3>
                <div class="flex flex-col gap-3">
                    <div class="flex items-start justify-between rounded-lg border border-primary/40 px-4 py-2 font-lora font-semibold">
                        <span class="font-lora font-semibold text-primary"><?= htmlspecialchars($propBalance['active']['title'] ?? 'Aktiva') ?></span>
                        <span><?= number_format($totalActive, 0, ',', ' ') ?> <?= $cur ?></span>
                    </div>
                    <div class="flex items-start justify-between gap-4 border-b border-mist px-4 pb-3 text-ink/75">
                        <span><?= htmlspecialchars($propBalance['active']['yeld']['title'] ?? '') ?></span>
                        <span><?= number_format($propBalance['active']['yeld']['percent'] ?? 0, 2, ',', ' ') ?> %</span>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <div class="flex items-start justify-between rounded-lg border border-primary/40 px-4 py-2 font-lora font-semibold">
                        <span class="font-lora font-semibold text-primary"><?= htmlspecialchars($propBalance['pasive']['title'] ?? 'Pasiva') ?></span>
                        <span><?= number_format($totalPassive, 0, ',', ' ') ?> <?= $cur ?></span>
                    </div>
                    <div class="flex items-start justify-between gap-4 border-b border-mist px-4 pb-3 text-ink/75">
                        <span><?= htmlspecialchars($propBalance['pasive']['yeld']['title'] ?? '') ?></span>
                        <span><?= number_format($propBalance['pasive']['yeld']['percent'] ?? 0, 2, ',', ' ') ?> %</span>
                    </div>
                </div>

                <div class="mt-2 flex items-center justify-between rounded-lg bg-primary px-4 py-3 font-lora font-semibold text-white">
                    <span><?= htmlspecialchars($propBalance['netto']['title'] ?? 'Čistý majetek') ?></span>
                    <span><?= number_format($netWorth, 0, ',', ' ') ?> <?= $cur ?></span>
                </div>
            </div>
        </div>
    </div><!-- /Bilance majetku -->

    <!-- Footer row -->
    <div class="grid grid-cols-[1fr_220px] gap-8 items-end">
        <?php if ($propBalanceStatus === 'success'): ?>
            <div class="bg-green-50 border border-success -ml-24 pl-24 max-w-2xl rounded-r-xl px-5 py-4 flex flex-col gap-1">
                <div class="flex items-center justify-between gap-4">
                    <div class="font-semibold">Poměr mezi aktivy a pasivy je vyrovnaný</div>
                    <div class="rounded-xl px-3 py-1 font-semibold flex-shrink-0 text-white bg-success"><?= number_format($propBalancePercent, 0, ',', ' ') ?>%</div>
                </div>
                <div class="text-ink/70">Rozdíl mezi ziskovostí aktiv a nákladovostí pasiv.</div>
            </div>
        <?php else: ?>
            <div class="bg-red-50 border border-error -ml-24 pl-24 max-w-2xl rounded-r-xl px-5 py-4 flex flex-col gap-1">
                <div class="flex items-center justify-between gap-4">
                    <div class="font-semibold">Pozor! Vaše pasiva jsou vysoká.</div>
                    <div class="rounded-xl px-3 py-1 font-semibold flex-shrink-0 text-white bg-error"><?= number_format($propBalancePercent, 0, ',', ' ') ?>%</div>
                </div>
                <div class="text-ink/70">Výše vašich aktiv se blíží hodnotě vašich pasiv. Kvůli tomu budete méně odolní v případě tržního výkyvu.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    new Chart(document.getElementById('chart-bilance-p2'), {
        type: 'bar',
        data: {
            labels: ['<?= $p2ChartLabel1 ?>', '<?= $p2ChartLabel2 ?>'],
            datasets: [{
                data: [<?= $totalActive ?>, <?= $totalPassive ?>],
                backgroundColor: [<?= implode(',', array_map(fn($c) => "'$c'", $p2BarColors)) ?>],
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
                    display: true,
                    beginAtZero: true,
                    grid: {
                        display: true,
                        color: '#f0f0f0'
                    },
                    ticks: {
                        stepSize: 500000,
                        color: '#888888',
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