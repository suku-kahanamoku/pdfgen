<?php
// ============================================================
// ACTION STEPS PAGE – CONTROLLER
// ============================================================
$steps = $goal['steps'] ?? [];
?>

<!-- ============================================================ -->
<!-- ACTION STEPS PAGE – Spodní část bez horního banneru           -->
<!-- ============================================================ -->
<div class="w-full box-border p-24 [page-break-before:always] [break-before:page] [page-break-after:always] [break-after:page] overflow-visible flex flex-col gap-12 [box-decoration-break:clone]">

    <!-- Header row -->
    <div class="grid grid-cols-[1fr_220px] gap-8 items-start">
        <h2 class="font-lora text-5xl font-semibold leading-none tracking-tight text-ink">
            Podíváme se<br>společně jak na to!
        </h2>

        <div class="pt-4 text-right leading-relaxed text-ink/70 self-end">
            Ukážeme vám jak naplnit<br>vaše sny krok za krokem
        </div>
    </div>

    <!-- Timeline + cards -->
    <div class="grid grid-cols-[52px_1fr] gap-8 items-start">
        <!-- Left timeline -->
        <div class="relative flex flex-col items-center min-h-[760px]">
            <div class="w-full text-left font-lora text-2xl font-semibold text-ink">
                2026
            </div>
            <div class="absolute left-8 top-12 bottom-0 border-l border-dashed border-mist"></div>
        </div>

        <!-- Right cards -->
        <div class="flex flex-col gap-7">
            <?php foreach ($steps as $row):
                $val          = (float)($row['value'] ?? 0);
                $note         = $row['note'] ?? '';
                $range        = $row['description'] ?? '';
                $product      = $row['title'] ?? '';
                $labels       = $row['labels'] ?? [];
                $cur          = $curMap[$row['currency'] ?? 'CZK'] ?? 'Kč';
            ?>
                <div class="flex border border-ink/15 rounded-xl px-4 py-3 flex gap-4 items-center shadow-sm [page-break-inside:avoid] [break-inside:avoid]">
                    <div class="bg-ink/5 px-3 py-2 rounded-lg w-48 flex-shrink-0 flex flex-col gap-1">
                        <div class="text-secondary text-lg font-lora"><?= number_format($val, 0, ',', ' ') ?> <?= $cur ?></div>
                        <div class="text-xs"><?= htmlspecialchars($note) ?></div>
                    </div>
                    <div class="flex-1 min-w-0 flex flex-col gap-1">
                        <div class="font-semibold font-lora overflow-hidden text-ellipsis"><?= htmlspecialchars($product) ?></div>
                        <div class="text-xs font-lora"><?= htmlspecialchars($range) ?></div>
                    </div>
                    <?php $lblCount = count($labels); ?>
                    <div class="<?= $lblCount > 3 ? 'grid grid-cols-2' : 'flex flex-col' ?> gap-1 w-72 flex-shrink-0">
                        <?php foreach ($labels as $i => $lbl): ?>
                            <div class="text-xs border border-primary/40 px-2 py-1.5 rounded-md text-center w-full box-border <?= ($lblCount > 3 && $lblCount % 2 === 1 && $i === $lblCount - 1) ? 'col-span-2' : '' ?>"><?= htmlspecialchars($lbl) ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>