<?php
// ============================================================
// HEALTH INSURANCE PAGE – CONTROLLER
// ============================================================
$healthRows = $health['cards'] ?? [];
?>

<!-- ============================================================ -->
<!-- HEALTH INSURANCE SECTION                                       -->
<!-- ============================================================ -->
<div class="w-full box-border p-24 pt-10 [page-break-after:always] [break-after:page] overflow-visible flex flex-col gap-10 [box-decoration-break:clone]">

    <!-- Header -->
    <div class="flex items-start justify-between gap-8">
        <div class="flex-1">
            <h2 class="font-lora text-5xl font-semibold leading-none text-ink">
                Životní pojištění
            </h2>

            <div class="mt-6 leading-relaxed text-ink/70">
                Životní pojištění
                Pojistné částky v případě smrti a podobných událostí jsou
                často podceňovaným, ale velmi důležitým aspektem
                našeho života. Může se zdát nepříjemné přemýšlet o
                takových situacích, ale mít v těchto případech pojistnou
                ochranu znamená, že vaše rodina bude finančně
                zajištěna, i když se stane něco neočekávaného.
            </div>
        </div>

        <div class="w-20 flex-shrink-0 flex justify-end text-primary/90">
            <i class="fa-solid fa-shield-halved text-6xl"></i>
        </div>
    </div>

    <!-- Question -->
    <div class="font-lora text-2xl font-semibold text-ink">
        K čemu slouží životní pojištění, a jak ho správně nastavit?
    </div>

    <!-- Main rows -->
    <div class="relative">
        <!-- right brackets -->
        <div class="absolute right-[-16px] top-0 bottom-[50%] w-3 border-r border-t border-b border-primary/30 rounded-r-xl"></div>
        <div class="absolute right-[-16px] top-[54%] bottom-0 w-3 border-r border-t border-b border-primary/30 rounded-r-xl"></div>

        <div class="absolute right-[-36px] top-[16%] rotate-90 text-xs text-ink/60">
            Do 1. roku
        </div>
        <div class="absolute right-[-36px] bottom-[14%] rotate-90 text-xs text-ink/60">
            Od 2. roku
        </div>

        <div class="grid grid-cols-3 gap-6">
            <?php foreach ($healthRows as $card): ?>
                <?php $colSpan = 'col-span-' . ($card['cols'] ?? 1); ?>

                <?php if (!empty($card['items'])): ?>
                    <!-- Multi-item grouped card -->
                    <div class="<?= $colSpan ?> rounded-2xl border border-ink/15 bg-white px-5 py-5 shadow-sm min-h-[128px]">
                        <div class="grid grid-cols-2 gap-x-10 gap-y-6">
                            <?php foreach ($card['items'] as $item): ?>
                                <div class="flex items-center gap-3">
                                    <i class="<?= htmlspecialchars($item['icon']) ?> text-primary text-2xl"></i>
                                    <span class="text-ink"><?= htmlspecialchars($item['title']) ?></span>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>

                <?php elseif (!empty($card['rows'])): ?>
                    <!-- Label/value table card -->
                    <div class="<?= $colSpan ?> rounded-2xl border border-ink/15 bg-white px-5 py-5 shadow-sm min-h-[150px]">
                        <div class="mb-4 flex items-center gap-3">
                            <i class="<?= htmlspecialchars($card['icon']) ?> text-primary text-2xl"></i>
                            <div class="font-semibold text-ink"><?= htmlspecialchars($card['title']) ?></div>
                        </div>
                        <div class="flex flex-col gap-2 text-xs text-ink/70">
                            <?php foreach ($card['rows'] as $row): ?>
                                <div class="flex justify-between gap-4">
                                    <span><?= htmlspecialchars($row['label']) ?></span>
                                    <span><?= htmlspecialchars($row['value']) ?></span>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>

                <?php elseif (!empty($card['lines'])): ?>
                    <!-- Lines card -->
                    <div class="<?= $colSpan ?> rounded-2xl border border-ink/15 bg-white px-5 py-5 shadow-sm min-h-[128px]">
                        <div class="mb-4 flex items-center gap-3">
                            <i class="<?= htmlspecialchars($card['icon']) ?> text-primary text-2xl"></i>
                            <div class="font-semibold text-ink"><?= htmlspecialchars($card['title']) ?></div>
                        </div>
                        <div class="flex flex-col gap-2 text-ink/70">
                            <?php foreach ($card['lines'] as $line): ?>
                                <div><?= htmlspecialchars($line) ?></div>
                            <?php endforeach ?>
                        </div>
                    </div>

                <?php else: ?>
                    <!-- Icon-only card -->
                    <div class="<?= $colSpan ?> rounded-2xl border border-ink/15 bg-white px-5 py-5 shadow-sm min-h-[150px] flex items-center justify-center">
                        <div class="flex items-center gap-4">
                            <i class="<?= htmlspecialchars($card['icon']) ?> text-primary text-3xl"></i>
                            <div class="font-semibold text-ink"><?= htmlspecialchars($card['title']) ?></div>
                        </div>
                    </div>
                <?php endif ?>

            <?php endforeach ?>
        </div>
    </div>
</div>