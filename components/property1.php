<?php
// ============================================================
// PAGE 1 – CONTROLLER
// ============================================================
$propertySummary = $property['summary'] ?? [];

$summaryNetto          = $propertySummary['netto'] ?? [];
$nettoValue            = (float)($summaryNetto['value']   ?? 0);
$nettoTotal            = (float)($summaryNetto['total']   ?? 0);
$nettoPercent          = isset($summaryNetto['percent'])
    ? (int)$summaryNetto['percent']
    : ($nettoTotal > 0 ? (int)round($nettoValue / $nettoTotal * 100) : 0);
$nettoPercentRemainder = 100 - $nettoPercent;
$cur                   = $curMap[$summaryNetto['currency'] ?? 'CZK'] ?? 'Kč';

$statusIconMap = [
    'success' => ['icon' => 'fa-solid fa-check',       'cls' => 'text-success border-success'],
    'warning' => ['icon' => 'fa-solid fa-exclamation', 'cls' => 'text-warning border-warning'],
    'danger'  => ['icon' => 'fa-solid fa-xmark',       'cls' => 'text-danger border-danger'],
];

$propertyColumns = [
    [
        'title' => 'Finanční aktiva',
        'icon'  => 'fa-solid fa-money-bill-1',
        'total' => (float)($propertySummary['active']['value'] ?? 0),
        'rows'  => $propertySummary['active']['rows'] ?? [],
    ],
    [
        'title' => 'Nemovitosti',
        'icon'  => 'fa-solid fa-house',
        'total' => (float)($propertySummary['estate']['value'] ?? 0),
        'rows'  => $propertySummary['estate']['rows'] ?? [],
    ],
    [
        'title' => 'Movitý majetek',
        'icon'  => 'fa-solid fa-car',
        'total' => (float)($propertySummary['properties']['value'] ?? 0),
        'rows'  => $propertySummary['properties']['rows'] ?? [],
    ],
];

$colors = ['#254b34', '#eeeeee'];
?>

<!-- ============================================================ -->
<!-- PAGE 1 – Přehled majetku                                     -->
<!-- ============================================================ -->
<div class="w-full box-border p-24 break-after-page box-decoration-clone overflow-visible flex flex-col gap-16">

    <?php EMIT_MARKER(); ?>
    <div class="flex justify-between items-start gap-12">
        <div class="flex-1 flex flex-col gap-6">
            <h2 class="font-lora text-5xl font-semibold leading-[1.1] m-0">
                <span class="text-ink/50">Přehled</span><br>vašeho majetku
            </h2>
            <p class="text-ink/70 leading-relaxed m-0">
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
            <div class="w-full box-border px-3 py-3 rounded-lg font-semibold text-white bg-secondary font-lora flex justify-between items-center gap-2">
                <span>Čistá hodnota majetku</span>
                <span class="whitespace-nowrap"><?= number_format($nettoValue, 0, ',', ' ') ?> <?= $cur ?></span>
            </div>
        </div>
    </div>

    <div class="flex gap-8">
        <?php foreach ($propertyColumns as $col): ?>
            <div class="flex-1 min-w-0 flex flex-col gap-8">
                <div class="border border-primary rounded-md py-1 px-2 flex items-center">
                    <div class="text-primary text-3xl w-10 text-center">
                        <i class="<?= $col['icon'] ?>"></i>
                    </div>
                    <div class="ml-auto text-right">
                        <div class="font-semibold text-primary font-lora"><?= $col['title'] ?></div>
                        <div class="text-sm text-ink/70"><?= number_format($col['total'], 0, ',', ' ') ?> <?= $cur ?></div>
                    </div>
                </div>

                <div class="flex flex-col gap-6">
                    <?php foreach ($col['rows'] as $row):
                        $val     = (float)($row['value'] ?? 0);
                        $name    = $row['title'] ?? '';
                        $status  = $row['status'] ?? 'success';
                        $iconCls = $statusIconMap[$status]['icon'] ?? 'fa-solid fa-check';
                        $iconTw  = $statusIconMap[$status]['cls']  ?? 'text-success border-success';
                    ?>
                        <div class="border border-ink/15 p-3 rounded-lg flex items-center gap-4 shadow-sm break-inside-avoid">
                            <div class="rounded-full w-5 h-5 flex justify-center items-center flex-shrink-0 border <?= $iconTw ?>">
                                <i class="<?= $iconCls ?> text-xs"></i>
                            </div>
                            <div class="overflow-hidden">
                                <div class="text-ink/70 overflow-hidden text-ellipsis"><?= htmlspecialchars($name) ?></div>
                                <div class="font-normal"><?= number_format($val, 0, ',', ' ') ?> <?= $cur ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php if (empty($col['rows'])): ?>
                        <div class="text-ink/35 text-center py-5">Žádné položky</div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    new Chart(document.getElementById('chart-donut-p1'), {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [<?= $nettoPercent ?>, <?= $nettoPercentRemainder ?>],
                backgroundColor: [<?= implode(',', array_map(fn($c) => "'$c'", $colors)) ?>],
                borderWidth: 0
            }]
        },
        options: {
            animation: false,
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
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