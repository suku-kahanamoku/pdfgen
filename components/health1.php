<?php
// ============================================================
// HEALTH INSURANCE PAGE – CONTROLLER
// ============================================================
$healthTitle = $health['title'] ?? 'Zdravotní pojištění';
$healthIntro = $health['intro'] ?? 'Zdravotní pojištění je klíčovým prvkem finanční ochrany v případě nemoci nebo úrazu. Poskytuje finanční podporu při léčbě, hospitalizaci a dalších zdravotních výdajích, což vám umožňuje soustředit se na zotavení bez zbytečného stresu z finančních nákladů.';

$healthQuestion = $health['question'] ?? 'Jaké zdravotní pojištění je pro vás nejvhodnější?';

$healthRows = $health['rows'] ?? [];
?>

<!-- ============================================================ -->
<!-- HEALTH INSURANCE SECTION                                       -->
<!-- ============================================================ -->
<div class="w-full box-border p-24 pt-10 [page-break-after:always] [break-after:page] overflow-visible flex flex-col gap-10 [box-decoration-break:clone]">

    <!-- Header -->
    <div class="flex items-start justify-between gap-8">
        <div class="flex-1">
            <h2 class="font-lora text-5xl font-semibold leading-none text-ink">
                <?= htmlspecialchars($healthTitle) ?>
            </h2>

            <div class="mt-6 leading-relaxed text-ink/70">
                <?= htmlspecialchars($healthIntro) ?>
            </div>
        </div>

        <div class="w-20 flex-shrink-0 flex justify-end text-primary/90">
            <i class="fa-solid fa-shield-halved text-6xl"></i>
        </div>
    </div>

    <!-- Question -->
    <div class="font-lora text-2xl font-semibold text-ink">
        <?= htmlspecialchars($healthQuestion) ?>
    </div>

    <!-- Main rows -->
    <div class="relative">
        <!-- right brackets -->
        <div class="absolute right-[-16px] top-0 bottom-[50%] w-3 border-r border-t border-b border-primary/30 rounded-r-xl"></div>
        <div class="absolute right-[-16px] top-[54%] bottom-0 w-3 border-r border-t border-b border-primary/30 rounded-r-xl"></div>

        <div class="absolute right-[-36px] top-[16%] rotate-90 text-xs text-ink/60">
            Do 1. roku
        </div>
        <div class="absolute right-[-36px] bottom-[14%] rotate-90 text-xs text-ink/60">
            Od 2. roku
        </div>

        <div class="grid grid-cols-3 gap-6">
            <!-- Death card, spans left width -->
            <div class="rounded-2xl border border-ink/15 bg-white px-5 py-5 shadow-sm col-span-1 min-h-[128px]">
                <div class="mb-4 flex items-center gap-3">
                    <i class="fa-regular fa-heart text-primary text-2xl"></i>
                    <div class="font-semibold text-ink">Pojištění pro případ úmrtí</div>
                </div>
                <div class="flex flex-col gap-2 text-ink/70">
                    <div>Jaké závazky po sobě zanecháte?</div>
                    <div>Přejete si své nejbližší finančně zajistit?</div>
                </div>
            </div>

            <!-- Top right grouped risks -->
            <div class="col-span-2 rounded-2xl border border-ink/15 bg-white px-5 py-5 shadow-sm min-h-[128px]">
                <div class="grid grid-cols-2 gap-x-10 gap-y-6">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-bandage text-primary text-2xl"></i>
                        <span class="text-ink">Úraz</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-briefcase-medical text-primary text-2xl"></i>
                        <span class="text-ink">Pracovní neschopnost</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <i class="fa-regular fa-hospital text-primary text-2xl"></i>
                        <span class="text-ink">Hospitalizace</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-virus-covid text-primary text-2xl"></i>
                        <span class="text-ink">Závažné onemocnění</span>
                    </div>
                </div>
            </div>

            <!-- Invalidita -->
            <div class="rounded-2xl border border-ink/15 bg-white px-5 py-5 shadow-sm min-h-[150px]">
                <div class="mb-4 flex items-center gap-3">
                    <i class="fa-solid fa-wheelchair text-primary text-2xl"></i>
                    <div class="font-semibold text-ink">Invalidita</div>
                </div>

                <div class="flex flex-col gap-2 text-xs text-ink/70">
                    <div class="flex justify-between gap-4">
                        <span>Snížená schopnost práce</span>
                        <span></span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span>I. stupeň</span>
                        <span>35% – 49%</span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span>II. stupeň</span>
                        <span>50% – 69%</span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span>III. stupeň</span>
                        <span>od 70%</span>
                    </div>
                </div>
            </div>

            <!-- Ztráta soběstačnosti -->
            <div class="rounded-2xl border border-ink/15 bg-white px-5 py-5 shadow-sm min-h-[150px]">
                <div class="mb-4 flex items-center gap-3">
                    <i class="fa-solid fa-bed text-primary text-2xl"></i>
                    <div class="font-semibold text-ink">Ztráta soběstačnosti</div>
                </div>

                <div class="flex flex-col gap-2 text-xs text-ink/70">
                    <div class="flex justify-between gap-4">
                        <span>Stupeň závislosti</span>
                        <span>Počet potřeb</span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span>I. lehká</span>
                        <span>3–4</span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span>II. střední</span>
                        <span>5–6</span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span>III. těžká</span>
                        <span>7–8</span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span>IV. úplná</span>
                        <span>9–10</span>
                    </div>
                </div>
            </div>

            <!-- Trvalé následky -->
            <div class="rounded-2xl border border-ink/15 bg-white px-5 py-5 shadow-sm min-h-[150px] flex items-center justify-center">
                <div class="flex items-center gap-4">
                    <i class="fa-solid fa-stethoscope text-primary text-3xl"></i>
                    <div class="font-semibold text-ink">Trvalé následky</div>
                </div>
            </div>
        </div>
    </div>
</div>