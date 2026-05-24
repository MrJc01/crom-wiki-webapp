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
} elseif ($currentRoute === 'site/projetos') {
    $initialTab = 'projetos';
} elseif ($currentRoute === 'site/comunidades') {
    $initialTab = 'comunidades';
} elseif ($currentRoute === 'site/aprendizado') {
    $initialTab = 'aprendizado';
} elseif (strpos($currentRoute, 'page_crud') !== false) {
    $initialTab = 'page_crud';
} elseif (strpos($currentRoute, 'wiki') !== false) {
    $initialTab = 'wiki';
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
                 'projetos': '<?= Url::to(['/site/projetos']) ?>',
                 'comunidades': '<?= Url::to(['/site/comunidades']) ?>',
                 'aprendizado': '<?= Url::to(['/site/aprendizado']) ?>',
                 'page_crud': '<?= Url::to(['/page_crud/default/index']) ?>',
                 'wiki': '<?= Url::to(['/wiki/default/index']) ?>'
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
                <!-- Logotipo do Infinito Colorido (Estilo Google) -->
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 9C5.34315 9 4 10.3431 4 12C4 13.6569 5.34315 15 7 15C8.65685 15 10 13.6569 10 12C10 10.3431 8.65685 9 7 9Z" stroke="#4285F4" stroke-width="2.5"/>
                    <path d="M17 9C15.3431 9 14 10.3431 14 12C14 13.6569 15.3431 15 17 15C18.6569 15 20 13.6569 20 12C20 10.3431 18.6569 9 17 9Z" stroke="#34A853" stroke-width="2.5"/>
                    <path d="M10 12C10 12.8 11.2 14.2 12 15C12.8 14.2 14 12.8 14 12C14 11.2 12.8 9.8 12 9C11.2 9.8 10 11.2 10 12Z" fill="#EA4335" stroke="#FBBC05" stroke-width="1.5"/>
                </svg>
                <div class="flex flex-col sm:flex-row sm:items-center gap-1">
                    <span class="text-sm font-extrabold tracking-wider text-slate-100 font-sans">CROM</span>
                    <span class="text-xs font-semibold text-slate-400 font-sans">Developer Program</span>
                </div>
            </div>

            <!-- Lado Direito: Alternador de Tema, Online Badge e Perfil -->
            <div class="flex items-center gap-4">
                <!-- Contador e Lista de Usuários Online (HTMX Polling de 10s) -->
                <div hx-get="<?= Url::to(['/site/index']) ?>"
                     hx-select="#online-badge"
                     hx-trigger="every 10s"
                     hx-swap="outerHTML"
                     id="online-badge-container">
                     <!-- Será atualizado dinamicamente -->
                </div>

                <!-- Botão de Alternar Modo Escuro/Claro (Visual) -->
                <button class="w-9 h-9 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-200 hover:bg-slate-800 transition duration-200" title="Alternar tema">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1.5M12 18.75V21m-8.25-9.75h1.5m16.5 0h1.5m-15.188-7.062l1.062 1.062m12.728 12.727l1.063 1.062M4.5 19.5l1.062-1.06m12.728-12.728l1.063-1.06M12 7.5a4.5 4.5 0 100 9 4.5 4.5 0 000-9z" />
                    </svg>
                </button>

                <!-- Avatar do Usuário com Borda -->
                <div class="w-9 h-9 rounded-full ring-2 ring-sky-500/20 bg-sky-500/10 flex items-center justify-center font-bold text-xs text-sky-400 cursor-pointer hover:bg-sky-500/20 transition-all duration-300">
                    <?= strtoupper(substr(Yii::$app->user->identity->username, 0, 2)) ?>
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

                    <!-- Aba 4: Projetos -->
                    <button @click="openTab('projetos')"
                            hx-get="<?= Url::to(['/site/projetos']) ?>"
                            hx-target="#container-projetos"
                            hx-trigger="click once"
                            id="btn-nav-projetos"
                            class="w-full flex flex-col items-center group cursor-pointer border border-transparent"
                            title="Projetos">
                        <div class="w-12 h-8 rounded-2xl flex items-center justify-center transition-all duration-200"
                             :class="activeTab === 'projetos' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20 shadow-md' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/40'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.377 0A48.536 48.536 0 0112 3m0 0c2.917 0 5.747.294 8.5.862m-21 1.402a48.536 48.536 0 013-.862m0 0c.266-.945 1.136-1.638 2.166-1.638h1.5c1.03 0 1.9.693 2.166 1.638M4.5 5.25a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 20.25h15a2.25 2.25 0 002.25-2.25V7.5a2.25 2.25 0 00-2.25-2.25M6.75 20.25v-1.5a2.25 2.25 0 00-2.25-2.25h-1.5" />
                            </svg>
                        </div>
                        <span class="text-[9px] font-bold text-center mt-1.5 tracking-wide transition-all"
                              :class="activeTab === 'projetos' ? 'text-sky-400 font-extrabold' : 'text-slate-500 group-hover:text-slate-300'">
                            Projetos
                        </span>
                    </button>

                    <!-- Aba 5: Comunidades -->
                    <button @click="openTab('comunidades')"
                            hx-get="<?= Url::to(['/site/comunidades']) ?>"
                            hx-target="#container-comunidades"
                            hx-trigger="click once"
                            id="btn-nav-comunidades"
                            class="w-full flex flex-col items-center group cursor-pointer border border-transparent"
                            title="Comunidades">
                        <div class="w-12 h-8 rounded-2xl flex items-center justify-center transition-all duration-200"
                             :class="activeTab === 'comunidades' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20 shadow-md' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/40'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94-3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                        </div>
                        <span class="text-[9px] font-bold text-center mt-1.5 tracking-wide transition-all"
                              :class="activeTab === 'comunidades' ? 'text-sky-400 font-extrabold' : 'text-slate-500 group-hover:text-slate-300'">
                            Comunidades
                        </span>
                    </button>

                    <!-- Aba 6: Aprendizado -->
                    <button @click="openTab('aprendizado')"
                            hx-get="<?= Url::to(['/site/aprendizado']) ?>"
                            hx-target="#container-aprendizado"
                            hx-trigger="click once"
                            id="btn-nav-aprendizado"
                            class="w-full flex flex-col items-center group cursor-pointer border border-transparent"
                            title="Aprendizado">
                        <div class="w-12 h-8 rounded-2xl flex items-center justify-center transition-all duration-200"
                             :class="activeTab === 'aprendizado' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20 shadow-md' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/40'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.62 48.62 0 0112 20.9c2.79 0 5.428-.465 7.9-1.324.253-.088.465-.269.601-.51a60.43 60.43 0 00-.49-6.346m-15.76 0a48.39 48.39 0 0115.76 0m-15.76 0L12 3l8.76 4.75a60.603 60.603 0 00-15.76 2.397m15.76 0l-1.468 6.13a48.652 48.652 0 01-12.824 0l-1.468-6.13M12 13.5a1.5 1.5 0 110-3 1.5 1.5 0 010 3z" />
                            </svg>
                        </div>
                        <span class="text-[9px] font-bold text-center mt-1.5 tracking-wide transition-all"
                              :class="activeTab === 'aprendizado' ? 'text-sky-400 font-extrabold' : 'text-slate-500 group-hover:text-slate-300'">
                            Aprendizado
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
                        <span class="text-[8px] font-extrabold text-amber-400 tracking-tighter uppercase leading-none mt-1">mrj.crom</span>
                        <span class="text-[7px] text-slate-500 font-bold uppercase tracking-tight">Plano Premium</span>
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

                <!-- Aba 4: Projetos -->
                <div class="h-full w-full absolute inset-0 overflow-y-auto scrollbar-thin p-6 md:p-10 bg-slate-950" 
                     x-show="activeTab === 'projetos'"
                     id="container-projetos"
                     <?= $initialTab === 'projetos' ? 'hx-isomorphic="true"' : '' ?>>
                     <?php if ($initialTab === 'projetos'): ?>
                         <?= $content ?>
                     <?php else: ?>
                         <div class="flex items-center justify-center h-full text-slate-500 text-sm">
                              <div class="flex flex-col items-center gap-2">
                                  <svg class="animate-spin h-5 w-5 text-sky-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                  </svg>
                                  <span class="font-mono text-xs text-slate-400 tracking-wider">carregando Projetos...</span>
                              </div>
                         </div>
                     <?php endif; ?>
                </div>

                <!-- Aba 5: Comunidades -->
                <div class="h-full w-full absolute inset-0 overflow-y-auto scrollbar-thin p-6 md:p-10 bg-slate-950" 
                     x-show="activeTab === 'comunidades'"
                     id="container-comunidades"
                     <?= $initialTab === 'comunidades' ? 'hx-isomorphic="true"' : '' ?>>
                     <?php if ($initialTab === 'comunidades'): ?>
                         <?= $content ?>
                     <?php else: ?>
                         <div class="flex items-center justify-center h-full text-slate-500 text-sm">
                              <div class="flex flex-col items-center gap-2">
                                  <svg class="animate-spin h-5 w-5 text-sky-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                  </svg>
                                  <span class="font-mono text-xs text-slate-400 tracking-wider">carregando Comunidades...</span>
                              </div>
                         </div>
                     <?php endif; ?>
                </div>

                <!-- Aba 6: Aprendizado -->
                <div class="h-full w-full absolute inset-0 overflow-y-auto scrollbar-thin p-6 md:p-10 bg-slate-950" 
                     x-show="activeTab === 'aprendizado'"
                     id="container-aprendizado"
                     <?= $initialTab === 'aprendizado' ? 'hx-isomorphic="true"' : '' ?>>
                     <?php if ($initialTab === 'aprendizado'): ?>
                         <?= $content ?>
                     <?php else: ?>
                         <div class="flex items-center justify-center h-full text-slate-500 text-sm">
                              <div class="flex flex-col items-center gap-2">
                                  <svg class="animate-spin h-5 w-5 text-sky-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                  </svg>
                                  <span class="font-mono text-xs text-slate-400 tracking-wider">carregando Aprendizado...</span>
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

            </main>
        </div>
    </div>
<?php endif; ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
