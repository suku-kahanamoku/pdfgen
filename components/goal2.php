<?php
// ============================================================
// ACTION STEPS PAGE – CONTROLLER
// ============================================================
$steps = $goal['steps'] ?? [];

$stepIconMap = [
    'car'   => 'fa-solid fa-car',
    'plane' => 'fa-solid fa-plane-departure',
    'piggy' => 'fa-solid fa-piggy-bank',
    'house' => 'fa-solid fa-house',
];

$goalFooter        = $goal['footer'] ?? [];
$goalFooterStatus  = $goalFooter['status'] ?? 'success';
$goalFooterPercent = (float)($goalFooter['percent'] ?? 0);
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
    <div class="relative flex flex-col gap-7">
        <!-- Dashed vertical line spanning all steps -->
        <div class="absolute left-6 top-0 bottom-0 border-l border-dashed border-mist"></div>

        <?php foreach ($steps as $row):
            $val     = (float)($row['value'] ?? 0);
            $note    = $row['note'] ?? '';
            $description   = $row['description'] ?? '';
            $title = $row['title'] ?? '';
            $labels  = $row['labels'] ?? [];
            $cur     = $curMap[$row['currency'] ?? 'CZK'] ?? 'Kč';
        ?>
            <div class="flex items-center gap-6 [page-break-inside:avoid] [break-inside:avoid]">
                <!-- Year crossing the vertical line -->
                <div class="w-12 flex-shrink-0 flex justify-center">
                    <?php if (!empty($row['date'])): ?>
                        <span class="font-lora font-semibold text-ink leading-tight text-center"><?= date('Y', strtotime((string)$row['date'])) ?></span>
                    <?php endif; ?>
                </div>
                <!-- Card -->
                <?php if (($row['status'] ?? '') === 'success'): ?>
                    <div class="flex-1 flex border border-success rounded-xl px-4 py-3 gap-4 items-center shadow-sm">
                        <!-- Check icon – same width as left column in else branch -->
                        <div class="w-48 flex-shrink-0 flex justify-start items-center">
                            <div class="rounded-full w-10 h-10 flex justify-center items-center border border-success text-success">
                                <i class="fa-solid fa-check"></i>
                            </div>
                        </div>
                        <!-- Goal icon + title + description – same flex-1 min-w-0 -->
                        <div class="flex items-center gap-4 flex-1 min-w-0">
                            <?php $iconCls = $stepIconMap[$row['icon'] ?? ''] ?? null; ?>
                            <?php if ($iconCls): ?>
                                <i class="<?= $iconCls ?> text-4xl flex-shrink-0"></i>
                            <?php endif; ?>
                            <div class="flex flex-col gap-1 min-w-0">
                                <div class="font-semibold font-lora overflow-hidden text-ellipsis"><?= htmlspecialchars($title) ?></div>
                                <div class="text-xs font-lora text-ink/60"><?= htmlspecialchars($description) ?></div>
                            </div>
                        </div>
                        <!-- Value + year labels – same w-72 flex-shrink-0 -->
                        <div class="flex flex-row gap-1 w-72 flex-shrink-0">
                            <div class="text-xs border border-primary/40 px-2 py-1.5 rounded-md text-center w-full box-border">
                                <?= number_format($val, 0, ',', ' ') ?> <?= $cur ?>
                            </div>
                            <?php if (!empty($row['date'])): ?>
                                <div class="text-xs border border-primary/40 px-2 py-1.5 rounded-md text-center w-full box-border">
                                    <?= htmlspecialchars(date('d.m.Y', strtotime((string)$row['date']))) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php else: ?>
                    <div class="flex-1 flex border border-ink/15 rounded-xl px-4 py-3 gap-4 items-center shadow-sm">
                        <div class="bg-ink/5 px-3 py-2 rounded-lg w-48 flex-shrink-0 flex flex-col gap-1">
                            <div class="text-secondary text-lg font-lora"><?= number_format($val, 0, ',', ' ') ?> <?= $cur ?></div>
                            <div class="text-xs"><?= htmlspecialchars($note) ?></div>
                        </div>
                        <div class="flex-1 min-w-0 flex flex-col gap-1">
                            <div class="font-semibold font-lora overflow-hidden text-ellipsis"><?= htmlspecialchars($title) ?></div>
                            <div class="text-xs font-lora"><?= htmlspecialchars($description) ?></div>
                        </div>
                        <div class="flex flex-col gap-1 w-72 flex-shrink-0">
                            <?php foreach ($labels as $i => $lbl): ?>
                                <div class="text-xs border border-primary/40 px-2 py-1.5 rounded-md text-center w-full box-border">
                                    <?= htmlspecialchars($lbl) ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Footer -->
    <div class="mt-10">
        <?php if ($goalFooterStatus === 'success'): ?>
            <div class="bg-secondary/10 border border-secondary -ml-24 pl-24 max-w-2xl rounded-r-xl px-5 py-4 flex flex-col gap-4">
                <div class="flex items-center justify-between gap-4">
                    <div class="font-semibold font-lora text-2xl text-ink">Všechny vaše sny si splníte!</div>
                    <div class="rounded-xl px-3 py-3 font-semibold flex-shrink-0 text-white bg-secondary"><?= number_format($goalFooterPercent, 0, ',', ' ') ?>%</div>
                </div>
                <div>Díky naší spolupráci se nám podaří naplnit všechny vaše sny.</div>
            </div>
        <?php else: ?>
            <div class="bg-primary/10 border border-primary -ml-24 pl-24 max-w-2xl rounded-r-xl px-5 py-4 flex flex-col gap-4">
                <div class="flex items-center justify-between gap-4">
                    <div class="font-semibold font-lora text-2xl text-ink">Některé cíle jsou mimo dosah</div>
                    <div class="rounded-xl px-3 py-3 font-semibold flex-shrink-0 text-white bg-primary"><?= number_format($goalFooterPercent, 0, ',', ' ') ?>%</div>
                </div>
                <div>Bez úprav v rozpočtu nebo investiční strategii nemusíme všechny vaše sny naplnit. Pojďme to společně změnit.</div>
            </div>
        <?php endif; ?>
    </div>
</div>