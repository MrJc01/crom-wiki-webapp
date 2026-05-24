<?php
/** @var yii\web\View $this */
/** @var array $users */
/** @var array $allPermissions */
/** @var array $settings */
/** @var array $modules */

$this->title = 'Painel Administrativo - CROM';
?>

<div class="h-full w-full flex flex-col gap-6"
     x-data="{
         localTab: 'users'
     }">
     
    <!-- Top Nav Local Tabs -->
    <div class="flex items-center justify-between border-b border-slate-900 pb-3 select-none flex-shrink-0">
        <div class="flex items-center gap-1.5">
            <h2 class="text-lg font-black tracking-tight text-white flex items-center gap-2">
                <i class="material-icons text-sky-400">admin_panel_settings</i>
                Painel Administrativo
            </h2>
            <span class="text-[9px] font-mono font-bold px-2 py-0.5 rounded bg-sky-500/10 border border-sky-500/20 text-sky-400 font-bold">SRE Active</span>
        </div>

        <div class="flex bg-slate-900/60 p-1 border border-slate-800 rounded-xl">
            <!-- Tab: Usuários -->
            <button @click="localTab = 'users'"
                    class="px-4 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 cursor-pointer border border-transparent"
                    :class="localTab === 'users' ? 'bg-sky-500 text-slate-950 font-black shadow-lg shadow-sky-500/10' : 'text-slate-400 hover:text-white'">
                <i class="material-icons text-base">people</i>
                Membros
            </button>
            
            <!-- Tab: Configurações -->
            <button @click="localTab = 'settings'"
                    class="px-4 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 cursor-pointer border border-transparent"
                    :class="localTab === 'settings' ? 'bg-sky-500 text-slate-950 font-black shadow-lg shadow-sky-500/10' : 'text-slate-400 hover:text-white'">
                <i class="material-icons text-base">tune</i>
                Portal Editor
            </button>

            <!-- Tab: Módulos -->
            <button @click="localTab = 'modules'"
                    class="px-4 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 cursor-pointer border border-transparent"
                    :class="localTab === 'modules' ? 'bg-sky-500 text-slate-950 font-black shadow-lg shadow-sky-500/10' : 'text-slate-400 hover:text-white'">
                <i class="material-icons text-base">apps</i>
                Módulos
            </button>

            <!-- Tab: Logs -->
            <button @click="localTab = 'logs'"
                    class="px-4 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 cursor-pointer border border-transparent"
                    :class="localTab === 'logs' ? 'bg-sky-500 text-slate-950 font-black shadow-lg shadow-sky-500/10' : 'text-slate-400 hover:text-white'">
                <i class="material-icons text-base">terminal</i>
                Auditoria Logs
            </button>
        </div>
    </div>

    <!-- Conteúdo da Aba Ativa Local -->
    <div class="flex-grow overflow-y-auto scrollbar-thin pr-1">
        
        <!-- Aba 1: Usuários -->
        <div x-show="localTab === 'users'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="h-full">
            <?= $this->render('_users', [
                'users' => $users,
                'allPermissions' => $allPermissions
            ]) ?>
        </div>

        <!-- Aba 2: Configurações -->
        <div x-show="localTab === 'settings'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="h-full"
             style="display: none;">
            <?= $this->render('_settings', [
                'settings' => $settings
            ]) ?>
        </div>

        <!-- Aba 3: Módulos -->
        <div x-show="localTab === 'modules'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="h-full"
             style="display: none;">
            <?= $this->render('_modules', [
                'modules' => $modules
            ]) ?>
        </div>

        <!-- Aba 4: Logs -->
        <div x-show="localTab === 'logs'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="h-full"
             style="display: none;">
            <?= $this->render('_logs') ?>
        </div>

    </div>
</div>
