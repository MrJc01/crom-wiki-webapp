<?php

declare(strict_types=1);

/** @var yii\web\View $this */

$this->title = 'CromIA Gateway — Controle de API & IA';
?>

<script>
window.cromiaHandler = function() {
    return {
        // Estado de Autenticação
        authenticated: false,
        authTab: 'login',
        username: '',
        password: '',
        loading: false,

        // Dados do Perfil e Sessão
        user: {
            username: '',
            balance: 0
        },
        loadingProfile: false,

        // Chaves de API
        apiKeys: [],
        newKeyName: '',
        generating: false,
        newlyGeneratedKey: '',
        copied: false,

        // Modelos
        modelsList: [],
        selectedModel: 'gpt-4o-mini',
        loadingModels: false,

        // Playground Chat
        playgroundKey: '',
        chatInput: '',
        chatLoading: false,
        chatHistory: [],

        // Mensagens Globais de feedback
        successMsg: '',
        errorMsg: '',

        CROMIA_API_URL: 'https://cromia-api.crom.me',

        init() {
            // Verifica se há sessão ativa no LocalStorage
            const token = localStorage.getItem("cromia_session_token");
            const cachedUser = localStorage.getItem("cromia_user");

            if (token && cachedUser) {
                this.authenticated = true;
                try {
                    this.user = JSON.parse(cachedUser);
                } catch (e) {
                    this.user = { username: '', balance: 0 };
                }
                this.fetchProfile();
                this.fetchKeys();
                this.fetchModels();
            }
        },

        async login() {
            this.successMsg = '';
            this.errorMsg = '';
            this.loading = true;

            try {
                const response = await fetch(`${this.CROMIA_API_URL}/v1/auth/login`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        username: this.username,
                        password: this.password
                    })
                });

                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.error || 'Falha na autenticação.');
                }

                // Salva sessão no localStorage
                localStorage.setItem("cromia_session_token", data.token);
                localStorage.setItem("cromia_user", JSON.stringify(data.user));

                this.user = data.user;
                this.authenticated = true;
                this.password = '';
                this.username = '';

                // Busca dados atualizados e chaves
                this.fetchProfile();
                this.fetchKeys();
                this.fetchModels();

            } catch (err) {
                this.errorMsg = err.message;
            } finally {
                this.loading = false;
            }
        },

        async register() {
            this.successMsg = '';
            this.errorMsg = '';
            this.loading = true;

            try {
                const response = await fetch(`${this.CROMIA_API_URL}/v1/auth/register`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        username: this.username,
                        password: this.password
                    })
                });

                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.error || 'Falha ao realizar cadastro.');
                }

                this.successMsg = data.message || 'Cadastro efetuado com sucesso! Saldo inicial: 0. Entre em contato com os Guardiões do CROM para recarga de créditos.';
                this.password = '';
                this.username = '';

            } catch (err) {
                this.errorMsg = err.message;
            } finally {
                this.loading = false;
            }
        },

        async fetchProfile() {
            const token = localStorage.getItem("cromia_session_token");
            if (!token) return;

            this.loadingProfile = true;
            try {
                const response = await fetch(`${this.CROMIA_API_URL}/v1/admin/me`, {
                    method: 'GET',
                    headers: { 'Authorization': `Bearer ${token}` }
                });

                if (!response.ok) {
                    if (response.status === 401) {
                        this.logout();
                    }
                    throw new Error('Falha ao buscar dados do perfil.');
                }

                const data = await response.json();
                this.user.username = data.username;
                this.user.balance = data.balance;

                // Atualiza cache local
                localStorage.setItem("cromia_user", JSON.stringify(this.user));

            } catch (err) {
                console.error(err);
            } finally {
                this.loadingProfile = false;
            }
        },

        async fetchKeys() {
            const token = localStorage.getItem("cromia_session_token");
            if (!token) return;

            try {
                const response = await fetch(`${this.CROMIA_API_URL}/v1/admin/keys`, {
                    method: 'GET',
                    headers: { 'Authorization': `Bearer ${token}` }
                });

                if (response.ok) {
                    const data = await response.json();
                    this.apiKeys = data.data || [];
                }
            } catch (err) {
                console.error('Erro ao buscar chaves de API:', err);
            }
        },

        async fetchModels() {
            const token = localStorage.getItem("cromia_session_token");
            if (!token) return;

            this.loadingModels = true;
            try {
                const response = await fetch(`${this.CROMIA_API_URL}/v1/models`, {
                    method: 'GET',
                    headers: { 'Authorization': `Bearer ${token}` }
                });

                if (response.ok) {
                    const data = await response.json();
                    this.modelsList = data.data || [];
                    if (this.modelsList.length > 0) {
                        this.selectedModel = this.modelsList[0].id;
                    }
                }
            } catch (err) {
                console.error('Erro ao buscar modelos:', err);
            } finally {
                this.loadingModels = false;
            }
        },

        async generateKey() {
            const token = localStorage.getItem("cromia_session_token");
            if (!token || !this.newKeyName.trim()) return;

            this.generating = true;
            this.newlyGeneratedKey = '';
            this.copied = false;

            try {
                const response = await fetch(`${this.CROMIA_API_URL}/v1/admin/keys`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({ name: this.newKeyName })
                });

                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.error || 'Erro ao gerar chave de API.');
                }

                this.newlyGeneratedKey = data.key_string;
                this.newKeyName = '';
                this.fetchKeys(); // Recarrega lista

            } catch (err) {
                alert(err.message);
            } finally {
                this.generating = false;
            }
        },

        async revokeKey(keyId) {
            const token = localStorage.getItem("cromia_session_token");
            if (!token) return;

            if (!confirm('Deseja realmente revogar esta chave de API? Qualquer aplicação que a utilize perderá o acesso imediatamente.')) {
                return;
            }

            try {
                const response = await fetch(`${this.CROMIA_API_URL}/v1/admin/keys/${keyId}`, {
                    method: 'DELETE',
                    headers: { 'Authorization': `Bearer ${token}` }
                });

                if (response.ok) {
                    this.fetchKeys();
                } else {
                    alert('Falha ao revogar chave.');
                }
            } catch (err) {
                console.error(err);
            }
        },

        autoSelectPlaygroundKey() {
            if (this.newlyGeneratedKey) {
                this.playgroundKey = this.newlyGeneratedKey;
            } else if (this.apiKeys.length > 0) {
                alert('Por favor, copie e cole sua chave de API gerada no campo de texto para iniciar os testes.');
            } else {
                alert('Gere uma chave de API primeiro para poder testar.');
            }
        },

        async sendChatMessage() {
            if (!this.playgroundKey.trim() || !this.chatInput.trim()) {
                alert('Insira uma chave de API CromIA válida para consumir a IA.');
                return;
            }

            const prompt = this.chatInput;
            this.chatInput = '';
            this.chatHistory.push({ role: 'user', content: prompt });
            this.chatLoading = true;

            // Scroll do chat para o fundo
            this.scrollChat();

            try {
                const response = await fetch(`${this.CROMIA_API_URL}/v1/chat/completions`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${this.playgroundKey}`
                    },
                    body: JSON.stringify({
                        model: this.selectedModel,
                        messages: [{ role: 'user', content: prompt }]
                    })
                });

                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.error || 'Erro na chamada de IA.');
                }

                const reply = data.choices?.[0]?.message?.content || 'Nenhuma resposta retornada.';
                this.chatHistory.push({ role: 'assistant', content: reply });

                // Recarrega saldo em segundo plano após consumo do token
                setTimeout(() => { this.fetchProfile(); }, 1500);

            } catch (err) {
                this.chatHistory.push({ role: 'assistant', content: `Erro ao chamar IA: ${err.message}` });
            } finally {
                this.chatLoading = false;
                this.scrollChat();
            }
        },

        scrollChat() {
            this.$nextTick(() => {
                const container = this.$refs.chatBody;
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        },

        copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                this.copied = true;
                setTimeout(() => { this.copied = false; }, 3000);
            });
        },

        logout() {
            localStorage.removeItem("cromia_session_token");
            localStorage.removeItem("cromia_user");
            this.authenticated = false;
            this.user = { username: '', balance: 0 };
            this.apiKeys = [];
            this.modelsList = [];
            this.selectedModel = 'gpt-4o-mini';
            this.newlyGeneratedKey = '';
            this.playgroundKey = '';
            this.chatHistory = [];
        },

        formatBalance(value) {
            return Number(value).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },

        formatDate(timestamp) {
            if (!timestamp) return '-';
            const date = new Date(Number(timestamp) * 1000);
            return date.toLocaleDateString('pt-BR') + ' ' + date.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
        }
    };
};
</script>

<div class="h-[calc(100vh-130px)] md:h-[calc(100vh-112px)] bg-slate-900/40 border border-slate-800/80 rounded-2xl overflow-hidden backdrop-blur-md flex flex-col relative"
     x-data="cromiaHandler()">

    <!-- CABEÇALHO DO PAINEL -->
    <header class="h-16 border-b border-slate-800/80 px-4 md:px-6 flex items-center justify-between bg-slate-950/60 backdrop-blur-md z-10 flex-shrink-0 select-none">
        <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center font-bold text-lg shadow-inner">
                🤖
            </div>
            <div>
                <h3 class="text-xs font-extrabold text-white tracking-wide uppercase">CromIA Gateway</h3>
                <span class="text-[9px] font-semibold text-slate-500 font-mono tracking-wider">Gerenciamento de API & Playground Serverless</span>
            </div>
        </div>

        <!-- Indicador de Status & Logout -->
        <div class="flex items-center gap-3">
            <a href="https://cromia-api.crom.me" target="_blank" rel="noopener noreferrer"
               class="px-2.5 py-1 bg-slate-800 hover:bg-purple-600 text-slate-300 hover:text-white rounded-lg text-[10px] font-bold uppercase transition duration-150 flex items-center gap-1 no-underline">
                <span class="material-icons text-xs">public</span>
                Site Oficial
            </a>
            <a href="https://cromia-api.crom.me/docs" target="_blank" rel="noopener noreferrer"
               class="px-2.5 py-1 bg-slate-800 hover:bg-purple-600 text-slate-300 hover:text-white rounded-lg text-[10px] font-bold uppercase transition duration-150 flex items-center gap-1 no-underline">
                <span class="material-icons text-xs">menu_book</span>
                Documentação
            </a>
            <template x-if="authenticated">
                <div class="flex items-center gap-3">
                    <span class="text-[9px] font-bold text-purple-400 bg-purple-500/10 border border-purple-500/20 px-2 py-0.5 rounded-full flex items-center gap-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-purple-500 animate-ping"></span>
                        CONECTADO
                    </span>
                    <button @click="logout()"
                            class="px-2.5 py-1 bg-slate-800 hover:bg-rose-600 hover:text-white text-slate-300 rounded-lg text-[10px] font-bold uppercase cursor-pointer transition duration-150">
                        Sair
                    </button>
                </div>
            </template>
            <template x-if="!authenticated">
                <span class="text-[9px] font-bold text-slate-500 bg-slate-950 border border-slate-800 px-2 py-0.5 rounded-full">
                    DESCONECTADO
                </span>
            </template>
        </div>
    </header>

    <!-- ÁREA DE CONTEÚDO -->
    <div class="flex-1 overflow-y-auto p-4 md:p-6 bg-slate-950/10">

        <!-- 1. TELA DESCONECTADA: LOGIN & REGISTRO -->
        <div x-show="!authenticated" class="h-full flex items-center justify-center py-6" x-transition>
            <div class="w-full max-w-md bg-slate-900 border border-slate-800/80 rounded-2xl shadow-2xl overflow-hidden">
                <!-- Abas -->
                <div class="flex border-b border-slate-800 select-none">
                    <button @click="authTab = 'login'; successMsg = ''; errorMsg = ''"
                            :class="authTab === 'login' ? 'border-purple-500 text-white bg-slate-950/40' : 'border-transparent text-slate-500 hover:text-slate-300'"
                            class="flex-1 py-3 text-xs font-bold border-b-2 transition">
                        Entrar
                    </button>
                    <button @click="authTab = 'register'; successMsg = ''; errorMsg = ''"
                            :class="authTab === 'register' ? 'border-purple-500 text-white bg-slate-950/40' : 'border-transparent text-slate-500 hover:text-slate-300'"
                            class="flex-1 py-3 text-xs font-bold border-b-2 transition">
                        Criar Conta
                    </button>
                </div>

                <div class="p-6 sm:p-8 space-y-4">
                    <!-- Mensagens de Feedback -->
                    <template x-if="successMsg">
                        <div class="p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold rounded-xl flex items-start gap-2 shadow-lg">
                            <span class="material-icons text-base mt-0.5">check_circle</span>
                            <span x-text="successMsg" class="leading-relaxed"></span>
                        </div>
                    </template>
                    <template x-if="errorMsg">
                        <div class="p-3 bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-semibold rounded-xl flex items-start gap-2 shadow-lg">
                            <span class="material-icons text-base mt-0.5">error</span>
                            <span x-text="errorMsg" class="leading-relaxed"></span>
                        </div>
                    </template>

                    <!-- Formulário de Login -->
                    <form x-show="authTab === 'login'" @submit.prevent="login()" class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest font-mono">Nome do Usuário</label>
                            <input type="text" x-model="username" required placeholder="Ex: joao.dev"
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-purple-500 focus:ring-1 focus:ring-purple-500/20 text-xs rounded-xl px-4 py-2.5 text-white outline-none font-sans font-semibold transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest font-mono">Senha</label>
                            <input type="password" x-model="password" required placeholder="Sua senha secreta..."
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-purple-500 focus:ring-1 focus:ring-purple-500/20 text-xs rounded-xl px-4 py-2.5 text-white outline-none font-sans font-semibold transition-all">
                        </div>
                        <button type="submit" :disabled="loading"
                                class="w-full py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 disabled:from-purple-800 disabled:to-indigo-800 text-white rounded-xl text-xs font-bold uppercase tracking-wider shadow-lg shadow-purple-600/15 hover:shadow-purple-500/25 transition duration-300 cursor-pointer flex justify-center items-center gap-2">
                            <span x-show="loading" class="animate-spin h-3 w-3 border-2 border-white border-t-transparent rounded-full"></span>
                            Acessar Gateway
                        </button>
                    </form>

                    <!-- Formulário de Registro -->
                    <form x-show="authTab === 'register'" @submit.prevent="register()" class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest font-mono">Nome do Usuário</label>
                            <input type="text" x-model="username" required placeholder="Ex: joao.dev"
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-purple-500 focus:ring-1 focus:ring-purple-500/20 text-xs rounded-xl px-4 py-2.5 text-white outline-none font-sans font-semibold transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest font-mono">Senha</label>
                            <input type="password" x-model="password" required placeholder="Crie uma senha forte..."
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-purple-500 focus:ring-1 focus:ring-purple-500/20 text-xs rounded-xl px-4 py-2.5 text-white outline-none font-sans font-semibold transition-all">
                        </div>
                        <button type="submit" :disabled="loading"
                                class="w-full py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 disabled:from-purple-800 disabled:to-indigo-800 text-white rounded-xl text-xs font-bold uppercase tracking-wider shadow-lg shadow-purple-600/15 hover:shadow-purple-500/25 transition duration-300 cursor-pointer flex justify-center items-center gap-2">
                            <span x-show="loading" class="animate-spin h-3 w-3 border-2 border-white border-t-transparent rounded-full"></span>
                            Solicitar Cadastro
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- 2. TELA CONECTADA: DASHBOARD & CONTROLES -->
        <div x-show="authenticated" class="space-y-6" x-transition>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Coluna Esquerda: Cartão de Perfil / Saldo -->
                <div class="space-y-6">
                    <!-- Holographic Card -->
                    <div class="relative bg-gradient-to-br from-purple-950/80 to-slate-900 border border-purple-800/40 rounded-2xl p-6 shadow-2xl overflow-hidden group">
                        <div class="absolute -right-16 -top-16 w-36 h-36 bg-purple-500/10 rounded-full blur-2xl group-hover:bg-purple-500/20 transition duration-500"></div>
                        
                        <div class="flex justify-between items-start select-none">
                            <div class="space-y-1">
                                <span class="text-[9px] font-bold text-purple-400 tracking-widest uppercase">CromIA API Account</span>
                                <h4 class="text-base font-extrabold text-white" x-text="'@' + user.username"></h4>
                            </div>
                            <button @click="fetchProfile()" :disabled="loadingProfile"
                                    class="p-1.5 rounded-lg bg-slate-950/40 border border-slate-800 hover:border-purple-500/50 text-slate-400 hover:text-white cursor-pointer transition">
                                <span class="material-icons text-base flex" :class="loadingProfile ? 'animate-spin' : ''">refresh</span>
                            </button>
                        </div>

                        <!-- Balanço / Créditos -->
                        <div class="mt-8 space-y-0.5">
                            <span class="text-[9px] font-mono text-slate-500 font-extrabold uppercase tracking-widest">Saldo de Tokens</span>
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl font-black text-white font-mono tracking-tight" x-text="formatBalance(user.balance)"></span>
                                <span class="text-xs font-bold text-purple-400">₡</span>
                            </div>
                        </div>

                        <div class="mt-8 pt-4 border-t border-slate-800/60 flex justify-between items-center text-[10px] text-slate-500 font-semibold font-mono">
                            <span>SESSÃO LOCAL</span>
                            <span class="text-purple-400">Ativa</span>
                        </div>
                    </div>

                    <!-- Código de Integração -->
                    <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-5 space-y-3 select-none">
                        <h4 class="text-xs font-extrabold text-white tracking-wide uppercase">Consumo Rápido</h4>
                        <div class="space-y-1.5 text-[10px] text-slate-400 font-semibold leading-relaxed">
                            <p>Base URL da API:</p>
                            <div class="bg-slate-950 px-3 py-2 rounded-lg font-mono text-[9px] text-purple-300 border border-slate-800 select-all overflow-x-auto">
                                https://cromia-api.crom.me/v1
                            </div>
                            <p class="mt-2">Cabeçalho de Autenticação:</p>
                            <div class="bg-slate-950 px-3 py-2 rounded-lg font-mono text-[9px] text-purple-300 border border-slate-800 select-all overflow-x-auto">
                                Authorization: Bearer crom_sk_...
                            </div>
                        </div>
                        <div class="pt-2">
                            <a href="https://cromia-api.crom.me/docs" target="_blank" rel="noopener noreferrer"
                               class="w-full py-2 bg-purple-600/20 hover:bg-purple-600 border border-purple-500/30 text-purple-300 hover:text-white rounded-xl text-[10px] font-bold uppercase tracking-wider transition duration-200 flex items-center justify-center gap-1.5 no-underline">
                                <span class="material-icons text-sm">menu_book</span>
                                Documentação Oficial
                            </a>
                        </div>
                    </div>

                    <!-- Modelos Disponíveis -->
                    <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-5 space-y-3 select-none">
                        <h4 class="text-xs font-extrabold text-white tracking-wide uppercase">Modelos Disponíveis</h4>
                        <div class="space-y-2 max-h-40 overflow-y-auto pr-1">
                            <template x-for="model in modelsList" :key="model.id">
                                <div class="flex items-center justify-between p-2 bg-slate-950 border border-slate-800/60 rounded-xl">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="text-xs font-bold text-white font-mono" x-text="model.id"></span>
                                        <span class="text-[8px] text-slate-500 font-extrabold uppercase tracking-wider" x-text="'Provedor: ' + model.owned_by"></span>
                                    </div>
                                    <span class="text-[8px] px-1.5 py-0.5 bg-purple-500/10 border border-purple-500/20 text-purple-400 font-extrabold rounded-md font-mono uppercase">Ativo</span>
                                </div>
                            </template>
                            <template x-if="modelsList.length === 0">
                                <div class="text-[10px] text-slate-500 italic py-2">
                                    Nenhum modelo listado. Conecte-se para puxar a lista de modelos ativos.
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Coluna Direita: Gerenciador de Chaves e Playground -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Gerenciador de Chaves -->
                    <div class="bg-slate-900/40 border border-slate-800/80 rounded-2xl p-5 sm:p-6 space-y-4">
                        <div class="flex justify-between items-center select-none">
                            <div>
                                <h4 class="text-xs font-extrabold text-white tracking-wide uppercase">Chaves de API Ativas</h4>
                                <p class="text-[9px] text-slate-500 font-semibold mt-0.5">Gerencie tokens de acesso para seus scripts e microsserviços.</p>
                            </div>
                        </div>

                        <!-- Lista de Chaves -->
                        <div class="border border-slate-800/60 rounded-xl overflow-hidden bg-slate-950/30">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-800 text-[9px] font-extrabold text-slate-500 uppercase tracking-widest bg-slate-900/30 select-none">
                                        <th class="p-3 pl-4">Identificador/Nome</th>
                                        <th class="p-3">Criada em</th>
                                        <th class="p-3 pr-4 text-right">Ação</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-800/30 text-[11px] text-slate-300 font-semibold font-mono">
                                    <template x-for="key in apiKeys" :key="key.id">
                                        <tr class="hover:bg-slate-900/10 transition">
                                            <td class="p-3 pl-4 text-white font-sans font-bold" x-text="key.name"></td>
                                            <td class="p-3 text-slate-500 text-[10px]" x-text="formatDate(key.created_at)"></td>
                                            <td class="p-3 pr-4 text-right">
                                                <button @click="revokeKey(key.id)"
                                                        class="px-2 py-1 bg-rose-500/10 hover:bg-rose-500 hover:text-white border border-rose-500/20 text-rose-400 rounded text-[9px] font-extrabold uppercase cursor-pointer transition">
                                                    Revogar
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-if="apiKeys.length === 0">
                                        <tr>
                                            <td colspan="3" class="p-4 text-center text-slate-600 font-sans italic text-[11px]">
                                                Nenhuma chave de API gerada. Use o campo abaixo para criar uma.
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- Formulário para Criar Nova Chave -->
                        <form @submit.prevent="generateKey()" class="flex gap-2 items-center">
                            <input type="text" x-model="newKeyName" required placeholder="Nome da chave (ex: Localhost Dev)..."
                                   class="flex-1 bg-slate-950 border border-slate-800 focus:border-purple-500 focus:ring-1 focus:ring-purple-500/20 text-xs rounded-xl px-4 py-2.5 text-white outline-none font-sans font-semibold transition-all">
                            <button type="submit" :disabled="generating"
                                    class="px-4 py-2.5 bg-purple-600 hover:bg-purple-500 disabled:bg-purple-800 text-white rounded-xl text-xs font-bold uppercase transition flex-shrink-0 cursor-pointer">
                                Gerar Chave
                            </button>
                        </form>

                        <!-- Alerta com Nova Chave Gerada -->
                        <template x-if="newlyGeneratedKey">
                            <div class="p-4 bg-purple-950/20 border border-purple-500/30 rounded-xl space-y-2.5 animate-fade-in">
                                <div class="flex items-center gap-1.5 text-purple-400 text-xs font-extrabold select-none">
                                    <span class="material-icons text-base">security</span>
                                    CHAVE GERADA COM SUCESSO!
                                </div>
                                <p class="text-[10px] text-slate-400 font-semibold leading-relaxed select-none">
                                    Esta chave concede acesso total ao seu saldo. <strong class="text-white">Salve-a agora em local seguro</strong>, pois ela não será exibida novamente por motivos de segurança.
                                </p>
                                <div class="flex gap-2">
                                    <div class="flex-1 bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 font-mono text-purple-300 text-xs select-all truncate" x-text="newlyGeneratedKey"></div>
                                    <button @click="copyToClipboard(newlyGeneratedKey)"
                                            class="px-3 bg-purple-600 hover:bg-purple-500 text-white text-[10px] font-bold rounded-lg uppercase cursor-pointer transition select-none flex items-center gap-1">
                                        <span class="material-icons text-sm">content_copy</span>
                                        Copiar
                                    </button>
                                </div>
                                <template x-if="copied">
                                    <span class="text-[9px] text-emerald-400 font-bold block animate-pulse">✓ Copiado para a área de transferência!</span>
                                </template>
                            </div>
                        </template>
                    </div>

                    <!-- Chat Playground -->
                    <div class="bg-slate-900/40 border border-slate-800/80 rounded-2xl p-5 sm:p-6 space-y-4">
                        <div class="flex justify-between items-center select-none">
                            <div>
                                <h4 class="text-xs font-extrabold text-white tracking-wide uppercase">CromIA Chat Playground</h4>
                                <p class="text-[9px] text-slate-500 font-semibold mt-0.5">Teste o gateway de IA diretamente do navegador usando suas credenciais.</p>
                            </div>
                        </div>

                        <!-- Seletor de Chave e Modelo para o Teste -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 select-none">
                            <div class="flex gap-2 items-center">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest font-mono flex-shrink-0">Chave:</span>
                                <input type="password" x-model="playgroundKey" placeholder="Cole sua chave crom_sk_... para testar"
                                       class="flex-1 bg-slate-950 border border-slate-800 focus:border-purple-500 focus:ring-1 focus:ring-purple-500/20 text-[11px] rounded-xl px-3 py-2 text-white outline-none font-sans font-semibold transition-all">
                                <button @click="autoSelectPlaygroundKey()" type="button"
                                        class="px-2 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white rounded-lg text-[9px] font-bold uppercase transition flex-shrink-0 cursor-pointer">
                                    Usar
                                </button>
                            </div>
                            <div class="flex gap-2 items-center">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest font-mono flex-shrink-0">Modelo:</span>
                                <select x-model="selectedModel"
                                        class="flex-1 bg-slate-950 border border-slate-800 focus:border-purple-500 focus:ring-1 focus:ring-purple-500/20 text-xs rounded-xl px-3 py-2.5 text-white outline-none font-sans font-semibold cursor-pointer">
                                    <template x-for="model in modelsList" :key="model.id">
                                        <option :value="model.id" x-text="model.id"></option>
                                    </template>
                                    <template x-if="modelsList.length === 0">
                                        <option value="gpt-4o-mini">gpt-4o-mini</option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <!-- Chat Console -->
                        <div class="border border-slate-800/60 rounded-xl overflow-hidden flex flex-col bg-slate-950/20 h-72">
                            <!-- Corpo do Chat -->
                            <div class="flex-1 p-4 overflow-y-auto space-y-4 font-sans text-xs text-slate-300" x-ref="chatBody">
                                <template x-for="msg in chatHistory">
                                    <div class="flex flex-col gap-1.5" :class="msg.role === 'user' ? 'items-end' : 'items-start'">
                                        <span class="text-[9px] font-extrabold font-mono uppercase tracking-wider" 
                                              :class="msg.role === 'user' ? 'text-purple-400' : 'text-slate-500'" 
                                              x-text="msg.role === 'user' ? 'Você' : 'CromIA'"></span>
                                        <div class="max-w-[85%] rounded-2xl px-4 py-2.5 font-semibold shadow-md whitespace-pre-wrap leading-relaxed"
                                             :class="msg.role === 'user' ? 'bg-purple-600 text-white rounded-tr-none' : 'bg-slate-900 border border-slate-800 text-slate-300 rounded-tl-none'"
                                             x-text="msg.content"></div>
                                    </div>
                                </template>
                                
                                <template x-if="chatHistory.length === 0">
                                    <div class="h-full flex items-center justify-center text-slate-600 italic select-none">
                                        Nenhuma mensagem enviada. Teste enviando uma pergunta abaixo!
                                    </div>
                                </template>

                                <!-- Loading da Resposta -->
                                <template x-if="chatLoading">
                                    <div class="flex flex-col gap-1.5 items-start animate-pulse">
                                        <span class="text-[9px] font-extrabold font-mono uppercase tracking-wider text-slate-500">CromIA</span>
                                        <div class="bg-slate-900 border border-slate-800 text-slate-500 rounded-2xl rounded-tl-none px-4 py-2.5 font-semibold flex items-center gap-2">
                                            <span class="h-1.5 w-1.5 rounded-full bg-purple-500 animate-ping"></span>
                                            Pensando...
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Input do Chat -->
                            <form @submit.prevent="sendChatMessage()" class="p-3 border-t border-slate-800/60 bg-slate-900/50 flex gap-2">
                                <input type="text" x-model="chatInput" placeholder="Pergunte algo à IA..." :disabled="chatLoading"
                                       class="flex-1 bg-slate-950 border border-slate-800 focus:border-purple-500 focus:ring-0 text-xs rounded-xl px-4 py-2 text-white outline-none font-sans font-semibold transition-all">
                                <button type="submit" :disabled="chatLoading || !chatInput.trim()"
                                        class="px-4 bg-purple-600 hover:bg-purple-500 disabled:bg-purple-900 text-white rounded-xl text-xs font-bold uppercase transition cursor-pointer flex items-center justify-center">
                                    Enviar
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>

