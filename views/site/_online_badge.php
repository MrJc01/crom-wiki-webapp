<?php
declare(strict_types=1);

/** @var int $onlineCount */

use yii\helpers\Url;
?>
<!-- Elemento do Badge Online a ser selecionado e substituído pelo HTMX -->
<div id="online-badge" 
     @click="openTab('online_members')" 
     class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/20 cursor-pointer shadow-sm hover:shadow-emerald-500/5 transition-all duration-300 select-none">
     <span class="relative flex h-2 w-2 flex-shrink-0">
       <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
       <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
     </span>
     <span class="text-[10px] font-extrabold uppercase tracking-wider font-mono whitespace-nowrap">
         <?= $onlineCount ?> ONLINE
     </span>
</div>
