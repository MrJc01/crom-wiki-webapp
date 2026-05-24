<?php
/** @var array $settings */
use yii\helpers\Url;
use yii\helpers\Html;
?>
<script>
window.adminSettingsHandler = function(initialSettings) {
    return {
        settings: initialSettings,
        successMsg: '',
        errorMsg: '',
        isSaving: false,
        
        ecosystemCards: [],
        alignmentCards: [],
        
        init() {
            try {
                this.ecosystemCards = JSON.parse(this.settings.ecosystem_cards_json || '[]');
            } catch(e) {
                this.ecosystemCards = [];
            }
            try {
                this.alignmentCards = JSON.parse(this.settings.alignment_cards_json || '[]');
            } catch(e) {
                this.alignmentCards = [];
            }
        },
        
        addEcosystemCard() {
            this.ecosystemCards.push({
                nome: '',
                tag: '',
                tag_style: 'bg-purple-500/20 text-purple-300 border-purple-500/30',
                bg_style: 'bg-gradient-to-br from-purple-950/40 to-slate-900 border-purple-900/60 hover:border-purple-500/40',
                icone: '📦',
                descricao: '',
                btn_texto: 'Acessar',
                btn_style: 'bg-purple-600 hover:bg-purple-500 text-white',
                disabled: false,
                tab: 'paravoce'
            });
        },
        
        removeEcosystemCard(index) {
            this.ecosystemCards.splice(index, 1);
        },
        
        addAlignmentCard() {
            this.alignmentCards.push({
                titulo: '',
                tag: '',
                descricao: '',
                btn_texto: 'Acessar',
                tab: 'paravoce'
            });
        },
        
        removeAlignmentCard(index) {
            this.alignmentCards.splice(index, 1);
        },
        
        saveSettings() {
            this.successMsg = '';
            this.errorMsg = '';
            this.isSaving = true;
            
            // Serializa os cartões de volta para JSON para envio
            this.settings.ecosystem_cards_json = JSON.stringify(this.ecosystemCards);
            this.settings.alignment_cards_json = JSON.stringify(this.alignmentCards);
            
            const formData = new FormData();
            for (const [key, val] of Object.entries(this.settings)) {
                formData.append('settings[' + key + ']', val);
            }
            formData.append('<?= Yii::$app->request->csrfParam ?>', '<?= Yii::$app->request->getCsrfToken() ?>');
            
            fetch('<?= Url::to(['/admin/default/save-settings']) ?>', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                this.isSaving = false;
                if (data.success) {
                    this.successMsg = data.message;
                    // Dispara evento global para o portal saber que as configuracoes foram salvas
                    document.body.dispatchEvent(new CustomEvent('portalSettingsUpdated'));
                } else {
                    this.errorMsg = data.message;
                }
            })
            .catch(err => {
                this.isSaving = false;
                this.errorMsg = 'Erro de rede ao salvar as configurações.';
            });
        }
    };
};
</script>

<div class="space-y-6"
     x-data="adminSettingsHandler(<?= Html::encode(json_encode($settings)) ?>)">

    <!-- Alertas -->
    <div class="flex-shrink-0">
        <template x-if="successMsg">
            <div class="p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold rounded-xl flex items-center gap-2 shadow-lg animate-fade-in">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span x-text="successMsg"></span>
            </div>
        </template>
        <template x-if="errorMsg">
            <div class="p-3 bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-bold rounded-xl flex items-center gap-2 shadow-lg animate-fade-in">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                <span x-text="errorMsg"></span>
            </div>
        </template>
    </div>

    <!-- Header -->
    <div class="space-y-1 select-none">
        <h3 class="text-sm font-extrabold text-white tracking-wide">Configurações do Portal & Dashboard</h3>
        <p class="text-[10px] text-slate-500 font-semibold leading-relaxed">Customize os títulos globais, textos, banners e o redirecionamento principal exibido aos membros.</p>
    </div>

    <!-- Formulário Premium de Configurações -->
    <form @submit.prevent="saveSettings()" class="bg-slate-950/40 border border-slate-800/80 rounded-2xl p-6 space-y-6 backdrop-blur-md">
        
        <!-- Bloco 1: Identidade Visual Global -->
        <div class="space-y-4">
            <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest font-mono flex items-center gap-1.5 select-none">
                <i class="material-icons text-sm text-sky-400">branding_watermark</i>
                Identidade Visual do Topbar
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-[9px] font-extrabold text-slate-500 uppercase tracking-widest font-mono">Título do Portal</label>
                    <input type="text" 
                           x-model="settings.portal_title" 
                           required
                           placeholder="Ex: CROM"
                           class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-4 py-2.5 text-white outline-none font-sans font-semibold transition-all">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[9px] font-extrabold text-slate-500 uppercase tracking-widest font-mono">Subtítulo do Portal</label>
                    <input type="text" 
                           x-model="settings.portal_subtitle" 
                           required
                           placeholder="Ex: Developer Program"
                           class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-4 py-2.5 text-white outline-none font-sans font-semibold transition-all">
                </div>
            </div>
        </div>

        <hr class="border-slate-900">

        <!-- Bloco 2: Hero Banner Principal -->
        <div class="space-y-4">
            <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest font-mono flex items-center gap-1.5 select-none">
                <i class="material-icons text-sm text-indigo-400">view_carousel</i>
                Banner Principal (Hero Section)
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="space-y-1.5 md:col-span-1">
                    <label class="text-[9px] font-extrabold text-slate-500 uppercase tracking-widest font-mono">Badge de Destaque</label>
                    <input type="text" 
                           x-model="settings.dashboard_badge" 
                           required
                           placeholder="Ex: Soberania"
                           class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-4 py-2.5 text-white outline-none font-sans font-semibold transition-all">
                </div>
                <div class="space-y-1.5 md:col-span-2">
                    <label class="text-[9px] font-extrabold text-slate-500 uppercase tracking-widest font-mono">Título Principal do Banner</label>
                    <input type="text" 
                           x-model="settings.dashboard_title" 
                           required
                           placeholder="Ex: CROM"
                           class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-4 py-2.5 text-white outline-none font-sans font-semibold transition-all">
                </div>
            </div>
            
            <div class="space-y-1.5">
                <label class="text-[9px] font-extrabold text-slate-500 uppercase tracking-widest font-mono">Descrição Detalhada do Banner</label>
                <textarea x-model="settings.dashboard_desc" 
                          required
                          rows="3"
                          placeholder="Digite o parágrafo de introdução exibido no topo..."
                          class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-4 py-2.5 text-white outline-none font-sans font-semibold transition-all resize-none"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-[9px] font-extrabold text-slate-500 uppercase tracking-widest font-mono">Texto do Botão de Ação</label>
                    <input type="text" 
                           x-model="settings.dashboard_btn_text" 
                           required
                           placeholder="Ex: Consultar Documentos"
                           class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-4 py-2.5 text-white outline-none font-sans font-semibold transition-all">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[9px] font-extrabold text-slate-500 uppercase tracking-widest font-mono">Aba de Destino (Redirecionamento)</label>
                    <select x-model="settings.dashboard_btn_tab" 
                            required
                            class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-4 py-2.5 text-white outline-none font-sans font-semibold transition-all cursor-pointer font-semibold text-white">
                        <option value="paravoce" class="bg-slate-950 text-white">Para Você (Dashboard)</option>
                        <option value="discover" class="bg-slate-950 text-white">Discover (Aplicativos)</option>
                        <option value="beneficios" class="bg-slate-950 text-white">Benefícios</option>
                        <option value="page_crud" class="bg-slate-950 text-white">Páginas Dinâmicas</option>
                        <option value="wiki" class="bg-slate-950 text-white">Wiki Documentações</option>
                        <option value="chat" class="bg-slate-950 text-white">Chat Integrado</option>
                    </select>
                </div>
            </div>
        </div>

        <hr class="border-slate-900">

        <!-- Bloco 3: Frase e Palavra do Dia -->
        <div class="space-y-4">
            <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest font-mono flex items-center gap-1.5 select-none">
                <i class="material-icons text-sm text-amber-400">lightbulb</i>
                Palavra e Frase do Dia
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="space-y-1.5 md:col-span-1">
                    <label class="text-[9px] font-extrabold text-slate-500 uppercase tracking-widest font-mono">Palavra (Título)</label>
                    <input type="text" 
                           x-model="settings.daily_quote_title" 
                           required
                           placeholder="Ex: Entusiasmo"
                           class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-4 py-2.5 text-white outline-none font-sans font-semibold transition-all">
                </div>
                <div class="space-y-1.5 md:col-span-2">
                    <label class="text-[9px] font-extrabold text-slate-500 uppercase tracking-widest font-mono">Frase Diária (Subtítulo)</label>
                    <input type="text" 
                           x-model="settings.daily_quote_text" 
                           required
                           placeholder="Ex: O entusiasmo é a maior força da alma."
                           class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-4 py-2.5 text-white outline-none font-sans font-semibold transition-all">
                </div>
            </div>
        </div>

        <hr class="border-slate-900">

        <!-- Bloco 4: Ecossistema & Outros Swipers (Editor Visual Premium) -->
        <div class="space-y-6">
            <div class="space-y-1 select-none">
                <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest font-mono flex items-center gap-1.5">
                    <i class="material-icons text-sm text-purple-400">layers</i>
                    Aplicativos do Ecossistema (Carrossel Principal)
                </h4>
                <p class="text-[10px] text-slate-500 font-semibold leading-relaxed">
                    Gerencie os cartões exibidos no carrossel horizontal principal do monólito.
                </p>
            </div>

            <!-- Lista de Cartões Interativos -->
            <div class="space-y-4">
                <template x-for="(card, index) in ecosystemCards" :key="index">
                    <div class="bg-slate-950/60 border border-slate-800/80 rounded-2xl p-5 space-y-4 relative group hover:border-slate-700/60 transition duration-300">
                        <!-- Botão de Deletar Card -->
                        <button type="button" 
                                @click="removeEcosystemCard(index)"
                                class="absolute top-4 right-4 text-slate-500 hover:text-rose-400 transition cursor-pointer p-1 rounded-lg hover:bg-rose-500/10 border-none outline-none bg-transparent"
                                title="Remover Card">
                            <i class="material-icons text-sm">delete</i>
                        </button>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Nome do App -->
                            <div class="space-y-1.5 md:col-span-2">
                                <label class="text-[9px] font-extrabold text-slate-500 uppercase tracking-widest font-mono">Nome do Aplicativo</label>
                                <input type="text" x-model="card.nome" required placeholder="Ex: CromIA Gateway"
                                       class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-3 py-2 text-white outline-none font-sans font-semibold transition-all">
                            </div>
                            <!-- Tag do App -->
                            <div class="space-y-1.5">
                                <label class="text-[9px] font-extrabold text-slate-500 uppercase tracking-widest font-mono">Tag/Categoria</label>
                                <input type="text" x-model="card.tag" required placeholder="Ex: Inteligência"
                                       class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-3 py-2 text-white outline-none font-sans font-semibold transition-all">
                            </div>
                            <!-- Ícone (Emoji ou Letra) -->
                            <div class="space-y-1.5">
                                <label class="text-[9px] font-extrabold text-slate-500 uppercase tracking-widest font-mono">Ícone (Emoji/Texto)</label>
                                <input type="text" x-model="card.icone" required placeholder="Ex: 🤖"
                                       class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-3 py-2 text-center text-white outline-none font-sans font-semibold transition-all">
                            </div>
                        </div>

                        <!-- Descrição do App -->
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-extrabold text-slate-500 uppercase tracking-widest font-mono">Descrição Detalhada do Card</label>
                            <textarea x-model="card.descricao" required rows="2" placeholder="Descreva brevemente a utilidade do aplicativo..."
                                      class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-3 py-2 text-white outline-none font-sans font-semibold transition-all resize-none"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Texto do Botão -->
                            <div class="space-y-1.5">
                                <label class="text-[9px] font-extrabold text-slate-500 uppercase tracking-widest font-mono">Texto do Botão</label>
                                <input type="text" x-model="card.btn_texto" required placeholder="Ex: Acessar Token"
                                       class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-3 py-2 text-white outline-none font-sans font-semibold transition-all">
                            </div>
                            <!-- Ação: Tab ou Link -->
                            <div class="space-y-1.5">
                                <label class="text-[9px] font-extrabold text-slate-500 uppercase tracking-widest font-mono">Aba de Destino (Local)</label>
                                <select x-model="card.tab" 
                                        class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-3 py-2 text-white outline-none font-sans font-semibold transition-all cursor-pointer font-semibold">
                                    <option value="" class="bg-slate-950 text-slate-500">Nenhuma (Usar link externo)</option>
                                    <option value="paravoce" class="bg-slate-950 text-white">Para Você (Dashboard)</option>
                                    <option value="discover" class="bg-slate-950 text-white">Discover (Aplicativos)</option>
                                    <option value="beneficios" class="bg-slate-950 text-white">Benefícios</option>
                                    <option value="page_crud" class="bg-slate-950 text-white">Páginas Dinâmicas</option>
                                    <option value="wiki" class="bg-slate-950 text-white">Wiki Documentações</option>
                                    <option value="chat" class="bg-slate-950 text-white">Chat Integrado</option>
                                </select>
                            </div>
                            <!-- Link Externo (Caso não use Tab) -->
                            <div class="space-y-1.5">
                                <label class="text-[9px] font-extrabold text-slate-500 uppercase tracking-widest font-mono">Link Externo (URL)</label>
                                <input type="text" x-model="card.link" placeholder="Ex: https://..."
                                       class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-3 py-2 text-white outline-none font-sans font-semibold transition-all">
                            </div>
                        </div>

                        <!-- Estilo e Estado -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-1">
                            <!-- Preset de Cores -->
                            <div class="space-y-1.5">
                                <label class="text-[9px] font-extrabold text-slate-500 uppercase tracking-widest font-mono">Tema de Cor (Preset)</label>
                                <select @change="
                                    if ($event.target.value === 'purple') {
                                        card.tag_style = 'bg-purple-500/20 text-purple-300 border-purple-500/30';
                                        card.bg_style = 'bg-gradient-to-br from-purple-950/40 to-slate-900 border-purple-900/60 hover:border-purple-500/40';
                                        card.btn_style = 'bg-purple-600 hover:bg-purple-500 text-white';
                                    } else if ($event.target.value === 'emerald') {
                                        card.tag_style = 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30';
                                        card.bg_style = 'bg-gradient-to-br from-emerald-950/40 to-slate-900 border-emerald-900/60 hover:border-emerald-500/40';
                                        card.btn_style = 'bg-emerald-600 hover:bg-emerald-500 text-white';
                                    } else if ($event.target.value === 'indigo') {
                                        card.tag_style = 'bg-indigo-500/20 text-indigo-300 border-indigo-500/30';
                                        card.bg_style = 'bg-gradient-to-br from-indigo-950/40 to-slate-900 border-indigo-900/60 hover:border-indigo-500/40';
                                        card.btn_style = 'bg-indigo-600 hover:bg-indigo-500 text-white';
                                    } else if ($event.target.value === 'rose') {
                                        card.tag_style = 'bg-rose-500/20 text-rose-300 border-rose-500/30';
                                        card.bg_style = 'bg-gradient-to-br from-rose-950/40 to-slate-900 border-rose-900/60 hover:border-rose-500/40';
                                        card.btn_style = 'bg-rose-600 hover:bg-rose-500 text-white';
                                    }
                                " class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-3 py-2 text-white outline-none font-sans font-semibold transition-all cursor-pointer font-semibold">
                                    <option value="purple" :selected="card.tag_style && card.tag_style.indexOf('purple') !== -1">Roxo (CromIA)</option>
                                    <option value="emerald" :selected="card.tag_style && card.tag_style.indexOf('emerald') !== -1">Verde (Privacidade)</option>
                                    <option value="indigo" :selected="card.tag_style && card.tag_style.indexOf('indigo') !== -1">Azul (Infraestrutura)</option>
                                    <option value="rose" :selected="card.tag_style && card.tag_style.indexOf('rose') !== -1">Vermelho (Filosofia)</option>
                                </select>
                            </div>
                            <!-- Estado (Skunkworks) -->
                            <div class="space-y-1.5 flex items-center gap-2 pt-5">
                                <input type="checkbox" x-model="card.disabled" :id="'ecosystem-card-disabled-' + index"
                                       class="rounded bg-slate-950 border-slate-800 text-sky-500 focus:ring-sky-500/20 h-4.5 w-4.5 cursor-pointer">
                                <label :for="'ecosystem-card-disabled-' + index" class="text-[10px] font-bold text-slate-400 cursor-pointer select-none">Em Desenvolvimento (Skunkworks)</label>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Botão Adicionar Card -->
            <div class="flex justify-start">
                <button type="button" @click="addEcosystemCard()"
                        class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow-md select-none">
                    <i class="material-icons text-sm text-sky-400">add_circle</i>
                    Adicionar Card do Ecossistema
                </button>
            </div>
        </div>

        <hr class="border-slate-900">

        <!-- Bloco 5: Alinhamento Operacional (Editor Visual Premium) -->
        <div class="space-y-6">
            <div class="space-y-1 select-none">
                <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest font-mono flex items-center gap-1.5">
                    <i class="material-icons text-sm text-indigo-400">handshake</i>
                    Alinhamento Operacional (Cartões Inferiores)
                </h4>
                <p class="text-[10px] text-slate-500 font-semibold leading-relaxed">
                    Gerencie os cartões de governança, vesting e diretrizes exibidos na parte inferior do Dashboard.
                </p>
            </div>

            <!-- Lista de Cartões Interativos -->
            <div class="space-y-4">
                <template x-for="(card, index) in alignmentCards" :key="index">
                    <div class="bg-slate-950/60 border border-slate-800/80 rounded-2xl p-5 space-y-4 relative group hover:border-slate-700/60 transition duration-300">
                        <!-- Botão de Deletar Card -->
                        <button type="button" 
                                @click="removeAlignmentCard(index)"
                                class="absolute top-4 right-4 text-slate-500 hover:text-rose-400 transition cursor-pointer p-1 rounded-lg hover:bg-rose-500/10 border-none outline-none bg-transparent"
                                title="Remover Card">
                            <i class="material-icons text-sm">delete</i>
                        </button>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Título do Card -->
                            <div class="space-y-1.5 md:col-span-2">
                                <label class="text-[9px] font-extrabold text-slate-500 uppercase tracking-widest font-mono">Título do Cartão</label>
                                <input type="text" x-model="card.titulo" required placeholder="Ex: Contrato de Cuidado & Vesting"
                                       class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-3 py-2 text-white outline-none font-sans font-semibold transition-all">
                            </div>
                            <!-- Tag do Card -->
                            <div class="space-y-1.5">
                                <label class="text-[9px] font-extrabold text-slate-500 uppercase tracking-widest font-mono">Tag (Opcional)</label>
                                <input type="text" x-model="card.tag" placeholder="Ex: Filosofia"
                                       class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-3 py-2 text-white outline-none font-sans font-semibold transition-all">
                            </div>
                        </div>

                        <!-- Descrição do Card -->
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-extrabold text-slate-500 uppercase tracking-widest font-mono">Descrição Detalhada do Cartão</label>
                            <textarea x-model="card.descricao" required rows="2" placeholder="Descreva as diretrizes..."
                                      class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-3 py-2 text-white outline-none font-sans font-semibold transition-all resize-none"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Texto do Botão -->
                            <div class="space-y-1.5">
                                <label class="text-[9px] font-extrabold text-slate-500 uppercase tracking-widest font-mono">Texto do Botão</label>
                                <input type="text" x-model="card.btn_texto" required placeholder="Ex: Acessar Manifesto"
                                       class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-3 py-2 text-white outline-none font-sans font-semibold transition-all">
                            </div>
                            <!-- Aba de Destino (Local) -->
                            <div class="space-y-1.5">
                                <label class="text-[9px] font-extrabold text-slate-500 uppercase tracking-widest font-mono">Aba de Destino (Local)</label>
                                <select x-model="card.tab" 
                                        class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-3 py-2 text-white outline-none font-sans font-semibold transition-all cursor-pointer font-semibold">
                                    <option value="" class="bg-slate-950 text-slate-500">Nenhuma (Usar link externo)</option>
                                    <option value="paravoce" class="bg-slate-950 text-white">Para Você (Dashboard)</option>
                                    <option value="discover" class="bg-slate-950 text-white">Discover (Aplicativos)</option>
                                    <option value="beneficios" class="bg-slate-950 text-white">Benefícios</option>
                                    <option value="page_crud" class="bg-slate-950 text-white">Páginas Dinâmicas</option>
                                    <option value="wiki" class="bg-slate-950 text-white">Wiki Documentações</option>
                                    <option value="chat" class="bg-slate-950 text-white">Chat Integrado</option>
                                </select>
                            </div>
                            <!-- Link Externo (Caso não use Tab) -->
                            <div class="space-y-1.5">
                                <label class="text-[9px] font-extrabold text-slate-500 uppercase tracking-widest font-mono">Link Externo (URL)</label>
                                <input type="text" x-model="card.link" placeholder="Ex: https://..."
                                       class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-3 py-2 text-white outline-none font-sans font-semibold transition-all">
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Botão Adicionar Card -->
            <div class="flex justify-start">
                <button type="button" @click="addAlignmentCard()"
                        class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow-md select-none">
                    <i class="material-icons text-sm text-sky-400">add_circle</i>
                    Adicionar Card de Alinhamento
                </button>
            </div>
        </div>

        <!-- Botão de Ação -->
        <div class="flex justify-end pt-2 select-none">
            <button type="submit" 
                    :disabled="isSaving"
                    class="px-6 py-2.5 bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 disabled:opacity-50 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow-lg shadow-sky-600/15">
                <template x-if="!isSaving">
                    <span class="flex items-center gap-1.5">
                        <i class="material-icons text-base">save</i>
                        Salvar Configurações
                    </span>
                </template>
                <template x-if="isSaving">
                    <span class="flex items-center gap-1.5 animate-pulse">
                        <svg class="animate-spin h-3.5 w-3.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Gravando dados...
                    </span>
                </template>
            </button>
        </div>
    </form>
</div>
