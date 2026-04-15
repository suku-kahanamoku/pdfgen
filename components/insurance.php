<?php
// ============================================================
// INSURANCE PAGE – CONTROLLER
// ============================================================
$insuranceCur  = $curMap[$insurance['currency'] ?? 'CZK'] ?? 'Kč';
$insuranceRows = $insurance['rows'] ?? [];
?>

<!-- ============================================================ -->
<!-- INSURANCE PAGE – Chraňte svůj majetek a své příjmy           -->
<!-- ============================================================ -->
<div class="w-full box-border p-24 [page-break-after:always] [break-after:page] overflow-visible flex flex-col gap-16 [box-decoration-break:clone]">

    <!-- Sekce -->
    <div class="[page-break-inside:avoid] [break-inside:avoid] flex flex-col gap-8">
        <h3 class="flex items-center gap-4 font-lora text-4xl font-semibold">
            Chraňte svůj majetek a své příjmy
            <i class="text-primary fa-solid fa-shield-halved"></i>
        </h3>
        <div class="text-ink/70">
            Chránit svůj obytný majetek je klíčové pro zajištění bezpečí a stability domova.
            Kvalitní pojištění nemovitosti a domácnosti poskytuje finanční ochranu před
            nepředvídatelnými událostmi, jako jsou požáry, povodně nebo krádeže.
        </div>

        <!-- Seznam karet -->
        <div class="flex flex-col gap-7">
            <?php foreach ($insuranceRows as $row):
                $title      = $row['title'] ?? '';
                $value      = (float)($row['value'] ?? 0);
                $valueLabel = $row['value_label'] ?? '';
                $dateLabel  = $row['date_label'] ?? 'Pojištěno do';
                $insuredTo  = $row['insured_to'] ?? '';
                $status     = $row['status'] ?? 'success';
                $note       = $row['note'] ?? '';
                $tags       = $row['tags'] ?? [];
            ?>
                <div class="flex items-center gap-6 [page-break-inside:avoid] [break-inside:avoid]">
                    <!-- Status icon -->
                    <?php if ($status === 'success'): ?>
                        <div class="rounded-full w-8 h-8 flex justify-center items-center flex-shrink-0 border text-success border-success">
                            <i class="fa-solid fa-check"></i>
                        </div>
                    <?php elseif ($status === 'warning'): ?>
                        <div class="rounded-full w-8 h-8 flex justify-center items-center flex-shrink-0 border text-warning border-warning">
                            <i class="fa-solid fa-exclamation"></i>
                        </div>
                    <?php else: ?>
                        <div class="rounded-full w-8 h-8 flex justify-center items-center flex-shrink-0 border text-danger border-danger">
                            <i class="fa-solid fa-xmark"></i>
                        </div>
                    <?php endif; ?>

                    <!-- Card -->
                    <div class="flex-1 border border-ink/15 rounded-xl px-4 py-3 flex gap-4 items-center shadow-sm">
                        <!-- Left box -->
                        <div class="bg-ink/5 px-3 py-2 rounded-lg w-48 flex-shrink-0 flex flex-col gap-1">
                            <?php if ($value > 0): ?>
                                <div class="text-primary text-lg font-lora"><?= number_format($value, 0, ',', ' ') ?> <?= $insuranceCur ?></div>
                            <?php elseif (!empty($valueLabel)): ?>
                                <div class="text-primary text-lg font-lora"><?= htmlspecialchars($valueLabel) ?></div>
                            <?php endif; ?>
                            <div class="text-xs"><?= htmlspecialchars($note) ?></div>
                        </div>

                        <!-- Middle -->
                        <div class="flex-1 min-w-0 flex flex-col gap-1">
                            <div class="font-semibold font-lora whitespace-nowrap overflow-hidden text-ellipsis"><?= htmlspecialchars($title) ?></div>
                            <div class="text-xs font-lora"><?= htmlspecialchars($dateLabel) ?> <?= htmlspecialchars($insuredTo) ?></div>
                        </div>

                        <!-- Tags -->
                        <?php $tagCount = count($tags); ?>
                        <?php if ($tagCount > 3): ?>
                            <div class="grid grid-cols-2 gap-1 min-w-72 flex-shrink-0">
                            <?php else: ?>
                                <div class="flex flex-col gap-1 min-w-72 flex-shrink-0">
                                <?php endif; ?>
                                <?php foreach ($tags as $i => $tag):
                                    $tagTitle  = is_array($tag) ? ($tag['title'] ?? '') : $tag;
                                    $tagStatus = is_array($tag) ? ($tag['status'] ?? 'neutral') : 'neutral';
                                    $spanClass = ($tagCount > 3 && $tagCount % 2 === 1 && $i === $tagCount - 1) ? ' col-span-2' : '';
                                ?>
                                    <?php if ($tagStatus === 'danger'): ?>
                                        <div class="text-xs border border-error px-2 py-1.5 rounded-md text-center w-full box-border whitespace-nowrap<?= $spanClass ?>">
                                            <?= htmlspecialchars($tagTitle) ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-xs border border-primary/40 px-2 py-1.5 rounded-md text-center w-full box-border whitespace-nowrap<?= $spanClass ?>">
                                            <?= htmlspecialchars($tagTitle) ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                </div>
                            </div>
                    </div>
                <?php endforeach; ?>
                </div>
        </div>
    </div>