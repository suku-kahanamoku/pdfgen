<?php
// ============================================================
// PAGE 3 – CONTROLLER
// ============================================================
$propertySummary = $property['summary'] ?? [];

$summaryNetto = $propertySummary['netto'] ?? [];
$nettoTotal   = (float)($summaryNetto['total'] ?? 0);
$nettoPercent = isset($summaryNetto['percent'])
    ? (int)$summaryNetto['percent']
    : ($nettoTotal > 0 ? (int)round((float)($summaryNetto['value'] ?? 0) / $nettoTotal * 100) : 0);
$cur          = $curMap[$summaryNetto['currency'] ?? 'CZK'] ?? 'Kč';

$sections = [
    [
        'key'   => 'horizon',
        'title' => 'Horizont',
        'desc'  => 'Investiční horizont vyjadřuje, na jak dlouhou dobu jsou vaše prostředky vázány. Krátkodobé investice nabízejí likviditu, střednědobé větší zhodnocení, dlouhodobé nejvyšší výnosy díky složenému úroku.',
    ],
    [
        'key'   => 'active_pasive',
        'title' => 'Aktiva a pasiva',
        'desc'  => 'Poměr aktiv a pasiv je klíčovým ukazatelem finanční kondice. Aktiva jsou vše, co vlastníte – nemovitosti, investice, hotovost. Pasiva jsou vaše závazky. Zdravá bilance zajišťuje finanční stabilitu.',
    ],
    [
        'key'   => 'liquidity',
        'title' => 'Likvidita',
        'desc'  => 'Likvidita měří, jak rychle lze majetek převést na hotovost. Bankovní účty jsou dostupné okamžitě, nemovitosti mají nízkou likviditu. Vyvážená likvidita portfolia zajišťuje finanční flexibilitu.',
    ],
];

$statusMap = [
    'success' => ['hex' => '#ebb081', 'cls' => 'border-warning'],
    'warning' => ['hex' => '#936746', 'cls' => 'border-primary'],
    'danger'  => ['hex' => '#E5E5E5', 'cls' => 'border-ink/20'],
];


// Precompute chart data for all sections (property.horizon, .active_pasive, .liquidity)
$chartsData = [];
foreach ($sections as $p3sec) {
    $rows   = $property[$p3sec['key']]['rows'] ?? [];
    $data   = [];
    $colors = [];
    foreach ($rows as $r) {
        $data[]   = (float)($r['value'] ?? 0);
        $colors[] = $statusMap[$r['status'] ?? 'success']['hex'] ?? '#936746';
    }
    $chartsData[$p3sec['key']] = ['data' => $data, 'colors' => $colors];
}
?>

<!-- ============================================================ -->
<!-- PAGE 3 – Analýza portfolia                                   -->
<!-- ============================================================ -->
<div class="w-full box-border p-24 break-after-page box-decoration-clone overflow-visible flex flex-col gap-16">

    <?php EMIT_MARKER(); ?>
    <?php foreach ($sections as $p3sec):
        $rows    = $property[$p3sec['key']]['rows'] ?? [];
        $total   = array_sum(array_column($rows, 'value'));
        if ($total <= 0) $total = 1;
        $chartId = 'chart-p3-' . $p3sec['key'];
    ?>
        <div class="break-inside-avoid flex flex-col gap-8">
            <h3 class="font-lora text-4xl font-semibold"><?= $p3sec['title'] ?></h3>
            <div class="flex gap-8 items-center">
                <div class="flex-1 flex flex-col gap-8">
                    <div class="text-ink/70 leading-relaxed"><?= $p3sec['desc'] ?></div>
                    <div class="flex flex-col gap-4">
                        <?php foreach ($rows as $row):
                            $val = (float)($row['value'] ?? 0);
                            $tw  = $statusMap[$row['status'] ?? 'success']['cls'] ?? 'border-primary';
                        ?>
                            <div class="flex justify-between items-center px-3 py-2 rounded-lg border text-sm <?= $tw ?>">
                                <span class="font-semibold"><?= htmlspecialchars($row['title'] ?? '') ?></span>
                                <span><?= number_format($val, 0, ',', ' ') ?> <?= $cur ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="w-52 h-52 relative flex-shrink-0">
                    <canvas id="<?= $chartId ?>"></canvas>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Footer row -->
    <div class="flex gap-8 items-center">
        <div class="flex-1 bg-secondary/10 border border-secondary text-ink -ml-24 pl-24 max-w-2xl rounded-r-xl px-6 py-4 flex flex-col gap-1">
            <div class="flex items-center justify-between gap-4">
                <div class="text-lg font-semibold">Diverzifikace portfolia</div>
                <div class="rounded-xl px-3 py-1 font-semibold flex-shrink-0 text-white bg-secondary"><?= $nettoPercent ?>%</div>
            </div>
            <div class="leading-relaxed text-ink/70">Dobře diverzifikované portfolio rozložené napříč horizonty, aktivy i likviditou snižuje celkové riziko a zvyšuje stabilitu dlouhodobého výnosu.</div>
        </div>

        <div class="w-56 flex-shrink-0 flex items-center justify-end text-primary/90">
        </div>
    </div>
</div>

<script>
    <?php foreach ($sections as $p3sec):
        $chartId = 'chart-p3-' . $p3sec['key'];
        $chartData = $chartsData[$p3sec['key']];
    ?>
        new Chart(document.getElementById('<?= $chartId ?>'), {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [<?= implode(',', $chartData['data']) ?>],
                    backgroundColor: [<?= implode(',', array_map(fn($c) => "'$c'", $chartData['colors'])) ?>],
                    borderWidth: 0
                }]
            },
            options: {
                animation: false,
                cutout: '90%',
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
    <?php endforeach; ?>
</script>