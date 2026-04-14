<div class="grid grid-cols-2 gap-16 items-start">
    <div>
        <h3 class="mb-12 font-lora text-3xl font-semibold leading-tight text-ink">
            Jak hodnotíte svou aktuální<br>
            finanční situaci?
        </h3>

        <?php $_active = 4;
        include __DIR__ . '/_list.php'; ?>
    </div>

    <div class="relative mt-20 rounded-2xl bg-pearl px-8 py-7 text-ink">
        <?php $_arrowTop = round(4.0625 + ($_active - 1) * 2.5, 4); ?>
        <div class="absolute left-0 h-5 w-5 -translate-x-1/2 -translate-y-1/2 rotate-45 bg-pearl" style="top: <?= $_arrowTop ?>rem"></div>

        <div class="space-y-6">
            <div>
                <h5 class="mb-1 font-lora text-xl font-semibold leading-tight text-ink">Emoce</h5>
                <p class="leading-relaxed text-ink/65">
                    Úleva, pocit úspěchu,<br>
                    opatrné optimistický
                </p>
            </div>
            <div>
                <h5 class="mb-1 font-lora text-xl font-semibold leading-tight text-ink">Chování</h5>
                <p class="leading-relaxed text-ink/65">
                    Hledání uklidnění, hledání<br>
                    informací, organizování
                </p>
            </div>
            <div>
                <h5 class="mb-1 font-lora text-xl font-semibold leading-tight text-ink">Finanční stav</h5>
                <p class="leading-relaxed text-ink/65">
                    Má propadce nebo plán spoření, dluh pod
                    kontrolou, buduje si prosperitu, dobrá
                    dovednost v každodenních situacích
                </p>
            </div>
        </div>
    </div>
</div>