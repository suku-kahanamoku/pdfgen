<?php
require_once __DIR__ . '/includes/helpers.php';

$dataRaw   = $GLOBALS['pdfData'];

$client    = $dataRaw['health']['health_client'] ?? [];
$actives   = $dataRaw['property']['property_active'] ?? [];
$pasives   = $dataRaw['property']['property_pasive'] ?? [];
$targets   = $dataRaw['target']['targets'] ?? [];
$solutions = $dataRaw['target']['solutions'] ?? [];
$mortgage_cap = $dataRaw['health']['mortgage_capacity'] ?? [];

$clientRow = $client['rows'][0] ?? [];

$total_active  = (float)($dataRaw['property']['property_summary']['total_active']['value']  ?? 0);
$total_pasive  = (float)($dataRaw['property']['property_summary']['total_pasive']['value']  ?? 0);
$total = (float)($dataRaw['property']['property_summary']['total']['value'] ?? 0);
$ratio_active = ($total_active > 0) ? round(($total_active / ($total_active + abs($total_pasive))) * 100) : 0;
$ratio_pasive = 100 - $ratio_active;
$chartConfig = "{
    type: 'doughnut',
    data: {
        datasets: [{
            data: [$ratio_active, $ratio_pasive],
            backgroundColor: ['#D6B89E', '#eeeeee'],
            borderWidth: 0
        }]
    },
    options: {
        plugins: {
            datalabels: { display: false },
            doughnutlabel: { display: false }
        },
        cutoutPercentage: 85
    }
}";

$chartUrl = "https://quickchart.io/chart?bkg=white&w=200&h=200&c=" . urlencode($chartConfig);
$cisty_majetek = $GLOBALS['cistyMajetek'] ?? $total;
$cisty_majetek_color = ($cisty_majetek >= 0) ? '#3d3229' : '#e74c3c';
?>

<!-- ============================================================ -->
<!-- PAGE 1 – Přehled majetku                                     -->
<!-- ============================================================ -->
<div class="w-full min-h-[257mm] px-10 py-[30px] box-border bg-white font-['Plus_Jakarta_Sans',sans-serif] [page-break-after:always] [break-after:page] overflow-visible">
    <table class="w-full table-fixed [border-collapse:separate] [border-spacing:0_8px] text-xs">
        <tr>
            <td class="w-[65%] align-top pr-5">
                <h1 class="font-['Lora',serif] text-[47px] leading-[1.1] m-0 mb-2 mt-0">
                    <span class="text-[#8c8c8c]">Přehled</span><br>vašeho majetku
                </h1>
                <p class="text-[#888] m-0 mb-[25px]">
                    Diverzifikace příjmů, například prostřednictvím vedlejších příjmů
                    nebo investic, může zvýšit naši finanční bezpečnost. Když
                    přemýšlíme o budoucnosti a strategicky investujeme, zajišťujeme
                    si lepší životní úroveň a klidnou mysl. Důležité je také udržovat
                    si přehled o svých příjmech a pravidelně přehodnocovat své
                    finanční cíle.
                </p>
            </td>

            <td class="w-[35%] align-middle text-center">
                <div class="relative w-[200px] mx-auto">
                    <?php if ($chartUrl): ?>
                        <img src="<?= $chartUrl ?>" class="w-40 h-auto block mb-2.5" />

                        <div class="text-center w-full">
                            <div class="text-[10px] text-[#8c8c8c] uppercase tracking-[1px] leading-tight">
                                Čistý majetek
                            </div>
                            <div class="text-base font-bold mt-1" style="color: <?= $cisty_majetek_color ?>;">
                                <?= number_format($cisty_majetek, 0, ',', ' ') ?> Kč
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
    </table>

    <!-- 3-column property overview -->
    <?php
    $summary = $dataRaw['summary'] ?? [];

    $statusIconMap = [
        'success' => ['cls' => 'fa-solid fa-check',       'clr' => '#2ecc71'],
        'warning' => ['cls' => 'fa-solid fa-exclamation', 'clr' => '#e67e22'],
        'danger'  => ['cls' => 'fa-solid fa-xmark',       'clr' => '#042444'],
    ];

    $propertyColumns = [
        [
            'title' => 'Finanční aktiva',
            'icon'  => 'fa-solid fa-money-bill-1',
            'total' => (float)($summary['active']['value'] ?? 0),
            'rows'  => $summary['active']['rows'] ?? [],
        ],
        [
            'title' => 'Nemovitosti',
            'icon'  => 'fa-solid fa-house',
            'total' => (float)($summary['estate']['value'] ?? 0),
            'rows'  => $summary['estate']['rows'] ?? [],
        ],
        [
            'title' => 'Movitý majetek',
            'icon'  => 'fa-solid fa-car',
            'total' => (float)($summary['properties']['value'] ?? 0),
            'rows'  => $summary['properties']['rows'] ?? [],
        ],
    ];
    ?>
    <div class="flex gap-[15px] mt-2.5">
        <?php foreach ($propertyColumns as $col): ?>
            <div class="flex-1 min-w-0">
                <div class="border border-[#927355] rounded-xl p-3 mb-[15px] bg-[#fcfaf8] flex items-center gap-2.5">
                    <div class="text-[#927355] text-lg w-6 text-center">
                        <i class="<?= $col['icon'] ?>"></i>
                    </div>
                    <div>
                        <div class="font-bold text-[#927355] text-sm font-['Lora',serif]"><?= htmlspecialchars($col['title']) ?></div>
                        <div class="text-xs text-[#666]"><?= format_czk($col['total']) ?> Kč</div>
                    </div>
                </div>

                <?php foreach ($col['rows'] as $row):
                    $val     = (float)($row['value'] ?? 0);
                    $name    = $row['title'] ?? '';
                    $status  = $row['status'] ?? 'success';
                    $iconCls = $statusIconMap[$status]['cls'] ?? 'fa-solid fa-check';
                    $iconClr = $statusIconMap[$status]['clr'] ?? '#2ecc71';
                ?>
                    <div class="bg-white border border-[#f0f0f0] p-3 rounded-[10px] mb-2 flex items-center gap-2.5 [page-break-inside:avoid] [break-inside:avoid]">
                        <div class="rounded-full w-[17px] h-[17px] flex justify-center items-center text-[9px] flex-shrink-0 border-[1.2px]"
                             style="color: <?= $iconClr ?>; border-color: <?= $iconClr ?>;">
                            <i class="<?= $iconCls ?>"></i>
                        </div>
                        <div class="overflow-hidden">
                            <div class="text-[10px] text-[#888] whitespace-nowrap overflow-hidden text-ellipsis"><?= htmlspecialchars($name) ?></div>
                            <div class="font-normal text-[13px]"><?= format_czk($val) ?> Kč</div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($col['rows'])): ?>
                    <div class="text-[#aaa] text-xs text-center py-5">Žádné položky</div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ============================================================ -->
<!-- PAGE 2 – Aktiva & Pasiva                                     -->
<!-- ============================================================ -->
<div class="w-full min-h-[257mm] px-10 py-[30px] box-border bg-white font-['Plus_Jakarta_Sans',sans-serif] [page-break-after:always] [break-after:page] overflow-visible">
    <?php
    $property = $dataRaw['property'] ?? [];
    $p2StatusMap = [
        'success' => ['cls' => 'fa-check',       'clr' => '#2ecc71'],
        'warning' => ['cls' => 'fa-exclamation', 'clr' => '#e67e22'],
        'danger'  => ['cls' => 'fa-exclamation', 'clr' => '#e74c3c'],
    ];
    $p2Sections = [
        ['key' => 'estate',     'title' => 'Nemovitosti',     'icon' => 'fa-house'],
        ['key' => 'active',     'title' => 'Finanční aktiva', 'icon' => 'fa-money-bill-1'],
        ['key' => 'properties', 'title' => 'Movitý majetek',  'icon' => 'fa-car'],
    ];
    ?>
    <?php foreach ($p2Sections as $sec):
        $rows = $property[$sec['key']]['rows'] ?? [];
        if (empty($rows)) continue;
    ?>
        <div class="mb-[30px]">
            <div class="flex items-center gap-3 text-[#927355] font-['Lora',serif] text-xl mb-3 border-b border-[#eee] pb-2.5">
                <i class="fa-solid <?= $sec['icon'] ?>"></i>
                <?= htmlspecialchars($sec['title']) ?>
            </div>
            <?php foreach ($rows as $row):
                $val     = (float)($row['value'] ?? 0);
                $title   = $row['title'] ?? '';
                $status  = $row['status'] ?? 'success';
                $note    = $row['note'] ?? '';
                $desc    = $row['description'] ?? '';
                $labels  = $row['labels'] ?? [];
                $iconCls = $p2StatusMap[$status]['cls'] ?? 'fa-check';
                $iconClr = $p2StatusMap[$status]['clr'] ?? '#2ecc71';
            ?>
                <div class="flex items-center gap-3 mb-2.5 [page-break-inside:avoid] [break-inside:avoid]">
                    <div class="rounded-full w-[22px] h-[22px] flex justify-center items-center text-[10px] flex-shrink-0 border-[1.2px]"
                         style="color: <?= $iconClr ?>; border-color: <?= $iconClr ?>;"><i class="fa-solid <?= $iconCls ?>"></i></div>
                    <div class="flex-1 bg-white border border-[#f0f0f0] rounded-xl px-4 py-[14px] flex gap-[15px] items-center">
                        <div class="bg-[#f8f8f8] px-[14px] py-[10px] rounded-lg min-w-[145px] flex-shrink-0">
                            <div class="text-base text-[#927355] font-['Lora',serif] mb-[2px]"><?= format_czk($val) ?> Kč</div>
                            <div class="text-[10px] text-[#777] font-bold uppercase tracking-[0.5px]"><?= htmlspecialchars($title) ?></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-[13px] text-[#333] mb-[3px] whitespace-nowrap overflow-hidden text-ellipsis"><?= htmlspecialchars($note) ?></div>
                            <div class="text-[11px] text-[#888]"><?= htmlspecialchars($desc) ?></div>
                        </div>
                        <div class="flex flex-col gap-[5px] items-end w-[130px] flex-shrink-0">
                            <?php foreach ($labels as $lbl): ?>
                                <div class="text-[9px] uppercase border border-[#d4c4b5] px-2 py-1 rounded-md text-[#927355] text-center w-full box-border whitespace-nowrap"><?= htmlspecialchars($lbl) ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

    <!-- Bilance majetku -->
    <?php
    $bilance    = $property['bilance'] ?? [];
    $bilActive  = (float)($bilance['active']['value']  ?? 0);
    $bilPasive  = (float)($bilance['pasive']['value']  ?? 0);
    $bilNetto   = (float)($bilance['netto']['value']   ?? 0);
    $bilMaxVal  = max($bilActive, $bilPasive, 1);
    $bilHAkt    = round($bilActive / $bilMaxVal * 100);
    $bilHPas    = round($bilPasive / $bilMaxVal * 100);
    $bilFooter  = $bilance['footer'] ?? [];
    $bilPercent = $bilFooter['percent'] ?? 0;
    $bilStatus  = $bilFooter['status'] ?? 'success';
    $bilFootClr = ($bilStatus === 'success') ? '#2ecc71' : (($bilStatus === 'warning') ? '#e67e22' : '#e74c3c');
    ?>
    <div class="mt-10 [page-break-before:always] [break-before:page]">
        <h2 class="font-['Lora',serif] text-[26px] text-[#333] mb-[25px]">Bilance majetku</h2>
        <div class="flex gap-[30px] items-start mb-[30px]">
            <!-- Bar chart -->
            <div class="flex-1 h-[200px] flex items-end gap-[30px] border-b-2 border-[#eee] px-[30px] pb-[10px]">
                <div class="flex-1 relative rounded-t-lg bg-[#ededed]" style="height: <?= $bilHAkt ?>%;">
                    <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-xs text-[#666] whitespace-nowrap">Aktiva</span>
                </div>
                <div class="flex-1 relative rounded-t-lg bg-[#8d6e53]" style="height: <?= $bilHPas ?>%;">
                    <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-xs text-[#666] whitespace-nowrap">Pasiva</span>
                </div>
            </div>
            <!-- Table -->
            <div class="flex-1">
                <div class="flex justify-between px-[14px] py-[10px] border border-[#ddd] rounded-lg mb-1 text-[13px]">
                    <span><?= htmlspecialchars($bilance['active']['title'] ?? 'Aktiva') ?></span>
                    <span class="font-bold"><?= format_czk($bilActive) ?> Kč</span>
                </div>
                <div class="flex justify-between px-[14px] pt-1 pb-[10px] text-xs text-[#888]">
                    <span><?= htmlspecialchars($bilance['active']['yeld']['title'] ?? '') ?></span>
                    <span><?= number_format($bilance['active']['yeld']['percent'] ?? 0, 2, ',', ' ') ?> %</span>
                </div>
                <div class="flex justify-between px-[14px] py-[10px] border border-[#ddd] rounded-lg mb-1 text-[13px]">
                    <span><?= htmlspecialchars($bilance['pasive']['title'] ?? 'Pasiva') ?></span>
                    <span class="font-bold"><?= format_czk($bilPasive) ?> Kč</span>
                </div>
                <div class="flex justify-between px-[14px] pt-1 pb-[10px] text-xs text-[#888]">
                    <span><?= htmlspecialchars($bilance['pasive']['yeld']['title'] ?? '') ?></span>
                    <span><?= number_format($bilance['pasive']['yeld']['percent'] ?? 0, 2, ',', ' ') ?> %</span>
                </div>
                <div class="flex justify-between px-[14px] py-3 bg-[#8d6e53] text-white rounded-lg text-[13px] font-bold">
                    <span><?= htmlspecialchars($bilance['netto']['title'] ?? 'Čistý majetek') ?></span>
                    <span><?= format_czk($bilNetto) ?> Kč</span>
                </div>
            </div>
        </div>
        <!-- Footer row -->
        <div class="flex gap-[15px] items-stretch">
            <div class="flex-[3] bg-[#fff5f5] border border-[#f8d7da] rounded-xl px-5 py-4 flex items-center gap-[18px]">
                <div class="w-12 h-12 rounded-[10px] flex items-center justify-center font-bold text-base flex-shrink-0"
                     style="color: <?= $bilFootClr ?>; background: <?= $bilFootClr ?>22; border: 1px solid <?= $bilFootClr ?>;">
                    <?= number_format($bilPercent, 2, ',', ' ') ?>%
                </div>
                <div>
                    <div class="font-bold text-[15px] mb-1">Čistý výnos majetku</div>
                    <div class="text-xs text-[#666]">Rozdíl mezi ziskovostí aktiv a nákladovostí pasiv.</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- PAGE 3 – Analýza portfolia                                   -->
<!-- ============================================================ -->
<div class="w-full min-h-[257mm] px-10 py-[30px] box-border bg-white font-['Plus_Jakarta_Sans',sans-serif] [page-break-after:always] [break-after:page] overflow-visible">
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
        $p3rows = $property[$p3sec['key']]['rows'] ?? [];
        $p3total = array_sum(array_column($p3rows, 'value'));
        if ($p3total <= 0) $p3total = 1;
    ?>
        <div class="mb-[30px]">
            <div class="font-['Lora',serif] text-[#927355] text-lg mb-1.5"><?= htmlspecialchars($p3sec['title']) ?></div>
            <div class="text-xs text-[#777] leading-relaxed mb-[14px]"><?= htmlspecialchars($p3sec['desc']) ?></div>
            <?php foreach ($p3rows as $p3row):
                $p3val  = (float)($p3row['value'] ?? 0);
                $p3pct  = round($p3val / $p3total * 100);
                $p3clr  = $p3StatusColor[$p3row['status'] ?? 'success'] ?? '#2ecc71';
            ?>
                <div class="mb-2.5">
                    <div class="flex justify-between text-xs mb-1">
                        <span><?= htmlspecialchars($p3row['title'] ?? '') ?> (<?= format_czk($p3val) ?> Kč)</span>
                        <span class="font-bold"><?= $p3pct ?>%</span>
                    </div>
                    <div class="bg-[#f3f3f3] h-2.5 rounded-full overflow-hidden">
                        <div class="h-full rounded-full" style="width: <?= $p3pct ?>%; background: <?= $p3clr ?>;"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

    <!-- Diverzifikace box -->
    <?php
    $p3netto = $dataRaw['summary']['netto'] ?? [];
    $p3total_pct = (int)($p3netto['total'] ?? 0);
    ?>
    <div class="bg-[#927355] text-white rounded-[20px] px-[30px] py-[25px] flex items-center gap-[30px] mt-[30px]">
        <div class="text-[44px] font-bold font-['Lora',serif] flex-shrink-0"><?= $p3total_pct ?>%</div>
        <div>
            <div class="text-[17px] font-bold mb-2">Diverzifikace portfolia</div>
            <div class="text-xs opacity-90 leading-relaxed">Dobře diverzifikované portfolio rozložené napříč horizonty, aktivy i likviditou snižuje celkové riziko a zvyšuje stabilitu dlouhodobého výnosu.</div>
        </div>
    </div>
</div>
