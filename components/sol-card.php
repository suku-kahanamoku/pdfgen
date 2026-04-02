<?php
// Required variables from caller:
//   $cardName    string
//   $cardLabels  array
//   $cardProgress array|null  ['left_lbl', 'left_val', 'right_lbl', 'right_val', 'pct', 'pct_label']
//   $cardHeader  array
//   $cardRow     array
//   $cardNameKey string
?>
<div class="sol-card">
    <div class="sol-header">
        <div class="sol-name"><?= safe_text($cardName) ?></div>
        <div>
            <?php foreach ($cardLabels as $lbl): ?>
                <span class="tag"><?= safe_text($lbl) ?></span>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if ($cardProgress !== null): ?>
        <div class="progress-wrap">
            <div class="progress-row">
                <span><?= htmlspecialchars($cardProgress['left_lbl']) ?>: <?= $cardProgress['left_val'] ?></span>
                <span><?= htmlspecialchars($cardProgress['right_lbl']) ?>: <?= $cardProgress['right_val'] ?></span>
            </div>
            <div class="progress-bar-outer">
                <div class="progress-bar-inner" style="width: <?= (int)$cardProgress['pct'] ?>%;"></div>
            </div>
            <div class="progress-pct"><?= (int)$cardProgress['pct'] ?>% <?= htmlspecialchars($cardProgress['pct_label'] ?? '') ?></div>
        </div>
    <?php endif; ?>

    <div class="sol-grid">
        <?php foreach ($cardHeader as $h):
            if ($h['key'] === $cardNameKey) continue;
            $field = $cardRow[$h['key']] ?? ['value' => '', 'type' => $h['type']];
            $val   = $field['value'] ?? '';
            if ($val === '' || $val === null) continue;
            if ($field['type'] === 'currency') $display = format_czk((float)$val) . ' Kč';
            elseif ($field['type'] === 'percent') $display = $val . ' %';
            elseif ($field['type'] === 'date')    $display = format_date((string)$val);
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