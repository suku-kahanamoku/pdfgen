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

    <?php if ($cardProgress !== null):
        $progressId = 'progressChart_' . uniqid();
    ?>
        <div class="progress-wrap">
            <div class="progress-row">
                <span><?= htmlspecialchars($cardProgress['left_lbl']) ?>: <?= $cardProgress['left_val'] ?></span>
                <span><?= htmlspecialchars($cardProgress['right_lbl']) ?>: <?= $cardProgress['right_val'] ?></span>
            </div>
            <div style="position: relative; width: 100%; height: 35px;">
                <canvas id="<?= $progressId ?>"></canvas>
            </div>
            <div class="progress-pct"><?= (int)$cardProgress['pct'] ?>% <?= htmlspecialchars($cardProgress['pct_label'] ?? '') ?></div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctxProg = document.getElementById('<?= $progressId ?>');
                if (ctxProg) {
                    new Chart(ctxProg, {
                        type: 'bar',
                        data: {
                            labels: [''],
                            datasets: [{
                                label: 'Dosaženo',
                                data: [<?= (int)$cardProgress['pct'] ?>],
                                backgroundColor: '#27ae60',
                                borderSkipped: false
                            }, {
                                label: 'Zbývá',
                                data: [<?= 100 - (int)$cardProgress['pct'] ?>],
                                backgroundColor: '#f0f0f0',
                                borderSkipped: false
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    enabled: false
                                }
                            },
                            scales: {
                                x: {
                                    stacked: true,
                                    display: false,
                                    min: 0,
                                    max: 100,
                                    grid: {
                                        display: false,
                                        drawBorder: false
                                    }
                                },
                                y: {
                                    stacked: true,
                                    display: false,
                                    grid: {
                                        display: false,
                                        drawBorder: false
                                    }
                                }
                            }
                        }
                    });
                }
            });
        </script>
    <?php endif; ?>

    <div class="sol-grid">
        <?php foreach ($cardHeader as $h):
            if ($h['key'] === $cardNameKey) {
                continue;
            }

            $field = $cardRow[$h['key']] ?? ['value' => '', 'type' => $h['type']];
            $val   = $field['value'] ?? '';

            if ($val === '' || $val === null) {
                continue;
            }

            if ($field['type'] === 'currency') {
                $display = format_czk((float)$val) . ' Kč';
            } elseif ($field['type'] === 'percent') {
                $display = $val . ' %';
            } elseif ($field['type'] === 'date') {
                $display = format_date((string)$val);
            } else {
                $display = htmlspecialchars((string)$val);
            }

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