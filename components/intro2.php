<?php
// ============================================================
// PAGE 1 – CONTROLLER
// ============================================================
$introExpect    = $intro['expect'];
$introExecValue = $introExpect['value'] ?? '';
$introExpectRows = $introExpect['rows'] ?? [];

$introExpectIcons = [
    'very_low'  => 'very_low.png',
    'low'       => 'low.png',
    'medium'    => 'medium.png',
    'high'      => 'high.png',
    'very_high' => 'very_high.png',
];

$introExpectIconsBase64 = [];
foreach ($introExpectIcons as $key => $file) {
    $path = __DIR__ . '/../img/emoticons/' . $file;
    if (file_exists($path)) {
        $introExpectIconsBase64[$key] = 'data:image/png;base64,' . base64_encode(file_get_contents($path));
    }
}
?>
<div class="w-full box-border p-24 flex h-screen flex-col [page-break-after:always] [break-after:page] [box-decoration-break:clone]">
    <!-- Horní obsah -->
    <!-- Nadpis -->
    <div class="mb-10">
        <h2 class="font-lora text-5xl font-semibold leading-none tracking-tight">
            Hodnoty <span class="text-primary">&amp;</span><br>
            očekávání
        </h2>
    </div>

    <!-- Intro text -->
    <div class="mb-14">
        <p class="leading-relaxed text-ink/70">
            Hodnoty jsou základní přesvědčení, principy nebo ideály, které určují,
            co je pro člověka důležité. Jsou to vnitřní normy, podle kterých se lidé
            rozhodují v různých životních situacích. Hodnoty ovlivňují chování,
            motivaci, jednání a jsou často spojeny s morálkou, etikou a osobními
            prioritami. Proto vnímáme důležité znát ty vaše.
        </p>
    </div>

    <div class="mt-auto">
        <!-- Otázka -->
        <div class="mb-20">
            <h4 class="mb-12 font-lora text-2xl font-semibold leading-tight">
                S jakým pocitem jste dnes přišel?
            </h4>

            <div class="relative w-full flex items-center justify-around gap-10">
                <?php foreach ($introExpectIcons as $key => $file):
                    $isActive = ($key === $introExecValue);
                    $row = $introExpectRows[$key] ?? [];
                    $src = $introExpectIconsBase64[$key] ?? '';
                ?>
                    <div class="relative flex flex-col items-center">
                        <?php if ($isActive): ?>
                            <div class="flex h-40 w-40 items-center justify-center rounded-2xl border border-primary bg-cream/40">
                                <div class="flex h-32 w-32 items-center justify-center rounded-full">
                                    <img src="<?= $src ?>" class="h-full w-full object-contain" alt="">
                                </div>
                            </div>
                            <div class="absolute -top-12 z-10 <?= $key === 'very_high' ? 'right-2/3' : 'left-2/3' ?>">
                                <div class="rounded-xl bg-primary px-4 py-2 text-white shadow-sm w-48">
                                    <div class="font-semibold leading-tight"><?= htmlspecialchars($row['title'] ?? '') ?></div>
                                    <div class="leading-tight text-white/85"><?= htmlspecialchars($row['description'] ?? '') ?></div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="flex flex-col items-center">
                                <div class="flex h-32 w-32 items-center justify-center rounded-full">
                                    <img src="<?= $src ?>" class="h-full w-full object-contain" alt="">
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Tmavý spodní blok -->
        <div class="-mx-24 -mb-24 rounded-t-3xl bg-surface px-24 pt-16 pb-24 text-white">
            <div class="mb-12 flex items-start justify-between gap-6">
                <h3 class="max-w-sm font-lora text-3xl font-semibold leading-none text-white">
                    Zásadní otázky pro vaši finanční pohodu
                </h3>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl border border-white/10/10 text-3xl text-white/85 shadow-md">
                    ?
                </div>
            </div>

            <div class="grid grid-cols-2 gap-x-4 gap-y-4">
                <div class="rounded-xl border border-white/20/5 px-4 py-3">
                    <h6 class="mb-2 leading-snug text-white">
                        Co by se mělo stát, abyste odcházel s pocitem, že dnešní schůzka byla úspěšná?
                    </h6>
                    <p class="leading-snug text-answer">
                        Zajištění vlastního bydlení, a ochrana rodiny.
                    </p>
                </div>

                <div class="rounded-xl border border-white/20/5 px-4 py-3">
                    <h6 class="mb-2 leading-snug text-white">
                        Kdybyste už neměli žádné finanční starosti, co byste dělali jinak?
                    </h6>
                    <p class="leading-snug text-answer">
                        Trávil bych víc času s rodinou a méně bych se stresoval.
                    </p>
                </div>

                <div class="rounded-xl border border-white/20/5 px-4 py-3">
                    <h6 class="mb-2 leading-snug text-white">
                        Čím jsou pro vás peníze důležité?
                    </h6>
                    <p class="leading-snug text-answer">
                        Je to prostředek k dosahování snů a cílů.
                    </p>
                </div>

                <div class="rounded-xl border border-white/20/5 px-4 py-3">
                    <h6 class="mb-2 leading-snug text-white">
                        Co by se stalo, kdybyste změnili to, jak utrácíte a spoříte? Jak byste se cítil?
                    </h6>
                    <p class="leading-snug text-answer">
                        Pořád stejně.
                    </p>
                </div>

                <div class="rounded-xl border border-white/20/5 px-4 py-3">
                    <h6 class="mb-2 leading-snug text-white">
                        Kdybyste dnes mohli vyřešit jakýkoliv finanční problém pouhým lusknutím prstu, co byste vyřešili?
                    </h6>
                    <p class="leading-snug text-answer">
                        Zajištění na stáří.
                    </p>
                </div>

                <div class="rounded-xl border border-white/20/5 px-4 py-3">
                    <h6 class="mb-2 leading-snug text-white">
                        Jaká je vaše strategie v budování majetku?
                    </h6>
                    <p class="leading-snug text-answer">
                        Volný kapitál dávat do nemovitostí.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>