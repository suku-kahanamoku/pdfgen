<!-- ============================================================ -->
<!-- PAGE 3 – Analýza portfolia                                   -->
<!-- ============================================================ -->
<?php
$p3StatusMap = [
    'success' => ['hex' => '#ebb081', 'tw' => 'border-warning'],
    'warning' => ['hex' => '#936746', 'tw' => 'border-primary'],
    'danger'  => ['hex' => '#E5E5E5', 'tw' => 'border-[#E5E5E5]'],
];

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
?>

<div class="w-full px-3 py-2 box-border bg-white [page-break-after:always] [break-after:page] overflow-visible flex flex-col gap-16">
    <?php foreach ($p3Sections as $p3sec):
        $p3rows  = $property[$p3sec['key']]['rows'] ?? [];
        $p3total = array_sum(array_column($p3rows, 'value'));
        if ($p3total <= 0) $p3total = 1;
        $p3chartData   = [];
        $p3chartColors = [];
        foreach ($p3rows as $r) {
            $p3chartData[]   = (float)($r['value'] ?? 0);
            $p3chartColors[] = $p3StatusMap[$r['status'] ?? 'success']['hex'] ?? '#936746';
        }
        $p3chartId = 'chart-p3-' . $p3sec['key'];
    ?>
        <div class="[page-break-inside:avoid] [break-inside:avoid] flex flex-col gap-8">
            <div class="font-lora text-4xl font-semibold"><?= htmlspecialchars($p3sec['title']) ?></div>
            <div class="flex gap-8 items-center">
                <div class="flex-1 flex flex-col gap-8">
                    <div class="text-[#666] leading-relaxed"><?= htmlspecialchars($p3sec['desc']) ?></div>
                    <!-- Rows as colored label badges -->
                    <div class="flex flex-col gap-4">
                        <?php foreach ($p3rows as $p3row):
                            $p3val = (float)($p3row['value'] ?? 0);
                            $p3pct = round($p3val / $p3total * 100);
                            $p3tw  = $p3StatusMap[$p3row['status'] ?? 'success']['tw'] ?? 'border-primary';
                        ?>
                            <div class="flex justify-between items-center px-3 py-2 rounded-lg border text-sm <?= $p3tw ?>">
                                <span class="font-semibold"><?= htmlspecialchars($p3row['title'] ?? '') ?></span>
                                <span><?= format_czk($p3val) ?> <?= $cur ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <!-- Donut chart -->
                <div style="width:160px; height:160px; position:relative; flex-shrink:0;">
                    <canvas id="<?= $p3chartId ?>"></canvas>
                </div>
            </div>
        </div>
        <script>
            new Chart(document.getElementById('<?= $p3chartId ?>'), {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [<?= implode(',', $p3chartData) ?>],
                        backgroundColor: [<?= implode(',', array_map(fn($c) => "'$c'", $p3chartColors)) ?>],
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
        </script>
    <?php endforeach; ?>

    <!-- Diverzifikace box -->
    <?php
    $p3netto     = $dataRaw['summary']['netto'] ?? [];
    $p3total_pct = (int)($p3netto['percent'] ?? 0);
    ?>
    <div class="flex items-center gap-8">
        <div class="bg-primary text-white rounded-3xl px-6 py-4 flex items-center gap-6 flex-1">
            <div class="text-5xl font-bold font-lora flex-shrink-0"><?= $p3total_pct ?>%</div>
            <div class="flex flex-col gap-1 flex-1">
                <div class="text-lg font-bold">Diverzifikace portfolia</div>
                <div class="leading-relaxed">Dobře diverzifikované portfolio rozložené napříč horizonty, aktivy i likviditou snižuje celkové riziko a zvyšuje stabilitu dlouhodobého výnosu.</div>
            </div>
        </div>
        <div class="text-7xl flex-shrink-0 w-48 text-right text-primary">
            <i class="fa-solid fa-trophy"></i>
        </div>
    </div>
</div>