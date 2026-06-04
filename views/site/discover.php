<?php
/** @var yii\web\View $this */
/** @var array $modules */

use yii\helpers\Url;
use yii\helpers\Html;

// Carregar módulos ativos do PHP para embutir na lista de apps do Alpine
$activeAppsJson = [];
foreach ($modules as $mod) {
    $alpineId = $mod['id'] === 'app-wiki' ? 'wiki' : $mod['id'];
    $hasPermission = empty($mod['required_permission']) || Yii::$app->user->can($mod['required_permission']);
    $activeAppsJson[] = [
        'id' => $mod['id'],
        'alpineId' => $alpineId,
        'name' => $mod['name'],
        'entry_point' => $mod['entry_point'],
        'icon' => $mod['icon'],
        'required_permission' => $mod['required_permission'] ?: '',
        'has_permission' => $hasPermission,
        'category' => 'Produtividade', // Categoria padrão para módulos ativos
        'status' => 'Ativo',
        'description' => $mod['id'] === 'wiki' || $mod['id'] === 'app-wiki' 
            ? 'Wiki colaborativa GitOps descentralizada. Crie e organize páginas Markdown com atribuição de criadores e administradores de forma integrada.'
            : 'Módulo de aplicação isolado do sistema integrado dinamicamente via barramento de eventos globais do monólito.'
    ];
}

// Módulos futuros do Roadmap estáticos para simular o ecossistema completo
$roadmapApps = [
    [
        'id' => 'app-chat',
        'alpineId' => 'chat',
        'name' => 'app-chat (Comunicação Realtime)',
        'entry_point' => 'chat/default/index',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.625.625 0 11-1.25 0 .625.625 0 011.25 0zm0 0H8.25m4.125 0a.625.625 0 11-1.25 0 .625.625 0 011.25 0zm0 0H12m4.125 0a.625.625 0 11-1.25 0 .625.625 0 011.25 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" /></svg>',
        'required_permission' => '',
        'category' => 'Comunicação',
        'status' => 'Roadmap',
        'description' => 'Sistema de chat persistente e bate-papo de membros em canais organizados, integrado com o barramento de eventos globais (notificação de login, menções).'
    ],
    [
        'id' => 'app-terminal',
        'alpineId' => 'terminal',
        'name' => 'app-terminal (Console Seguro)',
        'entry_point' => 'terminal/default/index',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" /></svg>',
        'required_permission' => '',
        'category' => 'Desenvolvedor',
        'status' => 'Roadmap',
        'description' => 'Terminal web sandboxed com acesso restrito e seguro para disparar tarefas administrativas de SRE, deploy de Docker Swarm locais e execução de scripts do Crom CLI.'
    ],
    [
        'id' => 'app-rbac',
        'alpineId' => 'rbac',
        'name' => 'rbac-manager (Segurança & Papéis)',
        'entry_point' => 'rbac/default/index',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>',
        'required_permission' => 'admin-access',
        'category' => 'Segurança',
        'status' => 'Roadmap',
        'description' => 'Painel avançado de segurança de informação para gerenciamento de papéis, permissões de acessos de membros e auditoria forense de logs de SRE.'
    ],
    [
        'id' => 'app-api-explorer',
        'alpineId' => 'api-explorer',
        'name' => 'api-explorer (Conexões Globais)',
        'entry_point' => 'api/default/index',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9.75L16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" /></svg>',
        'required_permission' => '',
        'category' => 'Desenvolvedor',
        'status' => 'Roadmap',
        'description' => 'Painel de documentação viva e teste dinâmico para integrações de microsserviços do monólito modular e barramentos remotos.'
    ],
    [
        'id' => 'wiki',
        'alpineId' => 'wiki',
        'name' => 'Wiki Interna (GitOps Engine)',
        'entry_point' => 'wiki/default/index',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>',
        'required_permission' => 'access-wiki',
        'category' => 'Produtividade',
        'status' => 'Roadmap',
        'description' => 'Wiki colaborativa baseada em repositório Git remoto e commits com Token OAuth. Oculta temporariamente para auditoria.'
    ]
];

// Obter IDs de todos os módulos cadastrados no banco para evitar duplicados ou ocultar desativados
$registeredModuleIds = [];
$inactiveModuleIds = [];
try {
    $allModules = Yii::$app->db->createCommand("SELECT id, is_active FROM core_modules")->queryAll();
    foreach ($allModules as $modRow) {
        $registeredModuleIds[] = $modRow['id'];
        if (!(int)$modRow['is_active']) {
            $inactiveModuleIds[] = $modRow['id'];
        }
    }
} catch (\Exception $e) {}

// Filtra a lista de roadmap para não incluir duplicados de módulos reais nem módulos explicitamente inativados
$roadmapAppsFiltered = [];
foreach ($roadmapApps as $app) {
    if (in_array($app['id'], $inactiveModuleIds) || in_array($app['alpineId'], $inactiveModuleIds)) {
        continue; // Oculta completamente módulos que foram desativados no painel admin
    }
    if (!in_array($app['id'], $registeredModuleIds) && !in_array($app['alpineId'], $registeredModuleIds)) {
        $hasPermission = empty($app['required_permission']) || Yii::$app->user->can($app['required_permission']);
        $app['has_permission'] = $hasPermission;
        $roadmapAppsFiltered[] = $app;
    }
}

// Mescla as listas
$allApps = array_merge($activeAppsJson, $roadmapAppsFiltered);
?>

<div class="space-y-8 select-none max-w-6xl mx-auto pb-16 animate-fade-in"
     x-data='{
        search: "",
        activeCategory: "Todos",
        categories: ["Todos", "Produtividade", "Comunicação", "Desenvolvedor", "Segurança"],
        apps: <?= json_encode($allApps) ?>,
        
        // Função de Filtragem Dinâmica Reativa
        filteredApps() {
            return this.apps.filter(app => {
                const matchesSearch = app.name.toLowerCase().includes(this.search.toLowerCase()) || 
                                      app.description.toLowerCase().includes(this.search.toLowerCase()) ||
                                      app.id.toLowerCase().includes(this.search.toLowerCase());
                
                const matchesCategory = this.activeCategory === "Todos" || app.category === this.activeCategory;
                
                return matchesSearch && matchesCategory;
            });
        }
     }'>
     
    <!-- Título Centralizado com Badge -->
    <div class="text-center space-y-4">
        <div class="inline-flex items-center gap-2 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 px-3 py-1 rounded-full text-[10px] font-mono font-bold tracking-widest uppercase mx-auto">
            🔌 CENTRAL DE APLICATIVOS
        </div>
        <h2 class="text-3xl md:text-4xl font-extrabold text-slate-100 tracking-tight font-sans">
            Descubra os Módulos do Portal
        </h2>
        <p class="text-xs text-slate-400 max-w-lg mx-auto leading-relaxed">
            O Portal CROM opera sob a arquitetura de Monólito Modular. Descubra e gerencie aplicativos acoplados dinamicamente no barramento de eventos.
        </p>
    </div>

    <!-- ÁREA DE PESQUISA E BADGES DE CATEGORIA -->
    <div class="space-y-4 max-w-2xl mx-auto">
        <!-- Barra de Pesquisa Redonda com Lupa -->
        <div class="relative">
            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.637 10.637z" />
                </svg>
            </div>
            <input type="text" 
                   x-model="search"
                   placeholder="Pesquisar por módulos ou funcionalidades..." 
                   class="w-full bg-slate-900 border border-slate-800/80 focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/20 text-slate-200 placeholder-slate-500 rounded-full pl-11 pr-6 py-3 text-sm focus:outline-none transition-all shadow-inner">
        </div>

        <!-- Badges de Categoria -->
        <div class="flex flex-wrap justify-center gap-2 text-xs select-none">
            <template x-for="cat in categories" :key="cat">
                <button @click="activeCategory = cat"
                        class="py-1.5 px-4 border rounded-full transition duration-300 font-bold"
                        :class="activeCategory === cat 
                            ? 'bg-sky-500/10 border-sky-500/30 text-sky-400' 
                            : 'bg-slate-900/60 border-slate-800 text-slate-400 hover:border-slate-700 hover:text-slate-200'">
                    <span x-text="cat"></span>
                </button>
            </template>
        </div>
    </div>

    <!-- GRADE DE MÓDULOS FILTRADOS -->
    <div class="space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800 pb-2">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                <span>📦 Módulos Encontrados</span>
                <span class="px-2 py-0.5 bg-slate-900 text-slate-500 border border-slate-800/80 text-[10px] font-mono rounded-full font-bold" x-text="filteredApps().length"></span>
            </h3>
            <span class="text-[9px] text-slate-600 font-mono">Barramento Monólito Modular</span>
        </div>

        <!-- Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Loop de Apps do Alpine -->
            <template x-for="app in filteredApps()" :key="app.id">
                <div class="bg-gradient-to-br from-slate-900/40 to-slate-950 border border-slate-800/80 hover:border-slate-700/60 rounded-3xl p-6 flex flex-col justify-between hover:shadow-2xl hover:shadow-sky-950/10 transition duration-300 group relative overflow-hidden backdrop-blur-sm"
                     :class="app.status === 'Roadmap' ? 'opacity-70' : ''">
                    
                    <!-- Detalhe decorativo de fundo com gradiente -->
                    <div class="absolute -right-12 -top-12 w-24 h-24 bg-sky-500/5 rounded-full blur-xl group-hover:bg-sky-500/10 transition-all duration-300"></div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <!-- Ícone do Módulo -->
                            <div class="w-12 h-12 bg-sky-500/10 text-sky-400 rounded-2xl flex items-center justify-center border border-sky-500/20 shadow-lg shadow-sky-950/20"
                                 x-html="app.icon">
                            </div>
                            
                            <!-- Status Badge -->
                            <div>
                                <template x-if="app.status === 'Ativo'">
                                    <span class="px-2.5 py-0.5 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[9px] font-mono rounded-full font-bold flex items-center gap-1">
                                        <span class="w-1 h-1 rounded-full bg-emerald-500 animate-ping"></span>
                                        Ativo
                                    </span>
                                </template>
                                <template x-if="app.status === 'Roadmap'">
                                    <span class="px-2.5 py-0.5 bg-amber-500/10 text-amber-400 border border-amber-500/20 text-[9px] font-mono rounded-full font-bold flex items-center gap-1">
                                        Roadmap
                                    </span>
                                </template>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-base font-bold text-slate-200 group-hover:text-sky-400 transition leading-snug"
                                x-text="app.name">
                            </h4>
                            <p class="text-[9px] text-slate-500 font-mono mt-1">
                                ID: <span class="text-slate-400" x-text="app.id"></span> &bull; Categoria: <span class="text-slate-400" x-text="app.category"></span>
                            </p>
                        </div>

                        <p class="text-xs text-slate-400 leading-relaxed font-sans"
                           x-text="app.description">
                        </p>

                        <!-- Informações de Segurança -->
                        <template x-if="app.required_permission">
                            <div class="pt-2">
                                <span class="inline-flex items-center gap-1 text-[9px] font-mono bg-slate-900 border border-slate-800 text-slate-400 px-2.5 py-0.5 rounded-md">
                                    🔒 Permissão: <span class="text-sky-400 font-bold" x-text="app.required_permission"></span>
                                </span>
                            </div>
                        </template>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-900">
                        <!-- Botão Ativo com Permissão -->
                        <template x-if="app.status === 'Ativo' && (app.has_permission === undefined || app.has_permission)">
                            <button @click="openTab(app.alpineId)"
                                    class="w-full py-2.5 px-4 bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-slate-100 text-xs font-bold rounded-xl transition duration-300 flex items-center justify-center gap-2 shadow-md">
                                🚀 Executar Módulo
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </button>
                        </template>
                        <!-- Botão Ativo sem Permissão -->
                        <template x-if="app.status === 'Ativo' && app.has_permission !== undefined && !app.has_permission">
                            <button disabled 
                                    class="w-full py-2.5 px-4 bg-slate-900/40 text-slate-600 text-xs font-bold rounded-xl border border-slate-800/40 cursor-not-allowed flex items-center justify-center gap-2">
                                🔒 Acesso Restrito
                            </button>
                        </template>
                        <!-- Botão Inativo / Roadmap -->
                        <template x-if="app.status === 'Roadmap'">
                            <button disabled 
                                    class="w-full py-2.5 px-4 bg-slate-900/40 text-slate-600 text-xs font-bold rounded-xl border border-slate-800/40 cursor-not-allowed flex items-center justify-center gap-2">
                                🔒 Módulo em Auditoria SRE
                            </button>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        <!-- Estado Vazio: Nenhum módulo filtrado -->
        <div x-show="filteredApps().length === 0" 
             class="py-16 text-center select-none animate-fade-in"
             style="display: none;">
            <span class="text-3xl block mb-2">🔍</span>
            <h4 class="text-sm font-bold text-slate-400">Nenhum módulo encontrado</h4>
            <p class="text-xs text-slate-600 mt-1">Experimente limpar a pesquisa ou selecionar outra categoria.</p>
        </div>
    </div>
</div>
