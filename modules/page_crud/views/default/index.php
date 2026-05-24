<?php

/** @var yii\web\View $this */
/** @var array $pages */
/** @var array $categories */

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Páginas Documentadas — Central Modular';
?>

<div class="flex flex-col w-full bg-slate-950 text-slate-100 border border-slate-800/40 rounded-2xl shadow-2xl backdrop-blur-sm relative"
     x-data='{
        currentUser: {
            id: <?= (int)Yii::$app->user->id ?>,
            username: <?= json_encode(Yii::$app->user->identity->username) ?>,
            isAdmin: <?= Yii::$app->user->can("admin-access") ? "true" : "false" ?>
        },
        canManage(page) {
            if (!page) return false;
            if (this.currentUser.isAdmin) return true;
            if (page.created_by === this.currentUser.username) return true;
            return page.admin_ids && page.admin_ids.map(Number).includes(this.currentUser.id);
        },
        activePageId: <?= $selectedPageId !== null ? json_encode((int)$selectedPageId) : "null" ?>,
        activePage: <?= $selectedPage !== null ? json_encode([
            "id" => $selectedPage->id,
            "slug" => $selectedPage->slug,
            "title" => $selectedPage->title,
            "content" => $selectedPage->content,
            "category" => $selectedPage->category,
            "is_public" => $selectedPage->is_public,
            "created_by" => $selectedPage->created_by,
            "admin_ids" => $selectedPage->adminIds,
            "admin_name" => "Nenhum",
            "updated_at" => date("d/m/Y H:i:s", $selectedPage->updated_at)
        ]) : "null" ?>,
        editing: false,
        creating: false,
        saving: false,
        successMsg: "",
        errMsg: "",
        users: [],
        
        // Listagem mestre de páginas vinda do banco
        pages: <?= json_encode($pages) ?>,
        categories: <?= json_encode($categories) ?>,
        
        // Filtros da Árvore Lateral (legado de compatibilidade)
        sidebarSearch: "",
        
        // Filtros do Discover Interno
        discoverSearch: "",
        discoverCategory: "Todos",
        
        // Campos do Formulário (Criação & Edição)
        formId: "",
        formSlug: "",
        formTitle: "",
        formContent: "# 📝 Título da Página\n\nDigite o conteúdo em Markdown aqui...",
        formCategory: "Geral",
        formAdminIds: [],
        formIsPublic: 0, // Campo para marcar se a página é pública ou não

        init() {
            this.loadUsers();
            
            // Se a página já veio pré-carregada de forma isomórfica pelo PHP
            if (this.activePage) {
                this.$nextTick(() => {
                    this.renderMarkdown();
                });
            }
            
            // Listener de popstate para responder aos botões de voltar/avançar do navegador de forma transparente
            window.addEventListener("popstate", (event) => {
                if (event.state && event.state.pageId) {
                    this.loadPage(event.state.pageId, false);
                } else {
                    this.goHome(false);
                }
            });
        },
        
        // Retorna ao portal principal (Discover) limpando filtros e sincronizando a URL do navegador
        goHome(pushHistory = true) {
            this.activePageId = null;
            this.creating = false;
            this.editing = false;
            this.discoverSearch = "";
            this.discoverCategory = "Todos";
            this.successMsg = "";
            this.errMsg = "";
            
            if (pushHistory) {
                let basePath = "/p";
                const path = window.location.pathname;
                if (path.includes("/index-test.php")) {
                    basePath = "/index-test.php/p";
                }
                history.pushState({}, "", basePath);
            }
        },
        
        // Aborta o formulário ativo com restauração do estado anterior
        cancelForm() {
            this.creating = false;
            this.editing = false;
            this.activePageId = this.formId ? this.formId : null;
            if (this.activePageId) {
                this.loadPage(this.activePageId, false);
            }
        },
        
        // Carrega usuários ativos para o select de administradores
        loadUsers() {
            fetch("<?= Url::to(['/page_crud/default/users-list']) ?>")
                .then(res => res.json())
                .then(data => {
                    this.users = data;
                })
                .catch(err => console.error("Erro ao carregar lista de usuários."));
        },
        
        // Adiciona ou remove um usuário dos administradores da página
        toggleAdmin(userId) {
            const idx = this.formAdminIds.indexOf(userId);
            if (idx > -1) {
                this.formAdminIds.splice(idx, 1);
            } else {
                this.formAdminIds.push(userId);
            }
        },
        
        // Retorna artigos relacionados no rodapé (Estilo Editorial Blog) baseados na categoria
        getRelatedPages(currentPage) {
            if (!currentPage) return [];
            return this.pages
                .filter(p => p.id !== currentPage.id && (p.category === currentPage.category || p.category === "Geral"))
                .slice(0, 2);
        },
        
        // Filtra a lista da barra lateral (legado compatível)
        filteredSidebarPages() {
            if (!this.sidebarSearch) return this.pages;
            return this.pages.filter(p => 
                p.title.toLowerCase().includes(this.sidebarSearch.toLowerCase()) ||
                p.slug.toLowerCase().includes(this.sidebarSearch.toLowerCase())
            );
        },
        
        // Filtra as páginas para a página principal estilo Discover
        filteredDiscoverPages() {
            return this.pages.filter(p => {
                const matchesSearch = p.title.toLowerCase().includes(this.discoverSearch.toLowerCase()) || 
                                       p.content.toLowerCase().includes(this.discoverSearch.toLowerCase()) ||
                                       p.slug.toLowerCase().includes(this.discoverSearch.toLowerCase());
                
                const matchesCategory = this.discoverCategory === "Todos" || p.category === this.discoverCategory;
                
                return matchesSearch && matchesCategory;
            });
        },
        
        // Retorna o ícone dinâmico baseado na categoria
        getCategoryIcon(cat) {
            switch (cat) {
                case "Produtividade":
                    return "📖";
                case "Comunicação":
                    return "💬";
                case "Desenvolvedor":
                    return "💻";
                case "Segurança":
                    return "🛡️";
                default:
                    return "📄";
            }
        },
        
        // Carrega os detalhes de uma página para visualização com tratamento de Pretty URLs e sincronização de histórico
        loadPage(id, pushHistory = true) {
            this.successMsg = "";
            this.errMsg = "";
            this.editing = false;
            this.creating = false;
            
            const baseUrl = "<?= Url::to(['/page_crud/default/view']) ?>";
            const url = baseUrl + (baseUrl.includes("?") ? "&" : "?") + "id=" + id;
            
            fetch(url)
                .then(res => res.json())
                .then(data => {
                    this.activePageId = data.id;
                    this.activePage = data;
                    
                    if (pushHistory) {
                        let basePath = "/p/";
                        const path = window.location.pathname;
                        if (path.includes("/index-test.php")) {
                            basePath = "/index-test.php/p/";
                        }
                        const newUrl = basePath + data.slug;
                        history.pushState({ pageId: id, slug: data.slug }, "", newUrl);
                    }
                    
                    // Renderiza o markdown de forma instantânea
                    this.$nextTick(() => {
                        this.renderMarkdown();
                    });
                })
                .catch(err => {
                    this.errMsg = "Erro ao carregar página documentada.";
                });
        },
        
        // Renderiza o markdown bruto para HTML com marked.js
        renderMarkdown() {
            const el = document.getElementById("documented-preview");
            if (el && this.activePage) {
                el.innerHTML = marked.parse(this.activePage.content || "");
            }
        },
        
        // Abre formulário para Criar Nova Página
        openCreateForm() {
            this.creating = true;
            this.editing = false;
            this.activePageId = null;
            this.activePage = null;
            
            // Limpa os campos do formulário
            this.formId = "";
            this.formSlug = "";
            this.formTitle = "";
            this.formContent = "# 📝 Título da Página\n\nDigite o conteúdo em Markdown aqui...";
            this.formCategory = "Geral";
            this.formAdminIds = [];
            this.formIsPublic = 0; // Default para privada
            
            this.successMsg = "";
            this.errMsg = "";
        },
        
        // Abre formulário para Editar Página Existente
        openEditForm() {
            if (!this.activePage) return;
            this.editing = true;
            this.creating = false;
            
            this.formId = this.activePage.id;
            this.formSlug = this.activePage.slug;
            this.formTitle = this.activePage.title;
            this.formContent = this.activePage.content;
            this.formCategory = this.activePage.category;
            this.formAdminIds = this.activePage.admin_ids ? [...this.activePage.admin_ids] : [];
            this.formIsPublic = this.activePage.is_public ? 1 : 0;
            
            this.successMsg = "";
            this.errMsg = "";
        },
        
        // Salva ou Cria a página documentada
        savePage() {
            this.saving = true;
            this.successMsg = "";
            this.errMsg = "";
            
            const formData = new FormData();
            if (this.formId) {
                formData.append("id", this.formId);
            }
            formData.append("slug", this.formSlug);
            formData.append("title", this.formTitle);
            formData.append("content", this.formContent);
            formData.append("category", this.formCategory);
            formData.append("is_public", this.formIsPublic);
            
            // Envia múltiplos administradores como lista separada por vírgulas para tratamento universal
            formData.append("admin_ids", this.formAdminIds.join(","));
            
            formData.append("<?= Yii::$app->request->csrfParam ?>", "<?= Yii::$app->request->getCsrfToken() ?>");
            
            fetch("<?= Url::to(['/page_crud/default/save']) ?>", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                this.saving = false;
                if (data.success) {
                    this.successMsg = data.message;
                    
                    // Força o reload parcial da aba recarregando a página principal com URL bonita
                    setTimeout(() => {
                        let basePath = "/p/";
                        const path = window.location.pathname;
                        if (path.includes("/index-test.php")) {
                            basePath = "/index-test.php/p/";
                        }
                        location.href = basePath + data.slug;
                    }, 1200);
                } else {
                    this.errMsg = data.message;
                }
            })
            .catch(err => {
                this.saving = false;
                this.errMsg = "Erro de rede ao salvar a página documentada.";
            });
        },
        
        // Exclui a página documentada do banco com URL resiliente
        deletePage() {
            if (!confirm("Tem certeza que deseja excluir permanentemente esta página documentada?")) {
                return;
            }
            
            this.successMsg = "";
            this.errMsg = "";
            
            const baseUrl = "<?= Url::to(['/page_crud/default/delete']) ?>";
            const url = baseUrl + (baseUrl.includes("?") ? "&" : "?") + "id=" + this.activePageId;
            
            fetch(url, {
                method: "POST",
                headers: {
                    "X-CSRF-Token": "<?= Yii::$app->request->getCsrfToken() ?>"
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.successMsg = data.message;
                    
                    setTimeout(() => {
                        const searchParams = new URLSearchParams(window.location.search);
                        searchParams.delete("page_id");
                        const searchStr = searchParams.toString();
                        location.href = window.location.pathname + (searchStr ? "?" + searchStr : "");
                    }, 1200);
                } else {
                    this.errMsg = data.message;
                }
            })
            .catch(err => {
                this.errMsg = "Erro de rede ao excluir a página documentada.";
            });
        }
     }'>

    <!-- PAINEL DE EXIBIÇÃO / FORMULÁRIOS (Fluxo Vertical Contínuo - Rolagem Unificada) -->
    <div class="flex flex-col w-full bg-slate-950/60 p-4 md:p-6 min-w-0 relative">
        
        <!-- ========================================== -->
        <!-- CASO 1: TELA PRINCIPAL ESTILO DISCOVER INTERNO -->
        <!-- ========================================== -->
        <div x-show="!activePageId && !creating && !editing" class="w-full animate-fade-in space-y-6">
            <!-- Header Central do Discover Interno -->
            <div class="text-center space-y-3 pt-4">
                <div class="inline-flex items-center gap-2 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 px-3 py-1 rounded-full text-[9px] font-mono font-bold tracking-widest uppercase mx-auto">
                    📄 PORTAL DE PÁGINAS DOCUMENTADAS
                </div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-100 tracking-tight font-sans">
                    Navegue e Pesquise no Banco
                </h2>
                <p class="text-xs text-slate-400 max-w-md mx-auto leading-relaxed">
                    Pesquise facilmente entre todos os artigos e documentos internos de múltiplos membros organizados na base do SQLite.
                </p>
                <div class="pt-2 select-none">
                    <button @click="openCreateForm()"
                            class="inline-flex bg-gradient-to-r from-indigo-600 to-sky-600 hover:from-indigo-500 hover:to-sky-500 border border-indigo-500/30 text-white text-xs py-2.5 px-5 rounded-xl transition duration-300 items-center justify-center gap-2 font-bold shadow-lg shadow-indigo-600/15">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Criar Nova Página Documentada
                    </button>
                </div>
            </div>

            <!-- Barra de Pesquisa e Badges de Categoria -->
            <div class="max-w-xl mx-auto w-full space-y-3">
                <div class="relative">
                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.637 10.637z" />
                        </svg>
                    </div>
                    <input type="text" 
                           x-model="discoverSearch"
                           placeholder="Pesquisar por títulos ou conteúdo das páginas..." 
                           class="w-full bg-slate-900 border border-slate-800 focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/20 text-slate-200 placeholder-slate-500 rounded-full pl-11 pr-6 py-2.5 text-xs focus:outline-none transition-all shadow-inner">
                </div>

                <!-- Badges de Categoria -->
                <div class="flex flex-wrap justify-center gap-1.5 text-[10px]">
                    <button @click="discoverCategory = 'Todos'"
                            class="py-1 px-3 border rounded-full transition font-bold"
                            :class="discoverCategory === 'Todos' ? 'bg-indigo-500/10 border-indigo-500/30 text-indigo-400' : 'bg-slate-900/60 border-slate-800 text-slate-500 hover:text-slate-300'">
                        Todos
                    </button>
                    <template x-for="cat in categories" :key="cat">
                        <button @click="discoverCategory = cat"
                                class="py-1 px-3 border rounded-full transition font-bold"
                                :class="discoverCategory === cat ? 'bg-indigo-500/10 border-indigo-500/30 text-indigo-400' : 'bg-slate-900/60 border-slate-800 text-slate-500 hover:text-slate-300'">
                            <span x-text="cat"></span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Grade de Documentos -->
            <div class="space-y-3 pt-4">
                <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Documentos Encontrados (<span x-text="filteredDiscoverPages().length"></span>)</span>
                    <span class="text-[9px] text-slate-600 font-mono">SQLite persistente</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <template x-for="page in filteredDiscoverPages()" :key="page.id">
                        <div class="bg-gradient-to-br from-slate-900/30 to-slate-950 border border-slate-900/60 hover:border-slate-800 rounded-2xl p-5 flex flex-col justify-between hover:shadow-lg transition duration-300 group relative overflow-hidden backdrop-blur-sm">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-[9px] text-slate-500 font-mono uppercase tracking-wider bg-slate-900/80 px-2 py-0.5 border border-slate-800/80 rounded-md" x-text="page.category"></span>
                                    <div class="flex items-center gap-1.5 select-none">
                                        <template x-if="page.is_public === 1">
                                            <span class="text-[8px] font-bold text-sky-400 bg-sky-500/10 border border-sky-500/20 px-1.5 py-0.5 rounded-full uppercase font-mono" title="Público (Acesso livre sem login)">🔓 Público</span>
                                        </template>
                                        <template x-if="page.is_public !== 1">
                                            <span class="text-[8px] font-bold text-slate-500 bg-slate-900 border border-slate-850 px-1.5 py-0.5 rounded-full uppercase font-mono" title="Privado (Exige login)">🔒 Privado</span>
                                        </template>
                                        <span class="text-xs" x-text="getCategoryIcon(page.category)"></span>
                                    </div>
                                </div>
                                <h4 class="text-sm font-extrabold text-slate-200 group-hover:text-indigo-400 transition leading-snug" x-text="page.title"></h4>
                                <p class="text-[10px] text-slate-500 font-mono" x-text="'slug: ' + page.slug"></p>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-900/80 flex items-center justify-between">
                                <span class="text-[9px] text-slate-500" x-text="'Autor: ' + page.created_by"></span>
                                <button @click="loadPage(page.id)"
                                        class="py-1 px-3 bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-slate-300 text-[10px] font-bold rounded-lg transition flex items-center gap-1">
                                    👁️ Ler Documento
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Estado Vazio no Discover -->
                <div x-show="filteredDiscoverPages().length === 0" class="py-12 text-center">
                    <span class="text-2xl block">🔍</span>
                    <h4 class="text-xs font-bold text-slate-500 mt-2">Nenhum documento encontrado.</h4>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- CASO 2: VISUALIZADOR DE PÁGINA (LEITURA ESTILO GOOGLE BLOG - ROLAGEM GERAL) -->
        <!-- ========================================== -->
        <div x-show="activePageId && !creating && !editing" class="w-full animate-fade-in" style="display: none;">
            
            <!-- Cabeçalho do Leitor Editorial (Google Blog Style) -->
            <div class="pb-6 border-b border-slate-800/80 mb-6 flex flex-col space-y-4 flex-shrink-0">
                <div class="flex items-center justify-between select-none">
                    <!-- Pílula de Categoria Google-like e Badge de Privacidade -->
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-extrabold tracking-widest text-indigo-400 bg-indigo-500/10 border border-indigo-500/20 px-3 py-1 rounded-full uppercase" x-text="activePage ? activePage.category : 'Artigo'"></span>
                        <template x-if="activePage && activePage.is_public === 1">
                            <span class="text-[9px] font-bold text-sky-400 bg-sky-500/10 border border-sky-500/20 px-2.5 py-1 rounded-full uppercase font-mono shadow-sm">🔓 Pública (Acesso Livre)</span>
                        </template>
                        <template x-if="activePage && activePage.is_public !== 1">
                            <span class="text-[9px] font-bold text-slate-500 bg-slate-900 border border-slate-800 px-2.5 py-1 rounded-full uppercase font-mono shadow-sm">🔒 Privada</span>
                        </template>
                    </div>
                    
                    <div class="flex items-center gap-2 select-none">
                        <button @click="goHome()" 
                                class="py-1.5 px-3.5 bg-slate-900 border border-slate-800 text-slate-400 hover:text-slate-200 hover:border-slate-700 text-xs rounded-xl font-bold transition flex items-center gap-1.5 shadow-sm">
                            🏠 Voltar
                        </button>
                        <button @click="openEditForm()" x-show="canManage(activePage)"
                                class="py-1.5 px-3.5 bg-indigo-500/5 border border-indigo-500/20 text-indigo-400 hover:bg-indigo-500/10 text-xs rounded-xl font-bold transition flex items-center gap-1 shadow-sm">
                            📝 Editar
                        </button>
                        <button @click="deletePage()" x-show="canManage(activePage)"
                                class="py-1.5 px-3.5 bg-rose-500/5 border border-rose-500/20 text-rose-400 hover:bg-rose-500/10 text-xs rounded-xl font-bold transition flex items-center shadow-sm">
                            🗑️ Excluir
                        </button>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <!-- Título Editorial Majestoso -->
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-b from-slate-100 to-slate-300 tracking-tight leading-tight font-sans" x-text="activePage ? activePage.title : ''"></h1>
                    
                    <!-- Metadados Delineados com Visual Clean -->
                    <div class="flex flex-wrap gap-4 items-center text-[10px] text-slate-500 font-mono">
                        <div class="flex items-center gap-1">
                            <span>Autor:</span>
                            <span class="text-sky-400 font-bold" x-text="activePage ? activePage.created_by : 'Sistema'"></span>
                        </div>
                        <span class="text-slate-800">•</span>
                        <div class="flex items-center gap-1">
                            <span>Admin Responsável:</span>
                            <span class="text-indigo-400 font-bold" x-text="activePage ? activePage.admin_name : 'Nenhum'"></span>
                        </div>
                        <span class="text-slate-800">•</span>
                        <div class="flex items-center gap-1">
                            <span>Publicado:</span>
                            <span class="text-slate-400" x-text="activePage ? activePage.updated_at : ''"></span>
                        </div>
                        <span class="text-slate-800">•</span>
                        <span class="text-slate-500" x-text="'slug: ' + (activePage ? activePage.slug : '')"></span>
                    </div>
                </div>
            </div>

            <!-- Toast Alerts -->
            <div class="flex-shrink-0">
                <template x-if="successMsg">
                    <div class="mb-4 p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold rounded-xl flex items-center gap-2 shadow-lg">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span x-text="successMsg"></span>
                    </div>
                </template>
            </div>

            <!-- Visualizador Markdown de Alta Fidelidade (Google Blog Style - Altura Fluida, Sem Rolagem Interna) -->
            <div class="bg-slate-900/10 rounded-[32px] border border-slate-900 p-6 md:p-8 shadow-inner flex flex-col justify-between">
                <div>
                    <!-- Hero radial mesh background decorativo de topo -->
                    <div class="w-full h-32 bg-gradient-to-r from-indigo-500/10 to-sky-500/10 border border-slate-900 rounded-2xl mb-8 flex items-center justify-center relative overflow-hidden select-none">
                        <div class="absolute -right-10 -bottom-10 w-28 h-28 bg-indigo-500/10 rounded-full blur-2xl"></div>
                        <span class="text-3xl text-slate-700/80 font-black tracking-widest font-mono">CROM ECOSYSTEM</span>
                    </div>

                    <article id="documented-preview" class="prose prose-invert max-w-none text-slate-300 prose-sm sm:prose-base prose-headings:text-slate-100 prose-headings:font-bold prose-headings:tracking-tight prose-h2:text-indigo-400 prose-h2:border-b prose-h2:border-slate-900 prose-h2:pb-2 prose-h2:mt-8 prose-h2:mb-4 prose-h3:text-sky-400 prose-h3:mt-6 prose-h3:mb-3 prose-a:text-sky-400 hover:prose-a:text-sky-300 prose-blockquote:border-l-4 prose-blockquote:border-indigo-500 prose-blockquote:bg-indigo-500/5 prose-blockquote:px-4 prose-blockquote:py-2 prose-blockquote:rounded-r-2xl prose-blockquote:text-slate-300 prose-blockquote:italic prose-code:text-sky-400 prose-code:font-mono prose-pre:bg-slate-950 prose-pre:border prose-pre:border-slate-800/80 prose-hr:border-slate-900 prose-img:rounded-3xl prose-img:mx-auto prose-img:shadow-2xl prose-img:border prose-img:border-slate-900 prose-img:max-h-[480px] prose-img:object-cover font-sans leading-relaxed">
                        <!-- Injetado pelo Marked.js -->
                    </article>
                </div>

                <!-- Artigos Relacionados no Rodapé (Cards Estilo Blog do Google) -->
                <template x-if="activePage">
                    <div class="mt-16 pt-8 border-t border-slate-900 space-y-6">
                        <h4 class="text-sm font-extrabold text-slate-200 tracking-tight flex items-center gap-2 select-none">
                            <span class="w-1.5 h-3.5 bg-indigo-500 rounded-full"></span>
                            Artigos Relacionados
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <template x-for="rel in getRelatedPages(activePage)" :key="rel.id">
                                <div @click="loadPage(rel.id)" 
                                     class="bg-gradient-to-br from-slate-900/40 to-slate-950 border border-slate-900 hover:border-slate-800 rounded-2xl p-5 flex flex-col justify-between cursor-pointer group transition-all duration-300 hover:shadow-lg backdrop-blur-sm relative overflow-hidden">
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between text-[9px] font-mono select-none">
                                            <span class="text-indigo-400 bg-indigo-500/10 border border-indigo-500/20 px-2 py-0.5 rounded-md uppercase" x-text="rel.category"></span>
                                            <span class="text-slate-500" x-text="rel.updated_at.split(' ')[0]"></span>
                                        </div>
                                        <h5 class="text-xs font-extrabold text-slate-200 group-hover:text-indigo-400 transition" x-text="rel.title"></h5>
                                        <p class="text-[10px] text-slate-500 line-clamp-2 leading-relaxed" x-text="rel.content.replace(/[#*`]/g, '')"></p>
                                    </div>
                                    <div class="mt-4 pt-3 border-t border-slate-900 flex items-center text-[10px] text-indigo-400 font-bold group-hover:text-indigo-300 select-none">
                                        <span>Ler Artigo Completo</span>
                                        <span class="ml-1 transition-transform group-hover:translate-x-1">→</span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- CASO 3: FORMULÁRIO DE CRIAÇÃO & EDIÇÃO -->
        <!-- ========================================== -->
        <div x-show="creating || editing" class="w-full animate-fade-in" style="display: none;">
            
            <!-- Cabeçalho do Formulário -->
            <div class="flex justify-between items-center pb-4 border-b border-slate-800/80 mb-4 flex-shrink-0">
                <div>
                    <h3 class="text-base font-extrabold text-slate-100 flex items-center gap-2">
                        <span class="text-indigo-400 font-mono text-[9px] bg-indigo-500/10 border border-indigo-500/20 px-2 py-0.5 rounded-md uppercase tracking-wider" x-text="creating ? 'NOVA PÁGINA' : 'EDIÇÃO'"></span>
                        <span x-text="creating ? 'Criar Página Documentada' : 'Editar Página Documentada'"></span>
                    </h3>
                    <p class="text-[10px] text-slate-500 mt-1">Escreva e armazene as especificações diretamente na base do banco de dados local.</p>
                </div>
                <button @click="cancelForm()" class="py-1 px-3 bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-400 hover:text-slate-200 text-xs rounded-lg font-bold transition">
                    Cancelar
                </button>
            </div>

            <!-- Toast Alerts do Form -->
            <template x-if="errMsg">
                <div class="mb-4 p-3 bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-bold rounded-xl flex items-center gap-2 shadow-lg flex-shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                    <span x-text="errMsg"></span>
                </div>
            </template>

            <!-- Inputs do Formulário (Altura Fluida, Sem Rolagem Local) -->
            <div class="bg-slate-900/10 rounded-2xl border border-slate-800/60 p-4 md:p-6 shadow-inner space-y-4">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 font-mono">Título da Página</label>
                        <input type="text" 
                               x-model="formTitle" 
                               placeholder="Ex: Guia Geral de Governança"
                               class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-xs text-slate-200 focus:outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 font-mono">Slug (URI Amigável)</label>
                        <input type="text" 
                               x-model="formSlug" 
                               :readonly="editing"
                               placeholder="Ex: governanca/guia-geral"
                               class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-xs focus:outline-none transition-all"
                               :class="editing ? 'text-slate-500 bg-slate-950/40 border-slate-900 cursor-not-allowed' : 'text-slate-200'">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 font-mono">Categoria</label>
                        <select x-model="formCategory"
                                class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-xs text-slate-200 focus:outline-none transition-all mb-4">
                            <option value="Geral">Geral</option>
                            <option value="Produtividade">Produtividade</option>
                            <option value="Comunicação">Comunicação</option>
                            <option value="Desenvolvedor">Desenvolvedor</option>
                            <option value="Segurança">Segurança</option>
                        </select>
                        
                        <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 font-mono">Privacidade da Página</label>
                        <div class="flex items-center gap-3 bg-slate-950 border border-slate-800 rounded-xl p-2 px-4 h-[38px] select-none">
                            <button type="button" 
                                    @click="formIsPublic = formIsPublic === 1 ? 0 : 1"
                                    class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                    :class="formIsPublic === 1 ? 'bg-sky-500' : 'bg-slate-800'">
                                <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                      :class="formIsPublic === 1 ? 'translate-x-4' : 'translate-x-0'"></span>
                            </button>
                            <span class="text-xs font-bold font-sans transition-colors"
                                  :class="formIsPublic === 1 ? 'text-sky-400' : 'text-slate-400'"
                                  x-text="formIsPublic === 1 ? '🔓 Pública (Acesso Livre Sem Login)' : '🔒 Privada'">
                            </span>
                        </div>
                    </div>
                    
                    <!-- Multi-Seletor de Administradores Premium (Múltiplos Donos Clicáveis) -->
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 font-mono">Administradores Responsáveis (Múltiplos Donos)</label>
                        <div class="flex flex-wrap gap-2 bg-slate-950 border border-slate-800 rounded-xl p-3 h-[98px] md:h-[104px] overflow-y-auto scrollbar-thin select-none">
                            <template x-for="user in users" :key="user.id">
                                <button type="button" 
                                        @click="toggleAdmin(parseInt(user.id))"
                                        class="px-3 py-1 border rounded-full text-[10px] font-bold transition duration-200 flex items-center gap-1.5"
                                        :class="formAdminIds.includes(parseInt(user.id)) ? 'bg-indigo-500/10 border-indigo-500/30 text-indigo-400' : 'bg-slate-900/60 border-slate-800 text-slate-500 hover:text-slate-300'">
                                    <span x-text="formAdminIds.includes(parseInt(user.id)) ? '✓' : '＋'"></span>
                                    <span x-text="user.username"></span>
                                </button>
                            </template>
                            <div x-show="users.length === 0" class="text-xs text-slate-600 italic">Carregando lista de membros...</div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col h-[320px] min-h-[220px]">
                    <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 font-mono">Conteúdo Markdown (Documento)</label>
                    <textarea x-model="formContent" 
                              class="w-full flex-1 bg-slate-950 border border-slate-800 focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/20 rounded-xl p-4 text-slate-100 font-mono text-xs resize-none focus:outline-none transition-all"
                              placeholder="# Digite seu Markdown aqui..."></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-slate-900 select-none">
                    <button type="button" @click="cancelForm()" class="py-2 px-4 bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-400 hover:text-slate-200 text-xs rounded-xl font-bold transition">Cancelar</button>
                    <button type="button" @click="savePage()" 
                            :disabled="saving"
                            class="py-2 px-5 bg-gradient-to-r from-indigo-600 to-sky-600 hover:from-indigo-500 hover:to-sky-500 text-white text-xs rounded-xl font-bold shadow-lg shadow-indigo-600/15 transition-all duration-300 transform active:scale-95 flex items-center gap-2">
                        <template x-if="saving">
                            <svg class="animate-spin h-3.5 w-3.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        <span x-text="saving ? 'Salvando...' : '🚀 Salvar Documento'"></span>
                    </button>
                </div>
            </div>
        </div>

    </div>

</div>
