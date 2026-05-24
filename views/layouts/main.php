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
} elseif (strpos($currentRoute, 'page_crud') !== false) {
    $initialTab = 'page_crud';
} elseif (strpos($currentRoute, 'wiki') !== false) {
    $initialTab = 'wiki';
} elseif (strpos($currentRoute, 'chat') !== false) {
    $initialTab = 'chat';
} elseif (strpos($currentRoute, 'json_store') !== false) {
    $initialTab = 'json_store';
} elseif (strpos($currentRoute, 'admin') !== false) {
    $initialTab = 'admin';
}

// Consulta de configurações dinâmicas (portal_title e portal_subtitle)
$settingsMap = [];
try {
    $settingsList = Yii::$app->db->createCommand("SELECT * FROM core_settings")->queryAll();
    foreach ($settingsList as $s) {
        $settingsMap[$s['key']] = $s['value'];
    }
} catch (\Exception $e) {
    // Silencia se a tabela não existir
}
$portalTitle = $settingsMap['portal_title'] ?? 'CROM';
$portalSubtitle = $settingsMap['portal_subtitle'] ?? 'Developer Program';

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

    <!-- CASCA PRINCIPAL PREMIUM SPA (Estilo Google Developer Program) -->
    <div class="flex flex-col h-screen w-screen bg-slate-950 text-slate-100 overflow-hidden" 
         x-data="{ 
             activeTab: '<?= $initialTab ?>',
             showChatDrawer: false,
             routes: {
                 'paravoce': '<?= Url::to(['/site/index']) ?>',
                 'discover': '<?= Url::to(['/site/discover']) ?>',
                 'beneficios': '<?= Url::to(['/site/beneficios']) ?>',
                 'page_crud': '<?= Url::to(['/page_crud/default/index']) ?>',
                 'wiki': '<?= Url::to(['/wiki/default/index']) ?>',
                 'profile': '<?= Url::to(['/site/profile']) ?>',
                 'online_members': '<?= Url::to(['/site/online-members']) ?>',
                 'chat': '<?= Url::to(['/chat/default/index']) ?>',
                 'json_store': '<?= Url::to(['/json_store/default/index']) ?>',
                 'terminal': '<?= Url::to(['/terminal/default/index']) ?>',
                 'admin': '<?= Url::to(['/admin/default/index']) ?>'
             },
             openTab(id, push = true) {
                 if (!id) return;
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
                 if (id === 'paravoce') {
                     setTimeout(() => {
                         if (typeof window.initDashboardSwipers === 'function') {
                             window.initDashboardSwipers();
                         }
                     }, 50);
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

                 // Escuta alterações de configurações/módulos para atualizar o portal
                 document.body.addEventListener('portalSettingsUpdated', () => {
                     window.location.reload();
                 });
                 document.body.addEventListener('portalModulesUpdated', () => {
                     window.location.reload();
                 });

                 // Escuta respostas HTMX para reinicializar os Swipers da home
                 document.body.addEventListener('htmx:afterSwap', (evt) => {
                     if (evt.detail.target.id === 'container-paravoce') {
                         if (typeof window.initDashboardSwipers === 'function') {
                             window.initDashboardSwipers();
                         }
                     }
                 });
             }
         }">

        <!-- 1. TOPBAR SUPERIOR FIXO (Google Style) -->
        <header class="h-16 bg-slate-900 border-b border-slate-800/80 flex items-center justify-between px-6 flex-shrink-0 select-none z-50 shadow-md">
            <!-- Lado Esquerdo: Logotipo CROM Program -->
            <div class="flex items-center gap-3 cursor-pointer" @click="openTab('paravoce')">
                <!-- Logotipo Oficial CROM (favicon de crom.run) -->
                <img src="<?= Yii::getAlias('@web/crom-logo.png') ?>" alt="CROM" class="w-8 h-8 rounded-md drop-shadow-md" />
                <div class="flex flex-col sm:flex-row sm:items-center gap-1">
                    <span class="text-sm font-extrabold tracking-wider text-slate-100 font-sans"><?= Html::encode($portalTitle) ?></span>
                    <span class="text-xs font-semibold text-slate-400 font-sans"><?= Html::encode($portalSubtitle) ?></span>
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

                <!-- Botão de Chat Recentes (Abre Drawer) -->
                <button @click="showChatDrawer = !showChatDrawer" 
                        class="h-9 w-9 rounded-full bg-slate-900 border border-slate-800/80 flex items-center justify-center text-slate-400 hover:text-white hover:bg-slate-850 hover:border-slate-700/80 transition-all duration-300 cursor-pointer relative focus:outline-none"
                        title="Mensagens Recentes">
                    <i class="material-icons text-xl">chat_bubble</i>
                </button>

              

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
                            hx-get="<?= Url::to(['/site/index']) ?>"
                            hx-target="#container-paravoce"
                            hx-trigger="click once"
                            id="btn-nav-paravoce"
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

                   

                    <?php if (Yii::$app->user->can('admin-access')): ?>
                        <!-- Aba Admin: Painel Administrativo -->
                        <button @click="openTab('admin')"
                                hx-get="<?= Url::to(['/admin/default/index']) ?>"
                                hx-target="#container-admin"
                                hx-trigger="click once"
                                id="btn-nav-admin"
                                class="w-full flex flex-col items-center group cursor-pointer border border-transparent"
                                title="Painel Admin">
                            <div class="w-12 h-8 rounded-2xl flex items-center justify-center transition-all duration-200"
                                 :class="activeTab === 'admin' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20 shadow-md' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/40'">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.43l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 010-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28z" />
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <span class="text-[9px] font-bold text-center mt-1.5 tracking-wide transition-all"
                                  :class="activeTab === 'admin' ? 'text-sky-400 font-extrabold' : 'text-slate-500 group-hover:text-slate-300'">
                                Admin
                            </span>
                        </button>
                    <?php endif; ?>

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
                 <div class="h-full w-full absolute inset-0 overflow-y-auto scrollbar-thin p-6 md:p-10 bg-slate-950" 
                      x-show="activeTab === 'paravoce'"
                      id="container-paravoce"
                      <?= $initialTab === 'paravoce' ? 'hx-isomorphic="true"' : '' ?>>
                      <?php if ($initialTab === 'paravoce'): ?>
                          <?= $content ?>
                      <?php else: ?>
                          <div class="flex items-center justify-center h-full text-slate-500 text-sm">
                               <div class="flex flex-col items-center gap-2">
                                   <svg class="animate-spin h-5 w-5 text-sky-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                       <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                       <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                   </svg>
                                   <span class="font-mono text-xs text-slate-400 tracking-wider">carregando Dashboard...</span>
                               </div>
                          </div>
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

                <!-- Gatilho de Carregamento Assíncrono Invisível HTMX para a Aba de Chat -->
                <button id="btn-nav-chat"
                        hx-get="<?= Url::to(['/chat/default/index']) ?>"
                        hx-target="#container-chat"
                        hx-trigger="click once"
                        class="hidden">
                </button>

                <!-- Aba 6: Módulo de Chat Premium (Injeção SPA) -->
                <div class="h-full w-full absolute inset-0 overflow-y-auto scrollbar-thin p-4 md:p-6 bg-slate-950" 
                     x-show="activeTab === 'chat'"
                     id="container-chat"
                     <?= $initialTab === 'chat' ? 'hx-isomorphic="true"' : '' ?>>
                     <?php if ($initialTab === 'chat'): ?>
                         <?= $content ?>
                     <?php else: ?>
                         <div class="flex items-center justify-center h-full text-slate-500 text-sm">
                              <div class="flex flex-col items-center gap-2">
                                  <svg class="animate-spin h-5 w-5 text-sky-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                  </svg>
                                  <span class="font-mono text-xs text-slate-400 tracking-wider">carregando módulo de chat...</span>
                              </div>
                         </div>
                     <?php endif; ?>
                </div>

                <!-- Aba 7: Painel Administrativo (Injeção SPA) -->
                <?php if (Yii::$app->user->can('admin-access')): ?>
                    <div class="h-full w-full absolute inset-0 overflow-y-auto scrollbar-thin p-6 md:p-10 bg-slate-950" 
                         x-show="activeTab === 'admin'"
                         id="container-admin"
                         <?= $initialTab === 'admin' ? 'hx-isomorphic="true"' : '' ?>>
                         <?php if ($initialTab === 'admin'): ?>
                             <?= $content ?>
                         <?php else: ?>
                             <div class="flex items-center justify-center h-full text-slate-500 text-sm">
                                  <div class="flex flex-col items-center gap-2">
                                      <svg class="animate-spin h-5 w-5 text-sky-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                      </svg>
                                      <span class="font-mono text-xs text-slate-400 tracking-wider">carregando painel admin...</span>
                                  </div>
                             </div>
                         <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Aba Wiki: Wiki Documentações (Injeção SPA) -->
                <div class="h-full w-full absolute inset-0 overflow-y-auto scrollbar-thin p-6 md:p-10 bg-slate-950" 
                     x-show="activeTab === 'wiki'"
                     id="container-wiki"
                     <?= $initialTab === 'wiki' ? 'hx-isomorphic="true"' : '' ?>>
                     <?php if ($initialTab === 'wiki'): ?>
                         <?= $content ?>
                     <?php else: ?>
                         <div class="flex items-center justify-center h-full text-slate-500 text-sm">
                              <div class="flex flex-col items-center gap-2">
                                  <svg class="animate-spin h-5 w-5 text-sky-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                  </svg>
                                  <span class="font-mono text-xs text-slate-400 tracking-wider">carregando Wiki...</span>
                              </div>
                         </div>
                     <?php endif; ?>
                </div>

                <!-- Gatilho de Carregamento Assíncrono Invisível HTMX para a Aba de Wiki -->
                <button id="btn-nav-wiki"
                        hx-get="<?= Url::to(['/wiki/default/index']) ?>"
                        hx-target="#container-wiki"
                        hx-trigger="click once"
                        class="hidden">
                </button>

                <!-- Aba Page Crud: Páginas Dinâmicas (Injeção SPA) -->
                <div class="h-full w-full absolute inset-0 overflow-y-auto scrollbar-thin p-6 md:p-10 bg-slate-950" 
                     x-show="activeTab === 'page_crud'"
                     id="container-page_crud"
                     <?= $initialTab === 'page_crud' ? 'hx-isomorphic="true"' : '' ?>>
                     <?php if ($initialTab === 'page_crud'): ?>
                         <?= $content ?>
                     <?php else: ?>
                         <div class="flex items-center justify-center h-full text-slate-500 text-sm">
                              <div class="flex flex-col items-center gap-2">
                                  <svg class="animate-spin h-5 w-5 text-sky-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                  </svg>
                                  <span class="font-mono text-xs text-slate-400 tracking-wider">carregando Páginas...</span>
                              </div>
                         </div>
                     <?php endif; ?>
                </div>

                <!-- Gatilho de Carregamento Assíncrono Invisível HTMX para a Aba de Page Crud -->
                <button id="btn-nav-page_crud"
                        hx-get="<?= Url::to(['/page_crud/default/index']) ?>"
                        hx-target="#container-page_crud"
                        hx-trigger="click once"
                        class="hidden">
                </button>

                <!-- Gatilho de Carregamento Assíncrono Invisível HTMX para a Aba de JSON Store -->
                <button id="btn-nav-json_store"
                        hx-get="<?= Url::to(['/json_store/default/index']) ?>"
                        hx-target="#container-json_store"
                        hx-trigger="click once"
                        class="hidden">
                </button>

                <!-- Aba JSON Store: API REST CRUD (Injeção SPA) -->
                <div class="h-full w-full absolute inset-0 overflow-y-auto scrollbar-thin p-6 md:p-10 bg-slate-950" 
                     x-show="activeTab === 'json_store'"
                     id="container-json_store"
                     <?= $initialTab === 'json_store' ? 'hx-isomorphic="true"' : '' ?>>
                     <?php if ($initialTab === 'json_store'): ?>
                         <?= $content ?>
                     <?php else: ?>
                         <div class="flex items-center justify-center h-full text-slate-500 text-sm">
                              <div class="flex flex-col items-center gap-2">
                                  <svg class="animate-spin h-5 w-5 text-sky-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                  </svg>
                                  <span class="font-mono text-xs text-slate-400 tracking-wider">carregando JSON Store...</span>
                              </div>
                         </div>
                     <?php endif; ?>
                </div>

                <!-- Gatilho de Carregamento Assíncrono Invisível HTMX para a Aba de Terminal SSH -->
                <button id="btn-nav-terminal"
                        hx-get="<?= Url::to(['/terminal/default/index']) ?>"
                        hx-target="#container-terminal"
                        hx-trigger="click once"
                        class="hidden">
                </button>

                <!-- Aba Terminal: SSH Multi-VPS (Injeção SPA) -->
                <div class="h-full w-full absolute inset-0 overflow-y-auto scrollbar-thin p-4 md:p-6 bg-slate-950" 
                     x-show="activeTab === 'terminal'"
                     id="container-terminal"
                     <?= $initialTab === 'terminal' ? 'hx-isomorphic="true"' : '' ?>>
                     <?php if ($initialTab === 'terminal'): ?>
                         <?= $content ?>
                     <?php else: ?>
                         <div class="flex items-center justify-center h-full text-slate-500 text-sm">
                              <div class="flex flex-col items-center gap-2">
                                  <svg class="animate-spin h-5 w-5 text-sky-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                  </svg>
                                  <span class="font-mono text-xs text-slate-400 tracking-wider">carregando módulo de terminal...</span>
                              </div>
                         </div>
                     <?php endif; ?>
                 </div>

            </main>
        </div>

        <!-- Drawer Lateral Direito de Conversas Recentes (Premium) -->
        <div x-show="showChatDrawer" 
             class="fixed inset-0 z-[100] flex justify-end overflow-hidden" 
             style="display: none;">
            
            <!-- Backdrop/Overlay -->
            <div x-show="showChatDrawer"
                 x-transition:enter="transition ease-in-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in-out duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="showChatDrawer = false"
                 class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity"></div>

            <!-- Painel Lateral -->
            <div x-show="showChatDrawer"
                 x-transition:enter="transform transition ease-in-out duration-300"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in-out duration-300"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="w-full max-w-sm bg-slate-900 border-l border-slate-800 shadow-2xl h-full flex flex-col z-10 relative">
                 
                 <!-- Header do Drawer -->
                 <div class="h-16 border-b border-slate-800/80 px-6 flex items-center justify-between bg-slate-900/60 flex-shrink-0 select-none">
                     <div class="flex items-center gap-2">
                         <span class="text-sm">💬</span>
                         <h3 class="text-sm font-extrabold text-white tracking-wide">Conversas Recentes</h3>
                     </div>
                     <button @click="showChatDrawer = false" 
                             class="h-8 w-8 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/60 flex items-center justify-center transition cursor-pointer">
                         <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                         </svg>
                     </button>
                 </div>

                 <!-- Lista de Conversas Recentes (Polling HTMX de 5s) -->
                 <div class="flex-1 overflow-y-auto p-4 scrollbar-thin"
                      id="chat-drawer-content"
                      hx-get="<?= Url::to(['/site/chat-drawer']) ?>"
                      hx-trigger="load, every 5s"
                      hx-swap="innerHTML">
                      
                      <!-- Loader de Polling -->
                      <div class="flex items-center justify-center h-40 text-slate-500 text-xs font-mono">
                           <svg class="animate-spin h-4 w-4 text-sky-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                               <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                               <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                           </svg>
                           <span>carregando conversas...</span>
                      </div>
                 </div>
            </div>
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

<?php if (Yii::$app->user->can('admin-access')): ?>
    <?= $this->render('_admin_js') ?>
<?php endif; ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
