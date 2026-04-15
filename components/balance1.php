<?php
// ============================================================
// FINANCE PAGE – CONTROLLER
// ============================================================
$balanceExpense       = $balance['expense'] ?? [];
$expenseAmount = (float)($balanceExpense['amount'] ?? 0);
$expenseMin    = (float)($balanceExpense['min']    ?? 0);
$cur           = $curMap[$balanceExpense['currency'] ?? 'CZK'] ?? 'Kč';

$expenseCategories = $balanceExpense['categories'] ?? [];

$expenseFooter        = $balanceExpense['footer'] ?? [];
$footerPercent = $expenseFooter['percent'] ?? 0;
$footerStatus  = $expenseFooter['status']  ?? 'success';

$colorPalette = ['peach', 'caramel', 'walnut', 'chestnut', 'umber'];
?>

<!-- ============================================================ -->
<!-- FINANCE PAGE – Výdaje                                        -->
<!-- ============================================================ -->
<div class="w-full box-border p-24 [page-break-after:always] [break-after:page] overflow-visible flex flex-col gap-16 [box-decoration-break:clone]">

    <!-- Nadpis -->
    <h2 class="font-lora text-6xl font-semibold leading-none text-ink">
        Výdaje
    </h2>

    <!-- Intro -->
    <div class="max-w-5xl">
        <p class="leading-relaxed text-ink/70">
            Výdaje jsou nedílnou součástí každodenního života a jejich správné řízení je klíčové pro finanční stabilitu a pohodu. Správné plánování a kontrolování výdajů umožňuje lépe hospodařit s penězi, předcházet dluhům a dosáhnout finančních cílů.
        </p>
    </div>

    <!-- Hlavní obsah -->
    <div class="flex gap-12 items-stretch">

        <!-- Levá část – tabulka výdajů -->
        <div class="flex-1 flex flex-col">

            <!-- Hlavička sloupců -->
            <div class="flex gap-8 py-2 text-ink/60">
                <div class="w-28"></div>
                <div class="flex-1 grid grid-cols-[80px_1fr_1fr]">
                    <div></div>
                    <div class="text-right">Běžná Částka</div>
                    <div class="text-right">Minimum</div>
                </div>
            </div>

            <!-- Kategorie -->
            <?php foreach ($expenseCategories as $ci => $cat):
                $rows     = $cat['rows'] ?? [];
                $catColor = $colorPalette[$ci] ?? 'sand';
                $catTitle = $cat['title'] ?? '';
                if (empty($rows)) continue;
            ?>
                <div class="flex gap-8 [page-break-inside:avoid] [break-inside:avoid]">
                    <div class="w-28 pt-2">
                        <div class="inline-flex w-28 items-center justify-center rounded-lg bg-<?= $catColor ?> px-4 py-2 leading-none text-sm <?= $catColor === 'peach' ? 'text-ink' : 'text-white' ?>">
                            <?= htmlspecialchars($catTitle) ?>
                        </div>
                    </div>
                    <div class="flex-1 mb-10">
                        <?php foreach ($rows as $idx => $row):
                            $isLast = ($idx === count($rows) - 1);
                        ?>
                            <div class="grid grid-cols-[80px_1fr_1fr] items-center border-b <?= $idx === 0 ? 'border-t' : '' ?> border-mist py-2 text-ink/70">
                                <div class="whitespace-nowrap"><?= htmlspecialchars($row['title'] ?? '') ?></div>
                                <div class="text-right"><?= number_format((float)($row['amount'] ?? 0), 0, ',', ' ') ?> <?= $cur ?></div>
                                <div class="text-right"><?= number_format((float)($row['min'] ?? 0), 0, ',', ' ') ?> <?= $cur ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Celkem -->
            <div class="mt-2">
                <div class="grid grid-cols-[1fr_120px_120px] items-center border border-taupe rounded-lg px-4 py-3">
                    <div class="font-lora text-lg font-semibold text-primary">Celkem</div>
                    <div class="text-right font-lora text-lg font-semibold text-ink"><?= number_format($expenseAmount, 0, ',', ' ') ?> <?= $cur ?></div>
                    <div class="text-right font-lora text-lg font-semibold text-ink"><?= number_format($expenseMin, 0, ',', ' ') ?> <?= $cur ?></div>
                </div>
            </div>
        </div>

        <!-- Pravá část – HTML stacked bar -->
        <div class="w-52 flex-shrink-0 flex flex-col pt-10">
            <div class="flex-1 rounded-2xl overflow-hidden flex flex-col">
                <?php
                $expenseTotal = $expenseAmount;
                $catCount    = count($expenseCategories);
                foreach ($expenseCategories as $ci => $cat):
                    $catTotal = 0;
                    foreach ($cat['rows'] ?? [] as $row) $catTotal += (float)($row['amount'] ?? 0);
                    $pct      = $expenseTotal > 0 ? round($catTotal / $expenseTotal * 100, 1) : 0;
                    $catColor = $colorPalette[$ci] ?? 'sand';
                    $isFirst  = ($ci === 0);
                    $isLast   = ($ci === $catCount - 1);
                    $rounding = $isFirst ? 'rounded-t-2xl' : ($isLast ? 'rounded-b-2xl' : '');
                ?>
                    <div class="bg-<?= $catColor ?> <?= $rounding ?> flex flex-col justify-start px-3 pt-4 pb-2 overflow-hidden"
                        style="height: <?= $pct ?>%;">
                        <div class="text-<?= $catColor === 'peach' ? 'ink' : 'white' ?> font-semibold leading-none"><?= number_format($pct, 1, ',', ' ') ?> %</div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-10">
        <?php if ($footerStatus === 'success'): ?>
            <div class="bg-green-50 border border-success -ml-24 pl-24 max-w-2xl rounded-r-xl px-5 py-4 flex flex-col gap-4">
                <div class="flex items-center justify-between gap-4">
                    <div class="font-semibold font-lora text-2xl text-ink">Poměr mezi běžnými a minimálními náklady je vyrovnaný</div>
                    <div class="rounded-xl px-3 py-3 font-semibold flex-shrink-0 text-white bg-success"><?= number_format($footerPercent, 0, ',', ' ') ?>%</div>
                </div>
                <div class="text-ink/70">Vaše běžné a minimální náklady jsou velmi podobné.</div>
            </div>
        <?php else: ?>
            <div class="bg-red-50 border border-error -ml-24 pl-24 max-w-2xl rounded-r-xl px-5 py-4 flex flex-col gap-4">
                <div class="flex items-center justify-between gap-4">
                    <div class="font-semibold font-lora text-2xl text-ink">Pozor! Vysoké minimální náklady</div>
                    <div class="rounded-xl px-3 py-3 font-semibold flex-shrink-0 text-white bg-error"><?= number_format($footerPercent, 0, ',', ' ') ?>%</div>
                </div>
                <div class="text-ink/70">Vaše běžné a minimální náklady jsou velmi podobné. To může být problém, pokud v životě nastanou negativní nečekávané události.</div>
            </div>
        <?php endif; ?>
    </div>
</div>