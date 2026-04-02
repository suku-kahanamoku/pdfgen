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
$cardHeader  = $targets['header'] ?? [];
$cardNameKey = 'name';
?>
<?php include __DIR__ . '/components/card-styles.php'; ?>

<div class="page">
    <div class="section-heading">
        <span style="background:#927355; color:white; width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center;">
            <i class="fa-solid fa-bullseye"></i>
        </span>
        <?= safe_text($targets['title'] ?? 'Finanční cíle a přání') ?>
    </div>

    <?php foreach ($targets['rows'] ?? [] as $cardRow):
        $targetAmt  = (float)($cardRow['targetAmount']['value']  ?? 0);
        $currentAmt = (float)($cardRow['currentAmount']['value'] ?? 0);
        $gap        = $targetAmt - $currentAmt;
        $pct        = $targetAmt > 0 ? min(100, round($currentAmt / $targetAmt * 100)) : 0;

        // gap is computed, inject it back
        $cardRow['gap'] = ['value' => $gap, 'type' => 'currency'];

        $cardName     = $cardRow[$cardNameKey]['value'] ?? '';
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