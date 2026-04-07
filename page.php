<?php
require_once __DIR__ . '/includes/helpers.php';
include_once __DIR__ . '/components/card-styles.php';

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

    <!-- Bilance -->
    <div class="section-title">Bilance majetku</div>
    <?php
    $maxB = max($total_active, $total_pasive, 1);
    $bars = [
        ['label' => 'Aktiva',  'val' => $total_active,              'color' => '#D6B89E'],
        ['label' => 'Pasiva',  'val' => $total_pasive,              'color' => '#927355'],
        ['label' => 'Čistý',   'val' => max($total, 0),     'color' => '#2ecc71'],
    ];
    ?>
    <div class="bilance-bar-wrap">
        <?php foreach ($bars as $b): ?>
            <div class="bilance-bar-row">
                <span class="bilance-bar-label"><?= $b['label'] ?></span>
                <div class="bilance-bar-outer">
                    <div class="bilance-bar-inner" style="width:<?= round($b['val'] / $maxB * 100) ?>%; background:<?= $b['color'] ?>;"></div>
                </div>
                <span class="bilance-bar-amount"><?= format_czk($b['val']) ?> Kč</span>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ============================================================ -->
<!-- PAGE 2 – Aktiva & Pasiva                                     -->
<!-- ============================================================ -->
<div class="page">
    <?php
    $sections = [
        [
            'data'          => $actives,
            'icon'          => 'fa-chart-line',
            'color'         => '#2ecc71',
            'bar_pct_key'   => null,
            'bar_left_key'  => 'invested',
            'bar_left_lbl'  => 'Investováno',
            'bar_right_key' => 'aum',
            'bar_right_lbl' => 'Aktuální hodnota',
        ],
        [
            'data'          => $pasives,
            'icon'          => 'fa-file-invoice-dollar',
            'color'         => '#e67e22',
            'bar_pct_key'   => 'paid',
            'bar_left_key'  => null,
            'bar_left_lbl'  => 'Splaceno',
            'bar_right_key' => 'aum',
            'bar_right_lbl' => 'Zbývá umořit',
        ],
    ];
    foreach ($sections as $sec):
        $cardHeader  = $sec['data']['header'] ?? [];
        $cardNameKey = $cardHeader[0]['key'] ?? 'name';
    ?>
        <div class="section-block">
            <div class="section-heading">
                <span class="section-icon" style="background:<?= $sec['color'] ?>22; color:<?= $sec['color'] ?>;">
                    <i class="fa-solid <?= $sec['icon'] ?>"></i>
                </span>
                <?= safe_text($sec['data']['title'] ?? '') ?>
            </div>

            <?php foreach ($sec['data']['rows'] ?? [] as $cardRow):
                $cardName   = $cardRow[$cardNameKey]['value'] ?? '';
                $cardLabels = $cardRow['labels'] ?? [];

                if ($sec['bar_pct_key'] !== null) {
                    $pct      = min(100, max(0, (float)($cardRow[$sec['bar_pct_key']]['value'] ?? 0)));
                    $leftVal  = $pct . ' %';
                    $rightVal = format_czk((float)($cardRow[$sec['bar_right_key']]['value'] ?? 0)) . ' Kč';
                } else {
                    $invested = (float)($cardRow[$sec['bar_left_key']]['value']  ?? 0);
                    $aum      = (float)($cardRow[$sec['bar_right_key']]['value'] ?? 0);
                    $pct      = $invested > 0 ? min(100, round($aum / $invested * 100)) : 0;
                    $leftVal  = format_czk($invested) . ' Kč';
                    $rightVal = format_czk($aum) . ' Kč';
                }

                $cardProgress = [
                    'left_lbl'  => $sec['bar_left_lbl'],
                    'left_val'  => $leftVal,
                    'right_lbl' => $sec['bar_right_lbl'],
                    'right_val' => $rightVal,
                    'pct'       => $pct,
                    'pct_label' => '',
                ];
                include __DIR__ . '/components/sol-card.php';
            endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>

<!-- ============================================================ -->
<!-- PAGE 3 – Finanční cíle                                       -->
<!-- ============================================================ -->
<div class="page">
    <div class="section-heading">
        <span class="section-icon section-icon--primary">
            <i class="fa-solid fa-bullseye"></i>
        </span>
        <?= safe_text($targets['title'] ?? 'Finanční cíle') ?>
    </div>

    <?php
    $cardHeader  = $targets['header'] ?? [];
    $cardNameKey = 'name';
    foreach ($targets['rows'] ?? [] as $cardRow):
        $targetAmt  = (float)($cardRow['targetAmount']['value']  ?? 0);
        $currentAmt = (float)($cardRow['currentAmount']['value'] ?? 0);
        $gap        = $targetAmt - $currentAmt;
        $pct        = $targetAmt > 0 ? min(100, round($currentAmt / $targetAmt * 100)) : 0;

        $cardRow['gap'] = ['value' => $gap, 'type' => 'currency'];

        $cardName     = $cardRow['name']['value'] ?? '';
        $cardLabels   = $cardRow['labels'] ?? [];
        $cardProgress = [
            'left_lbl'  => 'Naspořeno',
            'left_val'  => format_czk($currentAmt) . ' Kč',
            'right_lbl' => 'Cíl',
            'right_val' => format_czk($targetAmt) . ' Kč',
            'pct'       => $pct,
            'pct_label' => '',
        ];
        include __DIR__ . '/components/sol-card.php';
    endforeach; ?>
</div>

<!-- ============================================================ -->
<!-- PAGE 4 – Finanční plán                                       -->
<!-- ============================================================ -->
<div class="page">
    <div class="section-heading">
        <span class="section-icon section-icon--primary">
            <i class="fa-solid fa-seedling"></i>
        </span>
        <?= safe_text($solutions['title'] ?? 'Finanční plán') ?>
    </div>

    <?php
    $cardHeader  = $solutions['header'] ?? [];
    $cardNameKey = 'name';
    foreach ($solutions['rows'] ?? [] as $cardRow):
        $cardName   = $cardRow[$cardNameKey]['value'] ?? '';
        $cardLabels = $cardRow['labels'] ?? [];

        $startRaw = $cardRow['start']['value']      ?? '';
        $endRaw   = $cardRow['targetDate']['value']  ?? '';
        $pct      = 0;
        if ($startRaw && $endRaw) {
            try {
                $dtStart = new DateTime($startRaw);
                $dtEnd   = new DateTime($endRaw);
                $today   = new DateTime();
                $total   = $dtEnd->getTimestamp() - $dtStart->getTimestamp();
                $elapsed = $today->getTimestamp() - $dtStart->getTimestamp();
                $pct     = $total > 0 ? min(100, max(0, round($elapsed / $total * 100))) : 0;
            } catch (Exception $e) {
            }
        }

        $cardProgress = [
            'left_lbl'  => 'Začátek',
            'left_val'  => format_date($startRaw),
            'right_lbl' => 'Cílové datum',
            'right_val' => format_date($endRaw),
            'pct'       => $pct,
            'pct_label' => 'doby uplynulo',
        ];
        include __DIR__ . '/components/sol-card.php';
    endforeach; ?>

    <div class="diverz-box">
        <div class="diverz-icon"><i class="fa-solid fa-seedling"></i></div>
        <div class="diverz-text">
            <h3>Pravidelné investování buduje bohatství</h3>
            <p>Díky složenému úročení a pravidelným vkladům roste hodnota vašeho portfolia exponenciálně. Klíčem je konzistentnost a dlouhý investiční horizont.</p>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- PAGE 5 – Úvěrová kapacita, bonita                            -->
<!-- ============================================================ -->

<div class="page">
    <div class="section-heading">
        <span class="section-icon section-icon--primary">
            <i class="fa-solid fa-house-lock"></i>
        </span>
        <?= safe_text($mortgage_cap['title'] ?? 'Úvěrová kapacita a bonita') ?>
    </div>

    <div style="display: flex; justify-content: space-around; margin: 40px 0; text-align: center;">
        <?php foreach (($mortgage_cap['indicators'] ?? []) as $key => $ind):
            $val = (float)($ind['value'] ?? 0);
            $max = (float)($ind['max'] ?? 1);
            $percent = ($val / $max) * 100;
            $dashArray = (2 * pi() * 45);
            $dashOffset = $dashArray * (1 - $percent / 100);
        ?>
            <div style="width: 150px;">
                <div style="position: relative; width: 100px; height: 100px; margin: 0 auto;">
                    <svg width="100" height="100" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="45" fill="transparent" stroke="#f0f0f0" stroke-width="8" />
                        <circle cx="50" cy="50" r="45" fill="transparent" stroke="var(--clr-primary)" stroke-width="8"
                            stroke-dasharray="<?= $dashArray ?>" stroke-dashoffset="<?= $dashOffset ?>"
                            stroke-linecap="round" transform="rotate(-90 50 50)" />
                    </svg>
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-weight: bold; font-size: 16px;">
                        <?= $ind['value'] ?><?= $key === 'dti' ? '' : '%' ?>
                    </div>
                </div>
                <div style="font-size: 12px; font-weight: bold; margin-top: 10px; color: #333;"><?= $ind['label'] ?></div>
                <div style="font-size: 10px; color: var(--clr-gray);"><?= $ind['desc'] ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 30px;">
        <div>
            <div class="section-title">Dostupný kapitál</div>
            <div class="info-grid" style="grid-template-columns: 1fr;">
                <?php
                $rows = $mortgage_cap['capital_table'] ?? [];
                foreach ($rows as $row):
                    $rawVal = $row['value']['value'] ?? 0;
                ?>
                    <div class="info-row" style="padding: 10px 15px;">
                        <span class="info-label"><?= htmlspecialchars($row['label'] ?? '') ?></span>
                        <span class="info-val"><?= format_czk((float)$rawVal) ?> Kč</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div>
            <div class="section-title">Porovnání</div>
            <div style="background: #fcfaf8; border-radius: 14px; padding: 20px; height: 250px; display: flex; align-items: flex-end; justify-content: space-around; border: 1px solid #f0ebe5;">
                <div style="text-align: center; width: 40px;">
                    <div style="background: #f0f0f0; height: 200px; width: 100%; border-radius: 4px; position: relative;">
                        <div style="background: var(--clr-primary); height: 80%; width: 100%; border-radius: 4px; position: absolute; bottom: 0;"></div>
                    </div>
                    <div style="font-size: 10px; margin-top: 8px;">Majetek</div>
                </div>
                <div style="text-align: center; width: 40px;">
                    <div style="background: #f0f0f0; height: 200px; width: 100%; border-radius: 4px; position: relative;">
                        <div style="background: #D6B89E; height: 30%; width: 100%; border-radius: 4px; position: absolute; bottom: 0;"></div>
                    </div>
                    <div style="font-size: 10px; margin-top: 8px;">Kapitál</div>
                </div>
            </div>
        </div>
    </div>

    <div class="diverz-box" style="margin-top: 40px;">
        <div class="diverz-icon"><i class="fa-solid fa-calculator"></i></div>
        <div class="diverz-text">
            <h3>Dostupná výše financování</h3>
            <p>Na základě vaší bonity a aktuálních parametrů ČNB (LTV, DTI, DSTI) máte prostor pro financování dalších investičních nemovitostí až do výše 2 780 000 Kč vlastních zdrojů.</p>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- PAGE 6 – Chraňte svůj majetek&příjmy                         -->
<!-- ============================================================ -->

<div class="page">
    <h1 class="main-title">
        <span class="gray">Chraňte svůj majetek</span><br>a své příjmy
    </h1>
    <p class="page-subtitle">
        Chránit svůj obytný majetek je klíčové pro zajištění bezpečí a stability domova.
        Kvalitní pojištění poskytuje finanční ochranu před nepředvídatelnými událostmi.
    </p>

    <div class="protection-wrapper">
        <table class="protection-table" style="width: 100%; border-spacing: 15px 0; border-collapse: separate; margin-left: -15px;">
            <tr>
                <?php
                $protectionItems = $GLOBALS['protection']['items'] ?? [];
                foreach ($protectionItems as $item):
                ?>
                    <td class="protection-card" style="width: 50%; background: #fcfaf8; border: 1px solid #f0ebe5; border-radius: 18px; padding: 20px; vertical-align: top;">
                        <div class="protection-card-title" style="font-weight: 700; font-size: 16px; color: #3d3229; margin-bottom: 4px;"><?= htmlspecialchars($item['name']) ?></div>
                        <div class="protection-card-value" style="font-size: 15px; font-weight: 700; color: #3d3229; margin-bottom: 12px;"><?= number_format((float)$item['value'], 0, ',', ' ') ?> Kč</div>

                        <div class="badge-container" style="margin-bottom: 12px;">
                            <?php foreach (($item['badges'] ?? []) as $badge): ?>
                                <span class="badge-item" style="display: inline-block; background: #D6B89E; color: white; padding: 3px 10px; border-radius: 6px; font-size: 9px; font-weight: 600; margin-right: 4px; text-transform: uppercase;"><?= htmlspecialchars($badge) ?></span>
                            <?php endforeach; ?>
                        </div>

                        <div class="protection-date" style="font-size: 11px; color: #8c8c8c;">Pojištěno do: <?= htmlspecialchars($item['date_to']) ?></div>
                    </td>
                <?php endforeach; ?>
            </tr>
        </table>
    </div>

    <div style="margin-top: 40px;">
        <h2 style="font-family: 'Lora', serif; font-size: 28px; color: #3d3229; margin-bottom: 25px;">
            A teď společně <span style="color: var(--clr-primary);">naplníme vaše sny!</span>
        </h2>

        <?php
        $dreamGoals = $GLOBALS['dreams']['goals'] ?? [];
        foreach ($dreamGoals as $goal):
        ?>
            <div class="goal-row" style="background: white; border: 1px solid #f0ebe5; border-radius: 18px; padding: 15px 20px; margin-bottom: 15px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 60px; vertical-align: middle;">
                            <div class="goal-icon-circle" style="width: 40px; height: 40px; background: #fcfaf8; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #D6B89E; font-size: 20px; font-weight: bold;">+</div>
                        </td>
                        <td style="vertical-align: middle;">
                            <div style="font-weight: 700; color: #3d3229; font-size: 16px; line-height: 1.2;">
                                <?= htmlspecialchars($goal['name']) ?>
                            </div>
                            <div class="goal-meta" style="font-size: 11px; color: #8c8c8c;">
                                <?= htmlspecialchars($goal['type'] ?? '') ?> • <?= htmlspecialchars($goal['priority'] ?? '') ?>
                            </div>
                        </td>
                        <td style="text-align: right; vertical-align: middle;">
                            <div class="goal-amount" style="font-weight: 700; color: #3d3229; font-size: 15px;">
                                <?= number_format((float)$goal['amount'], 0, ',', ' ') ?> Kč
                            </div>
                            <div class="goal-meta" style="font-size: 11px; color: #8c8c8c;">
                                Splnit do: <?= htmlspecialchars($goal['deadline'] ?? '') ?>
                            </div>
                        </td>
                    </tr>
                </table>
                <div style="background: #fcfaf8; padding: 8px 15px; border-radius: 10px; margin-top: 10px; font-size: 11px; color: #3d3229; border: 1px solid #f0f0f0;">
                    Zbývá: <span style="font-weight: 700;"><?= number_format((float)$goal['amount'], 0, ',', ' ') ?> Kč</span> / <?= number_format((float)($goal['monthly'] ?? 0), 0, ',', ' ') ?> Kč p.m.
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ============================================================ -->
<!-- PAGE 7 – Plnění cílů                                         -->
<!-- ============================================================ -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div style="page-break-before: always; clear: both;"></div>

<div class="page" style="background: white;">

    <div class="p7-box">
        <div style="width: 280px; background: rgba(255,255,255,0.08); padding: 20px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.1);">
            <div style="width: 35px; height: 35px; margin-bottom: 10px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="#D6B89E" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <circle cx="12" cy="12" r="6" />
                    <circle cx="12" cy="12" r="2" />
                </svg>
            </div>
            <div style="font-size: 17px; line-height: 1.3;">
                Stačí pouze <span style="font-weight: 700;"><?= number_format($GLOBALS['investment_plan']['monthly_savings'] ?? 5000, 0, ',', ' ') ?> Kč</span> měsíčně s výnosem <span style="color: #D6B89E; font-weight: 700;"><?= str_replace('.', ',', $GLOBALS['investment_plan']['expected_yield'] ?? '7,54') ?> %</span>
            </div>
        </div>

        <div style="flex-grow: 1; margin-left: 30px; height: 150px; position: relative;">
            <canvas id="investmentChart"></canvas>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 20px;">
        <h1 style="margin: 0; font-size: 28px; line-height: 1.1; color: #3d3229;">
            Podíváme se<br>společně jak na to!
        </h1>
        <p style="text-align: right; color: #8c8c8c; font-size: 13px; width: 220px; margin: 0;">
            Ukážeme vám jak naplnit vaše sny krok za krokem
        </p>
    </div>

    <div class="timeline-wrapper" style="margin-top: 10px;">
        <div class="timeline-year">2024</div>
        <?php
        $timeline = $GLOBALS['investment_plan']['timeline'] ?? [];
        foreach ($timeline as $inv):
        ?>
            <div class="invest-card">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 150px; vertical-align: middle;">
                            <div class="invest-amount-box">
                                <div style="font-weight: 700; font-size: 17px; color: #3d3229;">
                                    <?= number_format($inv['amount'], 0, ',', ' ') ?> Kč
                                </div>
                                <?php if (!empty($inv['is_monthly'])): ?>
                                    <div style="font-size: 11px; color: #3d3229; margin-top: -2px;">měsíčně</div>
                                <?php endif; ?>
                                <div style="font-size: 10px; color: #8c8c8c; text-transform: uppercase; margin-top: 4px; font-weight: 600;">
                                    <?= htmlspecialchars($inv['company']) ?>
                                </div>
                            </div>
                        </td>
                        <td style="padding-left: 20px; vertical-align: middle;">
                            <div style="font-size: 10px; color: #8c8c8c; margin-bottom: 2px;"><?= htmlspecialchars($inv['period']) ?></div>
                            <div style="font-weight: 600; color: #3d3229; font-size: 13px; line-height: 1.2;">
                                <?= htmlspecialchars($inv['fund_name']) ?> <span style="color: #D6B89E;"><?= htmlspecialchars($inv['yield_range']) ?></span>
                            </div>
                        </td>
                        <td style="text-align: right; vertical-align: middle; width: 200px;">
                            <div style="font-size: 9px; color: #8c8c8c; text-transform: uppercase; margin-bottom: 5px; letter-spacing: 0.5px;">
                                <?= htmlspecialchars($inv['target_type'] ?? 'Cíl investice') ?>
                            </div>
                            <div style="background: #fcfaf8; border: 1px solid #f0ebe5; border-radius: 8px; padding: 8px 10px; font-size: 10.5px; color: #3d3229; text-align: left; line-height: 1.2;">
                                <?= htmlspecialchars($inv['action']) ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    (function() {
        const ctx = document.getElementById('investmentChart').getContext('2d');
        const dataValues = <?= json_encode($GLOBALS['investment_plan']['chart_data'] ?? [450000, 520000, 610000, 780000, 950000, 1200000]) ?>;
        const labels = <?= json_encode($GLOBALS['investment_plan']['chart_labels'] ?? ['2024', '2026', '2028', '2030', '2032', '2034']) ?>;

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    data: dataValues,
                    borderColor: '#D6B89E',
                    backgroundColor: 'rgba(214, 184, 158, 0.15)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: 'rgba(255,255,255,0.4)',
                            font: {
                                size: 9
                            }
                        }
                    },
                    y: {
                        display: false
                    }
                }
            }
        });
    })();
</script>

<!-- ============================================================ -->
<!-- PAGE 8 – Životní pojištění                                   -->
<!-- ============================================================ -->

<div style="page-break-before: always; clear: both;"></div>

<div class="page">

    <div class="timeline-container">
        <div class="timeline-line"></div>

        <div style="position: relative;">
            <div class="timeline-year-label">2027</div>
            <div class="timeline-card">
                <div class="timeline-card-main">
                    <div class="timeline-icon-circle"><i class="fa-solid fa-check"></i></div>
                    <i class="fa-solid fa-car" style="font-size: 24px; color: #3d3229;"></i>
                    <div class="timeline-card-info">
                        <h4>Auto</h4>
                        <span>Splněno!</span>
                    </div>
                </div>
                <div class="timeline-card-right">
                    <div class="tag-box">400 000 Kč</div>
                    <div class="tag-box">15.8.2027</div>
                </div>
            </div>
        </div>

        <div style="position: relative;">
            <div class="timeline-year-label">2034</div>
            <div class="timeline-card timeline-card--future" style="border-color: #f0f0f0;">
                <div class="timeline-card-main">
                    <div style="width: 36px;"></div>
                    <div class="timeline-card-info">
                        <h4 style="font-size: 16px;">5 000 000 Kč</h4>
                        <div style="color: #8c8c8c; font-size: 11px;">Palackého třída 15, 3+1</div>
                    </div>
                </div>
                <div style="flex-grow: 1; padding-left: 30px; font-size: 12px; color: #3d3229;">
                    od 15. 8. 2024 do 15. 8. 2034<br><strong>Prodej bytu</strong>
                </div>
                <div class="tag-box">Dlouhodobý cíl</div>
            </div>
        </div>

        <div style="position: relative;">
            <div class="timeline-year-label">2033</div>
            <div class="timeline-card">
                <div class="timeline-card-main">
                    <div class="timeline-icon-circle"><i class="fa-solid fa-check"></i></div>
                    <i class="fa-solid fa-plane" style="font-size: 24px; color: #3d3229;"></i>
                    <div class="timeline-card-info">
                        <h4>Cesta kolem světa</h4>
                        <span>Splněno!</span>
                    </div>
                </div>
                <div class="timeline-card-right">
                    <div class="tag-box">280 000 Kč</div>
                    <div class="tag-box">15.8.2033</div>
                </div>
            </div>
        </div>

        <div style="position: relative;">
            <div class="timeline-year-label">2055</div>
            <div class="timeline-card">
                <div class="timeline-card-main">
                    <div class="timeline-icon-circle"><i class="fa-solid fa-check"></i></div>
                    <i class="fa-solid fa-house-user" style="font-size: 24px; color: #3d3229;"></i>
                    <div class="timeline-card-info">
                        <h4>Renta</h4>
                        <span>Splněno!</span>
                    </div>
                </div>
                <div class="timeline-card-right">
                    <div class="tag-box">50 000 Kč měsíčně</div>
                    <div class="tag-box">15.8.2055</div>
                </div>
            </div>
        </div>
    </div>

    <div style="background: #f4faf7; border: 1px solid #2ecc71; border-radius: 15px; padding: 15px 25px; margin: 30px 0; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="margin: 0; color: #3d3229; font-family: 'Lora', serif;">Všechny vaše si splníte!</h3>
            <p style="margin: 5px 0 0 0; font-size: 12px; color: #8c8c8c;">Díky naší spolupráci se nám podaří naplnit všechny vaše sny.</p>
        </div>
        <div style="background: #2ecc71; color: white; font-weight: 700; padding: 5px 15px; border-radius: 20px; font-size: 18px;">100%</div>
    </div>

    <h2 style="font-family: 'Lora', serif; font-size: 32px; color: #3d3229; margin-bottom: 10px;">Životní pojištění</h2>
    <p style="font-size: 12px; color: #3d3229; line-height: 1.5; margin-bottom: 25px;">
        Pojistné částky v případě smrti a podobných událostí jsou často podceňovaným, ale velmi důležitým aspektem našeho života.
        Může se zdát nepříjemné přemýšlet o takových situacích, ale mít v těchto případech pojistnou ochranu znamená, že vaše rodina bude finančně zajištěna.
    </p>

    <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 20px;">
        <div style="border: 1px solid #f0ebe5; border-radius: 15px; padding: 20px; position: relative;">
            <div style="color: #D6B89E; font-size: 24px; margin-bottom: 10px;"><i class="fa-solid fa-hand-holding-heart"></i></div>
            <h4 style="margin: 0 0 10px 0;">Pojištění pro případ úmrtí</h4>
            <p style="font-size: 11px; color: #8c8c8c; margin: 0;">Jaké závazky po sobě zanecháte? Přejete si své nejbližší finančně zajistit?</p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; border: 1px solid #f0ebe5; border-radius: 15px; padding: 20px;">
            <div style="font-size: 12px;"><i class="fa-solid fa-band-aid" style="color: #D6B89E; margin-right: 8px;"></i> Úraz</div>
            <div style="font-size: 12px;"><i class="fa-solid fa-briefcase-medical" style="color: #D6B89E; margin-right: 8px;"></i> Pracovní neschopnost </div>
            <div style="font-size: 12px;"><i class="fa-solid fa-hospital" style="color: #D6B89E; margin-right: 8px;"></i> Hospitalizace</div>
            <div style="font-size: 12px;"><i class="fa-solid fa-virus" style="color: #D6B89E; margin-right: 8px;"></i> Závažné onemocnění</div>
        </div>
    </div>


</div>

<!-- ============================================================ -->
<!-- PAGE 9 – Výpadky příjmu                                      -->
<!-- ============================================================ -->

<div style="page-break-before: always; clear: both;"></div>

<div class="page">

    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px;">
        <div>
            <h2 style="font-family: 'Lora', serif; font-size: 28px; color: #3d3229; margin: 0;">Aktuální nastavení vašeho zabezpečení</h2>
            <div style="background: #e74c3c; color: white; display: inline-block; padding: 3px 12px; border-radius: 5px; font-size: 12px; font-weight: bold; margin-top: 5px;">
                Nedostatečné
            </div>
        </div>
    </div>

    <div style="margin-bottom: 40px;">
        <h3 style="color: #3d3229; border-left: 4px solid #D6B89E; padding-left: 15px; margin-bottom: 15px;">Krátkodobý výpadek příjmu – do jednoho roku</h3>
        <p style="font-size: 12px; color: #8c8c8c; line-height: 1.6; margin-bottom: 20px;">
            Analýza situace při pracovní neschopnosti. Graf znázorňuje propad příjmu oproti běžným výdajům a vliv vaší aktuální rezervy.
        </p>

        <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 30px;">
            <div style="background: #fcfaf8; border-radius: 15px; padding: 20px; height: 200px; display: flex; align-items: flex-end; gap: 10px; position: relative; border: 1px solid #f0ebe5;">
                <div style="position: absolute; left: 10px; top: 10px; bottom: 30px; border-right: 1px solid #ddd; padding-right: 5px; font-size: 9px; color: #aaa; display: flex; flex-direction: column; justify-content: space-between;">
                    <span>30 000</span><span>20 000</span><span>10 000</span><span>0</span>
                </div>
                <div style="flex: 1; height: 100%; background: #eee; border-radius: 3px 3px 0 0;"></div>
                <div style="flex: 1; height: 90%; background: #eee; border-radius: 3px 3px 0 0;"></div>
                <div style="flex: 1; height: 80%; background: #eee; border-radius: 3px 3px 0 0;"></div>
                <div style="flex: 1; height: 60%; background: #D6B89E; border-radius: 3px 3px 0 0; position: relative;">
                    <div style="position: absolute; top: -20px; width: 100%; text-align: center; font-size: 10px; font-weight: bold;">Nemoc</div>
                </div>
                <div style="flex: 1; height: 60%; background: #3d3229; border-radius: 3px 3px 0 0;"></div>
            </div>

            <div style="font-size: 12px;">
                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">
                    <span>Snížený příjem (nemocenská)</span>
                    <span style="font-weight: bold;">8 000 Kč</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">
                    <span>Příjem partnera + ostatní</span>
                    <span style="font-weight: bold;">7 000 Kč</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 2px solid #3d3229; color: #e74c3c;">
                    <span>Měsíčně chybí</span>
                    <span style="font-weight: bold;">-15 000 Kč</span>
                </div>
                <div style="background: #fdf2f2; padding: 15px; border-radius: 10px; margin-top: 15px; border: 1px solid #fadbd8;">
                    <strong style="color: #c0392b;">Rezerva 120 000 Kč vydrží na:</strong>
                    <div style="font-size: 20px; font-weight: bold; color: #3d3229; margin-top: 5px;">3,24 měsíce</div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <h3 style="color: #3d3229; border-left: 4px solid #3d3229; padding-left: 15px; margin-bottom: 15px;">Dlouhodobý výpadek příjmu (Invalidita)</h3>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">

            <div style="border: 1px solid #f0ebe5; border-radius: 15px; padding: 15px; background: #fff;">
                <h4 style="margin: 0 0 10px 0; font-size: 14px; color: #D6B89E;">I. stupeň</h4>
                <table style="width: 100%; font-size: 11px; border-collapse: collapse;">
                    <tr style="border-bottom: 1px solid #f9f9f9;">
                        <td style="padding: 4px 0;">Státní důchod</td>
                        <td style="text-align: right;">14 485 Kč</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f9f9f9;">
                        <td style="padding: 4px 0;">Příjem partnera</td>
                        <td style="text-align: right;">23 000 Kč</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f9f9f9;">
                        <td style="padding: 4px 0;">Ostatní příjmy</td>
                        <td style="text-align: right;">2 000 Kč</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #3d3229;">
                        <td style="padding: 4px 0;">Nezbytné výdaje</td>
                        <td style="text-align: right; color: #e74c3c;">-42 000 Kč</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Měsíčně zbývá</td>
                        <td style="text-align: right; font-weight: bold; color: #e74c3c;">-2 000 Kč</td>
                    </tr>
                </table>
            </div>

            <div style="border: 1px solid #f0ebe5; border-radius: 15px; padding: 15px; background: #fff;">
                <h4 style="margin: 0 0 10px 0; font-size: 14px; color: #D6B89E;">II. stupeň</h4>
                <table style="width: 100%; font-size: 11px; border-collapse: collapse;">
                    <tr style="border-bottom: 1px solid #f9f9f9;">
                        <td style="padding: 4px 0;">Státní důchod</td>
                        <td style="text-align: right;">14 485 Kč</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f9f9f9;">
                        <td style="padding: 4px 0;">Příjem partnera</td>
                        <td style="text-align: right;">23 000 Kč</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f9f9f9;">
                        <td style="padding: 4px 0;">Ostatní příjmy</td>
                        <td style="text-align: right;">2 000 Kč</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #3d3229;">
                        <td style="padding: 4px 0;">Nezbytné výdaje</td>
                        <td style="text-align: right; color: #e74c3c;">-42 000 Kč</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Měsíčně zbývá</td>
                        <td style="text-align: right; font-weight: bold; color: #e74c3c;">-7 000 Kč</td>
                    </tr>
                </table>
            </div>

            <div style="border: 1px solid #f0ebe5; border-radius: 15px; padding: 15px; background: #fff; border-color: #D6B89E;">
                <h4 style="margin: 0 0 10px 0; font-size: 14px; color: #D6B89E;">III. stupeň</h4>
                <div style="text-align: center; padding: 20px 0;">
                    <div style="font-size: 10px; color: #8c8c8c;">Pojistné plnění</div>
                    <div style="font-size: 18px; font-weight: bold; color: #3d3229;">1 500 000 Kč</div>
                    <div style="margin-top: 10px; font-size: 11px; color: #27ae60; font-weight: bold;">Plně zajištěno</div>
                </div>
            </div>

        </div>
    </div>

    <div style="margin-top: 30px; background: #fcfaf8; padding: 15px; border-radius: 10px; font-size: 11px; color: #8c8c8c; border: 1px solid #f0ebe5;">
        <strong>Poznámka:</strong> Výpočty vycházejí z aktuálně platné legislativy pro rok 2024 a nezahrnují budoucí valorizace státních důchodů.
    </div>

</div>

<!-- ============================================================ -->
<!-- PAGE 10 – Celkový přehled Vašeho životní pojištění           -->
<!-- ============================================================ -->


<?php
$clientRow = isset($data['health']['health_client']['rows'][0]) ? $data['health']['health_client']['rows'][0] : null;

if (!function_exists('getVal')) {
    function getVal($obj, $suffix = ' Kč')
    {
        if (!isset($obj['value']) || $obj['value'] === "") return '0' . $suffix;
        $val = $obj['value'];
        if (is_numeric($val)) {
            $num = (float)$val;
            if ($num < 0) {
                return '- ' . number_format(abs($num), 0, ',', ' ') . $suffix;
            }
            return number_format($num, 0, ',', ' ') . $suffix;
        }
        return $val . $suffix;
    }
}
?>

<div class="page" style="padding: 40px; font-family: 'Arial', sans-serif; color: #3d3229; background: #fff;">

    <h1 style="font-size: 26px; font-weight: bold; margin-bottom: 5px;">Pojištění pro případ smrti</h1>
    <p style="font-size: 11px; line-height: 1.4; margin-bottom: 30px; color: #555; max-width: 85%;">
        Pojistné částky v případě smrti a podobných událostí jsou často podceňovaným, ale velmi důležitým aspektem našeho života.
        Mít v těchto případech pojistnou ochranu znamená, že vaše rodina bude finančně zajištěna i v nečekaných situacích.
    </p>

    <div style="display: flex; gap: 40px; margin-bottom: 60px; align-items: flex-end;">
        <div style="flex: 1.1; position: relative; height: 180px; border-bottom: 1px solid #ddd; padding-left: 40px; margin-bottom: 25px;">
            <div style="position: absolute; left: 0; height: 100%; display: flex; flex-direction: column; justify-content: space-between; font-size: 9px; color: #999; padding-bottom: 1px;">
                <span>40 000</span><span>30 000</span><span>20 000</span><span>10 000</span><span>0</span>
            </div>

            <div style="display: flex; align-items: flex-end; justify-content: space-around; height: 100%; position: relative;">
                <div style="text-align: center; position: relative;">
                    <div style="background: #f0f0f0; width: 55px; height: 145px; border-radius: 12px 12px 0 0;"></div>
                    <div style="position: absolute; top: 100%; left: 0; right: 0; padding-top: 8px; font-size: 10px; color: #666;">Výdaje</div>
                </div>

                <div style="text-align: center; position: relative;">
                    <div style="background: #8c6239; width: 55px; height: 175px; border-radius: 12px 12px 0 0;"></div>
                    <div style="position: absolute; top: 100%; left: 0; right: 0; padding-top: 8px; font-size: 10px; color: #666;">Inv. I</div>
                </div>
            </div>
        </div>

        <div style="flex: 1;">
            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #eee; color: #666;">Hypotéka</td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #eee; text-align: right; font-weight: bold;">
                        <?= ($clientRow) ? getVal($clientRow['death_mortgage']) : '- 3 000 000 Kč' ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #eee; color: #666;">Úvěry</td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #eee; text-align: right; font-weight: bold;">
                        <?= ($clientRow) ? getVal($clientRow['death_loans']) : '- 180 000 Kč' ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #eee; color: #666;">Pojištění</td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #eee; text-align: right; font-weight: bold;">
                        <?= ($clientRow) ? getVal($clientRow['death_insurance_total']) : '4 020 000 Kč' ?>
                    </td>
                </tr>
                <tr style="background: #8c6239; color: white;">
                    <td style="padding: 12px 10px; font-weight: bold; border-radius: 5px 0 0 5px;">Předané krytí</td>
                    <td style="padding: 12px 10px; text-align: right; font-weight: bold; border-radius: 0 5px 5px 0;">
                        <?= ($clientRow) ? getVal($clientRow['death_surplus']) : '350 000 Kč' ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <h2 style="font-size: 26px; font-weight: bold; margin-bottom: 5px;">Celkový přehled Vašeho životního pojištění.</h2>
    <p style="font-size: 12px; font-style: italic; margin-bottom: 25px; color: #666;">Hlavní pojištěný</p>

    <table style="width: 100%; border-collapse: separate; border-spacing: 0 4px; font-size: 12px;">
        <thead>
            <tr style="color: #666; font-size: 11px;">
                <th style="width: 25px;"></th>
                <th style="width: 33%; text-align: left; padding: 6px 0 6px 10px; background: #f4f4f4; border-radius: 5px 0 0 0;">Pojistná rizika</th>
                <th style="width: 32%; text-align: center; background: #8c6239; color: white;">Aktuální stav</th>
                <th style="width: 32%; text-align: center; background: #a5836a; color: white; border-radius: 0 5px 0 0;">Navrhovaný stav</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td rowspan="5" style="background: #fff4e6; width: 25px; padding: 0; text-align: center; border-radius: 8px 0 0 8px; vertical-align: middle;">
                    <div style="writing-mode: vertical-lr; transform: rotate(180deg); font-weight: bold; text-transform: uppercase; font-size: 8px; letter-spacing: 1px; color: #3d3229; line-height: 25px; margin: 0 auto;">
                        Závazky
                    </div>
                </td>
                <td colspan="3" style="padding: 10px 0 4px 10px; font-weight: bold; font-size: 13px; color: #3d3229;">Smrt obecná</td>
            </tr>
            <tr>
                <td style="padding: 6px 12px; background: white; border: 1px solid #eee;">Konstantní</td>
                <td style="text-align: center; background: white; border: 1px solid #eee;">1 500 000 Kč</td>
                <td style="text-align: center; background: white; border: 1px solid #eee;">2 500 000 Kč</td>
            </tr>
            <tr>
                <td style="padding: 6px 12px; background: white; border: 1px solid #eee;">Klesající</td>
                <td style="text-align: center; background: white; border: 1px solid #eee;">0 Kč</td>
                <td style="text-align: center; background: white; border: 1px solid #eee;">1 500 000 Kč</td>
            </tr>
            <tr>
                <td style="padding: 6px 12px; background: white; border: 1px solid #eee;">Úvěry</td>
                <td style="text-align: center; background: white; border: 1px solid #eee;">750 000 Kč</td>
                <td style="text-align: center; background: white; border: 1px solid #eee;">1 750 000 Kč</td>
            </tr>
            <tr>
                <td style="padding: 6px 12px; background: white; border: 1px solid #eee;">Smrt úrazem</td>
                <td style="text-align: center; background: white; border: 1px solid #eee;">500 000 Kč</td>
                <td style="text-align: center; background: white; border: 1px solid #eee;">2 500 000 Kč</td>
            </tr>

            <tr>
                <td colspan="4" style="height: 10px; padding: 0; border: none;"></td>
            </tr>

            <tr>
                <td rowspan="9" style="background: #e5e5e5; width: 25px; text-align: center; border-radius: 8px 0 0 8px; vertical-align: middle; padding: 0;">
                    <div style="writing-mode: vertical-lr; transform: rotate(180deg); font-weight: bold; text-transform: uppercase; font-size: 8px; letter-spacing: 1px; white-space: nowrap; color: #3d3229; line-height: 25px; margin: 0 auto;">
                        Krátkodobý výpadek příjmu
                    </div>
                </td>
                <td colspan="3" style="padding: 10px 0 4px 10px; font-weight: bold; font-size: 13px; color: #3d3229;">Denní odškodné</td>
            </tr>
            <tr>
                <td style="padding: 6px 12px; background: white; border: 1px solid #eee;">od 21. dne</td>
                <td style="text-align: center; background: white; border: 1px solid #eee;">800 Kč</td>
                <td style="text-align: center; background: white; border: 1px solid #eee;">700 Kč</td>
            </tr>
            <tr>
                <td style="padding: 6px 12px; background: white; border: 1px solid #eee;">od 28. dne</td>
                <td style="text-align: center; background: white; border: 1px solid #eee;">0 Kč</td>
                <td style="text-align: center; background: white; border: 1px solid #eee;">700 Kč</td>
            </tr>
            <tr>
                <td style="padding: 6px 12px; background: white; border: 1px solid #eee;">od 42. dne</td>
                <td style="text-align: center; background: white; border: 1px solid #eee;">500 Kč</td>
                <td style="text-align: center; background: white; border: 1px solid #eee;">700 Kč</td>
            </tr>
            <tr>
                <td colspan="3" style="padding: 14px 0 4px 10px; font-weight: bold; font-size: 13px; color: #3d3229;">Pracovní neschopnost</td>
            </tr>
            <tr>
                <td style="padding: 6px 12px; background: white; border: 1px solid #eee;">od 21. dne</td>
                <td style="text-align: center; background: white; border: 1px solid #eee;">800 Kč</td>
                <td style="text-align: center; background: white; border: 1px solid #eee;">700 Kč</td>
            </tr>
            <tr>
                <td style="padding: 6px 12px; background: white; border: 1px solid #eee;">od 28. dne</td>
                <td style="text-align: center; background: white; border: 1px solid #eee;">0 Kč</td>
                <td style="text-align: center; background: white; border: 1px solid #eee;">700 Kč</td>
            </tr>
            <tr>
                <td style="padding: 6px 12px; background: white; border: 1px solid #eee;">od 42. dne</td>
                <td style="text-align: center; background: white; border: 1px solid #eee;">500 Kč</td>
                <td style="text-align: center; background: white; border: 1px solid #eee;">700 Kč</td>
            </tr>
        </tbody>
    </table>

    <!-- ============================================================ -->
    <!-- PAGE 11                                                      -->
    <!-- ============================================================ -->

    <div class="page" style="padding: 40px; font-family: 'Arial', sans-serif; color: #3d3229; background: #fff;">
        <table style="width: 100%; border-collapse: separate; border-spacing: 0 4px; font-size: 12px; table-layout: fixed; margin-left: -25px;">
            <thead>
                <tr style="height: 0; line-height: 0;">
                    <th style="width: 25px; padding: 0; border: none;"></th>
                    <th style="width: 240px; padding: 0; border: none;"></th>
                    <th style="width: 220px; padding: 0; border: none;"></th>
                    <th style="width: 225px; padding: 0; border: none;"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td rowspan="6" style="background: #dbd8cd; padding: 0; text-align: center; border-radius: 8px 0 0 8px; vertical-align: middle;">
                        <div style="writing-mode: vertical-lr; transform: rotate(180deg); font-weight: bold; text-transform: uppercase; font-size: 8px; letter-spacing: 1px; color: #3d3229; line-height: 25px; margin: 0 auto;">
                            Krátkodobý výpadek příjmu
                        </div>
                    </td>
                    <td colspan="3" style="padding: 10px 0 4px 10px; font-weight: bold; font-size: 13px;">Hospitalizace</td>
                </tr>
                <tr>
                    <td style="padding: 6px 12px; background: white; border: 1px solid #eee; border-radius: 5px;">úraz</td>
                    <td style="text-align: center; background: white; border: 1px solid #eee; border-radius: 5px;">800 Kč</td>
                    <td style="text-align: center; background: white; border: 1px solid #eee; border-radius: 5px;">700 Kč</td>
                </tr>
                <tr>
                    <td style="padding: 6px 12px; background: white; border: 1px solid #eee; border-radius: 5px;">úraz / nemoc</td>
                    <td style="text-align: center; background: white; border: 1px solid #eee; border-radius: 5px;">0 Kč</td>
                    <td style="text-align: center; background: white; border: 1px solid #eee; border-radius: 5px;">700 Kč</td>
                </tr>
                <tr>
                    <td colspan="3" style="padding: 14px 0 4px 10px; font-weight: bold; font-size: 13px;">Vážné onemocnění</td>
                </tr>
                <tr>
                    <td style="padding: 6px 12px; background: white; border: 1px solid #eee; border-radius: 5px;">Konstantní</td>
                    <td style="text-align: center; background: white; border: 1px solid #eee; border-radius: 5px;">800 Kč</td>
                    <td style="text-align: center; background: white; border: 1px solid #eee; border-radius: 5px;">700 Kč</td>
                </tr>
                <tr>
                    <td style="padding: 6px 12px; background: white; border: 1px solid #eee; border-radius: 5px;">Klesající</td>
                    <td style="text-align: center; background: white; border: 1px solid #eee; border-radius: 5px;">0 Kč</td>
                    <td style="text-align: center; background: white; border: 1px solid #eee; border-radius: 5px;">700 Kč</td>
                </tr>

                <tr>
                    <td colspan="4" style="height: 10px; border: none;"></td>
                </tr>

                <tr>
                    <td rowspan="8" style="background: #b2a688; padding: 0; text-align: center; border-radius: 8px 0 0 8px; vertical-align: middle;">
                        <div style="writing-mode: vertical-lr; transform: rotate(180deg); font-weight: bold; text-transform: uppercase; font-size: 8px; letter-spacing: 1px; color: #fff; line-height: 25px; margin: 0 auto;">
                            Dlouhodobý výpadek příjmu
                        </div>
                    </td>
                    <td colspan="3" style="padding: 10px 0 4px 10px; font-weight: bold; font-size: 13px;">Trvalé následky (progresivní plnění)</td>
                </tr>
                <tr>
                    <td style="padding: 6px 12px; background: white; border: 1px solid #eee; border-radius: 5px;">od 0,1 %</td>
                    <td style="text-align: center; background: white; border: 1px solid #eee; border-radius: 5px;">1 500 000 Kč</td>
                    <td style="text-align: center; background: white; border: 1px solid #eee; border-radius: 5px;">2 500 000 Kč</td>
                </tr>
                <tr>
                    <td style="padding: 6px 12px; background: white; border: 1px solid #eee; border-radius: 5px;">od 10 %</td>
                    <td style="text-align: center; background: white; border: 1px solid #eee; border-radius: 5px;">0 Kč</td>
                    <td style="text-align: center; background: white; border: 1px solid #eee; border-radius: 5px;">1 500 000 Kč</td>
                </tr>
                <tr>
                    <td style="padding: 6px 12px; background: white; border: 1px solid #eee; border-radius: 5px;">autonehoda od 25 %</td>
                    <td style="text-align: center; background: white; border: 1px solid #eee; border-radius: 5px;">750 000 Kč</td>
                    <td style="text-align: center; background: white; border: 1px solid #eee; border-radius: 5px;">1 750 000 Kč</td>
                </tr>
                <tr>
                    <td colspan="3" style="padding: 14px 0 4px 10px; font-weight: bold; font-size: 13px;">Invalidita</td>
                </tr>
                <tr>
                    <td style="padding: 6px 12px; background: white; border: 1px solid #eee; border-radius: 5px;">III. stupeň</td>
                    <td style="text-align: center; background: white; border: 1px solid #eee; border-radius: 5px;">800 Kč</td>
                    <td style="text-align: center; background: white; border: 1px solid #eee; border-radius: 5px;">700 Kč</td>
                </tr>
                <tr>
                    <td style="padding: 6px 12px; background: white; border: 1px solid #eee; border-radius: 5px;">II. stupeň</td>
                    <td style="text-align: center; background: white; border: 1px solid #eee; border-radius: 5px;">0 Kč</td>
                    <td style="text-align: center; background: white; border: 1px solid #eee; border-radius: 5px;">700 Kč</td>
                </tr>
                <tr>
                    <td style="padding: 6px 12px; background: white; border: 1px solid #eee; border-radius: 5px;">I. stupeň</td>
                    <td style="text-align: center; background: white; border: 1px solid #eee; border-radius: 5px;">500 Kč</td>
                    <td style="text-align: center; background: white; border: 1px solid #eee; border-radius: 5px;">700 Kč</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- ============================================================ -->
    <!-- PAGE 12                                                      -->
    <!-- ============================================================ -->

    <div class="page" style="padding: 50px 40px; font-family: 'Arial', sans-serif; color: #3d3229; background: #fff; line-height: 1.5;">

        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px;">
            <div style="font-size: 64px; font-weight: bold; color: #3d3229;">64%</div>
            <div style="margin-top: 10px;">
                <svg width="34" height="26" viewBox="0 0 34 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 24H32" stroke="#8c6239" stroke-width="2" stroke-linecap="square" />
                    <rect x="5" y="15" width="6" height="9" stroke="#8c6239" stroke-width="2" stroke-linecap="square" />
                    <rect x="14" y="9" width="6" height="15" stroke="#8c6239" stroke-width="2" stroke-linecap="square" />
                    <rect x="23" y="2" width="6" height="22" stroke="#8c6239" stroke-width="2" stroke-linecap="square" />
                </svg>
            </div>
        </div>

        <p style="font-size: 13px; color: #666; margin-bottom: 45px; max-width: 900px;">
            Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Itaque earum rerum hic tenetur asapiente delectus, ut
            aut reiciendis voluptatibus maiores alias consequatur aut perferendisdoloribus asperiores.
        </p>

        <div style="display: flex; flex-direction: column; gap: 20px;">

            <div style="display: flex; align-items: center; gap: 20px;">
                <div style="width: 32px; height: 32px; border-radius: 50%; border: 1px solid #c5dcb2; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.5 6L6 10.5L14.5 1.5" stroke="#7ab55c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 25px 30px; display: flex; flex-grow: 1; align-items: flex-start; gap: 40px; border: 1px solid #f0f0f0;">
                    <div style="width: 180px; flex-shrink: 0;">
                        <div style="font-weight: bold; font-size: 15px; margin-bottom: 4px;">Rozvaha</div>
                        <div style="font-size: 12px; color: #666;">Cashflow a finanční bilance</div>
                    </div>
                    <div style="flex-grow: 1;">
                        <div style="font-size: 14px; font-weight: bold; margin-bottom: 8px; color: #3d3229;">78%</div>
                        <div style="font-size: 12px; color: #666;">Je potřeba snížit zbytečné výdaje.</div>
                    </div>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 20px;">
                <div style="width: 32px; height: 32px; border-radius: 50%; border: 1px solid #f8dfc2; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="4" height="16" viewBox="0 0 4 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 1V10M2 14V15" stroke="#f09639" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </div>
                <div style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 25px 30px; display: flex; flex-grow: 1; align-items: flex-start; gap: 40px; border: 1px solid #f0f0f0;">
                    <div style="width: 180px; flex-shrink: 0;">
                        <div style="font-weight: bold; font-size: 15px; margin-bottom: 4px;">Portfolio</div>
                        <div style="font-size: 12px; color: #666;">Aktiva & Pasiva</div>
                    </div>
                    <div style="flex-grow: 1; display: flex; justify-content: space-between; align-items: flex-start;">
                        <div style="max-width: 300px;">
                            <div style="font-size: 14px; font-weight: bold; margin-bottom: 8px; color: #3d3229;">28 %</div>
                            <div style="font-size: 12px; color: #666;">Váš majetek je výrazně zatížen pasivy.</div>
                        </div>
                        <a href="#" style="font-size: 11px; color: #3d3229; text-decoration: none; border: 1px solid #d2c8bc; padding: 7px 20px; border-radius: 4px; white-space: nowrap; margin-top: 5px;">Akční plán na straně 21.</a>
                    </div>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 20px;">
                <div style="width: 32px; height: 32px; border-radius: 50%; border: 1px solid #f8dfc2; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="4" height="16" viewBox="0 0 4 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 1V10M2 14V15" stroke="#f09639" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </div>
                <div style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 25px 30px; display: flex; flex-grow: 1; align-items: flex-start; gap: 40px; border: 1px solid #f0f0f0;">
                    <div style="width: 180px; flex-shrink: 0;">
                        <div style="font-weight: bold; font-size: 15px; margin-bottom: 4px;">Zajištění majetku</div>
                        <div style="font-size: 12px; color: #666;">Pojištění vašich aktiv</div>
                    </div>
                    <div style="flex-grow: 1; display: flex; justify-content: space-between; align-items: flex-start;">
                        <div style="max-width: 300px;">
                            <div style="font-size: 14px; font-weight: bold; margin-bottom: 8px; color: #3d3229;">55 %</div>
                            <div style="font-size: 12px; color: #666;">Téměř polovina majetku není chráněná.</div>
                        </div>
                        <a href="#" style="font-size: 11px; color: #3d3229; text-decoration: none; border: 1px solid #d2c8bc; padding: 7px 20px; border-radius: 4px; white-space: nowrap; margin-top: 5px;">Akční plán na straně 21.</a>
                    </div>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 20px;">
                <div style="width: 32px; height: 32px; border-radius: 50%; border: 1px solid #c5dcb2; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.5 6L6 10.5L14.5 1.5" stroke="#7ab55c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 25px 30px; display: flex; flex-grow: 1; align-items: flex-start; gap: 40px; border: 1px solid #f0f0f0;">
                    <div style="width: 180px; flex-shrink: 0;">
                        <div style="font-weight: bold; font-size: 15px; margin-bottom: 4px;">Finanční cíle</div>
                        <div style="font-size: 12px; color: #666;">Plánování a dosažení cílů</div>
                    </div>
                    <div style="flex-grow: 1;">
                        <div style="font-size: 14px; font-weight: bold; margin-bottom: 8px; color: #3d3229;">100 %</div>
                        <div style="font-size: 12px; color: #666;">Všechny cíle se nám podaří splnit.</div>
                    </div>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 20px;">
                <div style="width: 32px; height: 32px; border-radius: 50%; border: 1px solid #c5dcb2; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.5 6L6 10.5L14.5 1.5" stroke="#7ab55c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 25px 30px; display: flex; flex-grow: 1; align-items: flex-start; gap: 40px; border: 1px solid #f0f0f0;">
                    <div style="width: 180px; flex-shrink: 0;">
                        <div style="font-weight: bold; font-size: 15px; margin-bottom: 4px;">Zajištění zdraví</div>
                        <div style="font-size: 12px; color: #666;">Životní pojištění vás a vaší rodiny</div>
                    </div>
                    <div style="flex-grow: 1;">
                        <div style="font-size: 14px; font-weight: bold; margin-bottom: 8px; color: #3d3229;">65 %</div>
                        <div style="font-size: 12px; color: #666;">Některé oblasti je možné dále zlepšit.</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ============================================================ -->
    <!-- PAGE 13 – Akční plán                                         -->
    <!-- ============================================================ -->

    <div class="page" style="padding: 50px 40px; font-family: 'Arial', sans-serif; color: #3d3229; background: #fff; line-height: 1.4;">

        <div style="display: flex; justify-content: flex-start; align-items: center; margin-bottom: 25px;">
            <h1 style="font-size: 38px; font-weight: bold; margin: 0; color: #3d3229;">Akční plán</h1>
        </div>

        <p style="font-size: 12px; color: #666; margin-bottom: 40px; max-width: 850px;">
            Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Itaque earum rerum hic tenetur asapiente delectus, ut
            aut reiciendis voluptatibus maiores alias consequatur aut perferendisdoloribus asperiores.
        </p>

        <div style="margin-bottom: 40px;">
            <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 15px;">
                <div style="width: 30px; height: 30px; border-radius: 50%; border: 1px solid #f8dfc2; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <span style="color: #f09639; font-weight: bold; font-size: 16px;">!</span>
                </div>
                <div style="background: #f8f8f8; border-radius: 8px; padding: 15px 25px; display: flex; gap: 40px; align-items: center; flex-grow: 1;">
                    <div style="width: 140px;">
                        <div style="font-weight: bold; font-size: 14px;">Portfolio</div>
                        <div style="font-size: 11px; color: #888;">Aktiva & Pasiva</div>
                    </div>
                    <div>
                        <div style="font-size: 14px; font-weight: bold; margin-bottom: 2px;">28 %</div>
                        <div style="font-size: 12px;">Váš majetek je výrazně zatížen pasivy.</div>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-left: 50px;">
                <div style="border: 1px solid #c5dcb2; border-radius: 12px; padding: 15px 20px;">
                    <div style="font-weight: bold; font-size: 13px; margin-bottom: 8px;">Stavební spoření</div>
                    <div style="font-size: 11px; color: #666;">V říjnu zažádat výpověď smlouvy. Ověříme podpis na formuláři. Prostředky budou k dispozici v únoru 2025</div>
                </div>
                <div style="border: 1px solid #c5dcb2; border-radius: 12px; padding: 15px 20px;">
                    <div style="font-weight: bold; font-size: 13px; margin-bottom: 8px;">Penzijní připojištění</div>
                    <div style="font-size: 11px; color: #666;">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Duis pulvinar. Vestibulum erat nulla, ullamcorper nec, rutrum non, nonummy ac, erat.</div>
                </div>
                <div style="grid-column: span 2; border: 1px solid #c5dcb2; border-radius: 12px; padding: 15px 20px;">
                    <div style="font-weight: bold; font-size: 13px; margin-bottom: 8px;">Investice do zlata</div>
                    <div style="font-size: 11px; color: #666;">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Duis pulvinar. Vestibulum erat nulla, ullamcorper nec, rutrum non, nonummy ac, erat. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Duis pulvinar. Vestibulum erat nulla, ullamcorper nec, rutrum non, nonummy ac, erat.</div>
                </div>
            </div>
        </div>

        <div>
            <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 15px;">
                <div style="width: 30px; height: 30px; border-radius: 50%; border: 1px solid #f8dfc2; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <span style="color: #f09639; font-weight: bold; font-size: 16px;">!</span>
                </div>
                <div style="background: #f8f8f8; border-radius: 8px; padding: 15px 25px; display: flex; gap: 40px; align-items: center; flex-grow: 1;">
                    <div style="width: 140px;">
                        <div style="font-weight: bold; font-size: 14px;">Zajištění majetku</div>
                        <div style="font-size: 11px; color: #888;">Pojištění vašich aktiv</div>
                    </div>
                    <div>
                        <div style="font-size: 14px; font-weight: bold; margin-bottom: 2px;">55 %</div>
                        <div style="font-size: 12px;">Téměř polovina majetku není chráněná.</div>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-left: 50px;">
                <div style="border: 1px solid #c5dcb2; border-radius: 12px; padding: 15px 20px;">
                    <div style="font-weight: bold; font-size: 13px; margin-bottom: 8px;">Havarijní pojištění na auto</div>
                    <div style="font-size: 11px; color: #666;">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Duis pulvinar.</div>
                </div>
                <div style="border: 1px solid #c5dcb2; border-radius: 12px; padding: 15px 20px;">
                    <div style="font-weight: bold; font-size: 13px; margin-bottom: 8px;">Pojištění odpovědnosti ve výkonu práce</div>
                    <div style="font-size: 11px; color: #666;">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Duis pulvinar. Vestibulum erat nulla, ullamcorper nec, rutrum non, nonummy ac, erat.</div>
                </div>
                <div style="grid-column: span 2; border: 1px solid #c5dcb2; border-radius: 12px; padding: 15px 20px;">
                    <div style="font-weight: bold; font-size: 13px; margin-bottom: 8px;">Pojištění stroje</div>
                    <div style="font-size: 11px; color: #666;">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Duis pulvinar.</div>
                </div>
            </div>
        </div>



    </div>

    <!-- ============================================================ -->
    <!-- PAGE 14                                                      -->
    <!-- ============================================================ -->

    <div class="page" style="padding: 60px 40px; font-family: 'Arial', sans-serif; color: #3d3229; background: #fff; line-height: 1.5; position: relative; min-height: 1000px;">

        <div style="margin-bottom: 50px;">
            <h1 style="font-size: 52px; font-weight: bold; margin: 0; line-height: 1.1; color: #3d3229;">
                Měníme vaše sny<br>
                <span style="color: #8c6239;">ve skutečnost</span>
            </h1>
            <p style="font-size: 16px; color: #666; margin-top: 25px; max-width: 550px;">
                Pro další informace a případné dotazy mě neváhejte kontaktovat!
            </p>
        </div>

        <div style="margin-bottom: 60px;">
            <img src="https://collegas.cz/img/logo/collegas_hands.svg" alt="Collegas Logo" style="height: 60px; width: auto;">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; max-width: 800px;">

            <div style="border: 1px solid #eee; border-radius: 12px; padding: 25px; background: #fff;">
                <div style="font-weight: bold; font-size: 15px; margin-bottom: 8px; color: #3d3229;">Sídlo</div>
                <div style="font-size: 13px; color: #666; line-height: 1.6;">Pražákova 1008/69 Brno-město, 649 00 Brno</div>
            </div>

            <div style="border: 1px solid #eee; border-radius: 12px; padding: 25px; background: #fff;">
                <div style="font-weight: bold; font-size: 15px; margin-bottom: 8px; color: #3d3229;">Kancelář</div>
                <div style="font-size: 13px; color: #666; line-height: 1.6;">Tovačovského 2784/24, Kroměříž</div>
            </div>

            <div style="border: 1px solid #eee; border-radius: 12px; padding: 25px; background: #fff;">
                <div style="font-weight: bold; font-size: 15px; margin-bottom: 8px; color: #3d3229;">Centrála</div>
                <div style="font-size: 13px; color: #666; line-height: 1.6;">AZ TOWER, 11. Patro, Pražákova 1008/69, 639 00 Brno</div>
            </div>

            <div style="border: 1px solid #eee; border-radius: 12px; padding: 25px; background: #fff;">
                <div style="font-weight: bold; font-size: 15px; margin-bottom: 8px; color: #3d3229;">Collegas s.r.o.</div>
                <div style="font-size: 13px; color: #666; line-height: 1.6;">
                    IČO: 022 62 975<br>
                    Spisová značka: C 80725
                </div>
            </div>

        </div>

    </div>