<?php
// ============================================================
// SCORE OVERVIEW PAGE – CONTROLLER
// ============================================================
$score = ($health['result'] ?? [])['score'] ?? [];

$scorePercent = (float)($score['percent'] ?? 64);
$scoreText    = 'Níže vidíte přehled všech klíčových oblastí vaší finanční situace. Každá oblast je ohodnocena na základě analýzy vašich dat – čím vyšší skóre, tím lépe jste na tom. U oblastí s nižším hodnocením najdete konkrétní akční kroky.';
$scoreRows    = $score['rows'] ?? [];

$statusMap = [
    'success' => ['icon' => 'fa-solid fa-check',       'cls' => 'text-success border-success'],
    'warning' => ['icon' => 'fa-solid fa-exclamation', 'cls' => 'text-warning border-warning'],
    'danger'  => ['icon' => 'fa-solid fa-xmark',       'cls' => 'text-danger border-danger'],
];
?>

<!-- ============================================================ -->
<!-- SCORE OVERVIEW PAGE                                          -->
<!-- ============================================================ -->
<div class="w-full box-border p-24 break-after-page overflow-visible flex flex-col gap-14 box-decoration-clone">

    <!-- Header -->
    <div class="flex items-start justify-between gap-8">
        <div class="flex flex-col gap-5">
            <h2 class="font-lora text-6xl font-semibold leading-none text-ink">
                <?= number_format($scorePercent, 0, ',', ' ') ?>%
            </h2>

            <p class="max-w-5xl leading-relaxed text-ink/70">
                <?= htmlspecialchars($scoreText) ?>
            </p>
        </div>

        <div class="text-primary/90">
            <i class="fa-solid fa-chart-simple text-6xl"></i>
        </div>
    </div>

    <!-- Rows -->
    <div class="flex flex-col gap-9">
        <?php foreach ($scoreRows as $row):
            $status  = $row['status'] ?? 'success';
            $iconCls = $statusMap[$status]['icon'] ?? 'fa-solid fa-check';
            $iconTw  = $statusMap[$status]['cls'] ?? 'text-success border-success';
        ?>
            <div class="flex items-center gap-6 break-inside-avoid">
                <!-- Status icon -->
                <div class="rounded-full w-8 h-8 flex justify-center items-center flex-shrink-0 border <?= $iconTw ?>">
                    <i class="<?= $iconCls ?>"></i>
                </div>

                <!-- Card -->
                <div class="flex-1 border border-ink/10 rounded-xl px-4 py-5 flex gap-8 items-center shadow-sm bg-white/70 min-h-[96px]">
                    <!-- Left box -->
                    <div class="bg-ink/5 px-4 py-3 rounded-lg w-48 flex-shrink-0 flex flex-col gap-1">
                        <div class="font-lora text-xl font-semibold text-ink">
                            <?= htmlspecialchars($row['title'] ?? '') ?>
                        </div>
                        <div class="text-sm text-ink/80 leading-snug">
                            <?= htmlspecialchars($row['note'] ?? '') ?>
                        </div>
                    </div>

                    <!-- Middle -->
                    <div class="w-20 flex-shrink-0 font-semibold text-ink">
                        <?= number_format((float)($row['percent'] ?? 0), 0, ',', ' ') ?> %
                    </div>

                    <div class="flex-1 text-ink/75 leading-snug">
                        <?= htmlspecialchars($row['description'] ?? '') ?>
                    </div>

                    <!-- Action -->
                    <div class="w-72 flex-shrink-0">
                        <?php if (!empty($row['action'])): ?>
                            <div class="text-xs border border-primary/40 px-2 py-1.5 rounded-md text-center w-full box-border">
                                <?= htmlspecialchars($row['action']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>