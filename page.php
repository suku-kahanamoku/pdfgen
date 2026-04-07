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
<div class="page">
    <table style="width: 100%; table-layout: fixed; border-collapse: separate; border-spacing: 0 8px; font-size: 12px;">
        <tr>
            <td style="width: 65%; vertical-align: top; padding-right: 20px;">
                <h1 class="main-title" style="margin-top: 0;">
                    <span class="gray">Přehled</span><br>vašeho majetku
                </h1>
                <p class="page-subtitle">
                    Diverzifikace příjmů, například prostřednictvím vedlejších příjmů
                    nebo investic, může zvýšit naši finanční bezpečnost. Když
                    přemýšlíme o budoucnosti a strategicky investujeme, zajišťujeme
                    si lepší životní úroveň a klidnou mysl. Důležité je také udržovat
                    si přehled o svých příjmech a pravidelně přehodnocovat své
                    finanční cíle.
                </p>
            </td>

            <td style="width: 35%; vertical-align: middle; text-align: center;">
                <div class="chart-container" style="width: 160px; margin: 0 auto;">
                    <?php if ($chartUrl): ?>
                        <img src="<?= $chartUrl ?>" style="width: 160px; height: auto; display: block; margin-bottom: 10px;" />

                        <div style="text-align: center; width: 100%;">
                            <div style="font-size: 10px; color: #8c8c8c; text-transform: uppercase; letter-spacing: 1px; font-family: 'Plus Jakarta Sans', sans-serif; line-height: 1.2;">
                                Čistý majetek
                            </div>
                            <div style="font-size: 16px; font-weight: 700; color: <?= $cisty_majetek_color ?>; font-family: 'Plus Jakarta Sans', sans-serif; margin-top: 4px;">
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
        'danger'  => ['cls' => 'fa-solid fa-xmark', 'clr' => '#042444'],
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
    <div style="display: flex; gap: 15px; margin-top: 10px;">
        <?php foreach ($propertyColumns as $col): ?>
            <div style="flex: 1; min-width: 0;">
                <div style="border: 1px solid #927355; border-radius: 12px; padding: 12px; margin-bottom: 15px; background: #fcfaf8; display: flex; align-items: center; gap: 10px;">
                    <div style="color: #927355; font-size: 18px; width: 24px; text-align: center;">
                        <i class="<?= $col['icon'] ?>"></i>
                    </div>
                    <div>
                        <div style="font-weight: bold; color: #927355; font-size: 14px; font-family: 'Lora', serif;"><?= htmlspecialchars($col['title']) ?></div>
                        <div style="font-size: 12px; color: #666;"><?= format_czk($col['total']) ?> Kč</div>
                    </div>
                </div>

                <?php foreach ($col['rows'] as $row):
                    $val     = (float)($row['value'] ?? 0);
                    $name    = $row['title'] ?? '';
                    $status  = $row['status'] ?? 'success';
                    $iconCls = $statusIconMap[$status]['cls'] ?? 'fa-solid fa-check';
                    $iconClr = $statusIconMap[$status]['clr'] ?? '#2ecc71';
                ?>
                    <div style="background: #fff; border: 1px solid #f0f0f0; padding: 12px; border-radius: 10px; margin-bottom: 8px; display: flex; align-items: center; gap: 10px; page-break-inside: avoid; break-inside: avoid;">
                        <div style="border-radius: 50%; width: 17px; height: 17px; display: flex; justify-content: center; align-items: center; font-size: 9px; flex-shrink: 0; border: 1.2px solid; color: <?= $iconClr ?>; border-color: <?= $iconClr ?>;">
                            <i class="<?= $iconCls ?>"></i>
                        </div>
                        <div style="overflow: hidden;">
                            <div style="font-size: 10px; color: #888; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($name) ?></div>
                            <div style="font-weight: normal; font-size: 13px;"><?= format_czk($val) ?> Kč</div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($col['rows'])): ?>
                    <div style="color: #aaa; font-size: 12px; text-align: center; padding: 20px 0;">Žádné položky</div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ============================================================ -->
<!-- PAGE 2 – Aktiva & Pasiva                                     -->
<!-- ============================================================ -->
<div class="page">
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
        <div class="p2-sec-block">
            <div class="p2-sec-title">
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
                <div class="p2-card-row">
                    <div class="p2-status-icon" style="color: <?= $iconClr ?>; border-color: <?= $iconClr ?>;"><i class="fa-solid <?= $iconCls ?>"></i></div>
                    <div class="p2-detail-card">
                        <div class="p2-price-box">
                            <div class="p2-val-amount"><?= format_czk($val) ?> Kč</div>
                            <div class="p2-val-label"><?= htmlspecialchars($title) ?></div>
                        </div>
                        <div class="p2-middle">
                            <div class="p2-text-main"><?= htmlspecialchars($note) ?></div>
                            <div class="p2-text-minor"><?= htmlspecialchars($desc) ?></div>
                        </div>
                        <div class="p2-tag-cloud">
                            <?php foreach ($labels as $lbl): ?>
                                <div class="p2-tag"><?= htmlspecialchars($lbl) ?></div>
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
    <div style="margin-top: 40px; page-break-before: always; break-before: page;">
        <h2 style="font-family: 'Lora', serif; font-size: 26px; color: #333; margin-bottom: 25px;">Bilance majetku</h2>
        <div style="display: flex; gap: 30px; align-items: flex-start; margin-bottom: 30px;">
            <!-- Bar chart -->
            <div style="flex: 1; height: 200px; display: flex; align-items: flex-end; gap: 30px; border-bottom: 2px solid #eee; padding: 0 30px 10px 30px;">
                <div style="flex: 1; position: relative; border-radius: 8px 8px 0 0; background: #ededed; height: <?= $bilHAkt ?>%;">
                    <span style="position: absolute; bottom: -24px; left: 50%; transform: translateX(-50%); font-size: 12px; color: #666; white-space: nowrap;">Aktiva</span>
                </div>
                <div style="flex: 1; position: relative; border-radius: 8px 8px 0 0; background: #8d6e53; height: <?= $bilHPas ?>%;">
                    <span style="position: absolute; bottom: -24px; left: 50%; transform: translateX(-50%); font-size: 12px; color: #666; white-space: nowrap;">Pasiva</span>
                </div>
            </div>
            <!-- Table -->
            <div style="flex: 1;">
                <div style="display: flex; justify-content: space-between; padding: 10px 14px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 4px; font-size: 13px;">
                    <span><?= htmlspecialchars($bilance['active']['title'] ?? 'Aktiva') ?></span>
                    <span style="font-weight: bold;"><?= format_czk($bilActive) ?> Kč</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 4px 14px 10px; font-size: 12px; color: #888;">
                    <span><?= htmlspecialchars($bilance['active']['yeld']['title'] ?? '') ?></span>
                    <span><?= number_format($bilance['active']['yeld']['percent'] ?? 0, 2, ',', ' ') ?> %</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 10px 14px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 4px; font-size: 13px;">
                    <span><?= htmlspecialchars($bilance['pasive']['title'] ?? 'Pasiva') ?></span>
                    <span style="font-weight: bold;"><?= format_czk($bilPasive) ?> Kč</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 4px 14px 10px; font-size: 12px; color: #888;">
                    <span><?= htmlspecialchars($bilance['pasive']['yeld']['title'] ?? '') ?></span>
                    <span><?= number_format($bilance['pasive']['yeld']['percent'] ?? 0, 2, ',', ' ') ?> %</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 12px 14px; background: #8d6e53; color: white; border-radius: 8px; font-size: 13px; font-weight: bold;">
                    <span><?= htmlspecialchars($bilance['netto']['title'] ?? 'Čistý majetek') ?></span>
                    <span><?= format_czk($bilNetto) ?> Kč</span>
                </div>
            </div>
        </div>
        <!-- Footer row -->
        <div style="display: flex; gap: 15px; align-items: stretch;">
            <div style="flex: 3; background: #fff5f5; border: 1px solid #f8d7da; border-radius: 12px; padding: 16px 20px; display: flex; align-items: center; gap: 18px;">
                <div style="background: #b56565; color: white; width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 16px; flex-shrink: 0; color: <?= $bilFootClr ?>; background: <?= $bilFootClr ?>22; border: 1px solid <?= $bilFootClr ?>;">
                    <?= number_format($bilPercent, 2, ',', ' ') ?>%
                </div>
                <div>
                    <div style="font-weight: bold; font-size: 15px; margin-bottom: 4px;">Čistý výnos majetku</div>
                    <div style="font-size: 12px; color: #666;">Rozdíl mezi ziskovostí aktiv a nákladovostí pasiv.</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- PAGE 3 – Finanční cíle                                       -->
<!-- ============================================================ -->
<div class="page">
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
    <style>
        .p3-analysis-section {
            margin-bottom: 30px;
        }

        .p3-analysis-title {
            font-family: 'Lora', serif;
            color: #927355;
            font-size: 18px;
            margin-bottom: 6px;
        }

        .p3-analysis-desc {
            font-size: 12px;
            color: #777;
            line-height: 1.5;
            margin-bottom: 14px;
        }

        .p3-bar-container {
            margin-bottom: 10px;
        }

        .p3-bar-label-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .p3-bar-outer {
            background: #f3f3f3;
            height: 10px;
            border-radius: 6px;
            overflow: hidden;
        }

        .p3-bar-inner {
            height: 100%;
            border-radius: 6px;
        }
    </style>

    <?php foreach ($p3Sections as $p3sec):
        $p3rows = $property[$p3sec['key']]['rows'] ?? [];
        $p3total = array_sum(array_column($p3rows, 'value'));
        if ($p3total <= 0) $p3total = 1;
    ?>
        <div class="p3-analysis-section">
            <div class="p3-analysis-title"><?= htmlspecialchars($p3sec['title']) ?></div>
            <div class="p3-analysis-desc"><?= htmlspecialchars($p3sec['desc']) ?></div>
            <?php foreach ($p3rows as $p3row):
                $p3val  = (float)($p3row['value'] ?? 0);
                $p3pct  = round($p3val / $p3total * 100);
                $p3clr  = $p3StatusColor[$p3row['status'] ?? 'success'] ?? '#2ecc71';
            ?>
                <div class="p3-bar-container">
                    <div class="p3-bar-label-row">
                        <span><?= htmlspecialchars($p3row['title'] ?? '') ?> (<?= format_czk($p3val) ?> Kč)</span>
                        <span style="font-weight: bold;"><?= $p3pct ?>%</span>
                    </div>
                    <div class="p3-bar-outer">
                        <div class="p3-bar-inner" style="width: <?= $p3pct ?>%; background: <?= $p3clr ?>;"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

    <!-- diverzifikace box -->
    <?php
    $p3netto = $dataRaw['summary']['netto'] ?? [];
    $p3total_pct = (int)($p3netto['total'] ?? 0);
    ?>
    <div style="background: #927355; color: white; border-radius: 20px; padding: 25px 30px; display: flex; align-items: center; gap: 30px; margin-top: 30px;">
        <div style="font-size: 44px; font-weight: bold; font-family: 'Lora', serif; flex-shrink: 0;"><?= $p3total_pct ?>%</div>
        <div>
            <div style="font-size: 17px; font-weight: bold; margin-bottom: 8px;">Diverzifikace portfolia</div>
            <div style="font-size: 12px; opacity: 0.9; line-height: 1.5;">Dobře diverzifikované portfolio rozložené napříč horizonty, aktivy i likviditou snižuje celkové riziko a zvyšuje stabilitu dlouhodobého výnosu.</div>
        </div>
    </div>
</div>