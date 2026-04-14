<?php
// ============================================================
// PAGE 3 – CONTROLLER
// ============================================================
$introSituation = (int)($intro['situation'] ?? 0);
?>
<div class="w-full box-border p-24 flex h-screen flex-col [page-break-after:always] [break-after:page] [box-decoration-break:clone]">

    <!-- Nadpis -->
    <div class="mb-14">
        <h2 class="max-w-xl font-lora text-5xl font-semibold leading-none tracking-tight">
            Finanční chování klienta v průběhu života
        </h2>
    </div>

    <!-- Intro -->
    <div class="mb-16">
        <p class="leading-relaxed text-ink/70">
            Hodnoty jsou základní přesvědčení, principy nebo ideály, které určují, co je
            pro člověka důležité. Jsou to vnitřní normy, podle kterých se lidé rozhodují
            v různých životních situacích. Hodnoty ovlivňují chování, motivaci, jednání a
            jsou často spojeny s morálkou, etikou a osobními prioritami. Proto vnímáme
            důležité znát ty vaše.
        </p>
    </div>

    <!-- Graf (nahrazen obrázkem) -->
    <div class="mb-20">
        <div class="w-full">
            <?php include __DIR__ . '/info-graph.php'; ?>
        </div>

        <!-- Popisky pod grafem -->
        <div class="flex items-center justify-around mt-8">
            <div class="flex items-center gap-2">
                <span class="inline-block h-3 w-3 rounded-full bg-peach flex-shrink-0"></span>
                <span>S finančním plánem</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-block h-3 w-3 rounded-full bg-caramel flex-shrink-0"></span>
                <span>S finančnímy cíly</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-block h-3 w-3 rounded-full bg-ink flex-shrink-0"></span>
                <span>Bez plánu cílů</span>
            </div>
        </div>
    </div>

    <!-- Spodní sekce -->
    <?php include __DIR__ . '/situation/' . $introSituation . '.php'; ?>

</div>