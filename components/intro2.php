<?php
// ============================================================
// PAGE 1 – CONTROLLER
// ============================================================
$introExpectationMap = [1 => 'very_low', 2 => 'low', 3 => 'medium', 4 => 'high', 5 => 'very_high'];
$introExecValue = $introExpectationMap[(int)($intro['expectation'] ?? 0)] ?? '';

$introExpectKeys = ['very_low', 'low', 'medium', 'high', 'very_high'];

$introQuestions = $intro['questions'] ?? [];
?>
<div class="w-full box-border p-24 flex h-screen flex-col break-after-page box-decoration-clone">

    <?php EMIT_MARKER(); ?>
    <!-- Horní obsah -->
    <!-- Nadpis -->
    <div class="mb-10">
        <h2 class="font-lora text-5xl leading-none tracking-tight">
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
                <?php foreach ($introExpectKeys as $key):
                    $isActive = ($key === $introExecValue);
                ?>
                    <?php include __DIR__ . '/expectation/' . $key . '.php'; ?>
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
                <?php foreach ($introQuestions as $q): ?>
                    <div class="rounded-xl border border-white/20 px-4 py-3">
                        <h6 class="mb-2 leading-snug text-white">
                            <?= htmlspecialchars($q['question'] ?? '') ?>
                        </h6>
                        <p class="leading-snug text-answer">
                            <?= htmlspecialchars($q['answer'] ?? '') ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>