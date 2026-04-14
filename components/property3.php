<?php
// ============================================================
// PAGE 3 – CONTROLLER
// ============================================================
$p3Sections = [
    [
        'key'   => 'horizon',
        'title' => 'Investiční horizont',
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

$p3StatusMap = [
    'success' => ['hex' => '#ebb081', 'cls' => 'border-warning'],
    'warning' => ['hex' => '#936746', 'cls' => 'border-primary'],
    'danger'  => ['hex' => '#E5E5E5', 'cls' => 'border-ink/20'],
];


$summary    = $property['summary'] ?? [];
$p3Netto    = $summary['netto'] ?? [];
$p3TotalPct = (int)($p3Netto['percent'] ?? 0);
$cur        = $curMap[$summary['netto']['currency'] ?? 'CZK'] ?? 'Kč';

// Precompute chart data for all sections (property.horizon, .active_pasive, .liquidity)
$p3ChartsData = [];
foreach ($p3Sections as $p3sec) {
    $rows   = $property[$p3sec['key']]['rows'] ?? [];
    $data   = [];
    $colors = [];
    foreach ($rows as $r) {
        $data[]   = (float)($r['value'] ?? 0);
        $colors[] = $p3StatusMap[$r['status'] ?? 'success']['hex'] ?? '#936746';
    }
    $p3ChartsData[$p3sec['key']] = ['data' => $data, 'colors' => $colors];
}
?>

<!-- ============================================================ -->
<!-- PAGE 3 – Analýza portfolia                                   -->
<!-- ============================================================ -->
<div class="w-full box-border p-24 [page-break-after:always] [break-after:page] [box-decoration-break:clone] overflow-visible flex flex-col gap-16">
    <?php foreach ($p3Sections as $p3sec):
        $p3Rows    = $property[$p3sec['key']]['rows'] ?? [];
        $p3Total   = array_sum(array_column($p3Rows, 'value'));
        if ($p3Total <= 0) $p3Total = 1;
        $p3ChartId = 'chart-p3-' . $p3sec['key'];
    ?>
        <div class="[page-break-inside:avoid] [break-inside:avoid] flex flex-col gap-8">
            <h3 class="font-lora text-4xl font-semibold"><?= htmlspecialchars($p3sec['title']) ?></h3>
            <div class="flex gap-8 items-center">
                <div class="flex-1 flex flex-col gap-8">
                    <div class="text-ink/70 leading-relaxed"><?= htmlspecialchars($p3sec['desc']) ?></div>
                    <div class="flex flex-col gap-4">
                        <?php foreach ($p3Rows as $p3Row):
                            $p3Val = (float)($p3Row['value'] ?? 0);
                            $p3Tw  = $p3StatusMap[$p3Row['status'] ?? 'success']['cls'] ?? 'border-primary';
                        ?>
                            <div class="flex justify-between items-center px-3 py-2 rounded-lg border text-sm <?= $p3Tw ?>">
                                <span class="font-semibold"><?= htmlspecialchars($p3Row['title'] ?? '') ?></span>
                                <span><?= number_format($p3Val, 0, ',', ' ') ?> <?= $cur ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div style="width:160px; height:160px; position:relative; flex-shrink:0;">
                    <canvas id="<?= $p3ChartId ?>"></canvas>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Footer row -->
    <div class="grid grid-cols-[1fr_220px] gap-8 items-end">
        <div class="bg-green-50 border border-success text-ink -ml-24 pl-24 max-w-2xl rounded-r-xl px-6 py-4 flex flex-col gap-1">
            <div class="flex items-center justify-between gap-4">
                <div class="text-lg font-semibold">Diverzifikace portfolia</div>
                <div class="rounded-xl px-3 py-1 font-semibold flex-shrink-0 text-white bg-success"><?= $p3TotalPct ?>%</div>
            </div>
            <div class="leading-relaxed text-ink/70">Dobře diverzifikované portfolio rozložené napříč horizonty, aktivy i likviditou snižuje celkové riziko a zvyšuje stabilitu dlouhodobého výnosu.</div>
        </div>

        <div class="flex items-center justify-end text-primary/90">
            <?php include __DIR__ . '/trophy.php'; ?>
        </div>
    </div>
</div>

<script>
    <?php foreach ($p3Sections as $p3sec):
        $p3ChartId = 'chart-p3-' . $p3sec['key'];
        $chartData = $p3ChartsData[$p3sec['key']];
    ?>
        new Chart(document.getElementById('<?= $p3ChartId ?>'), {
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