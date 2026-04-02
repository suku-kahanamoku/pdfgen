<?php
require_once __DIR__ . '/includes/helpers.php';

if (!function_exists('safe_text')) {
    function safe_text($text)
    {
        if (empty($text) || is_array($text)) return '';
        return htmlspecialchars(preg_replace('/\[cite.*?\]/', '', (string)$text));
    }
}

$dataRaw = $GLOBALS['pdfData'] ?? json_decode(file_get_contents(__DIR__ . '/data.json'), true);
$aktiva  = $dataRaw['property']['property_active'] ?? [];
$pasiva  = $dataRaw['property']['property_pasive'] ?? [];
?>
<style>
    :root {
        --clr-primary: #927355;
    }

    .page {
        width: 210mm;
        min-height: 297mm;
        padding: 20mm;
        box-sizing: border-box;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: white;
        color: #333;
    }

    .section-heading {
        font-family: 'Lora', serif;
        font-size: 24px;
        color: var(--clr-primary);
        margin: 0 0 18px 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-block {
        margin-bottom: 40px;
    }

    .sol-card {
        background: #fff;
        border: 1px solid #f0ebe5;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 18px;
    }

    .sol-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
    }

    .sol-name {
        font-family: 'Lora', serif;
        font-size: 20px;
        color: var(--clr-primary);
    }

    .tag {
        display: inline-block;
        font-size: 9px;
        text-transform: uppercase;
        border: 1px solid #d4c4b5;
        padding: 3px 8px;
        border-radius: 5px;
        color: var(--clr-primary);
        margin: 2px;
    }

    .sol-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

    .sol-item {
        background: #fcfaf8;
        border-radius: 10px;
        padding: 12px;
    }

    .sol-item-label {
        font-size: 10px;
        color: #aaa;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 5px;
    }

    .sol-item-value {
        font-size: 15px;
        font-weight: bold;
        color: #333;
    }

    .sol-item-value.highlight {
        color: var(--clr-primary);
        font-family: 'Lora', serif;
        font-size: 18px;
    }

    .progress-wrap {
        margin-bottom: 16px;
    }

    .progress-row {
        display: flex;
        justify-content: space-between;
        font-size: 11px;
        color: #888;
        margin-bottom: 5px;
    }

    .progress-bar-outer {
        background: #f3f3f3;
        height: 10px;
        border-radius: 5px;
        overflow: hidden;
    }

    .progress-bar-inner {
        height: 100%;
        border-radius: 5px;
        background: var(--clr-primary);
    }

    .progress-pct {
        text-align: right;
        font-size: 11px;
        color: var(--clr-primary);
        margin-top: 4px;
        font-weight: bold;
    }
</style>

<div class="page">
    <?php
    $sections = [
        [
            'data'  => $aktiva,
            'icon'  => 'fa-chart-line',
            'color' => '#2ecc71',
            // progress: investovaná částka → aktuální hodnota
            'bar_left_key'  => 'invested',
            'bar_left_lbl'  => 'Investováno',
            'bar_right_key' => 'aum',
            'bar_right_lbl' => 'Aktuální hodnota',
            'bar_pct_key'   => null, // computed from currency ratio
        ],
        [
            'data'  => $pasiva,
            'icon'  => 'fa-file-invoice-dollar',
            'color' => '#e67e22',
            // progress: paid % přímo
            'bar_left_key'  => null,
            'bar_left_lbl'  => 'Splaceno',
            'bar_right_key' => null,
            'bar_right_lbl' => 'Zbývá umořit',
            'bar_pct_key'   => 'paid', // direct percent field
        ],
    ];
    ?>
    <?php foreach ($sections as $sec): ?>
        <div class="section-block">
            <div class="section-heading">
                <span style="background:<?= $sec['color'] ?>22; color:<?= $sec['color'] ?>; width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                    <i class="fa-solid <?= $sec['icon'] ?>"></i>
                </span>
                <?= safe_text($sec['data']['title'] ?? '') ?>
            </div>

            <?php foreach ($sec['data']['rows'] ?? [] as $row):
                $nameKey = $sec['data']['header'][0]['key'] ?? 'name';

                // Compute progress bar values
                if ($sec['bar_pct_key'] !== null) {
                    // pasiva: use paid % directly
                    $pct       = min(100, max(0, (float)($row[$sec['bar_pct_key']]['value'] ?? 0)));
                    $leftVal   = $pct . ' %';
                    $rightVal  = format_czk((float)($row['aum']['value'] ?? 0)) . ' Kč';
                } else {
                    // aktiva: ratio invested → aum
                    $invested = (float)($row[$sec['bar_left_key']]['value']  ?? 0);
                    $aum      = (float)($row[$sec['bar_right_key']]['value'] ?? 0);
                    $pct      = $invested > 0 ? min(100, round($aum / $invested * 100)) : 0;
                    $leftVal  = format_czk($invested) . ' Kč';
                    $rightVal = format_czk($aum) . ' Kč';
                }
            ?>
                <div class="sol-card">
                    <div class="sol-header">
                        <div class="sol-name"><?= safe_text($row[$nameKey]['value'] ?? '') ?></div>
                        <div>
                            <?php foreach ($row['labels'] ?? [] as $lbl): ?>
                                <span class="tag"><?= safe_text($lbl) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="progress-wrap">
                        <div class="progress-row">
                            <span><?= $sec['bar_left_lbl'] ?>: <?= $leftVal ?></span>
                            <span><?= $sec['bar_right_lbl'] ?>: <?= $rightVal ?></span>
                        </div>
                        <div class="progress-bar-outer">
                            <div class="progress-bar-inner" style="width: <?= $pct ?>%;"></div>
                        </div>
                        <div class="progress-pct"><?= $pct ?>%</div>
                    </div>

                    <div class="sol-grid">
                        <?php foreach ($sec['data']['header'] ?? [] as $h):
                            if ($h['key'] === $nameKey) continue;
                            $field = $row[$h['key']] ?? ['value' => '', 'type' => $h['type']];
                            $val   = $field['value'] ?? '';
                            if ($val === '' || $val === null) continue;
                            if ($field['type'] === 'currency') $display = format_czk((float)$val) . ' Kč';
                            elseif ($field['type'] === 'percent') $display = $val . ' %';
                            elseif ($field['type'] === 'date') $display = format_date((string)$val);
                            else $display = htmlspecialchars((string)$val);
                            $isHighlight = in_array($field['type'] ?? '', ['currency', 'percent']);
                        ?>
                            <div class="sol-item">
                                <div class="sol-item-label"><?= safe_text($h['label']) ?></div>
                                <div class="sol-item-value <?= $isHighlight ? 'highlight' : '' ?>">
                                    <?= $display !== '' ? $display : '<span style="color:#ccc">—</span>' ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>
