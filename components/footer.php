<?php
// ============================================================
// FINAL CONTACT PAGE – CONTROLLER
// ============================================================
$contact    = $intro['contact'] ?? [];

$finalTitle  = "Měníme vaše sny\nve skutečnost";
$finalText   = "Pro další informace a případné dotazy\nmě neváhejte kontaktovat!";

$logoText        = $footer['logo'] ?? (__DIR__ . '/../img/logo/wealth_management.png');

$contactCards = $footer['contact'] ?? [];

$decorImage  = $footer['final_decor_image'] ?? (__DIR__ . '/../img/logo/wealth.png');
?>

<!-- ============================================================ -->
<!-- FINAL CONTACT PAGE                                           -->
<!-- ============================================================ -->
<div class="relative w-full box-border p-24 h-screen overflow-hidden flex flex-col break-after-page box-decoration-clone">

    <!-- Decorative background -->
    <div class="absolute inset-x-0 bottom-0 h-[430px] bg-[#f1f1f0] rounded-tl-[320px] translate-x-44"></div>

    <?php if ($decorImage): ?>
        <img
            src="<?= htmlspecialchars($decorImage) ?>"
            alt=""
            class="absolute left-[-100px] bottom-[-150px] w-[650px] max-w-none object-contain pointer-events-none">
    <?php else: ?>
        <!-- fallback abstract ribbons -->
        <div class="absolute left-[235px] bottom-[-120px] h-64 w-64 rounded-full border-[36px] border-ink/35 rotate-[-22deg]"></div>
        <div class="absolute left-[465px] bottom-[90px] h-44 w-28 rounded-full border-[36px] border-pebble rotate-[20deg]"></div>
        <div class="absolute right-[-225px] bottom-[120px] h-28 w-56 rounded-full border-[34px] border-[#efc400] rotate-[-20deg]"></div>
    <?php endif; ?>

    <!-- Content -->
    <div class="relative z-10 flex flex-col">
        <h1 class="font-lora text-6xl font-semibold leading-none tracking-tight text-ink">
            <span><?= nl2br(htmlspecialchars(explode("\n", $finalTitle)[0] ?? 'Měníme vaše sny')) ?></span><br>
            <span class="text-primary"><?= htmlspecialchars(explode("\n", $finalTitle)[1] ?? 've skutečnost') ?></span>
        </h1>

        <div class="mt-8 max-w-xl text-3xl leading-tight text-ink/85">
            <?= nl2br(htmlspecialchars($finalText)) ?>
        </div>

        <div class="mt-16">
            <img
                src="<?= htmlspecialchars($logoText) ?>"
                alt="Collegas Wealth Management"
                class="h-12 w-auto object-contain">
        </div>

        <div class="mt-8 grid grid-cols-2 gap-6 max-w-4xl">
            <?php foreach ($contactCards as $card): ?>
                <div class="rounded-2xl bg-white/80 px-6 py-5 shadow-md min-h-28">
                    <div class="mb-2 font-lora text-xl font-semibold text-ink">
                        <?= htmlspecialchars($card['title'] ?? '') ?>
                    </div>
                    <div class="text-lg leading-snug text-ink/80">
                        <?= nl2br(htmlspecialchars($card['text'] ?? '')) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>