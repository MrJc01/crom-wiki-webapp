<?php

/** @var yii\web\View $this */
/** @var array $endpoints */
/** @var array $categories */

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'JSON Store — API REST CRUD';
?>

<div class="flex flex-col md:flex-row h-full w-full bg-slate-950 text-slate-100 border border-slate-800/40 rounded-2xl overflow-hidden shadow-2xl backdrop-blur-sm"
     x-data='{
        currentUser: {
            id: <?= (int)Yii::$app->user->id ?>,
            username: <?= json_encode(Yii::$app->user->identity->username) ?>,
            isAdmin: <?= Yii::$app->user->can("admin-access") ? "true" : "false" ?>
        },
        canManage(ep) {
            if (!ep) return false;
            if (this.currentUser.isAdmin) return true;
            if (ep.created_by === this.currentUser.username) return true;
            return ep.admin_ids && ep.admin_ids.map(Number).includes(this.currentUser.id);
        },
        endpoints: <?= json_encode($endpoints) ?>,
        categories: <?= json_encode($categories) ?>,
        activeEndpoint: null,
        editing: false,
        creating: false,
        saving: false,
        successMsg: "",
        errMsg: "",
        users: [],
        tokens: [],
        generatedToken: "",
        showTokenModal: false,

        // Filtros
        sidebarSearch: "",
        filterCategory: "Todos",

        // Campos do Formulário
        formId: "",
        formSlug: "",
        formName: "",
        formJsonContent: "{\n  \n}",
        formCategory: "Geral",
        formAdminIds: [],
        formIsPublic: 1,
        formTokenLabel: "",

        init() {
            this.loadUsers();
        },

        async loadUsers() {
            try {
                const res = await fetch("<?= Url::to(["/json_store/default/users-list"]) ?>");
                this.users = await res.json();
            } catch(e) { console.error("Erro ao carregar usuários:", e); }
        },

        get filteredEndpoints() {
            return this.endpoints.filter(ep => {
                const matchSearch = !this.sidebarSearch || 
                    ep.name.toLowerCase().includes(this.sidebarSearch.toLowerCase()) ||
                    ep.slug.toLowerCase().includes(this.sidebarSearch.toLowerCase());
                const matchCat = this.filterCategory === "Todos" || ep.category === this.filterCategory;
                return matchSearch && matchCat;
            });
        },

        selectEndpoint(ep) {
            this.activeEndpoint = JSON.parse(JSON.stringify(ep));
            this.editing = false;
            this.creating = false;
            this.generatedToken = "";
            this.tokens = [];
            this.successMsg = "";
            this.errMsg = "";
            if (this.canManage(ep)) {
                this.loadTokens(ep.id);
            }
        },

        async loadTokens(endpointId) {
            try {
                const res = await fetch("<?= Url::to(["/json_store/default/tokens-list"]) ?>?endpoint_id=" + endpointId);
                this.tokens = await res.json();
            } catch(e) { console.error("Erro ao carregar tokens:", e); }
        },

        openCreateForm() {
            this.creating = true;
            this.editing = false;
            this.activeEndpoint = null;
            this.formId = "";
            this.formSlug = "";
            this.formName = "";
            this.formJsonContent = "{\n  \n}";
            this.formCategory = "Geral";
            this.formAdminIds = [];
            this.formIsPublic = 1;
            this.successMsg = "";
            this.errMsg = "";
            this.generatedToken = "";
        },

        openEditForm() {
            if (!this.activeEndpoint) return;
            this.editing = true;
            this.creating = false;
            this.formId = this.activeEndpoint.id;
            this.formSlug = this.activeEndpoint.slug;
            this.formName = this.activeEndpoint.name;
            // Pretty print the JSON content
            try {
                const parsed = JSON.parse(this.activeEndpoint.json_content);
                this.formJsonContent = JSON.stringify(parsed, null, 2);
            } catch(e) {
                this.formJsonContent = this.activeEndpoint.json_content;
            }
            this.formCategory = this.activeEndpoint.category;
            this.formAdminIds = this.activeEndpoint.admin_ids || [];
            this.formIsPublic = this.activeEndpoint.is_public ? 1 : 0;
            this.successMsg = "";
            this.errMsg = "";
        },

        cancelForm() {
            this.editing = false;
            this.creating = false;
            this.successMsg = "";
            this.errMsg = "";
        },

        async saveEndpoint() {
            this.saving = true;
            this.successMsg = "";
            this.errMsg = "";

            // Valida JSON antes de enviar
            try {
                JSON.parse(this.formJsonContent);
            } catch(e) {
                this.errMsg = "JSON inválido: " + e.message;
                this.saving = false;
                return;
            }

            const formData = new FormData();
            if (this.formId) formData.append("id", this.formId);
            formData.append("slug", this.formSlug);
            formData.append("name", this.formName);
            formData.append("json_content", this.formJsonContent);
            formData.append("category", this.formCategory);
            formData.append("is_public", this.formIsPublic);
            this.formAdminIds.forEach(id => formData.append("admin_ids[]", id));
            formData.append("<?= Yii::$app->request->csrfParam ?>", "<?= Yii::$app->request->csrfToken ?>");

            try {
                const res = await fetch("<?= Url::to(["/json_store/default/save"]) ?>", { method: "POST", body: formData });
                const data = await res.json();
                if (data.success) {
                    this.successMsg = data.message;
                    this.editing = false;
                    this.creating = false;
                    // Recarrega a lista
                    await this.reloadEndpoints();
                    // Seleciona o endpoint salvo
                    const saved = this.endpoints.find(e => e.id === data.id || e.slug === data.slug);
                    if (saved) this.selectEndpoint(saved);
                } else {
                    this.errMsg = data.message;
                }
            } catch(e) {
                this.errMsg = "Erro de conexão: " + e.message;
            }
            this.saving = false;
        },

        async deleteEndpoint() {
            if (!this.activeEndpoint) return;
            if (!confirm("Tem certeza que deseja excluir o endpoint \"" + this.activeEndpoint.name + "\"? Esta ação é irreversível.")) return;

            try {
                const res = await fetch("<?= Url::to(["/json_store/default/delete"]) ?>?id=" + this.activeEndpoint.id + "&<?= Yii::$app->request->csrfParam ?>=<?= Yii::$app->request->csrfToken ?>");
                const data = await res.json();
                if (data.success) {
                    this.successMsg = data.message;
                    this.activeEndpoint = null;
                    await this.reloadEndpoints();
                } else {
                    this.errMsg = data.message;
                }
            } catch(e) {
                this.errMsg = "Erro de conexão: " + e.message;
            }
        },

        async generateToken() {
            if (!this.activeEndpoint) return;
            this.generatedToken = "";

            const formData = new FormData();
            formData.append("endpoint_id", this.activeEndpoint.id);
            formData.append("label", this.formTokenLabel || ("Token " + new Date().toLocaleString("pt-BR")));
            formData.append("<?= Yii::$app->request->csrfParam ?>", "<?= Yii::$app->request->csrfToken ?>");

            try {
                const res = await fetch("<?= Url::to(["/json_store/default/generate-token"]) ?>", { method: "POST", body: formData });
                const data = await res.json();
                if (data.success) {
                    this.generatedToken = data.token;
                    this.showTokenModal = true;
                    this.formTokenLabel = "";
                    await this.loadTokens(this.activeEndpoint.id);
                } else {
                    this.errMsg = data.message;
                }
            } catch(e) {
                this.errMsg = "Erro ao gerar token: " + e.message;
            }
        },

        async revokeToken(tokenId) {
            if (!confirm("Revogar este token? Todos os clientes que o utilizam perderão acesso imediatamente.")) return;
            try {
                const res = await fetch("<?= Url::to(["/json_store/default/revoke-token"]) ?>?id=" + tokenId + "&<?= Yii::$app->request->csrfParam ?>=<?= Yii::$app->request->csrfToken ?>");
                const data = await res.json();
                if (data.success) {
                    this.successMsg = data.message;
                    await this.loadTokens(this.activeEndpoint.id);
                } else {
                    this.errMsg = data.message;
                }
            } catch(e) {
                this.errMsg = "Erro ao revogar: " + e.message;
            }
        },

        copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                this.successMsg = "Copiado para o clipboard!";
                setTimeout(() => this.successMsg = "", 2000);
            });
        },

        async reloadEndpoints() {
            try {
                const res = await fetch("<?= Url::to(["/json_store/default/index"]) ?>", {
                    headers: { "X-Requested-With": "XMLHttpRequest" }
                });
                const html = await res.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, "text/html");
                const script = doc.querySelector("script#json-store-data");
                if (script) {
                    const data = JSON.parse(script.textContent);
                    this.endpoints = data.endpoints;
                    this.categories = data.categories;
                }
            } catch(e) {
                // Fallback: recarrega via JSON
                window.location.reload();
            }
        },

        formatJson(content) {
            try {
                return JSON.stringify(JSON.parse(content), null, 2);
            } catch(e) {
                return content;
            }
        },

        getApiUrl(slug) {
            return window.location.origin + "/api/json/" + slug;
        }
    }'>

    <!-- Script de dados para recarregamento sem página -->
    <script id="json-store-data" type="application/json">
        <?= json_encode([
            'endpoints' => $endpoints,
            'categories' => $categories,
        ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>
    </script>

    <!-- ============================================================ -->
    <!-- SIDEBAR ESQUERDA — Lista de Endpoints -->
    <!-- ============================================================ -->
    <div class="w-full md:w-72 lg:w-80 bg-slate-900/40 border-b md:border-b-0 md:border-r border-slate-800/80 flex flex-col flex-shrink-0">
        <!-- Header da Sidebar -->
        <div class="p-4 border-b border-slate-800/80 flex-shrink-0">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-extrabold text-slate-200 flex items-center gap-2">
                    <span class="text-sky-400">{ }</span> JSON Store
                </h2>
                <button @click="openCreateForm()"
                        class="h-7 w-7 rounded-lg bg-sky-500/10 border border-sky-500/20 text-sky-400 hover:bg-sky-500/20 hover:text-sky-300 flex items-center justify-center transition cursor-pointer shadow-md"
                        title="Criar novo endpoint JSON">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </button>
            </div>

            <!-- Barra de Busca -->
            <input type="text" x-model="sidebarSearch" placeholder="Buscar endpoints..."
                   class="w-full bg-slate-950/80 border border-slate-800 rounded-xl px-3 py-1.5 text-xs text-slate-300 placeholder:text-slate-600 focus:outline-none focus:border-sky-500/30 focus:ring-1 focus:ring-sky-500/20 transition font-mono">

            <!-- Filtro por Categoria -->
            <div class="flex flex-wrap gap-1.5 mt-2">
                <button @click="filterCategory = 'Todos'"
                        :class="filterCategory === 'Todos' ? 'bg-sky-500/10 text-sky-400 border-sky-500/20' : 'bg-slate-900/60 text-slate-500 border-slate-800 hover:text-slate-300'"
                        class="px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider rounded-full border transition cursor-pointer">
                    Todos
                </button>
                <template x-for="cat in categories" :key="cat">
                    <button @click="filterCategory = cat"
                            :class="filterCategory === cat ? 'bg-sky-500/10 text-sky-400 border-sky-500/20' : 'bg-slate-900/60 text-slate-500 border-slate-800 hover:text-slate-300'"
                            class="px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider rounded-full border transition cursor-pointer"
                            x-text="cat">
                    </button>
                </template>
            </div>
        </div>

        <!-- Lista de Endpoints -->
        <div class="flex-1 overflow-y-auto scrollbar-thin p-2 space-y-1">
            <template x-for="ep in filteredEndpoints" :key="ep.id">
                <button @click="selectEndpoint(ep)"
                        :class="activeEndpoint && activeEndpoint.id === ep.id 
                            ? 'bg-sky-500/10 border-sky-500/20 text-sky-300 shadow-lg shadow-sky-950/20' 
                            : 'bg-transparent border-transparent text-slate-400 hover:bg-slate-800/40 hover:text-slate-200'"
                        class="w-full text-left px-3 py-2.5 rounded-xl border transition-all cursor-pointer group flex flex-col gap-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold truncate" x-text="ep.name"></span>
                        <span :class="ep.is_public ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border-amber-500/20'"
                              class="text-[8px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded-full border flex-shrink-0"
                              x-text="ep.is_public ? 'PUB' : 'PRI'">
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-[9px] font-mono">
                        <span class="text-slate-600 truncate" x-text="'/' + ep.slug"></span>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span class="text-slate-600" x-text="ep.total_requests + ' req'"></span>
                            <span class="text-sky-400/60" x-text="ep.requests_24h + ' /24h'"></span>
                        </div>
                    </div>
                </button>
            </template>

            <template x-if="filteredEndpoints.length === 0">
                <div class="text-center py-8 text-slate-600 text-xs font-mono">
                    Nenhum endpoint encontrado.
                </div>
            </template>
        </div>

        <!-- Stats Footer -->
        <div class="p-3 border-t border-slate-800/80 text-[9px] font-mono text-slate-600 flex justify-between select-none flex-shrink-0">
            <span x-text="endpoints.length + ' endpoints'"></span>
            <span x-text="endpoints.reduce((s,e) => s + e.total_requests, 0) + ' req total'"></span>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- PAINEL PRINCIPAL — Visualizador / Editor / Criador -->
    <!-- ============================================================ -->
    <div class="flex-1 flex flex-col min-h-0 p-4 md:p-6 overflow-y-auto scrollbar-thin">

        <!-- Toast de Sucesso -->
        <template x-if="successMsg">
            <div class="mb-4 p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold rounded-xl animate-fade-in flex items-center gap-2 shadow-lg flex-shrink-0">
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span x-text="successMsg"></span>
            </div>
        </template>

        <!-- Toast de Erro -->
        <template x-if="errMsg">
            <div class="mb-4 p-3 bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-bold rounded-xl animate-fade-in flex items-center gap-2 shadow-lg flex-shrink-0">
                <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                <span x-text="errMsg"></span>
            </div>
        </template>

        <!-- ESTADO VAZIO (nenhum endpoint selecionado e não está criando) -->
        <template x-if="!activeEndpoint && !creating">
            <div class="flex-1 flex items-center justify-center">
                <div class="text-center space-y-4 select-none max-w-md">
                    <div class="text-6xl mb-4 opacity-60">{ }</div>
                    <h3 class="text-lg font-extrabold text-slate-300">JSON Store API</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Crie endpoints JSON e disponibilize-os via API REST pública ou protegida por token.
                        Perfeito para configurações remotas, webhooks e feature flags.
                    </p>
                    <button @click="openCreateForm()" 
                            class="mt-4 px-6 py-2.5 bg-gradient-to-r from-sky-500 to-indigo-600 hover:from-sky-400 hover:to-indigo-500 text-white text-xs font-bold rounded-xl transition shadow-lg shadow-sky-950/30 cursor-pointer">
                        + Criar Primeiro Endpoint
                    </button>
                </div>
            </div>
        </template>

        <!-- ============================================================ -->
        <!-- FORMULÁRIO DE CRIAÇÃO / EDIÇÃO -->
        <!-- ============================================================ -->
        <template x-if="creating || editing">
            <div class="flex-1 flex flex-col min-h-0 animate-fade-in">
                <div class="flex items-center justify-between pb-4 border-b border-slate-800/80 mb-4 flex-shrink-0">
                    <h3 class="text-base font-extrabold text-slate-100 flex items-center gap-2">
                        <span class="text-sky-400 font-mono text-[10px] bg-sky-500/10 border border-sky-500/20 px-2 py-0.5 rounded-md uppercase tracking-wider"
                              x-text="creating ? 'NOVO' : 'EDITAR'"></span>
                        <span x-text="creating ? 'Criar Endpoint JSON' : 'Editar: ' + formName"></span>
                    </h3>
                    <button @click="cancelForm()" class="text-xs text-slate-500 hover:text-slate-200 transition cursor-pointer font-mono">✕ Cancelar</button>
                </div>

                <div class="flex-1 overflow-y-auto scrollbar-thin space-y-4 pr-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5 font-mono">Nome do Endpoint</label>
                            <input type="text" x-model="formName" placeholder="Ex: Configuração App Mobile"
                                   class="w-full bg-slate-950/80 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-slate-200 placeholder:text-slate-700 focus:outline-none focus:border-sky-500/30 focus:ring-1 focus:ring-sky-500/20 transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5 font-mono">Slug (URL da API)</label>
                            <div class="flex items-center gap-1">
                                <span class="text-[10px] text-slate-600 font-mono">/api/json/</span>
                                <input type="text" x-model="formSlug" placeholder="config-app-mobile"
                                       class="flex-1 bg-slate-950/80 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-slate-200 placeholder:text-slate-700 focus:outline-none focus:border-sky-500/30 focus:ring-1 focus:ring-sky-500/20 transition font-mono">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5 font-mono">Categoria</label>
                            <input type="text" x-model="formCategory" placeholder="Geral"
                                   class="w-full bg-slate-950/80 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-slate-200 placeholder:text-slate-700 focus:outline-none focus:border-sky-500/30 focus:ring-1 focus:ring-sky-500/20 transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5 font-mono">Acesso</label>
                            <select x-model="formIsPublic"
                                    class="w-full bg-slate-950/80 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-sky-500/30 focus:ring-1 focus:ring-sky-500/20 transition cursor-pointer">
                                <option value="1">🌐 Público (sem token)</option>
                                <option value="0">🔒 Privado (Bearer Token)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5 font-mono">Responsáveis</label>
                            <select x-model="formAdminIds" multiple
                                    class="w-full bg-slate-950/80 border border-slate-800 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-sky-500/30 focus:ring-1 focus:ring-sky-500/20 transition cursor-pointer h-[38px]">
                                <template x-for="u in users" :key="u.id">
                                    <option :value="u.id" x-text="u.username"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <!-- Editor JSON -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5 font-mono">Conteúdo JSON</label>
                        <textarea x-model="formJsonContent" rows="16" spellcheck="false"
                                  class="w-full bg-slate-950/80 border border-slate-800 rounded-xl px-4 py-3 text-xs text-emerald-300 placeholder:text-slate-700 focus:outline-none focus:border-sky-500/30 focus:ring-1 focus:ring-sky-500/20 transition font-mono leading-relaxed resize-y"
                                  placeholder='{ "key": "value" }'></textarea>
                    </div>
                </div>

                <!-- Barra de Ação -->
                <div class="flex items-center justify-between pt-4 border-t border-slate-800/80 mt-4 flex-shrink-0">
                    <button @click="cancelForm()" class="px-4 py-2 text-xs text-slate-500 hover:text-slate-200 font-bold transition cursor-pointer">Cancelar</button>
                    <button @click="saveEndpoint()" :disabled="saving"
                            class="px-6 py-2.5 bg-gradient-to-r from-sky-500 to-indigo-600 hover:from-sky-400 hover:to-indigo-500 disabled:opacity-40 text-white text-xs font-bold rounded-xl transition shadow-lg shadow-sky-950/30 cursor-pointer flex items-center gap-2">
                        <template x-if="saving">
                            <svg class="animate-spin h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        <span x-text="saving ? 'Salvando...' : (creating ? '+ Criar Endpoint' : '💾 Salvar Alterações')"></span>
                    </button>
                </div>
            </div>
        </template>

        <!-- ============================================================ -->
        <!-- VISUALIZADOR DE ENDPOINT ATIVO -->
        <!-- ============================================================ -->
        <template x-if="activeEndpoint && !creating && !editing">
            <div class="flex-1 flex flex-col min-h-0 animate-fade-in">
                <!-- Header do Endpoint -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-800/80 mb-4 gap-3 flex-shrink-0">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-100 flex items-center gap-2">
                            <span :class="activeEndpoint.is_public ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border-amber-500/20'"
                                  class="font-mono text-[10px] px-2 py-0.5 rounded-md uppercase tracking-wider border"
                                  x-text="activeEndpoint.is_public ? 'PÚBLICO' : 'PRIVADO'"></span>
                            <span x-text="activeEndpoint.name"></span>
                        </h3>
                        <div class="flex items-center gap-3 mt-1 text-[10px] font-mono text-slate-500">
                            <span>Por: <span class="text-slate-400" x-text="activeEndpoint.created_by"></span></span>
                            <span>•</span>
                            <span x-text="activeEndpoint.category" class="text-indigo-400/80"></span>
                            <span>•</span>
                            <span x-text="activeEndpoint.updated_at"></span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 select-none" x-show="canManage(activeEndpoint)">
                        <button @click="openEditForm()"
                                class="py-1.5 px-3.5 bg-slate-900/60 hover:bg-slate-800 border border-slate-800 text-slate-300 hover:text-slate-100 text-xs rounded-xl font-bold transition flex items-center gap-1.5 shadow-md cursor-pointer">
                            📝 Editar
                        </button>
                        <button @click="deleteEndpoint()"
                                class="py-1.5 px-3.5 bg-rose-500/5 hover:bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs rounded-xl font-bold transition cursor-pointer shadow-md">
                            🗑️ Excluir
                        </button>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto scrollbar-thin space-y-6 pr-2">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div class="bg-slate-900/40 border border-slate-800/60 rounded-xl p-3 text-center">
                            <div class="text-lg font-extrabold text-sky-400" x-text="activeEndpoint.total_requests"></div>
                            <div class="text-[9px] font-mono text-slate-500 uppercase tracking-wider mt-1">Total Requests</div>
                        </div>
                        <div class="bg-slate-900/40 border border-slate-800/60 rounded-xl p-3 text-center">
                            <div class="text-lg font-extrabold text-emerald-400" x-text="activeEndpoint.requests_24h"></div>
                            <div class="text-[9px] font-mono text-slate-500 uppercase tracking-wider mt-1">Últimas 24h</div>
                        </div>
                        <div class="bg-slate-900/40 border border-slate-800/60 rounded-xl p-3 text-center">
                            <div class="text-lg font-extrabold text-indigo-400" x-text="activeEndpoint.token_count"></div>
                            <div class="text-[9px] font-mono text-slate-500 uppercase tracking-wider mt-1">Tokens Ativos</div>
                        </div>
                        <div class="bg-slate-900/40 border border-slate-800/60 rounded-xl p-3 text-center">
                            <div class="text-lg font-extrabold" :class="activeEndpoint.is_public ? 'text-emerald-400' : 'text-amber-400'" x-text="activeEndpoint.is_public ? '🌐' : '🔒'"></div>
                            <div class="text-[9px] font-mono text-slate-500 uppercase tracking-wider mt-1" x-text="activeEndpoint.is_public ? 'Público' : 'Privado'"></div>
                        </div>
                    </div>

                    <!-- URL da API -->
                    <div class="bg-slate-900/40 border border-slate-800/60 rounded-xl p-4">
                        <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 font-mono">Endpoint da API</div>
                        <div class="flex items-center gap-2">
                            <code class="flex-1 bg-slate-950/80 border border-slate-800 rounded-lg px-3 py-2 text-xs text-sky-400 font-mono select-all overflow-x-auto" x-text="getApiUrl(activeEndpoint.slug)"></code>
                            <button @click="copyToClipboard(getApiUrl(activeEndpoint.slug))"
                                    class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs rounded-lg transition cursor-pointer flex-shrink-0 font-bold">
                                📋
                            </button>
                        </div>
                        <!-- Exemplo curl -->
                        <div class="mt-3 text-[10px] font-mono text-slate-600">
                            <span class="text-slate-500">$</span> 
                            <span class="text-emerald-400/70">curl</span>
                            <span x-show="!activeEndpoint.is_public"> -H "Authorization: Bearer {token}"</span>
                            <span class="text-sky-400/70" x-text="' ' + getApiUrl(activeEndpoint.slug)"></span>
                        </div>
                    </div>

                    <!-- JSON Content (apenas para donos) -->
                    <template x-if="canManage(activeEndpoint)">
                        <div class="bg-slate-900/40 border border-slate-800/60 rounded-xl p-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono">Conteúdo JSON</div>
                                <button @click="copyToClipboard(formatJson(activeEndpoint.json_content))"
                                        class="text-[9px] text-slate-500 hover:text-sky-400 transition cursor-pointer font-mono">
                                    📋 Copiar
                                </button>
                            </div>
                            <pre class="bg-slate-950/80 border border-slate-800 rounded-xl p-4 text-xs text-emerald-300 font-mono overflow-x-auto max-h-96 scrollbar-thin leading-relaxed"><code x-text="formatJson(activeEndpoint.json_content)"></code></pre>
                        </div>
                    </template>

                    <!-- Aviso para não-donos -->
                    <template x-if="!canManage(activeEndpoint)">
                        <div class="bg-slate-900/40 border border-slate-800/60 rounded-xl p-6 text-center">
                            <div class="text-2xl mb-2 opacity-40">🔐</div>
                            <p class="text-xs text-slate-500 font-mono">O conteúdo JSON deste endpoint é visível apenas para os responsáveis.</p>
                        </div>
                    </template>

                    <!-- Gerenciamento de Tokens (apenas para donos) -->
                    <template x-if="canManage(activeEndpoint)">
                        <div class="bg-slate-900/40 border border-slate-800/60 rounded-xl p-4">
                            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-3">🔑 Tokens de Acesso</div>

                            <!-- Gerar Token -->
                            <div class="flex items-center gap-2 mb-4">
                                <input type="text" x-model="formTokenLabel" placeholder="Nome do token (opcional)"
                                       class="flex-1 bg-slate-950/80 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-200 placeholder:text-slate-700 focus:outline-none focus:border-sky-500/30 transition font-mono">
                                <button @click="generateToken()"
                                        class="px-4 py-2 bg-amber-500/10 border border-amber-500/20 text-amber-400 hover:bg-amber-500/15 text-xs font-bold rounded-xl transition cursor-pointer flex-shrink-0">
                                    + Gerar Token
                                </button>
                            </div>

                            <!-- Token recém-gerado (exibido uma única vez) -->
                            <template x-if="showTokenModal && generatedToken">
                                <div class="mb-4 p-4 bg-amber-500/5 border-2 border-amber-500/30 rounded-xl animate-fade-in">
                                    <div class="text-[10px] font-bold text-amber-400 uppercase tracking-widest mb-2">⚠️ TOKEN GERADO — COPIE AGORA!</div>
                                    <div class="flex items-center gap-2">
                                        <code class="flex-1 bg-slate-950 border border-amber-500/20 rounded-lg px-3 py-2 text-xs text-amber-300 font-mono select-all break-all" x-text="generatedToken"></code>
                                        <button @click="copyToClipboard(generatedToken)"
                                                class="px-3 py-2 bg-amber-500/10 hover:bg-amber-500/20 text-amber-400 text-xs rounded-lg transition cursor-pointer font-bold flex-shrink-0">
                                            📋
                                        </button>
                                    </div>
                                    <p class="text-[9px] text-amber-500/60 mt-2 font-mono">Este token não será exibido novamente. Armazene-o em local seguro.</p>
                                    <button @click="showTokenModal = false" class="mt-2 text-[9px] text-slate-500 hover:text-slate-300 transition cursor-pointer font-mono">Fechar aviso</button>
                                </div>
                            </template>

                            <!-- Lista de Tokens Existentes -->
                            <div class="space-y-1.5">
                                <template x-for="t in tokens" :key="t.id">
                                    <div class="flex items-center justify-between bg-slate-950/60 border border-slate-800/60 rounded-lg px-3 py-2">
                                        <div class="flex items-center gap-2">
                                            <span class="text-[9px] text-slate-600 font-mono">🔑</span>
                                            <span class="text-xs text-slate-300 font-mono" x-text="t.label"></span>
                                            <span class="text-[8px] text-slate-600 font-mono" x-text="t.created_at_fmt"></span>
                                        </div>
                                        <button @click="revokeToken(t.id)"
                                                class="text-[9px] text-rose-400/60 hover:text-rose-400 transition cursor-pointer font-mono font-bold">
                                            Revogar
                                        </button>
                                    </div>
                                </template>
                                <template x-if="tokens.length === 0">
                                    <div class="text-[10px] text-slate-600 font-mono text-center py-2">Nenhum token ativo para este endpoint.</div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Info de Responsáveis -->
                    <div class="bg-slate-900/40 border border-slate-800/60 rounded-xl p-4">
                        <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2">👥 Responsáveis</div>
                        <p class="text-xs text-slate-400 font-mono" x-text="activeEndpoint.admin_name"></p>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
