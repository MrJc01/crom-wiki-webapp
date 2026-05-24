<?php
use yii\helpers\Url;
?>
<div class="space-y-6"
     x-data="{
         isLoading: false,
         logsContent: '',
         
         loadLogs() {
             this.isLoading = true;
             fetch('<?= Url::to(['/admin/default/get-logs']) ?>')
             .then(res => res.text())
             .then(text => {
                 this.logsContent = text;
                 this.isLoading = false;
                 // Scroll do terminal para o fim após o carregamento
                 this.$nextTick(() => {
                     const terminal = this.$refs.terminalContainer;
                     if (terminal) {
                         terminal.scrollTop = terminal.scrollHeight;
                     }
                 });
             })
             .catch(err => {
                 this.logsContent = '<div class=\"p-4 text-rose-400 font-mono text-xs\">Erro de rede ao carregar os logs do sistema.</div>';
                 this.isLoading = false;
             });
         },
         
         clearTerminal() {
             this.logsContent = '<div class=\"p-4 text-slate-500 font-mono text-xs\">Terminal limpo localmente. Clique em Atualizar para buscar novos logs.</div>';
         },
         
         init() {
             this.loadLogs();
         }
     }">

    <!-- Header -->
    <div class="flex justify-between items-center select-none">
        <div class="space-y-1">
            <h3 class="text-sm font-extrabold text-white tracking-wide">Monitoramento SRE & Auditoria de Logs</h3>
            <p class="text-[10px] text-slate-500 font-semibold leading-relaxed">Exibição incremental em tempo real das últimas 150 linhas de logs do monólito em `runtime/logs/app.log`.</p>
        </div>
        <div class="flex gap-2">
            <button @click="clearTerminal()"
                    class="px-3 py-1.5 border border-slate-800 hover:border-slate-700 bg-slate-900/60 hover:bg-slate-800 text-slate-400 hover:text-white rounded-xl text-xs font-bold transition flex items-center gap-1 cursor-pointer">
                <i class="material-icons text-base">clear_all</i>
                Limpar
            </button>
            <button @click="loadLogs()"
                    :disabled="isLoading"
                    class="px-4 py-1.5 bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow-lg shadow-sky-600/15">
                <template x-if="!isLoading">
                    <span class="flex items-center gap-1.5">
                        <i class="material-icons text-base">refresh</i>
                        Atualizar
                    </span>
                </template>
                <template x-if="isLoading">
                    <span class="flex items-center gap-1.5 animate-pulse">
                        <svg class="animate-spin h-3.5 w-3.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Lendo arquivo...
                    </span>
                </template>
            </button>
        </div>
    </div>

    <!-- Terminal/Console de Logs -->
    <div class="border border-slate-800/80 rounded-2xl overflow-hidden bg-slate-950 shadow-2xl flex flex-col h-[400px]">
        <!-- Top bar do terminal -->
        <div class="h-10 bg-slate-900 border-b border-slate-800 px-4 flex items-center justify-between select-none">
            <div class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span class="text-[10px] font-bold text-slate-500 font-mono ml-2">app.log</span>
            </div>
            <span class="text-[9px] font-extrabold text-slate-600 font-mono uppercase tracking-wider">Console de Diagnóstico</span>
        </div>
        
        <!-- Conteúdo do terminal -->
        <div x-ref="terminalContainer"
             class="flex-1 p-6 overflow-y-auto scrollbar-thin bg-slate-950/80 select-text font-mono text-[10px]"
             x-html="logsContent">
        </div>
    </div>
</div>
