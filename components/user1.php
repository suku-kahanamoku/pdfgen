<?php
// ============================================================
// PAGE – USER
// ============================================================
$userData     = $dataRaw['user'] ?? [];
$client       = $userData['client']  ?? [];
$partner      = $userData['partner'] ?? [];
$childrenRows = $userData['children']['rows'] ?? [];
$petsRows     = $userData['pets']['rows']     ?? [];
?>

<div class="w-full box-border p-24 flex h-screen flex-col bg-[#f3f2f1] [page-break-after:always] [break-after:page] [box-decoration-break:clone]">

    <!-- Sekce: Klient -->
    <div class="mb-10 rounded-3xl bg-white/60 px-10 py-8 shadow-sm">
        <h3 class="mb-6 font-lora text-3xl font-semibold">
            Základní údaje o klientovi
        </h3>

        <div class="divide-y divide-mist">
            <div class="grid grid-cols-[240px_1fr] items-center py-3">
                <div class="flex items-center gap-3 text-ink/70">
                    <i class="fa-regular fa-user text-primary"></i>
                    <span>Jméno a příjmení</span>
                </div>
                <div class="text-ink font-semibold"><?= htmlspecialchars($client['name'] ?? '') ?></div>
            </div>

            <div class="grid grid-cols-[240px_1fr] items-center py-3">
                <div class="flex items-center gap-3 text-ink/70">
                    <i class="fa-regular fa-calendar text-primary"></i>
                    <span>Datum narození</span>
                </div>
                <div class="text-ink font-semibold"><?= htmlspecialchars($client['birth_date'] ?? '') ?></div>
            </div>

            <div class="grid grid-cols-[240px_1fr] items-center py-3">
                <div class="flex items-center gap-3 text-ink/70">
                    <i class="fa-solid fa-location-dot text-primary"></i>
                    <span>Adresa</span>
                </div>
                <div class="text-ink font-semibold"><?= htmlspecialchars($client['address'] ?? '') ?></div>
            </div>

            <div class="grid grid-cols-[240px_1fr] items-center py-3">
                <div class="flex items-center gap-3 text-ink/70">
                    <i class="fa-regular fa-heart text-primary"></i>
                    <span>Rodinný stav</span>
                </div>
                <div class="text-ink font-semibold"><?= htmlspecialchars($client['marital_status'] ?? '') ?></div>
            </div>

            <div class="grid grid-cols-[240px_1fr] items-center py-3">
                <div class="flex items-center gap-3 text-ink/70">
                    <i class="fa-solid fa-phone text-primary"></i>
                    <span>Telefon</span>
                </div>
                <div class="text-ink font-semibold"><?= htmlspecialchars($client['phone'] ?? '') ?></div>
            </div>

            <div class="grid grid-cols-[240px_1fr] items-center py-3">
                <div class="flex items-center gap-3 text-ink/70">
                    <i class="fa-regular fa-envelope text-primary"></i>
                    <span>Email</span>
                </div>
                <div class="text-ink font-semibold"><?= htmlspecialchars($client['email'] ?? '') ?></div>
            </div>
        </div>
    </div>

    <!-- Sekce: Partner -->
    <div class="mb-10 rounded-3xl bg-white/60 px-10 py-8 shadow-sm">
        <h3 class="mb-6 font-lora text-3xl font-semibold">
            Partner
        </h3>

        <div class="divide-y divide-mist">
            <div class="grid grid-cols-[240px_1fr] items-center py-3">
                <div class="flex items-center gap-3 text-ink/70">
                    <i class="fa-regular fa-user text-primary"></i>
                    <span>Jméno a příjmení</span>
                </div>
                <div class="text-ink font-semibold"><?= htmlspecialchars($partner['name'] ?? '') ?></div>
            </div>

            <div class="grid grid-cols-[240px_1fr] items-center py-3">
                <div class="flex items-center gap-3 text-ink/70">
                    <i class="fa-regular fa-calendar text-primary"></i>
                    <span>Datum narození</span>
                </div>
                <div class="text-ink font-semibold"><?= htmlspecialchars($partner['birth_date'] ?? '') ?></div>
            </div>

            <div class="grid grid-cols-[240px_1fr] items-center py-3">
                <div class="flex items-center gap-3 text-ink/70">
                    <i class="fa-solid fa-location-dot text-primary"></i>
                    <span>Adresa</span>
                </div>
                <div class="text-ink font-semibold"><?= htmlspecialchars($partner['address'] ?? '') ?></div>
            </div>

            <div class="grid grid-cols-[240px_1fr] items-center py-3">
                <div class="flex items-center gap-3 text-ink/70">
                    <i class="fa-regular fa-heart text-primary"></i>
                    <span>Rodinný stav</span>
                </div>
                <div class="text-ink font-semibold"><?= htmlspecialchars($partner['marital_status'] ?? '') ?></div>
            </div>
        </div>
    </div>

    <!-- Sekce: Děti -->
    <?php if (!empty($childrenRows)): ?>
        <div class="mb-10 rounded-3xl bg-white/60 px-10 py-8 shadow-sm">
            <h3 class="mb-6 font-lora text-3xl font-semibold">
                Děti
            </h3>

            <div class="divide-y divide-mist">
                <div class="grid grid-cols-[240px_1fr_1fr] items-center py-3">
                    <div class="flex items-center gap-3 text-ink/70">
                        <i class="fa-regular fa-user text-primary"></i>
                        <span>Jméno</span>
                    </div>
                    <?php foreach ($childrenRows as $child): ?>
                        <div class="text-ink font-semibold"><?= htmlspecialchars($child['name'] ?? '') ?></div>
                    <?php endforeach; ?>
                </div>

                <div class="grid grid-cols-[240px_1fr_1fr] items-center py-3">
                    <div class="flex items-center gap-3 text-ink/70">
                        <i class="fa-regular fa-calendar text-primary"></i>
                        <span>Věk</span>
                    </div>
                    <?php foreach ($childrenRows as $child): ?>
                        <div class="text-ink font-semibold"><?= htmlspecialchars($child['age'] ?? '') ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Sekce: Mazlíčci -->
    <?php if (!empty($petsRows)): ?>
        <div class="rounded-3xl bg-white/60 px-10 py-8 shadow-sm">
            <h3 class="mb-6 font-lora text-3xl font-semibold">
                Mazlíčci
            </h3>

            <div class="divide-y divide-mist">
                <div class="grid grid-cols-[240px_1fr_1fr] items-center py-3">
                    <div class="flex items-center gap-3 text-ink/70">
                        <i class="fa-solid fa-paw text-primary"></i>
                        <span>Jméno</span>
                    </div>
                    <?php foreach ($petsRows as $pet): ?>
                        <div class="text-ink font-semibold"><?= htmlspecialchars($pet['name'] ?? '') ?></div>
                    <?php endforeach; ?>
                </div>

                <div class="grid grid-cols-[240px_1fr_1fr] items-center py-3">
                    <div class="flex items-center gap-3 text-ink/70">
                        <i class="fa-regular fa-calendar text-primary"></i>
                        <span>Věk</span>
                    </div>
                    <?php foreach ($petsRows as $pet): ?>
                        <div class="text-ink font-semibold"><?= htmlspecialchars($pet['age'] ?? '') ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>