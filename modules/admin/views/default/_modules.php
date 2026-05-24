<?php
/** @var array $modules */
use yii\helpers\Url;
use yii\helpers\Html;
?>
<div class="space-y-6"
     x-data="adminModulesHandler(<?= Html::encode(json_encode($modules)) ?>)">

<script>
window.adminModulesHandler = function(initialModules) {
    return {
        modules: initialModules,
        successMsg: '',
        errorMsg: '',
        isToggling: false,
        
        toggleModule(module) {
            if (module.id === 'admin') {
                this.errorMsg = 'Não é permitido desativar o módulo de administração.';
                return;
            }
            
            this.successMsg = '';
            this.errorMsg = '';
            this.isToggling = true;
            
            fetch('<?= Url::to(['/admin/default/toggle-module']) ?>?id=' + module.id)
            .then(res => res.json())
            .then(data => {
                this.isToggling = false;
                if (data.success) {
                    module.is_active = data.is_active;
                    this.successMsg = data.message;
                    // Dispara evento global para o portal saber que os modulos foram alterados
                    document.body.dispatchEvent(new CustomEvent('portalModulesUpdated'));
                } else {
                    this.errorMsg = data.message;
                }
            })
            .catch(err => {
                this.isToggling = false;
                this.errorMsg = 'Erro de rede ao alterar status do módulo.';
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
        <h3 class="text-sm font-extrabold text-white tracking-wide">Central de Módulos & Integrações</h3>
        <p class="text-[10px] text-slate-500 font-semibold leading-relaxed">Gerencie a ativação e visibilidade de módulos de terceiros no ecossistema CROM.</p>
    </div>

    <!-- Grid de Módulos -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <template x-for="mod in modules" :key="mod.id">
            <div class="border border-slate-800/80 rounded-2xl p-5 bg-slate-950/40 backdrop-blur-md flex flex-col justify-between hover:border-slate-700/60 transition duration-300 relative overflow-hidden group"
                 :class="mod.is_active ? 'bg-slate-950/40' : 'bg-slate-950/10 opacity-70 border-slate-900'">
                 
                <!-- Glow decorativo discreto no canto superior direito -->
                <div class="absolute top-0 right-0 w-16 h-16 bg-white/5 rounded-full blur-xl group-hover:scale-125 transition duration-300 pointer-events-none"></div>

                <div class="space-y-4">
                    <div class="flex justify-between items-start gap-2 select-none">
                        <div class="w-9 h-9 bg-slate-800/80 text-white rounded-xl flex items-center justify-center shadow-inner border border-slate-700/60" 
                             x-html="mod.icon">
                        </div>
                        <div class="flex flex-col gap-1 items-end">
                            <span class="px-2 py-0.5 rounded-full text-[8px] font-extrabold font-mono tracking-wide uppercase border"
                                  :class="mod.is_active ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' : 'bg-rose-500/10 border-rose-500/20 text-rose-400'"
                                  x-text="mod.is_active ? 'Ativo' : 'Inativo'">
                            </span>
                            <span class="text-[9px] text-slate-500 font-bold font-mono" x-text="'Ordem: ' + mod.sort_order"></span>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <h4 class="text-sm font-extrabold text-white tracking-wide" x-text="mod.name"></h4>
                        <div class="space-y-0.5">
                            <p class="text-[9px] text-slate-500 font-semibold font-mono flex items-center gap-1">
                                <span class="text-slate-600 font-bold uppercase">Entry:</span>
                                <span x-text="mod.entry_point"></span>
                            </p>
                            <p class="text-[9px] text-slate-500 font-semibold font-mono flex items-center gap-1">
                                <span class="text-slate-600 font-bold uppercase">ID:</span>
                                <span x-text="mod.id"></span>
                            </p>
                            <template x-if="mod.required_permission">
                                <p class="text-[9px] text-indigo-400 font-bold font-mono flex items-center gap-1 select-none">
                                    <i class="material-icons text-[10px]">lock</i>
                                    <span class="uppercase">RBAC:</span>
                                    <span x-text="mod.required_permission"></span>
                                </p>
                            </template>
                            <template x-if="!mod.required_permission">
                                <p class="text-[9px] text-slate-600 font-semibold font-mono flex items-center gap-1 select-none">
                                    <i class="material-icons text-[10px]">lock_open</i>
                                    <span>Livre para todos</span>
                                </p>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end items-center select-none pt-2 border-t border-slate-900">
                    <template x-if="mod.id === 'admin'">
                        <span class="text-[10px] text-slate-500 font-bold italic flex items-center gap-1 py-1">
                            <i class="material-icons text-xs">shield</i>
                            Módulo do Sistema (Protegido)
                        </span>
                    </template>
                    <template x-if="mod.id !== 'admin'">
                        <button @click="toggleModule(mod)"
                                :disabled="isToggling"
                                class="px-4 py-1.5 rounded-xl text-[10px] font-bold transition flex items-center gap-1 cursor-pointer"
                                :class="mod.is_active ? 'bg-rose-500/10 hover:bg-rose-500/20 text-rose-400' : 'bg-sky-500/15 hover:bg-sky-500/25 text-sky-400'">
                            <i class="material-icons text-xs" x-text="mod.is_active ? 'power_settings_new' : 'play_arrow'"></i>
                            <span x-text="mod.is_active ? 'Desativar Módulo' : 'Ativar Módulo'"></span>
                        </button>
                    </template>
                </div>
            </div>
        </template>
    </div>
</div>
