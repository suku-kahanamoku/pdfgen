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
    <div class="grid grid-cols-2 gap-16 items-start">
        <!-- Levá část -->
        <div>
            <h3 class="mb-12 font-lora text-3xl font-semibold leading-tight text-ink">
                Jak hodnotíte svou aktuální<br>
                finanční situaci?
            </h3>

            <div class="space-y-3">
                <div class="flex items-center gap-6 text-xl text-ink/70">
                    <span class="w-10 text-right text-pebble">01</span>
                    <span>Finanční chaos</span>
                </div>

                <div class="flex items-center gap-6 text-xl text-ink/70">
                    <span class="w-10 text-right text-pebble">02</span>
                    <span>Vyhýbání se financím</span>
                </div>

                <div class="flex items-center gap-6 text-xl text-ink/70">
                    <span class="w-10 text-right text-pebble">03</span>
                    <span>Finanční uvědomění</span>
                </div>

                <div class="flex items-center gap-6 text-xl rounded-xl bg-linen py-2 text-primary">
                    <span class="w-10 text-right text-ink">04</span>
                    <span class="font-lora">Finanční stabilita</span>
                </div>

                <div class="flex items-center gap-6 text-xl text-ink/70">
                    <span class="w-10 text-right text-pebble">05</span>
                    <span>Finanční zabezpečení</span>
                </div>

                <div class="flex items-center gap-6 text-xl text-ink/70">
                    <span class="w-10 text-right text-pebble">06</span>
                    <span>Finanční svoboda</span>
                </div>

                <div class="flex items-center gap-6 text-xl text-ink/70">
                    <span class="w-10 text-right text-pebble">07</span>
                    <span>Finanční naplnění</span>
                </div>
            </div>
        </div>

        <!-- Pravá část -->
        <div class="relative mt-20 rounded-2xl bg-pearl px-8 py-7 text-ink">
            <div class="absolute left-0 top-1/2 h-5 w-5 -translate-x-1/2 -translate-y-1/2 rotate-45 bg-pearl"></div>

            <div class="space-y-6">
                <div>
                    <h5 class="mb-1 font-lora text-xl font-semibold leading-tight text-ink">
                        Emoce
                    </h5>
                    <p class="leading-relaxed text-ink/65">
                        Úleva, pocit úspěchu,<br>
                        opatrné optimistický
                    </p>
                </div>

                <div>
                    <h5 class="mb-1 font-lora text-xl font-semibold leading-tight text-ink">
                        Chování
                    </h5>
                    <p class="leading-relaxed text-ink/65">
                        Hledání uklidnění, hledání<br>
                        informací, organizování
                    </p>
                </div>

                <div>
                    <h5 class="mb-1 font-lora text-xl font-semibold leading-tight text-ink">
                        Finanční stav
                    </h5>
                    <p class="leading-relaxed text-ink/65">
                        Má propadce nebo plán spoření, dluh pod
                        kontrolou, buduje si prosperitu, dobrá
                        dovednost v každodenních situacích
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>