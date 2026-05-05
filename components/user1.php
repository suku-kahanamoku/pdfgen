<?php
// ============================================================
// PAGE – USER
// ============================================================
$client       = $user['client']  ?? [];
$partner      = $user['partner'] ?? [];
$childrenRows = $user['children']['rows'] ?? [];
$petsRows     = $user['pets']['rows']     ?? [];

?>

<div class="w-full box-border p-24 flex flex-col gap-8 break-after-page box-decoration-clone">

    <!-- Sekce: Klient -->
    <div class="rounded-3xl bg-white/80 px-8 py-8 shadow break-inside-avoid">
        <h3 class="mb-4 font-lora text-3xl font-semibold">
            Základní údaje o klientovi
        </h3>

        <div class="border-b border-mist/70">
            <div class="grid grid-cols-2 items-center py-2.5">
                <div class="flex items-center gap-3 text-ink/70">
                    <i class="fa-regular fa-user text-primary"></i>
                    <span>Jméno a příjmení</span>
                </div>
                <div class="text-ink font-semibold"><?= htmlspecialchars($client['name'] ?? '') ?></div>
            </div>

            <div class="grid grid-cols-2 items-center py-2.5 border-t border-mist/70">
                <div class="flex items-center gap-3 text-ink/70">
                    <i class="fa-regular fa-calendar text-primary"></i>
                    <span>Datum narození</span>
                </div>
                <div class="text-ink font-semibold"><?= htmlspecialchars($client['birth_date'] ?? '') ?></div>
            </div>

            <div class="grid grid-cols-2 items-center py-2.5 border-t border-mist/70">
                <div class="flex items-center gap-3 text-ink/70">
                    <i class="fa-solid fa-location-dot text-primary"></i>
                    <span>Adresa</span>
                </div>
                <div class="text-ink font-semibold"><?= htmlspecialchars($client['address'] ?? '') ?></div>
            </div>

            <div class="grid grid-cols-2 items-center py-2.5 border-t border-mist/70">
                <div class="flex items-center gap-3 text-ink/70">
                    <i class="fa-regular fa-heart text-primary"></i>
                    <span>Rodinný stav</span>
                </div>
                <div class="text-ink font-semibold"><?= htmlspecialchars($client['marital_status'] ?? '') ?></div>
            </div>

            <div class="grid grid-cols-2 items-center py-2.5 border-t border-mist/70">
                <div class="flex items-center gap-3 text-ink/70">
                    <i class="fa-solid fa-phone text-primary"></i>
                    <span>Telefon</span>
                </div>
                <div class="text-ink font-semibold"><?= htmlspecialchars($client['phone'] ?? '') ?></div>
            </div>

            <div class="grid grid-cols-2 items-center py-2.5 border-t border-mist/70">
                <div class="flex items-center gap-3 text-ink/70">
                    <i class="fa-regular fa-envelope text-primary"></i>
                    <span>Email</span>
                </div>
                <div class="text-ink font-semibold"><?= htmlspecialchars($client['email'] ?? '') ?></div>
            </div>
        </div>
    </div>

    <!-- Sekce: Partner -->
    <div class="rounded-3xl bg-white/80 px-8 py-8 shadow break-inside-avoid">
        <h3 class="mb-4 font-lora text-3xl font-semibold">
            Partner
        </h3>

        <div class="border-b border-mist/70">
            <div class="grid grid-cols-2 items-center py-2.5">
                <div class="flex items-center gap-3 text-ink/70">
                    <i class="fa-regular fa-user text-primary"></i>
                    <span>Jméno a příjmení</span>
                </div>
                <div class="text-ink font-semibold"><?= htmlspecialchars($partner['name'] ?? '') ?></div>
            </div>

            <div class="grid grid-cols-2 items-center py-2.5 border-t border-mist/70">
                <div class="flex items-center gap-3 text-ink/70">
                    <i class="fa-regular fa-calendar text-primary"></i>
                    <span>Datum narození</span>
                </div>
                <div class="text-ink font-semibold"><?= htmlspecialchars($partner['birth_date'] ?? '') ?></div>
            </div>

            <div class="grid grid-cols-2 items-center py-2.5 border-t border-mist/70">
                <div class="flex items-center gap-3 text-ink/70">
                    <i class="fa-solid fa-location-dot text-primary"></i>
                    <span>Adresa</span>
                </div>
                <div class="text-ink font-semibold"><?= htmlspecialchars($partner['address'] ?? '') ?></div>
            </div>

            <div class="grid grid-cols-2 items-center py-2.5 border-t border-mist/70">
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
        <div class="rounded-3xl bg-white/80 px-8 py-8 shadow break-inside-avoid">
            <h3 class="mb-4 font-lora text-3xl font-semibold">
                Děti
            </h3>

            <div class="border-b border-mist/70">
                <div class="grid grid-cols-2 items-center py-2.5">
                    <div class="flex items-center gap-3 text-ink/70">
                        <i class="fa-regular fa-user text-primary"></i>
                        <span>Jméno</span>
                    </div>
                    <div class="flex items-center gap-3 text-ink/70">
                        <i class="fa-regular fa-calendar text-primary"></i>
                        <span>Věk</span>
                    </div>
                </div>
                <?php foreach ($childrenRows as $child): ?>
                    <div class="grid grid-cols-2 items-center py-2.5 border-t border-mist/70">
                        <div class="text-ink font-semibold"><?= htmlspecialchars($child['name'] ?? '') ?></div>
                        <div class="text-ink font-semibold"><?= htmlspecialchars($child['age'] ?? '') ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Sekce: Mazlíčci -->
    <?php if (!empty($petsRows)): ?>
        <div class="rounded-3xl bg-white/80 px-8 py-8 shadow break-inside-avoid">
            <h3 class="mb-4 font-lora text-3xl font-semibold">
                Mazlíčci
            </h3>

            <div class="border-b border-mist/70">
                <div class="grid grid-cols-2 items-center py-2.5">
                    <div class="flex items-center gap-3 text-ink/70">
                        <i class="fa-solid fa-paw text-primary"></i>
                        <span>Jméno</span>
                    </div>
                    <div class="flex items-center gap-3 text-ink/70">
                        <i class="fa-regular fa-calendar text-primary"></i>
                        <span>Věk</span>
                    </div>
                </div>
                <?php foreach ($petsRows as $pet): ?>
                    <div class="grid grid-cols-2 items-center py-2.5 border-t border-mist/70">
                        <div class="text-ink font-semibold"><?= htmlspecialchars($pet['name'] ?? '') ?></div>
                        <div class="text-ink font-semibold"><?= htmlspecialchars($pet['age'] ?? '') ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

</div>