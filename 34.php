<?php
require_once __DIR__ . '/includes/helpers.php';

if (!function_exists('safe_text')) {
    function safe_text($text)
    {
        if (empty($text) || is_array($text)) return '';
        return htmlspecialchars(preg_replace('/\[cite.*?\]/', '', (string)$text));
    }
}

$dataRaw   = $GLOBALS['pdfData'] ?? json_decode(file_get_contents(__DIR__ . '/data.json'), true);
$solutions = $dataRaw['target']['solutions'] ?? [];
$cardHeader  = $solutions['header'] ?? [];
$cardNameKey = 'name';
?>
<?php include __DIR__ . '/components/card-styles.php'; ?>

<div class="page">
    <div class="section-heading">
        <span style="background:#927355; color:white; width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center;">
            <i class="fa-solid fa-seedling"></i>
        </span>
        <?= safe_text($solutions['title'] ?? 'Finanční plán') ?>
    </div>

    <?php foreach ($solutions['rows'] ?? [] as $cardRow):
        $cardName   = $cardRow[$cardNameKey]['value'] ?? '';
        $cardLabels = $cardRow['labels'] ?? [];

        // Time-based progress: start → targetDate
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