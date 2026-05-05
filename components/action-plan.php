<?php
// ============================================================
// ACTION PLAN PAGE – CONTROLLER
// ============================================================
$actionPlanSections = $actionPlan['sections'] ?? [];

$statusMap = [
    'success' => ['icon' => 'fa-solid fa-check',       'cls' => 'text-success border-success'],
    'warning' => ['icon' => 'fa-solid fa-exclamation', 'cls' => 'text-warning border-warning'],
    'danger'  => ['icon' => 'fa-solid fa-xmark',       'cls' => 'text-danger border-danger'],
];
?>

<!-- ============================================================ -->
<!-- ACTION PLAN PAGE                                             -->
<!-- ============================================================ -->
<div class="w-full box-border p-24 break-after-page overflow-visible flex flex-col gap-12 box-decoration-clone">

    <!-- Header -->
    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <h2 class="font-lora text-5xl font-semibold text-ink">
                Akční plán
            </h2>

            <i class="text-secondary fa-solid fa-route text-6xl"></i>
        </div>

        <p class="text-ink/70 max-w-4xl">
            Níže jsou shrnuty konkrétní kroky, které společně podńkemē pro dosažení vašich finančních cílů.
        </p>
    </div>

    <!-- Sections -->
    <div class="flex flex-col gap-10">

        <?php foreach ($actionPlanSections as $section):
            $status  = $section['status'] ?? 'warning';
            $iconCls = $statusMap[$status]['icon'];
            $iconTw  = $statusMap[$status]['cls'];
        ?>

            <div class="flex flex-col gap-6">

                <!-- Top summary card -->
                <div class="flex items-center gap-6">
                    <div class="rounded-full w-8 h-8 flex justify-center items-center border <?= $iconTw ?>">
                        <i class="<?= $iconCls ?>"></i>
                    </div>

                    <div class="flex-1 border border-ink/10 rounded-xl px-4 py-4 flex items-center gap-6 bg-white/60 shadow-sm">
                        <div class="bg-ink/5 px-4 py-3 rounded-lg w-56 flex-shrink-0">
                            <div class="font-lora text-lg"><?= htmlspecialchars($section['title']) ?></div>
                            <div class="text-sm text-ink/70"><?= htmlspecialchars($section['subtitle']) ?></div>
                        </div>

                        <div class="w-20 text-sm font-semibold">
                            <?= (int)$section['percent'] ?> %
                        </div>

                        <div class="flex-1 text-sm text-ink/70">
                            <?= htmlspecialchars($section['description']) ?>
                        </div>
                    </div>
                </div>

                <!-- Action cards -->
                <div class="grid grid-cols-2 gap-6">
                    <?php foreach ($section['actions'] as $i => $card): ?>

                        <?php
                        // full width pokud je poslední lichý
                        $isLastOdd = (count($section['actions']) % 2 === 1 && $i === count($section['actions']) - 1);
                        ?>

                        <div class="border border-success/60 rounded-xl p-4 flex flex-col gap-2 <?= $isLastOdd ? 'col-span-2' : '' ?>">
                            <div class="font-semibold font-lora text-ink">
                                <?= htmlspecialchars($card['title']) ?>
                            </div>
                            <div class="text-sm text-ink/70 leading-snug">
                                <?= htmlspecialchars($card['text']) ?>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>

            </div>

        <?php endforeach; ?>
    </div>
</div>