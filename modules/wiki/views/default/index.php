<?php

/** @var yii\web\View $this */
/** @var array $tree */
/** @var array $rawPages */

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Wiki Interna — Central Modular';
?>

<?php
// Função recursiva de renderização física da árvore de pastas e arquivos no PHP
if (!function_exists('renderWikiTree')) {
    function renderWikiTree(array $items, int $level = 0) {
        foreach ($items as $key => $item) {
            if (isset($item['type']) && $item['type'] === 'dir') {
                ?>
                <li x-data="{ open: false }" class="mt-0.5">
                    <div @click="open = !open" 
                         class="flex items-center gap-1.5 py-1 px-2 hover:bg-slate-800/40 rounded-lg text-slate-300 hover:text-sky-400 cursor-pointer transition select-none text-xs font-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" 
                             class="w-3 h-3 text-slate-500 transition-transform duration-200" :class="open ? 'rotate-90 text-sky-400' : ''">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                        <span class="flex items-center gap-1">
                            <span class="text-sky-500/80">📁</span> 
                            <span><?= Html::encode($item['name']) ?></span>
                        </span>
                    </div>
                    <ul x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 transform -translate-y-1"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="pl-3 border-l border-slate-800/60 ml-3.5 mt-0.5 space-y-0.5">
                        <?php renderWikiTree($item['children'], $level + 1); ?>
                    </ul>
                </li>
                <?php
            } else {
                $pageItem = $item;
                ?>
                <li class="mt-0.5">
                    <div @click="loadFile('<?= Html::encode(str_replace("'", "\'", $pageItem['path'])) ?>')"
                         class="flex items-center gap-2 py-1 px-2 hover:bg-slate-800/40 rounded-lg text-slate-400 hover:text-slate-200 cursor-pointer transition text-xs font-mono"
                         :class="activeFile === '<?= Html::encode($pageItem['path']) ?>' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20 font-bold' : 'border border-transparent'">
                        <span class="text-slate-500">📄</span>
                        <span class="truncate" title="<?= Html::encode($pageItem['title']) ?>"><?= Html::encode($pageItem['name']) ?></span>
                    </div>
                </li>
                <?php
            }
        }
    }
}
?>

<div class="flex flex-col md:flex-row h-full w-full bg-slate-950 text-slate-100 border border-slate-800/40 rounded-2xl overflow-hidden shadow-2xl backdrop-blur-sm"
     x-data="wikiApp()">
    
    <!-- LADO ESQUERDO: Árvore de Diretórios (25% no desktop, full/auto no mobile) -->
    <div class="w-full md:w-80 border-b md:border-b-0 md:border-r border-slate-800/80 bg-slate-900/20 p-4 flex flex-col justify-between flex-shrink-0 select-none">
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Diretório da Wiki</span>
                <span class="px-2 py-0.5 bg-sky-500/10 text-sky-400 border border-sky-500/20 text-[10px] font-mono rounded-full font-bold">docs/</span>
            </div>
            
            <!-- Botão Premium para Criar Nova Página -->
            <button @click="openCreateForm()"
                    class="w-full bg-gradient-to-r from-sky-600/20 to-indigo-600/20 border border-sky-500/30 hover:border-sky-500/60 hover:from-sky-600/30 hover:to-indigo-600/30 text-sky-400 hover:text-sky-300 text-xs py-2.5 px-3 rounded-xl transition duration-300 flex items-center justify-center gap-2 font-bold shadow-md shadow-sky-950/20">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Criar Nova Página
            </button>
            
            <div class="overflow-y-auto max-h-[300px] md:max-h-[calc(100vh-260px)] pr-2 scrollbar-thin">
                <ul class="space-y-1">
                    <?php if (empty($tree)): ?>
                        <div class="text-xs text-slate-500 italic p-2 font-mono">Nenhuma página encontrada.</div>
                    <?php else: ?>
                        <?php renderWikiTree($tree); ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <!-- Botão de Sincronização Local -->
        <div class="pt-4 border-t border-slate-900 flex-shrink-0 mt-4">
            <button hx-post="<?= Url::to(['/wiki/default/sync']) ?>"
                    hx-swap="none"
                    hx-on::after-request="location.reload()"
                    class="w-full bg-slate-900/60 border border-slate-800 hover:border-sky-500/30 hover:bg-sky-500/5 text-slate-300 hover:text-sky-400 text-xs py-2.5 px-3 rounded-xl transition duration-300 flex items-center justify-center gap-2 font-bold shadow-md shadow-black/20">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5 animate-spin-hover">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                🔄 Atualizar Base (Git Pull)
            </button>
        </div>

        <!-- Painel de Conexão do GitHub -->
        <div class="pt-4 border-t border-slate-900 flex-shrink-0 mt-4 space-y-2 select-none">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Integração GitHub</span>
                <span class="px-2 py-0.5 bg-slate-950 text-slate-400 border border-slate-800 text-[10px] font-mono rounded-full font-bold">OAuth</span>
            </div>
            
            <template x-if="github.connected">
                <div class="space-y-2">
                    <div class="p-3 bg-slate-950 border border-slate-800 rounded-xl flex items-center justify-between">
                        <div class="min-w-0 flex-1 pr-2">
                            <div class="text-xs font-bold text-sky-400 truncate">@<span x-text="github.username"></span></div>
                            <div class="text-[9px] text-slate-500 font-mono truncate" x-text="github.repo"></div>
                        </div>
                        <span class="text-xs flex-shrink-0">🟢</span>
                    </div>
                    <a href="<?= Url::to(['/wiki/auth/disconnect']) ?>"
                       class="w-full bg-rose-500/10 border border-rose-500/20 hover:border-rose-500/40 text-rose-400 hover:text-rose-300 text-xs py-2 px-3 rounded-xl transition duration-300 flex items-center justify-center gap-1.5 font-bold shadow-md shadow-rose-950/20">
                        Desconectar GitHub
                    </a>
                </div>
            </template>
            
            <template x-if="!github.connected">
                <a href="<?= Url::to(['/wiki/auth/login']) ?>"
                   class="w-full bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white text-xs py-2.5 px-3 rounded-xl transition duration-300 flex items-center justify-center gap-2 font-bold shadow-md shadow-sky-950/20">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.167 6.839 9.49.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.464-1.11-1.464-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.831.092-.646.35-1.086.636-1.336-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.579.688.481C19.137 20.164 22 16.418 22 12c0-5.523-4.477-10-10-10z"/>
                    </svg>
                    Conectar com GitHub
                </a>
            </template>
        </div>
    </div>

    <!-- LADO DIREITO: Leitor e Editor de Markdown (75%) -->
    <div class="flex-1 flex flex-col h-full bg-slate-950/60 p-4 md:p-6 overflow-hidden min-w-0">
        
        <!-- Estado Vazio: Nenhuma página selecionada e não está criando -->
        <template x-if="!activeFile && !creating">
            <div class="flex-1 flex flex-col items-center justify-center text-center p-8 select-none animate-fade-in">
                <div class="w-16 h-16 bg-slate-900/60 text-slate-500 rounded-3xl flex items-center justify-center font-bold text-4xl border border-slate-800 shadow-xl mb-4">
                    📖
                </div>
                <h2 class="text-lg font-bold text-slate-300">Central Wiki Crom</h2>
                <p class="text-xs text-slate-500 mt-2 max-w-sm leading-relaxed">
                    Selecione um arquivo Markdown na árvore de diretórios à esquerda para visualizar seu conteúdo estruturado ou criar um novo arquivo organizacional utilizando metadados.
                </p>
            </div>
        </template>

        <!-- FORMULÁRIO DE CRIAÇÃO DE NOVA PÁGINA (x-show="creating") -->
        <div x-show="creating" class="flex-1 flex flex-col min-h-0 animate-fade-in" style="display: none;">
            <!-- Cabeçalho de Criação -->
            <div class="flex justify-between items-center pb-4 border-b border-slate-800/80 mb-4 flex-shrink-0">
                <div>
                    <h3 class="text-base font-extrabold text-slate-100 flex items-center gap-2">
                        <span class="text-emerald-400 font-mono text-[10px] bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded-md uppercase tracking-wider">NOVO</span>
                        <span>Criar Nova Página Wiki</span>
                    </h3>
                    <p class="text-[10px] text-slate-500 mt-1">Crie arquivos Markdown diretamente na árvore organizacional.</p>
                </div>
                <button @click="creating = false; activeFile = '';" class="py-1 px-3 bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-400 hover:text-slate-200 text-xs rounded-lg font-bold transition">
                    Fechar
                </button>
            </div>

            <!-- Toast Alerts dentro do Form -->
            <template x-if="errMsg">
                <div class="mb-4 p-3 bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-bold rounded-xl flex items-center gap-2 shadow-lg">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                    <span x-text="errMsg"></span>
                </div>
            </template>
            <template x-if="successMsg">
                <div class="mb-4 p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold rounded-xl flex items-center gap-2 shadow-lg">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span x-text="successMsg"></span>
                </div>
            </template>

            <!-- Formulário Estruturado -->
            <div class="flex-1 min-h-0 bg-slate-900/10 rounded-2xl border border-slate-800/60 p-4 md:p-6 overflow-y-auto scrollbar-thin shadow-inner space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 font-mono">Caminho do Arquivo (Ex: membros/guia-novo.md)</label>
                        <input type="text" 
                               x-model="newFilePath" 
                               placeholder="diretorio/nome-do-arquivo.md"
                               class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 rounded-xl px-4 py-2.5 text-xs text-slate-200 placeholder-slate-600 focus:outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 font-mono">Selecionar Administrador da Página</label>
                        <select x-model="newFileAdminId"
                                class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 rounded-xl px-4 py-2.5 text-xs text-slate-200 focus:outline-none transition-all">
                            <option value="">-- Sem Administrador Definido --</option>
                            <template x-for="user in users" :key="user.id">
                                <option :value="user.id" x-text="user.username"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col h-[300px] md:h-[calc(100vh-450px)] min-h-[250px]">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 font-mono">Conteúdo em Markdown</label>
                    <textarea x-model="newFileContent" 
                              class="w-full flex-1 bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 rounded-xl p-4 text-slate-100 font-mono text-xs resize-none focus:outline-none transition-all"
                              placeholder="# Digite seu Markdown aqui..."></textarea>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-slate-900 select-none">
                    <div>
                        <template x-if="github.connected">
                            <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-300 hover:text-slate-100 transition">
                                <input type="checkbox" x-model="commitGithub" class="rounded border-slate-800 bg-slate-950 text-emerald-500 focus:ring-emerald-500/20 focus:ring-offset-slate-950 w-4 h-4">
                                <span>🚀 Enviar commit para o GitHub (<span class="font-mono text-emerald-400 text-[10px]" x-text="github.repo"></span>)</span>
                            </label>
                        </template>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" @click="creating = false; activeFile = '';" class="py-2 px-4 bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-400 hover:text-slate-200 text-xs rounded-xl font-bold transition">Cancelar</button>
                        <button type="button" @click="createPage()" 
                                :disabled="saving"
                                class="py-2 px-5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-xs rounded-xl font-bold shadow-lg shadow-emerald-600/15 transition-all duration-300 transform active:scale-95 flex items-center gap-2">
                            <template x-if="saving">
                                <svg class="animate-spin h-3.5 w-3.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </template>
                            <span x-text="saving ? 'Salvando...' : '🚀 Criar Página Wiki'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- VISUALIZADOR / EDITOR DE PÁGINA ATIVA (x-show="activeFile && !creating") -->
        <div x-show="activeFile && !creating" class="flex-1 flex flex-col min-h-0 animate-fade-in" style="display: none;">
            
            <!-- Cabeçalho do Editor/Visualizador -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-800/80 mb-4 gap-4 flex-shrink-0">
                <div>
                    <h3 class="text-base font-extrabold text-slate-100 flex items-center gap-2">
                        <span class="text-sky-400 font-mono text-[10px] bg-sky-500/10 border border-sky-500/20 px-2 py-0.5 rounded-md uppercase tracking-wider">ATIVO</span>
                        <span x-text="fileTitle"></span>
                    </h3>
                    
                    <!-- Polling de Lock Concorrente via HTMX -->
                    <div :hx-get="'<?= Url::to(['/wiki/default/lock-status']) ?>?path=' + encodeURIComponent(activeFile) + '&status=' + (editing ? 'EDITING' : 'VIEWING')" 
                         hx-trigger="every 5s" 
                         hx-swap="innerHTML"
                         class="mt-1">
                         <div class="text-xs text-slate-500 flex items-center gap-1.5 mt-1 font-mono bg-slate-900/50 border border-slate-800/80 px-2 py-0.5 rounded-lg w-fit">
                             <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                             ✓ Obtendo status do lock...
                         </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-2 select-none">
                    <!-- Alternador Editar / Visualizar -->
                    <button @click="editing = !editing; if(!editing) { renderMarkdown(); }" 
                            x-show="canManage()"
                            class="py-1.5 px-3.5 bg-slate-900/60 hover:bg-slate-800 border border-slate-800 text-slate-300 hover:text-slate-100 text-xs rounded-xl font-bold transition flex items-center gap-1.5 shadow-md shadow-black/20"
                            :class="editing ? 'bg-sky-600/10 border-sky-500/20 text-sky-400 hover:bg-sky-500/10' : ''">
                        <span x-text="editing ? '👁️ Visualizar' : '📝 Editar Página'"></span>
                    </button>
                </div>
            </div>

            <!-- Toast Alerts / Notificações -->
            <div class="flex-shrink-0">
                <template x-if="successMsg">
                    <div class="mb-4 p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold rounded-xl animate-fade-in flex items-center gap-2 shadow-lg shadow-emerald-950/20">
                        <span class="flex h-2 w-2 relative">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span x-text="successMsg"></span>
                    </div>
                </template>
                <template x-if="errMsg">
                    <div class="mb-4 p-3 bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-bold rounded-xl animate-fade-in flex items-center gap-2 shadow-lg shadow-rose-950/20">
                        <span class="flex h-2 w-2 relative">
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                        </span>
                        <span x-text="errMsg"></span>
                    </div>
                </template>
            </div>

            <!-- Painel Conteúdo: Modo Leitura vs Modo Edição -->
            <div class="flex-1 min-h-0 bg-slate-900/10 rounded-2xl border border-slate-800/60 p-4 md:p-6 overflow-y-auto scrollbar-thin shadow-inner">
                
                <!-- MODO EDIÇÃO (Formulário Atômico) -->
                <div x-show="editing" class="h-full flex flex-col justify-between min-h-[400px]">
                    <div class="flex-grow space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5 font-mono">caminho do arquivo</label>
                                <input type="text" :value="activeFile" readonly class="w-full bg-slate-950/80 border border-slate-900 rounded-xl px-4 py-2 text-xs text-slate-500 font-mono select-all focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5 font-mono">Administrador da Página</label>
                                <select x-model="adminId"
                                        class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none transition-all">
                                    <option value="">-- Sem Administrador Definido --</option>
                                    <template x-for="user in users" :key="user.id">
                                        <option :value="user.id" :selected="user.id == adminId" x-text="user.username"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-col h-[280px] md:h-[calc(100vh-420px)] min-h-[220px]">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 font-mono">Editor Markdown</label>
                            <textarea x-model="fileContent" 
                                      class="w-full flex-grow bg-slate-950/80 border border-slate-800/80 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 rounded-xl p-4 text-slate-100 font-mono text-xs sm:text-sm resize-none focus:outline-none transition-all"
                                      placeholder="# Digite seu Markdown aqui..."></textarea>
                        </div>
                    </div>
                    
                    <div class="mt-4 flex items-center justify-between pt-4 border-t border-slate-900 flex-shrink-0 select-none">
                        <div>
                            <template x-if="github.connected">
                                <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-300 hover:text-slate-100 transition">
                                    <input type="checkbox" x-model="commitGithub" class="rounded border-slate-800 bg-slate-950 text-sky-500 focus:ring-sky-500/20 focus:ring-offset-slate-950 w-4 h-4">
                                    <span>🚀 Enviar commit para o GitHub (<span class="font-mono text-sky-400 text-[10px]" x-text="github.repo"></span>)</span>
                                </label>
                            </template>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" @click="editing = false; renderMarkdown();" class="py-2 px-4 bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-400 hover:text-slate-200 text-xs rounded-xl font-bold transition">Cancelar</button>
                            <button type="button" @click="saveFile()" 
                                    :disabled="saving"
                                    class="py-2 px-5 bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white text-xs rounded-xl font-bold shadow-lg shadow-sky-600/15 transition-all duration-300 transform active:scale-95 flex items-center gap-2">
                                <template x-if="saving">
                                    <svg class="animate-spin h-3.5 w-3.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </template>
                                <span x-text="saving ? 'Salvando...' : '🚀 Salvar Alterações'"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- MODO VISUALIZAÇÃO (Prose Markdown Renderizado) -->
                <div x-show="!editing" class="h-full flex flex-col justify-between">
                    <div class="flex-grow">
                        <article id="markdown-preview" class="prose prose-invert max-w-none select-text">
                            <!-- Injetado pelo Marked.js -->
                        </article>
                    </div>
                    
                    <!-- PÍLULAS DE METADADOS PREMIUM NO RODAPÉ -->
                    <div class="mt-8 pt-4 border-t border-slate-900/60 space-y-3">
                        <div class="flex flex-wrap gap-2 items-center select-none text-[10px] font-mono">
                            <!-- Criador -->
                            <div class="flex items-center gap-1.5 bg-slate-900 border border-slate-800 text-slate-400 px-3 py-1 rounded-full">
                                <span>👤 Autor:</span>
                                <span class="text-sky-400 font-bold" x-text="createdBy"></span>
                            </div>
                            
                            <!-- Administrador -->
                            <div class="flex items-center gap-1.5 bg-slate-900 border border-slate-800 text-slate-400 px-3 py-1 rounded-full">
                                <span>🔑 Admin Responsável:</span>
                                <span class="text-indigo-400 font-bold" x-text="adminName"></span>
                            </div>

                            <!-- Última Modificação -->
                            <div class="flex items-center gap-1.5 bg-slate-900 border border-slate-800 text-slate-400 px-3 py-1 rounded-full ml-auto">
                                <span>🕒 Sincronizado:</span>
                                <span class="text-slate-300" x-text="lastSynced"></span>
                            </div>
                        </div>

                        <div class="text-[9px] text-slate-600 font-mono">
                            caminho relativo do arquivo: <span class="text-slate-500" x-text="activeFile"></span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>

<!-- Folha de estilos para animar o hover do spinner -->
<style>
.animate-spin-hover:hover {
    animation: spin 1.2s linear infinite;
}
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>

<script>
function wikiApp() {
    return {
        currentUser: {
            id: <?= (int)Yii::$app->user->id ?>,
            username: <?= json_encode(Yii::$app->user->identity->username) ?>,
            isAdmin: <?= Yii::$app->user->can('admin-access') ? 'true' : 'false' ?>
        },
        canManage() {
            if (this.currentUser.isAdmin) return true;
            if (this.createdBy === this.currentUser.username) return true;
            return this.adminId !== null && parseInt(this.adminId) === this.currentUser.id;
        },
        activeFile: '',
        fileTitle: '',
        fileContent: '',
        lastSynced: '',
        createdBy: '',
        adminId: '',
        adminName: '',
        editing: false,
        creating: false,
        saving: false,
        successMsg: '',
        errMsg: '',
        users: [],
        github: { connected: false, username: '', repo: '' },
        commitGithub: false,
        
        // Dados para Criação de Nova Página
        newFilePath: '',
        newFileContent: '# 📝 Nova Página\n\nDigite o conteúdo em Markdown aqui.',
        newFileAdminId: '',
        
        // Inicializador
        init() {
            this.loadUsers();
            this.loadGithubStatus();
        },
        
        // Carrega a lista de usuários ativos para administradores
        loadUsers() {
            fetch('<?= Url::to(['/wiki/default/users-list']) ?>')
                .then(res => res.json())
                .then(data => {
                    this.users = data;
                })
                .catch(err => {
                    console.error('Erro ao buscar lista de usuários.');
                });
        },
        
        // Carrega status da conexão GitHub
        loadGithubStatus() {
            fetch('<?= Url::to(['/wiki/auth/status']) ?>')
                .then(res => res.json())
                .then(data => {
                    this.github = data;
                })
                .catch(err => {
                    console.error('Erro ao buscar status do GitHub.');
                });
        },
        
        // Carrega arquivo do backend
        loadFile(path) {
            this.activeFile = path;
            this.editing = false;
            this.creating = false;
            this.successMsg = '';
            this.errMsg = '';
            
            fetch('<?= Url::to(['/wiki/default/view']) ?>?path=' + encodeURIComponent(path))
                .then(response => response.json())
                .then(data => {
                    this.fileTitle = data.title;
                    this.fileContent = data.content;
                    this.lastSynced = data.last_synced_at;
                    this.createdBy = data.created_by;
                    this.adminId = data.admin_id || '';
                    this.adminName = data.admin_name;
                    
                    // Dispara renderização imediata do markdown
                    this.$nextTick(() => {
                        this.renderMarkdown();
                    });
                })
                .catch(err => {
                    this.errMsg = 'Erro ao ler arquivo do servidor.';
                });
        },
        
        // Renderiza o markdown bruto para HTML com marked.js
        renderMarkdown() {
            const el = document.getElementById('markdown-preview');
            if (el) {
                el.innerHTML = marked.parse(this.fileContent || '');
                if (window.addCopyButtonsToPreElements) {
                    window.addCopyButtonsToPreElements(el);
                }
            }
        },
        
        // Abre formulário para Criar Nova Página
        openCreateForm() {
            this.creating = true;
            this.editing = false;
            this.activeFile = 'criar-nova-pagina';
            this.fileTitle = 'Criar Nova Página';
            this.newFilePath = '';
            this.newFileContent = '# 📝 Nova Página\n\nDigite o conteúdo em Markdown aqui.';
            this.newFileAdminId = '';
            this.successMsg = '';
            this.errMsg = '';
        },
        
        // Salva arquivo (Edição de existente)
        saveFile() {
            this.saving = true;
            this.successMsg = '';
            this.errMsg = '';
            
            const formData = new FormData();
            formData.append('filepath', this.activeFile);
            formData.append('markdown_content', this.fileContent);
            formData.append('commit_github', this.commitGithub ? '1' : '0');
            if (this.adminId) {
                formData.append('admin_id', this.adminId);
            }
            
            // Adiciona o token CSRF de segurança do Yii2
            formData.append('<?= Yii::$app->request->csrfParam ?>', '<?= Yii::$app->request->getCsrfToken() ?>');
            
            fetch('<?= Url::to(['/wiki/default/save']) ?>', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                this.saving = false;
                if (data.success) {
                    this.successMsg = data.message;
                    this.fileTitle = data.title;
                    this.editing = false;
                    this.renderMarkdown();
                    
                    // Atualiza metadados
                    this.loadFile(this.activeFile);
                    
                    // Autoclose alert depois de 3 segundos
                    setTimeout(() => { this.successMsg = ''; }, 3000);
                } else {
                    this.errMsg = data.message;
                }
            })
            .catch(err => {
                this.saving = false;
                this.errMsg = 'Falha de comunicação com o servidor.';
            });
        },
        
        // Cria Nova Página no servidor
        createPage() {
            if (!this.newFilePath) {
                this.errMsg = 'O caminho relativo do arquivo é obrigatório.';
                return;
            }
            
            this.saving = true;
            this.successMsg = '';
            this.errMsg = '';
            
            const formData = new FormData();
            formData.append('filepath', this.newFilePath);
            formData.append('markdown_content', this.newFileContent);
            formData.append('commit_github', this.commitGithub ? '1' : '0');
            if (this.newFileAdminId) {
                formData.append('admin_id', this.newFileAdminId);
            }
            
            formData.append('<?= Yii::$app->request->csrfParam ?>', '<?= Yii::$app->request->getCsrfToken() ?>');
            
            fetch('<?= Url::to(['/wiki/default/save']) ?>', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                this.saving = false;
                if (data.success) {
                    this.successMsg = data.message;
                    
                    // Força a recarga para atualizar a árvore com o novo item
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    this.errMsg = data.message;
                }
            })
            .catch(err => {
                this.saving = false;
                this.errMsg = 'Erro de rede ou permissão ao criar página.';
            });
        }
    };
}
</script>
