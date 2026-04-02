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
$targets = $dataRaw['target']['targets'] ?? [];
$rows    = $targets['rows'] ?? [];
$header  = $targets['header'] ?? [];
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
    <div class="section-heading">
        <span style="background:#927355; color:white; width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center;">
            <i class="fa-solid fa-bullseye"></i>
        </span>
        <?= safe_text($targets['title'] ?? 'Finanční cíle a přání') ?>
    </div>

    <?php foreach ($rows as $row):
        $targetAmt  = (float)($row['targetAmount']['value']  ?? 0);
        $currentAmt = (float)($row['currentAmount']['value'] ?? 0);
        $gap        = $targetAmt - $currentAmt;
        $pct        = $targetAmt > 0 ? min(100, round($currentAmt / $targetAmt * 100)) : 0;
        $nameKey    = 'name';
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
                    <span>Naspořeno: <?= format_czk($currentAmt) ?> Kč</span>
                    <span>Cíl: <?= format_czk($targetAmt) ?> Kč</span>
                </div>
                <div class="progress-bar-outer">
                    <div class="progress-bar-inner" style="width: <?= $pct ?>%;"></div>
                </div>
                <div class="progress-pct"><?= $pct ?>%</div>
            </div>

            <div class="sol-grid">
                <?php foreach ($header as $h):
                    if ($h['key'] === $nameKey) continue;
                    $field = $row[$h['key']] ?? ['value' => '', 'type' => $h['type']];
                    $val   = $field['value'] ?? '';
                    if ($h['key'] === 'gap') { $val = $gap; $field['type'] = 'currency'; }
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
