<?php
// ============================================================
// FINANCIAL PLAN COVER PAGE – CONTROLLER
// ============================================================
$introductionLogo = $introduction['logo_text'] ?? (__DIR__ . '/../img/logo/wealth_management.png');
?>

<!-- ============================================================ -->
<!-- FINANCIAL PLAN COVER PAGE                                    -->
<!-- ============================================================ -->
<div class="w-full h-screen box-border p-24 overflow-hidden flex flex-col justify-between relative break-after-page box-decoration-clone">

    <!-- Top content -->
    <div class="flex flex-col gap-6 relative z-10">
        <h1 class="font-lora text-8xl leading-[1.05] font-semibold text-ink">
            Finanční plán
        </h1>

        <p class="text-4xl text-ink/70 max-w-xl leading-relaxed">
            k dosažení finanční nezávislosti<br> a vašich dalších cílů.
        </p>
    </div>

    <!-- Decorative circle -->
    <div class="absolute top-24 right-24 w-12 h-12 rounded-full bg-primary/80"></div>

    <!-- Bottom: chart bars + logo -->
    <div class="relative z-10 flex flex-col">
        <!-- Chart bars -->
        <div class="flex items-end gap-4 -mx-24">
            <?php
            $bars = [120, 180, 140, 220, 240, 200, 280, 300, 260, 340, 400, 360, 460];
            foreach ($bars as $h):
            ?>
                <div class="flex-1 bg-gradient-to-t from-[#6b4a2b] to-[#9a6b45] rounded-t-xl"
                    style="height: <?= $h ?>px"></div>
            <?php endforeach; ?>
        </div>

        <!-- Logo -->
        <div class="pt-24">
            <img src="<?= htmlspecialchars($introductionLogo) ?>" alt="Collegas" class="h-16 opacity-90">
        </div>
    </div>
</div>