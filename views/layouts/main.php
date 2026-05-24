<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $content */

use yii\helpers\Html;
use yii\helpers\Url;

// Resolve a aba ativa inicial com base na rota atual do Yii2
$currentRoute = Yii::$app->controller->route;
$initialTab = 'paravoce';
if ($currentRoute === 'site/discover') {
    $initialTab = 'discover';
} elseif ($currentRoute === 'site/beneficios') {
    $initialTab = 'beneficios';
}  elseif (strpos($currentRoute, 'page_crud') !== false) {
    $initialTab = 'page_crud';
} elseif (strpos($currentRoute, 'wiki') !== false) {
    $initialTab = 'wiki';
}

// Consulta de usuários online ativa nos últimos 15 minutos (Otimização SRE)
$timeThreshold = time() - 900;
$onlineCount = 0;
try {
    $onlineCount = (int)Yii::$app->db->createCommand("
        SELECT COUNT(user_id) 
        FROM core_session_status 
        WHERE last_activity >= :threshold AND is_online = 1
    ", [':threshold' => $timeThreshold])->queryScalar();
} catch (\Exception $e) {
    // Silencia
}

echo $this->render('_head');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-full bg-slate-950 font-sans">
<head>
    <?php $this->head() ?>
    <title><?= Html::encode($this->title) ?></title>
<body class="h-full text-slate-100 <?= Yii::$app->user->isGuest ? 'overflow-y-auto' : 'overflow-hidden' ?>">
<?php $this->beginBody() ?>

<!-- Barra de Progresso Superior Dinâmica (YouTube/GitHub Style) -->
<div id="top-progress-bar"></div>

<!-- Loader Principal Premium de Página Inteira -->
<div id="page-loader-root">
    <div class="loader-logo-glow">
        <div class="loader-glow-ring"></div>
        <!-- Logotipo CROM Oficial (favicon de crom.run) -->
        <img src="<?= Yii::getAlias('@web/crom-logo.png') ?>" alt="CROM" class="w-20 h-20 z-10 drop-shadow-[0_0_25px_rgba(255,255,255,0.15)]" />
    </div>
    <div class="mt-6 flex flex-col items-center gap-1 z-10">
        <div class="flex items-center gap-1.5">
            <span class="text-sm font-extrabold tracking-wider text-slate-100 font-sans">CROM</span>
            <span class="text-xs font-semibold text-slate-400 font-sans">Developer Program</span>
        </div>
        <span class="text-[10px] font-semibold text-slate-500 font-mono tracking-wider animate-pulse uppercase mt-1">inicializando ambiente...</span>
    </div>
</div>

<?php if (Yii::$app->user->isGuest): ?>
    <!-- Layout Limpo para Convidados (Tela de Login) -->
    <main class="min-h-screen w-full flex items-center justify-center bg-slate-950">
        <?= $content ?>
    </main>
<?php else: ?>
    <?php
    // Carrega módulos ativos do banco SQLite (para a Wiki)
    $activeModules = [];
    try {
        $activeModules = Yii::$app->db->createCommand("
            SELECT id, name, icon, entry_point, required_permission 
            FROM core_modules 
            WHERE is_active = 1 
            ORDER BY sort_order ASC
        ")->queryAll();
    } catch (\Exception $e) {
        // Silencia
    }
    ?>

    <!-- CASCA PRINCIPAL PREMIUM SPA (Estilo Google Developer Program) -->
    <div class="flex flex-col h-screen w-screen bg-slate-950 text-slate-100 overflow-hidden" 
         x-data="{ 
             activeTab: '<?= $initialTab ?>',
             routes: {
                 'paravoce': '<?= Url::to(['/site/index']) ?>',
                 'discover': '<?= Url::to(['/site/discover']) ?>',
                 'beneficios': '<?= Url::to(['/site/beneficios']) ?>',
                 'page_crud': '<?= Url::to(['/page_crud/default/index']) ?>',
                 'wiki': '<?= Url::to(['/wiki/default/index']) ?>',
                 'profile': '<?= Url::to(['/site/profile']) ?>',
                 'online_members': '<?= Url::to(['/site/online-members']) ?>'
             },
             openTab(id, push = true) {
                 this.activeTab = id;
                 if (push) {
                     const path = this.routes[id] || ('/' + id);
                     if (window.location.pathname !== path) {
                         history.pushState({ tabId: id }, '', path);
                     }
                 }
                 // Simula clique no botão HTMX correspondente caso necessário
                 const btn = document.getElementById('btn-nav-' + id);
                 if (btn && !btn.getAttribute('hx-loaded')) {
                     btn.click();
                     btn.setAttribute('hx-loaded', 'true');
                 }
             },
             init() {
                 // Trata o botão de Voltar/Avançar do navegador
                 window.addEventListener('popstate', (event) => {
                     if (event.state && event.state.tabId) {
                         this.openTab(event.state.tabId, false);
                     } else {
                         // Fallback para a aba inicial baseada na URL atual
                         const path = window.location.pathname;
                         let matchedTab = 'paravoce';
                         for (const [tab, url] of Object.entries(this.routes)) {
                             if (path.endsWith(url) || url.endsWith(path)) {
                                 matchedTab = tab;
                                 break;
                             }
                         }
                         this.openTab(matchedTab, false);
                     }
                 });

                 // Se a aba ativa atual não for a 'paravoce', garante que HTMX está disparado
                 if (this.activeTab !== 'paravoce') {
                     const btn = document.getElementById('btn-nav-' + this.activeTab);
                     if (btn) {
                         const container = document.getElementById('container-' + this.activeTab);
                         if (container && container.getAttribute('hx-isomorphic') === 'true') {
                             btn.setAttribute('hx-loaded', 'true');
                         } else {
                             this.openTab(this.activeTab, false);
                         }
                     }
                 }
             }
         }">

        <!-- 1. TOPBAR SUPERIOR FIXO (Google Style) -->
        <header class="h-16 bg-slate-900 border-b border-slate-800/80 flex items-center justify-between px-6 flex-shrink-0 select-none z-50 shadow-md">
            <!-- Lado Esquerdo: Logotipo CROM Program -->
            <div class="flex items-center gap-3 cursor-pointer" @click="openTab('paravoce')">
                <!-- Logotipo Oficial CROM (favicon de crom.run) -->
                <img src="<?= Yii::getAlias('@web/crom-logo.png') ?>" alt="CROM" class="w-8 h-8 rounded-md drop-shadow-md" />
                <div class="flex flex-col sm:flex-row sm:items-center gap-1">
                    <span class="text-sm font-extrabold tracking-wider text-slate-100 font-sans">CROM</span>
                    <span class="text-xs font-semibold text-slate-400 font-sans">Developer Program</span>
                </div>
            </div>

            <!-- Lado Direito: Alternador de Tema, Online Badge e Perfil -->
            <div class="flex items-center gap-4">
                <!-- Contador e Lista de Usuários Online (HTMX Polling de 10s) -->
                <!-- Contador e Lista de Usuários Online (Otimização SRE: swap innerHTML) -->
                <div hx-get="<?= Url::to(['/site/online-badge']) ?>"
                     hx-trigger="every 10s"
                     hx-swap="innerHTML"
                     id="online-badge-container">
                     <?= $this->render('@app/views/site/_online_badge', ['onlineCount' => $onlineCount]) ?>
                </div>

              

                <!-- Avatar do Usuário com Borda & Dropdown Premium -->
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="w-9 h-9 rounded-full ring-2 ring-sky-500/20 bg-sky-500/10 flex items-center justify-center font-bold text-xs text-sky-400 hover:bg-sky-500/20 transition-all duration-300 focus:outline-none cursor-pointer">
                        <?= strtoupper(substr(Yii::$app->user->identity->username, 0, 2)) ?>
                    </button>
                    <!-- Menu Dropdown -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100" 
                         x-transition:enter-start="transform opacity-0 scale-95" 
                         x-transition:enter-end="transform opacity-100 scale-100" 
                         x-transition:leave="transition ease-in duration-75" 
                         x-transition:leave-start="transform opacity-100 scale-100" 
                         x-transition:leave-end="transform opacity-0 scale-95" 
                         class="absolute right-0 mt-2 w-56 rounded-xl bg-slate-900 border border-slate-800 shadow-2xl py-2 z-50 backdrop-blur-lg bg-opacity-95"
                         style="display: none;">
                        <div class="px-4 py-2.5 border-b border-slate-800/80 flex flex-col">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Usuário ativo</span>
                            <span class="text-sm font-extrabold text-white truncate" id="profile-display-username"><?= Html::encode(Yii::$app->user->identity->username) ?></span>
                        </div>
                        <div class="p-1.5 flex flex-col gap-1">
                            <button @click="openTab('profile'); open = false;" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-bold text-slate-300 hover:text-white rounded-lg hover:bg-slate-800/60 transition-all cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-sky-400">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.43l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 010-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28z" />
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Configurações
                            </button>
                            <a href="<?= Url::to(['/site/logout']) ?>" data-method="post" class="flex items-center gap-2.5 px-3 py-2 text-xs font-bold text-rose-400 hover:text-rose-300 rounded-lg hover:bg-rose-500/10 transition-all cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                </svg>
                                Sair do Sistema
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- 2. LAYOUT INFERIOR (SIDEBAR + CONTEÚDO) -->
        <div class="flex flex-grow h-[calc(100vh-64px)] w-screen overflow-hidden relative">
            
            <!-- SIDEBAR VERTICAL (Google Developer Style) -->
            <aside class="w-20 md:w-24 bg-slate-900 border-r border-slate-800/80 flex flex-col justify-between py-6 select-none flex-shrink-0 z-40 shadow-lg">
                <div class="flex flex-col items-center gap-6 w-full px-2">
                    
                    <!-- Aba 1: Para Você (Dashboard) -->
                    <button @click="openTab('paravoce')"
                            class="w-full flex flex-col items-center group cursor-pointer border border-transparent"
                            title="Para você">
                        <div class="w-12 h-8 rounded-2xl flex items-center justify-center transition-all duration-200"
                             :class="activeTab === 'paravoce' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20 shadow-md' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/40'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                        </div>
                        <span class="text-[9px] font-bold text-center mt-1.5 tracking-wide transition-all"
                              :class="activeTab === 'paravoce' ? 'text-sky-400 font-extrabold' : 'text-slate-500 group-hover:text-slate-300'">
                            Para você
                        </span>
                    </button>

                    <!-- Aba 2: Discover -->
                    <button @click="openTab('discover')"
                            hx-get="<?= Url::to(['/site/discover']) ?>"
                            hx-target="#container-discover"
                            hx-trigger="click once"
                            id="btn-nav-discover"
                            class="w-full flex flex-col items-center group cursor-pointer border border-transparent"
                            title="Discover">
                        <div class="w-12 h-8 rounded-2xl flex items-center justify-center transition-all duration-200"
                             :class="activeTab === 'discover' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20 shadow-md' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/40'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c.172-.44.82-.44.992 0l1.986 5.07a1 1 0 00.754.673l5.518.442c.473.038.662.618.312.946l-4.148 3.87a1 1 0 00-.291.895l1.184 5.39c.101.462-.395.823-.807.546l-4.757-3.213a1 1 0 00-1.1 0l-4.757 3.213c-.412.277-.908-.084-.807-.546l1.184-5.39a1 1 0 00-.291-.895l-4.148-3.87c-.35-.328-.16-.908.312-.946l5.518-.442a1 1 0 00.754-.673l1.986-5.07H11.48z" />
                            </svg>
                        </div>
                        <span class="text-[9px] font-bold text-center mt-1.5 tracking-wide transition-all"
                              :class="activeTab === 'discover' ? 'text-sky-400 font-extrabold' : 'text-slate-500 group-hover:text-slate-300'">
                            Discover
                        </span>
                    </button>

                    <!-- Aba 3: Benefícios -->
                    <button @click="openTab('beneficios')"
                            hx-get="<?= Url::to(['/site/beneficios']) ?>"
                            hx-target="#container-beneficios"
                            hx-trigger="click once"
                            id="btn-nav-beneficios"
                            class="w-full flex flex-col items-center group cursor-pointer border border-transparent"
                            title="Benefícios">
                        <div class="w-12 h-8 rounded-2xl flex items-center justify-center transition-all duration-200"
                             :class="activeTab === 'beneficios' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20 shadow-md' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/40'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                        </div>
                        <span class="text-[9px] font-bold text-center mt-1.5 tracking-wide transition-all"
                              :class="activeTab === 'beneficios' ? 'text-sky-400 font-extrabold' : 'text-slate-500 group-hover:text-slate-300'">
                            Benefícios
                        </span>
                    </button>

                  

                   


                    <!-- Divisor e Módulo Wiki (Mapeamento RBAC) -->
                    <?php foreach ($activeModules as $mod): ?>
                        <?php if (empty($mod['required_permission']) || Yii::$app->user->can($mod['required_permission'])): ?>
                            <div class="w-10 border-t border-slate-800/80 my-2"></div>
                            <button @click="openTab('<?= Html::encode($mod['id']) ?>')"
                                    hx-get="<?= Url::to([$mod['entry_point']]) ?>"
                                    hx-target="#container-<?= Html::encode($mod['id']) ?>"
                                    hx-trigger="click once"
                                    id="btn-nav-<?= Html::encode($mod['id']) ?>"
                                    class="w-full flex flex-col items-center group cursor-pointer border border-transparent"
                                    title="<?= Html::encode($mod['name']) ?>">
                                <div class="w-12 h-8 rounded-2xl flex items-center justify-center transition-all duration-200"
                                     :class="activeTab === '<?= Html::encode($mod['id']) ?>' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20 shadow-md' : 'text-slate-400 hover:text-sky-400 hover:bg-slate-800/40'">
                                    <span class="w-5 h-5 flex items-center justify-center">
                                        <?= $mod['icon'] ?>
                                    </span>
                                </div>
                                <span class="text-[9px] font-bold text-center mt-1.5 tracking-wide transition-all"
                                      :class="activeTab === '<?= Html::encode($mod['id']) ?>' ? 'text-sky-400 font-extrabold' : 'text-slate-500 group-hover:text-slate-300'">
                                    <?= Html::encode($mod['id'] === 'page_crud' ? 'Páginas' : 'Wiki') ?>
                                </span>
                            </button>
                        <?php endif; ?>
                    <?php endforeach; ?>

                </div>
                
                <!-- Rodapé da Sidebar: Perfil Premium & Logout -->
                <div class="flex flex-col items-center gap-4 w-full px-1">
                    <!-- Link Premium mrj.crom -->
                    <div class="flex flex-col items-center gap-1 cursor-pointer">
                        <div class="w-7 h-7 rounded-full bg-gradient-to-r from-amber-500 to-rose-500 flex items-center justify-center text-[10px] font-bold text-white shadow-md">
                            Ω
                        </div>
                    </div>

                    <!-- Botão de Sair -->
                    <a href="<?= Url::to(['/site/logout']) ?>" 
                       data-method="post"
                       class="w-8 h-8 rounded-xl flex items-center justify-center text-slate-500 hover:text-rose-400 hover:bg-rose-500/10 border border-transparent hover:border-rose-500/20 transition-all duration-200"
                       title="Sair do Portal">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                    </a>
                </div>
            </aside>

            <!-- CONTEÚDO PRINCIPAL COM ABAS SPA INJETADAS ESTÁTICAMENTE -->
            <main class="flex-1 bg-slate-950 overflow-hidden relative">
                
                <!-- Aba 1: Para Você (Dashboard Central Yii2) -->
                <div class="h-full w-full overflow-y-auto scrollbar-thin p-6 md:p-10" x-show="activeTab === 'paravoce'">
                    <?php if ($initialTab === 'paravoce'): ?>
                        <?= $content ?>
                    <?php endif; ?>
                </div>

                <!-- Aba 2: Discover (Artigos) -->
                <div class="h-full w-full absolute inset-0 overflow-y-auto scrollbar-thin p-6 md:p-10 bg-slate-950" 
                     x-show="activeTab === 'discover'"
                     id="container-discover"
                     <?= $initialTab === 'discover' ? 'hx-isomorphic="true"' : '' ?>>
                     <?php if ($initialTab === 'discover'): ?>
                         <?= $content ?>
                     <?php else: ?>
                         <div class="flex items-center justify-center h-full text-slate-500 text-sm">
                              <div class="flex flex-col items-center gap-2">
                                  <svg class="animate-spin h-5 w-5 text-sky-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                  </svg>
                                  <span class="font-mono text-xs text-slate-400 tracking-wider">carregando Discover...</span>
                              </div>
                         </div>
                     <?php endif; ?>
                </div>

                <!-- Aba 3: Benefícios -->
                <div class="h-full w-full absolute inset-0 overflow-y-auto scrollbar-thin p-6 md:p-10 bg-slate-950" 
                     x-show="activeTab === 'beneficios'"
                     id="container-beneficios"
                     <?= $initialTab === 'beneficios' ? 'hx-isomorphic="true"' : '' ?>>
                     <?php if ($initialTab === 'beneficios'): ?>
                         <?= $content ?>
                     <?php else: ?>
                         <div class="flex items-center justify-center h-full text-slate-500 text-sm">
                              <div class="flex flex-col items-center gap-2">
                                  <svg class="animate-spin h-5 w-5 text-sky-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                  </svg>
                                  <span class="font-mono text-xs text-slate-400 tracking-wider">carregando Benefícios...</span>
                              </div>
                         </div>
                     <?php endif; ?>
                </div>

              


                <!-- Aba Módulo Wiki (Injeções HTMX Dinâmicas por Módulo) -->
                <?php foreach ($activeModules as $mod): ?>
                    <?php if (empty($mod['required_permission']) || Yii::$app->user->can($mod['required_permission'])): ?>
                        <div class="h-full w-full absolute inset-0 overflow-y-auto scrollbar-thin p-4 md:p-6 bg-slate-950" 
                             x-show="activeTab === '<?= Html::encode($mod['id']) ?>'"
                             id="container-<?= Html::encode($mod['id']) ?>"
                             <?= $initialTab === $mod['id'] ? 'hx-isomorphic="true"' : '' ?>>
                             <?php if ($initialTab === $mod['id']): ?>
                                 <?= $content ?>
                             <?php else: ?>
                                 <div class="flex items-center justify-center h-full text-slate-500 text-sm">
                                      <div class="flex flex-col items-center gap-2">
                                          <svg class="animate-spin h-5 w-5 text-sky-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                          </svg>
                                          <span class="font-mono text-xs text-slate-400 tracking-wider">carregando modulo...</span>
                                      </div>
                                 </div>
                             <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>

                <!-- Aba 4: Perfil & Configurações Premium (Injeção SPA) -->
                <div class="h-full w-full absolute inset-0 overflow-y-auto scrollbar-thin p-6 md:p-10 bg-slate-950" 
                     x-show="activeTab === 'profile'"
                     id="container-profile"
                     <?= $initialTab === 'profile' ? 'hx-isomorphic="true"' : '' ?>>
                     <?php if ($initialTab === 'profile'): ?>
                         <?= $content ?>
                     <?php else: ?>
                         <div class="flex items-center justify-center h-full text-slate-500 text-sm">
                              <div class="flex flex-col items-center gap-2">
                                  <svg class="animate-spin h-5 w-5 text-sky-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                  </svg>
                                  <span class="font-mono text-xs text-slate-400 tracking-wider">carregando Configurações...</span>
                              </div>
                         </div>
                     <?php endif; ?>
                </div>

                <!-- Gatilho de Carregamento Assíncrono Invisível HTMX para a Aba de Perfil -->
                <button id="btn-nav-profile"
                        hx-get="<?= Url::to(['/site/profile']) ?>"
                        hx-target="#container-profile"
                        hx-trigger="click once"
                        class="hidden">
                </button>

                <!-- Aba 5: Membros Online Premium (Injeção SPA) -->
                <div class="h-full w-full absolute inset-0 overflow-y-auto scrollbar-thin p-6 md:p-10 bg-slate-950" 
                     x-show="activeTab === 'online_members'"
                     id="container-online_members"
                     <?= $initialTab === 'online_members' ? 'hx-isomorphic="true"' : '' ?>>
                     <?php if ($initialTab === 'online_members'): ?>
                         <?= $content ?>
                     <?php else: ?>
                         <div class="flex items-center justify-center h-full text-slate-500 text-sm">
                              <div class="flex flex-col items-center gap-2">
                                  <svg class="animate-spin h-5 w-5 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                  </svg>
                                  <span class="font-mono text-xs text-slate-400 tracking-wider">carregando Membros Online...</span>
                              </div>
                         </div>
                     <?php endif; ?>
                </div>

                <!-- Gatilho de Carregamento Assíncrono Invisível HTMX para a Aba de Membros Online -->
                <button id="btn-nav-online_members"
                        hx-get="<?= Url::to(['/site/online-members']) ?>"
                        hx-target="#container-online_members"
                        hx-trigger="click once"
                        class="hidden">
                </button>

            </main>
        </div>
    </div>
<?php endif; ?>

<script>
    // Objeto Controlador da Barra de Progresso Superior (YouTube/GitHub Style)
    const ProgressBar = {
        el: null,
        timer: null,
        width: 0,
        init() {
            this.el = document.getElementById('top-progress-bar');
        },
        start() {
            if (!this.el) this.init();
            if (!this.el) return;
            clearInterval(this.timer);
            this.width = 0;
            this.el.style.width = '0%';
            this.el.style.opacity = '1';
            this.timer = setInterval(() => {
                if (this.width < 85) {
                    // Avanço dinâmico simulando carregamento real
                    this.width += Math.random() * 7 + 2;
                    this.el.style.width = this.width + '%';
                }
            }, 120);
        },
        finish() {
            if (!this.el) this.init();
            if (!this.el) return;
            clearInterval(this.timer);
            this.el.style.width = '100%';
            setTimeout(() => {
                this.el.style.opacity = '0';
                setTimeout(() => {
                    this.el.style.width = '0%';
                }, 300);
            }, 220);
        }
    };

    // Função de ocultação do Loader Principal
    function hidePageLoader() {
        const loader = document.getElementById('page-loader-root');
        if (loader) {
            loader.style.opacity = '0';
            setTimeout(() => {
                loader.style.display = 'none';
                loader.remove(); // Remove completamente do DOM para economizar recursos
            }, 450);
        }
    }

    // Inicializa listeners assim que o DOM for lido
    document.addEventListener('DOMContentLoaded', () => {
        // Vincula eventos do HTMX globalmente
        document.body.addEventListener('htmx:beforeRequest', () => {
            ProgressBar.start();
        });
        document.body.addEventListener('htmx:afterRequest', () => {
            ProgressBar.finish();
        });
        document.body.addEventListener('htmx:requestError', () => {
            ProgressBar.finish();
        });
    });

    // Oculta o loader no evento de carregamento completo
    window.addEventListener('load', hidePageLoader);

    // Fallback preventivo de SRE: oculta em 2.5s se algum recurso externo falhar
    setTimeout(hidePageLoader, 2500);
</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
