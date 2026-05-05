<?php
// ============================================================
// TABLE OF CONTENTS – CONTROLLER
// ============================================================
$tocItems = $tocSections ?? [];
?>

<!-- ============================================================ -->
<!-- TABLE OF CONTENTS PAGE                                       -->
<!-- ============================================================ -->
<div class="w-full box-border p-24 break-after-page overflow-visible box-decoration-clone grid grid-cols-[1fr_260px] gap-16">

    <!-- LEFT -->
    <div>

        <!-- Title -->
        <h2 class="font-lora text-6xl font-semibold text-ink mb-10">
            Obsah
        </h2>

        <!-- Sections -->
        <div>
            <?php foreach ($tocItems as $section): ?>
                <div class="mb-8" style="page-break-inside: avoid;">

                    <!-- Section title -->
                    <div class="bg-ink/5 rounded-t-xl px-4 py-2 text-sm font-semibold text-ink">
                        <?= htmlspecialchars($section['title']) ?>
                    </div>

                    <!-- Items -->
                    <div class="bg-white/70 rounded-b-xl px-4 py-3 shadow-sm">
                        <?php foreach ($section['items'] as $item): ?>
                            <div class="flex items-center text-sm text-ink/80 mb-2">

                                <!-- Title -->
                                <span class="whitespace-nowrap">
                                    <?= htmlspecialchars($item['title']) ?>
                                </span>

                                <!-- dotted line -->
                                <span class="flex-1 border-b border-dashed border-ink/20 mx-2"></span>

                                <!-- page -->
                                <span class="font-medium">
                                    <?= str_pad((string)$item['page'], 2, '0', STR_PAD_LEFT) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- RIGHT DECOR -->
    <div class="overflow-hidden flex justify-center items-start">
        <span class="font-lora text-[320px] leading-none select-none text-transparent tracking-wide [-webkit-text-stroke:1px_#9a6b45]"
            style="writing-mode: vertical-rl; transform: rotate(180deg);">
            obsah
        </span>
    </div>

</div>