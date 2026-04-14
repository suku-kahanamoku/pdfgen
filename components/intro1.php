<?php
// ============================================================
// PAGE 1 – CONTROLLER
// ============================================================
$introTitle   = $intro['title'] ?? 'Collegas';

$introContact = $intro['contact'] ?? [];
?>
<div class="w-full box-border p-24 [page-break-after:always] [break-after:page] [box-decoration-break:clone] overflow-visible flex flex-col h-screen">
    <!-- Intro -->
    <div class="mb-14">
        <h3 class="text-3xl leading-tight">
            Jsme společnost
            <span class="text-primary"><?php echo $introTitle; ?></span><br>
            váš partner na cestě k finančním úspěchům.
        </h3>
    </div>

    <!-- Hlavní nadpis -->
    <div class="mb-12">
        <h1 class="font-lora text-6xl font-semibold">
            Finanční analýza
        </h1>
    </div>

    <!-- Blok 1 -->
    <div class="mb-10">
        <div class="flex items-start gap-4">
            <div class="mt-1 flex h-14 w-14 shrink-0 items-center justify-center text-primary">
                <i class="fa-solid fa-crosshairs text-4xl"></i>
            </div>

            <div class="max-w-lg">
                <h4 class="mb-4 font-lora text-2xl leading-tight font-semibold">
                    Životní cíle
                </h4>
                <p class="leading-relaxed text-ink/80">
                    Životní cíle jsou základními kameny naší existence, které nás vedou
                    a motivují k dosažení vyšší kvality života a osobního naplnění.
                    Mohou být různé, od profesionálních ambicí přes osobní rozvoj až po
                    sociální či duchovní aspirace. Zahrnují jak krátkodobé, tak dlouhodobé
                    plány, které nám pomáhají udržet směr a smysl v našem každodenním životě.
                </p>
            </div>
        </div>
    </div>

    <!-- Oddělovač -->
    <div class="mb-10 h-px w-full bg-mist"></div>

    <!-- Blok 2 -->
    <div class="mb-14">
        <div class="flex items-start gap-4">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center text-primary">
                <i class="fa-solid fa-chart-line text-4xl"></i>
            </div>

            <div class="max-w-lg">
                <h4 class="mb-4 font-lora text-2xl leading-tight font-semibold">
                    Myslete na budoucnost
                </h4>
                <p class="leading-relaxed text-ink/80">
                    Plnění životních cílů je důležité z několika důvodů. Především nám dává
                    smysl a směr. Když víme, kam směřujeme, je snadnější se rozhodovat a
                    soustředit své úsilí na to, co je pro nás skutečně důležité. To nám
                    umožňuje lépe se vypořádat s překážkami a výzvami, které náš život přináší.
                </p>
            </div>
        </div>
    </div>

    <div class="mt-auto">
        <div class="mb-32 -ml-24 pl-24 max-w-2xl rounded-r-xl border border-sand bg-cream py-3">
            <p class="leading-normal text-ink/80">
                <?php echo $intro['motto']; ?>
            </p>
        </div>

        <!-- Kontakt -->
        <div class="flex items-start gap-4">
            <div class="h-28 w-28 overflow-hidden rounded-xl bg-linen">
                <img
                    src="<?= htmlspecialchars($introContact['photo'] ?? '') ?>"
                    alt="Profilová fotografie"
                    class="h-full w-full object-cover object-[center_top]">
            </div>

            <div class="pt-px">
                <div class="font-lora leading-tight font-semibold text-primary">
                    <?= htmlspecialchars($introContact['name'] ?? '') ?>
                </div>
                <div class="mb-2 leading-tight text-ink/80">
                    <?= htmlspecialchars($introContact['position'] ?? '') ?>
                </div>

                <div class="border-l border-taupe pl-2 leading-snug text-ink/80">
                    <div><?= htmlspecialchars($introContact['email'] ?? '') ?></div>
                    <div><?= htmlspecialchars($introContact['phone'] ?? '') ?></div>
                </div>

                <div class="mt-1 leading-tight text-pebble">
                    <?= htmlspecialchars($introContact['copyright'] ?? '') ?>
                </div>
            </div>
        </div>
    </div>
</div>