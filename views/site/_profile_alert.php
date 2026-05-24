<?php

declare(strict_types=1);

/** @var string $type */
/** @var string $message */

$bgColor = 'bg-slate-900/80';
$borderColor = 'border-slate-800';
$textColor = 'text-slate-200';
$iconColor = 'text-sky-400';
$iconSvg = '';

if ($type === 'success') {
    $bgColor = 'bg-emerald-500/10 backdrop-blur-md';
    $borderColor = 'border-emerald-500/30';
    $textColor = 'text-emerald-300';
    $iconColor = 'text-emerald-400';
    $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
} elseif ($type === 'error') {
    $bgColor = 'bg-rose-500/10 backdrop-blur-md';
    $borderColor = 'border-rose-500/30';
    $textColor = 'text-rose-300';
    $iconColor = 'text-rose-400';
    $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>';
} elseif ($type === 'info') {
    $bgColor = 'bg-sky-500/10 backdrop-blur-md';
    $borderColor = 'border-sky-500/30';
    $textColor = 'text-sky-300';
    $iconColor = 'text-sky-400';
    $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.083.992l-.532.532a.75.75 0 01-1.061 0l-.532-.532a.75.75 0 010-1.061zM12 9a.75.75 0 100-1.5.75.75 0 000 1.5z" /></svg>';
}
?>

<div x-data="{ show: true }" 
     x-show="show" 
     x-init="setTimeout(() => show = false, 5000)"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-2 scale-95"
     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95"
     class="flex items-center gap-3 p-4 rounded-xl border <?= $bgColor ?> <?= $borderColor ?> <?= $textColor ?> shadow-lg shadow-black/25 relative overflow-hidden group">
    
    <!-- Linha de progresso que desvanece em 5s -->
    <div class="absolute bottom-0 left-0 h-[2px] bg-current opacity-25 animate-[progress_5s_linear_forwards]" style="width: 100%;"></div>

    <div class="flex-shrink-0 <?= $iconColor ?>">
        <?= $iconSvg ?>
    </div>
    <div class="flex-1 text-xs font-bold font-sans tracking-wide">
        <?= \yii\helpers\Html::encode($message) ?>
    </div>
    <button @click="show = false" class="flex-shrink-0 text-slate-400 hover:text-white transition cursor-pointer focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>

<style>
@keyframes progress {
    from { width: 100%; }
    to { width: 0%; }
}
</style>
