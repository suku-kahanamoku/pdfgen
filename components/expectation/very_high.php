<?php
$_src = '';
$_path = __DIR__ . '/../../img/emoticons/very_high.png';
if (file_exists($_path)) {
    $_src = 'data:image/png;base64,' . base64_encode(file_get_contents($_path));
}
?>
<div class="relative flex flex-col items-center">
    <?php if ($isActive ?? false): ?>
        <div class="flex h-40 w-40 items-center justify-center rounded-2xl border border-primary bg-cream/40">
            <div class="flex h-32 w-32 items-center justify-center rounded-full">
                <img src="<?= $_src ?>" class="h-full w-full object-contain" alt="">
            </div>
        </div>
        <div class="absolute -top-12 z-10 right-2/3">
            <div class="rounded-xl bg-secondary px-4 py-2 text-white shadow-sm w-48">
                <div class="font-semibold leading-tight">Nadšení</div>
                <div class="leading-tight text-white/85">Chci maximum a hned.</div>
            </div>
        </div>
    <?php else: ?>
        <div class="flex h-32 w-32 items-center justify-center rounded-full">
            <img src="<?= $_src ?>" class="h-full w-full object-contain" alt="">
        </div>
    <?php endif; ?>
</div>