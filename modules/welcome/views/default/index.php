<?php
/** @var yii\web\View $this */
/** @var array $sliders */

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Configuração de Boas-vindas & Badges';
?>

<div class="h-full w-full flex flex-col gap-6"
     x-data="welcomeCRUDHandler(<?= Html::encode(json_encode($sliders)) ?>)">
     
    <!-- Top Nav / Header -->
    <div class="flex items-center justify-between border-b border-slate-900 pb-3 select-none flex-shrink-0">
        <div class="flex items-center gap-1.5">
            <h2 class="text-lg font-black tracking-tight text-white flex items-center gap-2">
                <i class="material-icons text-sky-400">welcome</i>
                Boas-vindas & Badges do Banner
            </h2>
            <span class="text-[9px] font-mono font-bold px-2 py-0.5 rounded bg-sky-500/10 border border-sky-500/20 text-sky-400">Welcome Manager</span>
        </div>

        <button @click="openCreateModal()"
                class="px-4 py-2 bg-sky-500 hover:bg-sky-400 text-slate-950 font-extrabold rounded-xl text-xs flex items-center gap-1.5 transition duration-200 transform active:scale-95 cursor-pointer shadow-lg shadow-sky-500/10 hover:shadow-sky-500/20">
            <i class="material-icons text-sm">add</i>
            Nova Experiência
        </button>
    </div>

    <!-- Alertas -->
    <div class="flex-shrink-0" x-show="successMsg || errorMsg">
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

    <!-- Conteúdo Principal / Grid de Experiências -->
    <div class="flex-grow overflow-y-auto scrollbar-thin pr-1 select-none">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5" x-show="sliders.length > 0">
            <template x-for="item in sliders" :key="item.id">
                <div class="border border-slate-800/80 rounded-2xl p-5 bg-slate-950/40 backdrop-blur-md flex flex-col justify-between hover:border-slate-700/60 transition duration-300 relative group overflow-hidden">
                    
                    <!-- Glow decorativo discreto no canto superior direito -->
                    <div class="absolute top-0 right-0 w-24 h-24 bg-white/5 rounded-full blur-xl group-hover:scale-125 transition duration-300 pointer-events-none"></div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-start gap-2">
                            <div class="w-10 h-10 bg-slate-800/80 text-white rounded-xl flex items-center justify-center shadow-inner border border-slate-700/60 text-xl" 
                                 x-text="item.icon">
                            </div>
                            <div class="flex flex-col gap-1 items-end">
                                <span class="px-2 py-0.5 rounded-full text-[8px] font-extrabold font-mono tracking-wide uppercase border"
                                      :class="item.is_active ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' : 'bg-rose-500/10 border-rose-500/20 text-rose-400'"
                                      x-text="item.is_active ? 'Ativo' : 'Inativo'">
                                </span>
                                
                                <span class="px-2 py-0.5 rounded-md text-[8px] font-extrabold font-mono tracking-wide uppercase border border-slate-800 bg-slate-900 text-slate-400"
                                      x-text="item.required_role === 'new_membro' ? 'Novos Membros' : (item.required_role === 'membro' ? 'Membros' : 'Livre')">
                                </span>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <h4 class="text-sm font-extrabold text-white tracking-wide flex items-center gap-1.5">
                                <span x-text="item.title"></span>
                                <template x-if="item.badge_text">
                                    <span class="px-1.5 py-0.5 bg-rose-500/20 text-rose-450 border border-rose-500/30 text-[8px] uppercase tracking-wide rounded font-extrabold" x-text="item.badge_text"></span>
                                </template>
                            </h4>
                            <p class="text-[10px] text-slate-500 font-semibold font-mono" x-text="item.slides.length + ' slide(s) cadastrado(s)'"></p>
                        </div>
                        
                        <!-- Lista rápida dos slides como pequenas badges -->
                        <div class="flex flex-wrap gap-1.5 pt-1">
                            <template x-for="(s, idx) in item.slides" :key="idx">
                                <span class="text-[9px] font-medium bg-slate-900 border border-slate-800 px-2 py-0.5 rounded-lg text-slate-300 flex items-center gap-1">
                                    <span x-text="s.image_url || '✨'"></span>
                                    <span x-text="s.title || 'Slide ' + (idx+1)"></span>
                                </span>
                            </template>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between items-center pt-3 border-t border-slate-900/60">
                        <div class="flex gap-2">
                            <button @click="openEditModal(item)"
                                    class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 border border-slate-700/50 hover:border-slate-600 text-slate-200 hover:text-white rounded-xl text-[10px] font-bold transition flex items-center gap-1 cursor-pointer">
                                <i class="material-icons text-[12px] text-sky-400">edit</i>
                                <span>Editar</span>
                            </button>
                            <button @click="deleteSlider(item.id)"
                                    class="px-3.5 py-1.5 bg-slate-950 hover:bg-rose-950/20 border border-slate-900 hover:border-rose-900/50 text-slate-400 hover:text-rose-400 rounded-xl text-[10px] font-bold transition flex items-center gap-1 cursor-pointer">
                                <i class="material-icons text-[12px]">delete</i>
                                <span>Excluir</span>
                            </button>
                        </div>
                        
                        <button @click="toggleActive(item)"
                                class="px-3.5 py-1.5 rounded-xl text-[10px] font-bold transition flex items-center gap-1 cursor-pointer"
                                :class="item.is_active ? 'bg-rose-500/10 hover:bg-rose-500/20 text-rose-450' : 'bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400'">
                            <i class="material-icons text-xs" x-text="item.is_active ? 'power_settings_new' : 'play_arrow'"></i>
                            <span x-text="item.is_active ? 'Desativar' : 'Ativar'"></span>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <div class="h-48 border border-dashed border-slate-800 rounded-2xl flex flex-col items-center justify-center gap-3 text-slate-500 p-6 select-none bg-slate-950/10"
             x-show="sliders.length === 0">
            <i class="material-icons text-4xl text-slate-650">welcome</i>
            <div class="text-center">
                <p class="text-xs font-bold text-slate-400">Nenhum slider de boas-vindas ou badge cadastrado</p>
                <p class="text-[10px] text-slate-500 mt-1">Crie um novo fluxo clicando no botão no topo direito.</p>
            </div>
        </div>

    </div>

    <!-- MODAL: CRIAR / EDITAR EXPERIÊNCIA -->
    <div x-show="showModal" 
         x-transition
         class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-[100] flex items-center justify-center p-4"
         style="display: none;">
        <div class="w-full max-w-2xl bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl p-6 flex flex-col max-h-[85vh] overflow-hidden"
             @click.outside="showModal = false">
            
            <!-- Modal Header -->
            <div class="flex justify-between items-center select-none pb-4 border-b border-slate-800/80">
                <div>
                    <h3 class="text-sm font-extrabold text-white tracking-wide" x-text="isEditing ? 'Editar Experiência' : 'Nova Experiência'"></h3>
                    <p class="text-[10px] text-slate-500 font-semibold mt-0.5">Configure o badge de destaque e o fluxo do slider em tela cheia.</p>
                </div>
                <button @click="showModal = false" class="text-slate-500 hover:text-white cursor-pointer transition">
                    <i class="material-icons text-base">close</i>
                </button>
            </div>

            <!-- Modal Content (Scrollable) -->
            <form @submit.prevent="saveSlider()" class="flex-grow overflow-y-auto scrollbar-thin py-4 space-y-5 pr-1">
                
                <!-- Campos Básicos -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[9px] font-extrabold text-slate-550 uppercase tracking-widest font-mono">Título Identificador</label>
                        <input type="text" 
                               x-model="formTitle"
                               required
                               placeholder="Ex: Boas-vindas Gerais"
                               class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-4 py-2.5 text-white outline-none font-sans font-semibold transition-all">
                    </div>
                    
                    <div class="space-y-1">
                        <label class="text-[9px] font-extrabold text-slate-550 uppercase tracking-widest font-mono">Texto do Badge (Opcional)</label>
                        <input type="text" 
                               x-model="formBadgeText"
                               placeholder="Ex: Novo, Alerta, Info"
                               class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-4 py-2.5 text-white outline-none font-sans font-semibold transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[9px] font-extrabold text-slate-550 uppercase tracking-widest font-mono">Ícone / Emoji do Badge</label>
                        <input type="text" 
                               x-model="formIcon"
                               required
                               placeholder="Ex: 👋 ou um nome de ícone"
                               class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-4 py-2.5 text-white outline-none font-sans font-semibold transition-all">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[9px] font-extrabold text-slate-550 uppercase tracking-widest font-mono">Público-alvo / Regra de Exibição</label>
                        <select x-model="formRequiredRole"
                                class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-4 py-2.5 text-slate-300 outline-none font-sans font-semibold transition-all">
                            <option value="all">Livre para todos (Qualquer usuário logado)</option>
                            <option value="membro">Apenas Membros (role is_membro)</option>
                            <option value="new_membro">Apenas Novos Membros (contas criadas &lt; 48h)</option>
                        </select>
                    </div>
                </div>

                <label class="flex items-center gap-3 cursor-pointer p-3.5 bg-slate-950/40 border border-slate-800/80 rounded-xl hover:border-slate-700 transition select-none">
                    <input type="checkbox" 
                           x-model="formIsActive" 
                           class="rounded border-slate-800 text-sky-500 bg-slate-950 h-5 w-5 focus:ring-0 focus:ring-offset-0">
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-slate-200">Ativar experiência de imediato</span>
                        <span class="text-[9px] text-slate-500 font-semibold mt-0.5">Se desmarcado, o badge não aparecerá no Swiper e o auto-abrir fica desativado.</span>
                    </div>
                </label>

                <hr class="border-slate-800">

                <!-- Slides Manager -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest font-mono flex items-center gap-1.5">
                            <i class="material-icons text-sm text-indigo-400">slideshow</i>
                            Gerenciador de Slides
                        </h4>
                        <button type="button" 
                                @click="addSlide()"
                                class="px-3 py-1.5 bg-slate-800 hover:bg-slate-750 border border-slate-700 hover:border-slate-650 text-slate-200 rounded-xl text-[10px] font-extrabold transition flex items-center gap-1 cursor-pointer">
                            <i class="material-icons text-[12px]">add</i>
                            Adicionar Slide
                        </button>
                    </div>

                    <!-- Lista de Slides a Editar -->
                    <div class="space-y-4 max-h-[300px] overflow-y-auto pr-1">
                        <template x-for="(slide, idx) in formSlides" :key="idx">
                            <div class="bg-slate-950/60 border border-slate-800/80 rounded-2xl p-4 space-y-4 relative group hover:border-slate-800 transition duration-200">
                                
                                <!-- Controles do Slide -->
                                <div class="flex items-center justify-between pb-2 border-b border-slate-900/60 select-none">
                                    <span class="text-[10px] font-bold text-sky-400 font-mono" x-text="'Slide ' + (idx + 1)"></span >
                                    <div class="flex items-center gap-1.5">
                                        <button type="button" 
                                                @click="moveSlide(idx, -1)"
                                                :disabled="idx === 0"
                                                class="text-slate-500 hover:text-white disabled:opacity-20 disabled:cursor-not-allowed transition cursor-pointer p-0.5"
                                                title="Subir">
                                            ▲
                                        </button>
                                        <button type="button" 
                                                @click="moveSlide(idx, 1)"
                                                :disabled="idx === formSlides.length - 1"
                                                class="text-slate-500 hover:text-white disabled:opacity-20 disabled:cursor-not-allowed transition cursor-pointer p-0.5"
                                                title="Descer">
                                            ▼
                                        </button>
                                        <button type="button" 
                                                @click="duplicateSlide(idx)"
                                                class="text-slate-500 hover:text-white transition cursor-pointer p-0.5"
                                                title="Duplicar">
                                            📋
                                        </button>
                                        <button type="button" 
                                                @click="removeSlide(idx)"
                                                class="text-rose-500/80 hover:text-rose-400 transition cursor-pointer p-0.5 ml-1"
                                                title="Excluir">
                                            🗑️
                                        </button>
                                    </div>
                                </div>

                                <!-- Inputs do Slide -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div class="space-y-1">
                                        <label class="text-[8px] font-extrabold text-slate-550 uppercase tracking-widest font-mono">Título do Slide</label>
                                        <input type="text" 
                                               x-model="slide.title"
                                               required
                                               placeholder="Ex: Bem-vindo ao CROM"
                                               class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 text-[11px] rounded-lg px-3 py-2 text-white outline-none font-sans font-semibold">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[8px] font-extrabold text-slate-550 uppercase tracking-widest font-mono">Emoji / Ícone do Slide</label>
                                        <input type="text" 
                                               x-model="slide.image_url"
                                               required
                                               placeholder="Ex: 👋 ou URL da imagem"
                                               class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 text-[11px] rounded-lg px-3 py-2 text-white outline-none font-sans font-semibold">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div class="space-y-1">
                                        <label class="text-[8px] font-extrabold text-slate-550 uppercase tracking-widest font-mono">Descrição do Slide</label>
                                        <textarea x-model="slide.description"
                                                  required
                                                  rows="2"
                                                  placeholder="Descreva o conteúdo deste slide..."
                                                  class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 text-[11px] rounded-lg px-3 py-2 text-white outline-none font-sans font-semibold resize-none"></textarea>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[8px] font-extrabold text-slate-550 uppercase tracking-widest font-mono">Estilo do Gradiente (Tailwind CSS)</label>
                                        <input type="text" 
                                               x-model="slide.gradiente"
                                               placeholder="Ex: from-sky-500/20 to-indigo-500/0"
                                               class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 text-[11px] rounded-lg px-3 py-2 text-white outline-none font-sans font-semibold">
                                        <span class="text-[8px] text-slate-550 block leading-tight font-mono">Exemplos: from-sky-500/20 to-indigo-500/0 ou from-emerald-500/20 to-teal-500/0</span>
                                    </div>
                                </div>

                            </div>
                        </template>
                    </div>

                    <div x-show="formSlides.length === 0" 
                         class="p-4 border border-slate-800 bg-slate-950/20 rounded-xl text-center text-slate-550 text-[10px]">
                        Nenhum slide adicionado. Clique em "Adicionar Slide" acima.
                    </div>
                </div>

                <!-- Footer do Formulário -->
                <div class="pt-4 border-t border-slate-800/80 flex justify-end gap-2 select-none">
                    <button type="button" @click="showModal = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-400 hover:text-white bg-slate-800/50 cursor-pointer">Cancelar</button>
                    <button type="submit" 
                            :disabled="isSaving"
                            class="px-4 py-2 rounded-xl text-xs font-bold text-slate-950 bg-sky-400 hover:bg-sky-300 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer transition flex items-center gap-1.5">
                        <template x-if="isSaving">
                            <span class="w-3 h-3 border-2 border-slate-950 border-t-transparent rounded-full animate-spin"></span>
                        </template>
                        <span x-text="isSaving ? 'Salvando...' : 'Salvar Experiência'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function welcomeCRUDHandler(initialSliders) {
    return {
        sliders: initialSliders,
        successMsg: '',
        errorMsg: '',
        isSaving: false,
        showModal: false,
        isEditing: false,

        // Form fields
        formId: '',
        formTitle: '',
        formBadgeText: '',
        formIcon: '👋',
        formRequiredRole: 'all',
        formIsActive: true,
        formSlides: [],

        init() {
            // inicializações se necessárias
        },

        openCreateModal() {
            this.formId = '';
            this.formTitle = '';
            this.formBadgeText = '';
            this.formIcon = '👋';
            this.formRequiredRole = 'all';
            this.formIsActive = true;
            this.formSlides = [
                { title: '', description: '', image_url: '👋', gradiente: 'from-sky-500/20 to-indigo-500/0' }
            ];
            this.isEditing = false;
            this.successMsg = '';
            this.errorMsg = '';
            this.showModal = true;
        },

        openEditModal(slider) {
            this.formId = slider.id;
            this.formTitle = slider.title;
            this.formBadgeText = slider.badge_text || '';
            this.formIcon = slider.icon;
            this.formRequiredRole = slider.required_role;
            this.formIsActive = !!slider.is_active;
            this.formSlides = JSON.parse(JSON.stringify(slider.slides || []));
            this.isEditing = true;
            this.successMsg = '';
            this.errorMsg = '';
            this.showModal = true;
        },

        addSlide() {
            this.formSlides.push({
                title: '',
                description: '',
                image_url: '✨',
                gradiente: 'from-sky-500/20 to-indigo-500/0'
            });
        },

        removeSlide(index) {
            this.formSlides.splice(index, 1);
        },

        duplicateSlide(index) {
            const slide = JSON.parse(JSON.stringify(this.formSlides[index]));
            this.formSlides.splice(index + 1, 0, slide);
        },

        moveSlide(index, direction) {
            const newIndex = index + direction;
            if (newIndex < 0 || newIndex >= this.formSlides.length) return;
            const temp = this.formSlides[index];
            this.formSlides[index] = this.formSlides[newIndex];
            this.formSlides[newIndex] = temp;
        },

        saveSlider() {
            this.successMsg = '';
            this.errorMsg = '';
            this.isSaving = true;

            const formData = new FormData();
            formData.append('id', this.formId);
            formData.append('title', this.formTitle);
            formData.append('badge_text', this.formBadgeText);
            formData.append('icon', this.formIcon);
            formData.append('required_role', this.formRequiredRole);
            formData.append('is_active', this.formIsActive ? '1' : '0');
            formData.append('slides_json', JSON.stringify(this.formSlides));
            formData.append('<?= Yii::$app->request->csrfParam ?>', '<?= Yii::$app->request->getCsrfToken() ?>');

            fetch('<?= Url::to(['/welcome/default/save']) ?>', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                this.isSaving = false;
                if (data.success) {
                    this.successMsg = data.message;
                    this.showModal = false;
                    setTimeout(() => { window.location.reload(); }, 800);
                } else {
                    this.errorMsg = data.message;
                }
            })
            .catch(err => {
                this.isSaving = false;
                this.errorMsg = 'Erro ao salvar dados.';
            });
        },

        toggleActive(slider) {
            this.successMsg = '';
            this.errorMsg = '';

            fetch('<?= Url::to(['/welcome/default/toggle']) ?>?id=' + slider.id)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    slider.is_active = data.is_active;
                    this.successMsg = data.message;
                } else {
                    this.errorMsg = data.message;
                }
            })
            .catch(err => {
                this.errorMsg = 'Erro de rede ao alternar status.';
            });
        },

        deleteSlider(id) {
            if (!confirm('Deseja realmente excluir esta experiência de boas-vindas?')) return;
            this.successMsg = '';
            this.errorMsg = '';

            fetch('<?= Url::to(['/welcome/default/delete']) ?>?id=' + id)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.successMsg = data.message;
                    setTimeout(() => { window.location.reload(); }, 800);
                } else {
                    this.errorMsg = data.message;
                }
            })
            .catch(err => {
                this.errorMsg = 'Erro ao excluir.';
            });
        }
    };
}
</script>
