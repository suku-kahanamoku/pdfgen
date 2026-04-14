<?php
$_items = [
    1 => 'Finanční chaos',
    2 => 'Vyhýbání se financím',
    3 => 'Finanční uvědomění',
    4 => 'Finanční stabilita',
    5 => 'Finanční zabezpečení',
    6 => 'Finanční svoboda',
    7 => 'Finanční naplnění',
];
?>
<div class="space-y-3">
    <?php foreach ($_items as $n => $label): ?>
        <?php if ($n === ($_active ?? 0)): ?>
            <div class="flex items-center gap-6 text-xl rounded-xl bg-linen py-2 text-primary">
                <span class="w-10 text-right text-ink"><?= str_pad($n, 2, '0', STR_PAD_LEFT) ?></span>
                <span class="font-lora"><?= $label ?></span>
            </div>
        <?php else: ?>
            <div class="flex items-center gap-6 text-xl text-ink/70">
                <span class="w-10 text-right text-pebble"><?= str_pad($n, 2, '0', STR_PAD_LEFT) ?></span>
                <span><?= $label ?></span>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>