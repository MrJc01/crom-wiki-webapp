<?php
/** @var yii\web\View $this */
/** @var string $url */
?>
<div class="w-full h-full bg-slate-950 overflow-hidden relative transition-all duration-300"
     :class="isFullscreen ? 'min-h-screen' : 'min-h-[calc(100vh-64px)]'"
     x-data="{ 
         url: '<?= htmlspecialchars($url) ?>', 
         copied: false,
         isOpen: false,
         copyUrl() {
             navigator.clipboard.writeText(this.url).then(() => {
                 this.copied = true;
                 setTimeout(() => this.copied = false, 2000);
             });
         }
     }">
    
    <!-- Floating Action Toolbar (Collapsible & Left-aligned) -->
    <div class="absolute top-4 left-4 z-50 flex items-center select-none">
        <div class="bg-slate-900/95 backdrop-blur-md border border-slate-800/80 rounded-xl p-1.5 shadow-2xl flex items-center gap-1.5 transition-all duration-300">
            <!-- Toggle Button (always visible) -->
            <button @click="isOpen = !isOpen" 
                    class="p-2 text-slate-400 hover:text-white hover:bg-slate-850/60 rounded-lg transition-all duration-200 cursor-pointer flex items-center justify-center"
                    aria-label="Menu de Ferramentas">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-300" :class="isOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            
            <!-- Separator -->
            <div class="w-[1px] h-4 bg-slate-800" x-show="isOpen" x-transition></div>

            <!-- Expanded Buttons -->
            <div class="flex items-center gap-1.5" 
                 x-show="isOpen" 
                 x-transition:enter="transition ease-out duration-350"
                 x-transition:enter-start="opacity-0 translate-x-[-15px] scale-95"
                 x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-x-[-15px] scale-95">
                 
                <!-- Botão: Copiar URL -->
                <button @click="copyUrl()" 
                        class="relative group p-2 hover:bg-slate-850/80 text-slate-400 hover:text-sky-400 rounded-lg transition-all duration-200 cursor-pointer flex items-center justify-center"
                        aria-label="Copiar link">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                    </svg>
                    <!-- Tooltip Feedback -->
                    <span class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 px-2.5 py-1 text-[10px] font-bold text-slate-100 bg-slate-950/95 border border-slate-800/80 rounded-md shadow-xl transition-all duration-200 opacity-0 scale-95 pointer-events-none group-hover:opacity-100 group-hover:scale-100 whitespace-nowrap"
                          :class="copied ? 'opacity-100 scale-100' : ''"
                          x-text="copied ? 'Copiado!' : 'Copiar URL'">
                    </span>
                </button>
                
                <!-- Separador -->
                <div class="w-[1px] h-4 bg-slate-800"></div>

                <!-- Botão: Tela Cheia -->
                <button @click="isFullscreen = !isFullscreen" 
                        class="relative group p-2 hover:bg-slate-850/80 rounded-lg transition-all duration-200 cursor-pointer flex items-center justify-center"
                        :class="isFullscreen ? 'text-sky-400' : 'text-slate-400 hover:text-sky-400'"
                        aria-label="Tela cheia">
                    <template x-if="!isFullscreen">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path>
                        </svg>
                    </template>
                    <template x-if="isFullscreen">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 14h6v6m10-6h-6v6M4 10h6V4m10 6h-6V4"></path>
                        </svg>
                    </template>
                    <!-- Tooltip -->
                    <span class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 px-2.5 py-1 text-[10px] font-bold text-slate-100 bg-slate-950/95 border border-slate-800/80 rounded-md shadow-xl transition-all duration-200 opacity-0 scale-95 pointer-events-none group-hover:opacity-100 group-hover:scale-100 whitespace-nowrap"
                          x-text="isFullscreen ? 'Sair da tela cheia' : 'Tela cheia'">
                    </span>
                </button>
                
                <!-- Separador -->
                <div class="w-[1px] h-4 bg-slate-800"></div>
                
                <!-- Botão: Abrir Nova Guia -->
                <a :href="url" 
                   target="_blank" 
                   class="relative group p-2 hover:bg-slate-850/80 text-slate-400 hover:text-indigo-400 rounded-lg transition-all duration-200 flex items-center justify-center"
                   aria-label="Abrir em nova aba">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                        <polyline points="15 3 21 3 21 9"></polyline>
                        <line x1="10" y1="14" x2="21" y2="3"></line>
                    </svg>
                    <!-- Tooltip -->
                    <span class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 px-2.5 py-1 text-[10px] font-bold text-slate-100 bg-slate-950/95 border border-slate-800/80 rounded-md shadow-xl transition-all duration-200 opacity-0 scale-95 pointer-events-none group-hover:opacity-100 group-hover:scale-100 whitespace-nowrap">
                        Abrir em nova guia
                    </span>
                </a>
            </div>
        </div>
    </div>

    <iframe src="<?= htmlspecialchars($url) ?>" class="w-full h-full absolute inset-0 border-0" allow="clipboard-write; clipboard-read"></iframe>
</div>
