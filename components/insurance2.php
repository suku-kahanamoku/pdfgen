<?php
// ============================================================
// GOAL STRIP – CONTROLLER
// ============================================================
$goal = $rawData['goal'] ?? [];

$goalTitle      = 'A teď společně naplníme vaše sny!';
$goalItems      = $goal['rows'] ?? [];

$goalSummary      = $goal['summary'] ?? [];
$goalSummaryIcon  = 'fa-solid fa-bullseye';
$goalMonthly      = number_format((float)($goalSummary['monthly'] ?? 0), 0, ',', ' ');
$goalYield        = number_format((float)($goalSummary['percent'] ?? 0), 2, '.', '');
$goalSummaryLine1 = 'Stačí pouze ' . $goalMonthly . ' Kč' . "\n" . 'měsíčně s výnosem ' . $goalYield . ' %';
$goalSummaryLine2 = "...a Vaše sny se stanou\nskutečností!";

$goalCur = $curMap[$goal['currency'] ?? 'CZK'] ?? 'Kč';

$goalIconMap = [
    'car'      => 'fa-solid fa-car',
    'plane'    => 'fa-solid fa-plane-departure',
    'piggy'    => 'fa-solid fa-piggy-bank',
    'house'    => 'fa-solid fa-house',
    'heart'    => 'fa-solid fa-heart',
    'target'   => 'fa-solid fa-bullseye',
    'default'  => 'fa-solid fa-star',
];

$goalPriorityMap = [
    'low'    => 'border-success text-white/90',
    'medium' => 'border-warning text-white/90',
    'high'   => 'border-error text-white/90',
];

$goalTermMap = [
    'short'  => 'Krátkodobý cíl',
    'medium' => 'Střednědobý cíl',
    'long'   => 'Dlouhodobý cíl',
];
?>

<!-- Tmavý spodní blok -->
<div class="w-full box-border bg-surface p-24 pb-16 text-white flex flex-col gap-16 [page-break-before:always] [break-before:page]">
    <!-- Horní část -->
    <div class="flex items-center justify-between gap-6">
        <h2 class="max-w-lg font-lora text-5xl font-semibold tracking-wide text-white">
            <?= htmlspecialchars($goalTitle) ?>
        </h2>

        <div class="flex items-center justify-center text-answer">
            <i class="fa-solid fa-wand-magic-sparkles text-6xl"></i>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-8">
        <?php foreach ($goalItems as $item):
            $iconKey       = $item['icon'] ?? 'default';
            $iconCls       = $goalIconMap[$iconKey] ?? $goalIconMap['default'];
            $itemTitle     = $item['title'] ?? '';
            $itemValue     = (float)($item['value'] ?? 0);
            $itemDate      = $item['date'] ?? '';
            $itemRemaining = $item['remaining'] ?? '';
            $priority      = $item['priority'] ?? 'medium';
            $term          = $item['term'] ?? 'medium';

            $priorityText = $item['priority_label']
                ?? ($priority === 'high' ? 'Vysoká priorita' : ($priority === 'low' ? 'Nízká priorita' : 'Střední priorita'));
            $termText = $item['term_label'] ?? ($goalTermMap[$term] ?? 'Střednědobý cíl');
            $priorityTw = $goalPriorityMap[$priority] ?? 'border-warning text-white/90';
        ?>
            <div class="rounded-2xl bg-white/5 px-5 py-5 shadow-lg">
                <div class="mb-4 text-answer">
                    <i class="<?= $iconCls ?> text-3xl"></i>
                </div>

                <div class="mb-1 font-semibold text-white">
                    <?= htmlspecialchars($itemTitle) ?>
                </div>

                <div class="mb-4 font-lora text-lg text-answer">
                    <?= number_format($itemValue, 0, ',', ' ') ?> <?= $goalCur ?>
                </div>

                <div class="mb-1 text-sm text-white/70">
                    Splnit do <?= htmlspecialchars($itemDate) ?>
                </div>

                <div class="mb-4 text-sm text-white/70">
                    Zbývá <?= htmlspecialchars($itemRemaining) ?>
                </div>

                <div class="mb-2 rounded-md border px-3 py-1.5 text-center text-sm <?= $priorityTw ?>">
                    <?= htmlspecialchars($priorityText) ?>
                </div>

                <div class="rounded-md border border-white/30 px-3 py-1.5 text-center text-sm text-white/90">
                    <?= htmlspecialchars($termText) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Spodní část -->
    <div class="grid grid-cols-[260px_1fr] gap-10 items-stretch">
        <!-- Levá info karta -->
        <div class="rounded-3xl bg-white/5 px-6 py-6 shadow-lg flex flex-col justify-center">
            <div class="mb-6 text-answer">
                <i class="<?= htmlspecialchars($goalSummaryIcon) ?> text-4xl"></i>
            </div>

            <div class="font-lora text-xl font-semibold text-white">
                <?= nl2br(htmlspecialchars($goalSummaryLine1)) ?>
            </div>

            <div class="mt-4 font-lora text-xl font-semibold text-answer">
                <?= nl2br(htmlspecialchars($goalSummaryLine2)) ?>
            </div>
        </div>

        <!-- Pravý graf jako obrázek -->
        <?php include __DIR__ . '/info-graph2.php'; ?>
    </div>
</div>