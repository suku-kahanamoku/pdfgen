<?php
// ============================================================
// HEALTH INSURANCE PAGE – CONTROLLER
// ============================================================
$healthRows = $health['cards'] ?? [];
?>

<!-- ============================================================ -->
<!-- HEALTH INSURANCE SECTION                                       -->
<!-- ============================================================ -->
<div class="w-full box-border p-24 break-after-page box-decoration-clone overflow-visible flex flex-col gap-16">

    <?php emitMarker(); ?>

    <!-- Header -->
    <div class="flex items-start justify-between gap-8">
        <div class="flex flex-col gap-8">
            <h2 class="font-lora text-5xl font-semibold leading-none text-ink flex items-center justify-between gap-4">
                Životní pojištění
                <i class="text-secondary fa-solid fa-shield-halved text-5xl"></i>
            </h2>

            <div class="leading-relaxed text-ink/70">
                Životní pojištění
                Pojistné částky v případě smrti a podobných událostí jsou
                často podceňovaným, ale velmi důležitým aspektem
                našeho života. Může se zdát nepříjemné přemýšlet o
                takových situacích, ale mít v těchto případech pojistnou
                ochranu znamená, že vaše rodina bude finančně
                zajištěna, i když se stane něco neočekávaného.
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-7">
        <!-- Question -->
        <div class="font-lora text-2xl font-semibold text-ink">
            K čemu slouží životní pojištění, a jak ho správně nastavit?
        </div>

        <!-- Main rows -->
        <div class="grid grid-cols-3 gap-6">
            <?php foreach ($healthRows as $card): ?>
                <?php
                $lines = $card['lines'] ?? [];
                $rows = $card['rows'] ?? [];
                $colSpan = 'col-span-' . ($card['cols'] ?? 1);
                ?>

                <?php if (!empty($lines)): ?>
                    <!-- Lines card -->
                    <div class="<?= $colSpan ?> rounded-2xl border border-ink/15 bg-white px-5 py-5 shadow-sm min-h-32">
                        <div class="mb-4 flex items-center gap-3">
                            <i class="<?= htmlspecialchars($card['icon']) ?> text-primary text-2xl"></i>
                            <div class="font-semibold text-ink"><?= htmlspecialchars($card['title']) ?></div>
                        </div>
                        <div class="flex flex-col gap-2 text-ink/70">
                            <?php foreach ($lines as $line): ?>
                                <div><?= htmlspecialchars($line) ?></div>
                            <?php endforeach ?>
                        </div>
                    </div>

                <?php elseif (!empty($rows)): ?>
                    <!-- Rows card -->
                    <div class="<?= $colSpan ?> rounded-2xl border border-ink/15 bg-white px-5 py-5 shadow-sm min-h-32">
                        <?php if (!empty($card['icon']) || !empty($card['title'])): ?>
                            <div class="mb-4 flex items-center gap-3">
                                <?php if (!empty($card['icon'])): ?>
                                    <i class="<?= htmlspecialchars($card['icon']) ?> text-primary text-2xl"></i>
                                <?php endif ?>
                                <?php if (!empty($card['title'])): ?>
                                    <div class="font-semibold text-ink"><?= htmlspecialchars($card['title']) ?></div>
                                <?php endif ?>
                            </div>
                        <?php endif ?>
                        <div class="<?= !empty($card['icon']) ? 'flex flex-col gap-2 text-xs text-ink/70' : 'grid grid-cols-2 gap-x-10 gap-y-6' ?>">
                            <?php foreach ($rows as $row): ?>
                                <?php if (!empty($row['icon'])): ?>
                                    <div class="flex items-center gap-3">
                                        <i class="<?= htmlspecialchars($row['icon']) ?> text-primary text-2xl"></i>
                                        <span class="text-ink"><?= htmlspecialchars($row['label'] ?? '') ?></span>
                                    </div>
                                <?php else: ?>
                                    <div class="flex justify-between gap-4">
                                        <span><?= htmlspecialchars($row['label'] ?? '') ?></span>
                                        <span><?= htmlspecialchars($row['value'] ?? '') ?></span>
                                    </div>
                                <?php endif ?>
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