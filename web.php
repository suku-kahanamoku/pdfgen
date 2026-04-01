<?php
$pages = [
    ['file' => '31.php', 'title' => 'Strana 31 – Přehled majetku',      'icon' => '📊'],
    ['file' => '32.php', 'title' => 'Strana 32 – Detailní přehled',      'icon' => '📋'],
    ['file' => '33.php', 'title' => 'Strana 33 – Movitý majetek',        'icon' => '🚗'],
    ['file' => '34.php', 'title' => 'Strana 34 – Analýza portfolia',     'icon' => '📈'],
];
?>
<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Parser – Přehled stránek</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f9f7f5;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-8">
    <div class="max-w-lg w-full">
        <h1 class="text-3xl font-bold text-[#3d3229] mb-2">Finanční přehled</h1>
        <p class="text-[#927355] mb-8">Vyberte stránku, kterou chcete zobrazit:</p>
        <div class="space-y-3">
            <?php foreach ($pages as $page): ?>
                <a href="<?= htmlspecialchars($page['file']) ?>"
                    class="flex items-center gap-4 bg-white border border-[#f0efeb] rounded-2xl px-6 py-4 shadow-sm hover:shadow-md hover:border-[#927355] transition-all group">
                    <span class="text-2xl"><?= $page['icon'] ?></span>
                    <span class="text-[#3d3229] font-semibold group-hover:text-[#927355] transition-colors">
                        <?= htmlspecialchars($page['title']) ?>
                    </span>
                    <span class="ml-auto text-[#c0b9b3] group-hover:text-[#927355]">→</span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>