<?php
/** @var array $settings */
use yii\helpers\Url;
?>
<div class="space-y-6"
     x-data="adminSettingsHandler(<?= Html::encode(json_encode($settings)) ?>)">

<script>
window.adminSettingsHandler = function(initialSettings) {
    return {
        settings: initialSettings,
        successMsg: '',
        errorMsg: '',
        isSaving: false,
        
        saveSettings() {
            this.successMsg = '';
            this.errorMsg = '';
            this.isSaving = true;
            
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
