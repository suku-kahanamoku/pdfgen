<!-- ============================================================ -->
<!-- PAGE 3 – Analýza portfolia                                   -->
<!-- ============================================================ -->
<div class="w-full min-h-[257mm] px-3 py-2 box-border bg-white [page-break-after:always] [break-after:page] overflow-visible flex flex-col gap-16">
    <?php
    $p3StatusColor = [
        'success' => '#2ecc71',
        'warning' => '#e67e22',
        'danger'  => '#e74c3c',
    ];

    $p3Sections = [
        [
            'key'   => 'horizon',
            'title' => 'Investiční horizont',
            'desc'  => 'Rozložení majetku podle délky investičního horizontu – krátkodobé, střednědobé a dlouhodobé.',
        ],
        [
            'key'   => 'active_pasive',
            'title' => 'Aktiva a pasiva',
            'desc'  => 'Poměr aktiv a pasiv v portfoliu ukazuje míru zadlužení vůči celkovému majetku.',
        ],
        [
            'key'   => 'liquidity',
            'title' => 'Likvidita',
            'desc'  => 'Přehled likvidity majetku – jak rychle lze jednotlivé složky převést na hotovost.',
        ],
    ];
    ?>

    <?php foreach ($p3Sections as $p3sec):
        $p3rows  = $property[$p3sec['key']]['rows'] ?? [];
        $p3total = array_sum(array_column($p3rows, 'value'));
        if ($p3total <= 0) $p3total = 1;
    ?>
        <div class="[page-break-inside:avoid] [break-inside:avoid] flex flex-col gap-4">
            <div class="flex flex-col gap-1.5">
                <div class="font-lora text-[#927355] text-lg"><?= htmlspecialchars($p3sec['title']) ?></div>
                <div class="text-[#777] leading-relaxed"><?= htmlspecialchars($p3sec['desc']) ?></div>
            </div>
            <div class="flex flex-col gap-6">
                <?php foreach ($p3rows as $p3row):
                    $p3val = (float)($p3row['value'] ?? 0);
                    $p3pct = round($p3val / $p3total * 100);
                    $p3clr = $p3StatusColor[$p3row['status'] ?? 'success'] ?? '#2ecc71';
                ?>
                    <div class="flex flex-col gap-1">
                        <div class="flex justify-between text-xs">
                            <span><?= htmlspecialchars($p3row['title'] ?? '') ?> (<?= format_czk($p3val) ?> <?= $cur ?>)</span>
                            <span class="font-bold"><?= $p3pct ?>%</span>
                        </div>
                        <div class="bg-[#f3f3f3] h-2.5 rounded-full overflow-hidden">
                            <div class="h-full rounded-full" style="width: <?= $p3pct ?>%; background: <?= $p3clr ?>;"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Diverzifikace box -->
    <?php
    $p3netto     = $dataRaw['summary']['netto'] ?? [];
    $p3total_pct = (int)($p3netto['total'] ?? 0);
    ?>
    <div class="bg-[#927355] text-white rounded-3xl px-8 py-6 flex items-center gap-8">
        <div class="text-5xl font-bold font-lora flex-shrink-0"><?= $p3total_pct ?>%</div>
        <div class="flex flex-col gap-2">
            <div class="text-lg font-bold">Diverzifikace portfolia</div>
            <div class="opacity-90 leading-relaxed">Dobře diverzifikované portfolio rozložené napříč horizonty, aktivy i likviditou snižuje celkové riziko a zvyšuje stabilitu dlouhodobého výnosu.</div>
        </div>
    </div>
</div>