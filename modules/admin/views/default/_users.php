<?php
/** @var array $users */
/** @var array $allPermissions */
use yii\helpers\Url;
use yii\helpers\Html;
?>

<div class="space-y-6"
     x-data="adminUsersHandler(<?= Html::encode(json_encode($users)) ?>, <?= Html::encode(json_encode($allPermissions)) ?>)">

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

    <!-- Header & Botão de Criação -->
    <div class="flex justify-between items-center select-none">
        <div class="space-y-1">
            <h3 class="text-sm font-extrabold text-white tracking-wide">Membros & Credenciais</h3>
            <p class="text-[10px] text-slate-500 font-semibold leading-relaxed">Gerencie cadastros locais, controle de status de bloqueios temporários e associação de privilégios RBAC.</p>
        </div>
        <button @click="openCreateModal()" 
                class="px-4 py-2 bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow-lg shadow-sky-600/15 select-none">
            <i class="material-icons text-base">person_add</i>
            Criar Usuário
        </button>
    </div>

    <!-- Lista de Usuários -->
    <div class="border border-slate-800/80 rounded-2xl overflow-hidden bg-slate-950/40 backdrop-blur-md">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-800 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest bg-slate-900/30 select-none">
                        <th class="p-4 pl-6">ID</th>
                        <th class="p-4">Username</th>
                        <th class="p-4">Cadastrado em</th>
                        <th class="p-4">Privilégios RBAC</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 pr-6 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40 text-xs text-slate-300 font-semibold">
                    <template x-for="user in users" :key="user.id">
                        <tr class="hover:bg-slate-900/10 transition duration-200"
                            :class="user.status === 0 ? 'bg-rose-500/5 hover:bg-rose-500/10' : ''">
                            <td class="p-4 pl-6 font-mono text-[10px] text-slate-500" x-text="user.id"></td>
                             <td class="p-4">
                                <div class="flex flex-col gap-1">
                                    <span class="font-bold text-white" x-text="user.username"></span>
                                    <div class="flex flex-wrap gap-1 mt-1 select-none">
                                        <template x-if="user.is_membro">
                                            <span class="px-1.5 py-0.2 bg-sky-500/10 border border-sky-500/20 text-sky-400 text-[8px] font-mono rounded font-extrabold uppercase">Membro</span>
                                        </template>
                                        <template x-if="user.is_guardiao">
                                            <span class="px-1.5 py-0.2 bg-amber-500/10 border border-amber-500/20 text-amber-400 text-[8px] font-mono rounded font-extrabold uppercase">Guardião</span>
                                        </template>
                                        <template x-if="user.is_pilar">
                                            <span class="px-1.5 py-0.2 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[8px] font-mono rounded font-extrabold uppercase">Pilar</span>
                                        </template>
                                        <template x-if="user.is_forja">
                                            <span class="px-1.5 py-0.2 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-[8px] font-mono rounded font-extrabold uppercase">Forja</span>
                                        </template>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-slate-500 font-mono text-[10px]" x-text="user.created_at"></td>
                            <td class="p-4">
                                <div class="flex flex-wrap gap-1">
                                    <template x-for="perm in user.permissions" :key="perm">
                                        <span class="px-2 py-0.5 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-[9px] font-mono rounded-md" x-text="perm"></span>
                                    </template>
                                    <template x-if="user.permissions.length === 0">
                                        <span class="text-slate-600 font-normal italic text-[10px]">Sem privilégios</span>
                                    </template>
                                </div>
                            </td>
                            <td class="p-4">
                                <template x-if="user.status === 1">
                                    <span class="px-2 py-0.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[9px] rounded-full uppercase tracking-wider select-none font-bold">Ativo</span>
                                </template>
                                <template x-if="user.status === 0">
                                    <span class="px-2 py-0.5 bg-rose-500/10 border border-rose-500/20 text-rose-400 text-[9px] rounded-full uppercase tracking-wider select-none font-bold animate-pulse">Bloqueado</span>
                                </template>
                            </td>
                            <td class="p-4 pr-6 text-right select-none">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Visualizar Detalhes -->
                                    <button @click="openInfoModal(user)" 
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition cursor-pointer"
                                            title="Visualizar Informações">
                                        <i class="material-icons text-base">visibility</i>
                                    </button>
                                    <!-- Alterar Permissões -->
                                    <button @click="openPermModal(user)" 
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition cursor-pointer"
                                            title="Privilégios RBAC">
                                        <i class="material-icons text-base">vpn_key</i>
                                    </button>
                                    <!-- Editar Dados -->
                                    <button @click="openEditModal(user)" 
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition cursor-pointer"
                                            title="Editar Cadastro">
                                        <i class="material-icons text-base">edit</i>
                                    </button>
                                    <!-- Bloquear / Desbloquear -->
                                    <button @click="toggleStatus(user)"
                                            class="p-1.5 rounded-lg transition cursor-pointer"
                                            :class="user.status === 1 ? 'text-rose-400 hover:bg-rose-500/10' : 'text-emerald-400 hover:bg-emerald-500/10'"
                                            :title="user.status === 1 ? 'Bloquear Acesso' : 'Desbloquear Acesso'">
                                        <i class="material-icons text-base" x-text="user.status === 1 ? 'block' : 'lock_open'"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 1. MODAL: CRIAR / EDITAR USUÁRIO -->
    <div x-show="showUserModal" 
         x-transition
         class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-[100] flex items-center justify-center p-4"
         style="display: none;">
        <div class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl p-6 space-y-4"
             @click.outside="showUserModal = false">
            
            <div class="flex justify-between items-center select-none">
                <h3 class="text-sm font-extrabold text-white tracking-wide" x-text="isEditing ? 'Editar Usuário' : 'Novo Usuário'"></h3>
                <button @click="showUserModal = false" class="text-slate-500 hover:text-white cursor-pointer transition">
                    <i class="material-icons text-base">close</i>
                </button>
            </div>

            <form @submit.prevent="saveUser()" class="space-y-4">
                
                <div class="space-y-1.5">
                    <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest font-mono">Nome do Usuário</label>
                    <input type="text" 
                           x-model="username" 
                           required
                           placeholder="Ex: joao.infra"
                           class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-4 py-2.5 text-white outline-none font-sans font-semibold transition-all">
                </div>

                <div class="space-y-1.5">
                    <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest font-mono">Senha</label>
                    <input type="password" 
                           x-model="password" 
                           :required="!isEditing"
                           placeholder="Nova senha secreta..."
                           class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-4 py-2.5 text-white outline-none font-sans font-semibold transition-all">
                    <template x-if="isEditing">
                        <span class="text-[9px] text-slate-500 block font-semibold">Deixe em branco para manter a senha atual.</span>
                    </template>
                </div>

                <!-- Checkboxes Premium de Tags de Governança -->
                <div class="space-y-1.5 pt-1">
                    <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest font-mono">Tags de Governança</label>
                    <div class="p-3 bg-slate-950/40 border border-slate-800 rounded-xl grid grid-cols-2 gap-3 select-none">
                        <label class="flex items-center gap-2 cursor-pointer p-1">
                            <input type="checkbox" x-model="isMembro" class="rounded border-slate-800 text-sky-500 bg-slate-950 h-4.5 w-4.5">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-200">Membro</span>
                                <span class="text-[8px] text-slate-500 font-semibold">Geral</span>
                            </div>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer p-1">
                            <input type="checkbox" x-model="isGuardiao" class="rounded border-slate-800 text-sky-500 bg-slate-950 h-4.5 w-4.5">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-200">Guardião</span>
                                <span class="text-[8px] text-slate-500 font-semibold">Infra</span>
                            </div>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer p-1">
                            <input type="checkbox" x-model="isPilar" class="rounded border-slate-800 text-sky-500 bg-slate-950 h-4.5 w-4.5">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-200">Pilar</span>
                                <span class="text-[8px] text-slate-500 font-semibold">Garante</span>
                            </div>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer p-1">
                            <input type="checkbox" x-model="isForja" class="rounded border-slate-800 text-sky-500 bg-slate-950 h-4.5 w-4.5">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-200">Forja</span>
                                <span class="text-[8px] text-slate-500 font-semibold">Apps</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="pt-2 flex justify-end gap-2 select-none">
                    <button type="button" @click="showUserModal = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-400 hover:text-white bg-slate-800/50 cursor-pointer">Cancelar</button>
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-950 bg-sky-400 hover:bg-sky-300 cursor-pointer transition" x-text="isEditing ? 'Atualizar' : 'Salvar'"></button>
                </div>
            </form>
        </div>
    </div>

    <!-- 2. MODAL: ATRIBUIR PRIVILÉGIOS (RBAC) -->
    <div x-show="showPermModal" 
         x-transition
         class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-[100] flex items-center justify-center p-4"
         style="display: none;">
        <div class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl p-6 space-y-4"
             @click.outside="showPermModal = false">
            
            <div class="flex justify-between items-center select-none">
                <div>
                    <h3 class="text-sm font-extrabold text-white tracking-wide">Privilégios de Segurança</h3>
                    <p class="text-[10px] text-slate-500 font-semibold mt-0.5">Defina o escopo de atuação do usuário <strong class="text-slate-300" x-text="permUsername"></strong>.</p>
                </div>
                <button @click="showPermModal = false" class="text-slate-500 hover:text-white cursor-pointer transition">
                    <i class="material-icons text-base">close</i>
                </button>
            </div>

            <form @submit.prevent="savePermissions()" class="space-y-4">
                
                <div class="space-y-2">
                    <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block font-mono">Associações de Permissões</label>
                    <div class="max-h-56 overflow-y-auto border border-slate-800 rounded-xl divide-y divide-slate-800/40 p-2 space-y-1 bg-slate-950/40">
                        <template x-for="perm in allPermissions" :key="perm.name">
                            <label class="flex items-center justify-between p-2.5 rounded-lg hover:bg-slate-800/30 cursor-pointer">
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-xs font-bold text-slate-200" x-text="perm.name"></span>
                                    <span class="text-[9px] text-slate-500 font-semibold" x-text="perm.description"></span>
                                </div>
                                <input type="checkbox" 
                                       :value="perm.name" 
                                       x-model="permSelected"
                                       class="rounded border-slate-800 text-sky-500 focus:ring-0 bg-slate-950 h-4.5 w-4.5">
                            </label>
                        </template>
                    </div>
                </div>

                <div class="pt-2 flex justify-end gap-2 select-none">
                    <button type="button" @click="showPermModal = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-400 hover:text-white bg-slate-800/50 cursor-pointer">Cancelar</button>
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-950 bg-sky-400 hover:bg-sky-300 cursor-pointer transition">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 3. MODAL: DETALHES DO USUÁRIO -->
    <div x-show="showInfoModal" 
         x-transition
         class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-[100] flex items-center justify-center p-4"
         style="display: none;">
        <div class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl p-6 space-y-5"
             @click.outside="showInfoModal = false">
            
            <!-- Cabeçalho -->
            <div class="flex justify-between items-center select-none">
                <div class="flex items-center gap-2.5">
                    <div class="h-10 w-10 rounded-xl bg-sky-500/10 border border-sky-500/20 text-sky-400 flex items-center justify-center font-bold text-lg">
                        👤
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-white tracking-wide" x-text="infoUser.username"></h3>
                        <span class="text-[9px] text-slate-500 font-mono" x-text="'ID: #' + infoUser.id"></span>
                    </div>
                </div>
                <button @click="showInfoModal = false" class="text-slate-500 hover:text-white cursor-pointer transition">
                    <i class="material-icons text-base">close</i>
                </button>
            </div>

            <!-- Corpo -->
            <div class="space-y-4 font-sans text-xs">
                
                <!-- Status & Tags -->
                <div class="grid grid-cols-2 gap-3 p-3 bg-slate-950/40 border border-slate-800 rounded-xl select-none">
                    <div class="space-y-1">
                        <span class="text-[8px] font-extrabold text-slate-500 uppercase tracking-wider block">Status da Conta</span>
                        <template x-if="infoUser.status === 1">
                            <span class="inline-flex px-2 py-0.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[9px] rounded-full uppercase font-bold">Ativo</span>
                        </template>
                        <template x-if="infoUser.status === 0">
                            <span class="inline-flex px-2 py-0.5 bg-rose-500/10 border border-rose-500/20 text-rose-400 text-[9px] rounded-full uppercase font-bold animate-pulse">Bloqueado</span>
                        </template>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[8px] font-extrabold text-slate-500 uppercase tracking-wider block">Categorias</span>
                        <div class="flex flex-wrap gap-1">
                            <template x-if="infoUser.is_membro">
                                <span class="px-1.5 py-0.2 bg-sky-500/10 border border-sky-500/20 text-sky-400 text-[8px] font-mono rounded font-extrabold uppercase">Membro</span>
                            </template>
                            <template x-if="infoUser.is_guardiao">
                                <span class="px-1.5 py-0.2 bg-amber-500/10 border border-amber-500/20 text-amber-400 text-[8px] font-mono rounded font-extrabold uppercase">Guardião</span>
                            </template>
                            <template x-if="infoUser.is_pilar">
                                <span class="px-1.5 py-0.2 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[8px] font-mono rounded font-extrabold uppercase">Pilar</span>
                            </template>
                            <template x-if="infoUser.is_forja">
                                <span class="px-1.5 py-0.2 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-[8px] font-mono rounded font-extrabold uppercase">Forja</span>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Detalhes de Cadastro -->
                <div class="space-y-2.5">
                    <h4 class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block font-mono">Informações de Registro</h4>
                    <div class="grid grid-cols-2 gap-3 bg-slate-950/20 p-3 border border-slate-800 rounded-xl font-mono text-[10px] text-slate-300">
                        <div>
                            <span class="text-[8px] font-bold text-slate-500 uppercase tracking-wider block mb-0.5">Criado em</span>
                            <span x-text="infoUser.created_at || '-'"></span>
                        </div>
                        <div>
                            <span class="text-[8px] font-bold text-slate-500 uppercase tracking-wider block mb-0.5">Atualizado em</span>
                            <span x-text="infoUser.updated_at || '-'"></span>
                        </div>
                        <div class="col-span-2">
                            <span class="text-[8px] font-bold text-slate-500 uppercase tracking-wider block mb-0.5">IP de Registro</span>
                            <span x-text="infoUser.registration_ip || 'Não registrado'"></span>
                        </div>
                    </div>
                </div>

                <!-- Contatos / Integrações -->
                <div class="space-y-2.5">
                    <h4 class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block font-mono">Contato & Redes</h4>
                    <div class="space-y-2 bg-slate-950/20 p-3 border border-slate-800/40 rounded-xl">
                        <!-- Email -->
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="text-slate-500 font-semibold flex items-center gap-1.5">
                                <span>📧</span> Email
                            </span>
                            <template x-if="infoUser.email">
                                <a :href="'mailto:' + infoUser.email" class="text-sky-400 hover:text-sky-300 font-bold transition" x-text="infoUser.email"></a>
                            </template>
                            <template x-if="!infoUser.email">
                                <span class="text-slate-600 italic">Não informado</span>
                            </template>
                        </div>
                        <!-- Discord -->
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="text-slate-500 font-semibold flex items-center gap-1.5">
                                <span>💬</span> Discord
                            </span>
                            <template x-if="infoUser.discord">
                                <span class="text-indigo-400 font-bold" x-text="infoUser.discord"></span>
                            </template>
                            <template x-if="!infoUser.discord">
                                <span class="text-slate-600 italic">Não informado</span>
                            </template>
                        </div>
                        <!-- WhatsApp -->
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="text-slate-500 font-semibold flex items-center gap-1.5">
                                <span>📱</span> WhatsApp
                            </span>
                            <template x-if="infoUser.whatsapp">
                                <span class="text-emerald-400 font-bold" x-text="infoUser.whatsapp"></span>
                            </template>
                            <template x-if="!infoUser.whatsapp">
                                <span class="text-slate-600 italic">Não informado</span>
                            </template>
                        </div>
                        <!-- GitHub -->
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="text-slate-500 font-semibold flex items-center gap-1.5">
                                <span>💻</span> GitHub
                            </span>
                            <template x-if="infoUser.github">
                                <a :href="'https://github.com/' + infoUser.github" target="_blank" class="text-white hover:text-slate-300 font-bold transition flex items-center gap-0.5" x-text="'@' + infoUser.github"></a>
                            </template>
                            <template x-if="!infoUser.github">
                                <span class="text-slate-600 italic">Não informado</span>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Privilégios RBAC -->
                <div class="space-y-2">
                    <h4 class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block font-mono">Privilégios de Segurança (RBAC)</h4>
                    <div class="flex flex-wrap gap-1 p-2 border border-slate-800/40 rounded-xl bg-slate-950/20">
                        <template x-for="perm in infoUser.permissions" :key="perm">
                            <span class="px-2 py-0.5 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-[9px] font-mono rounded-md" x-text="perm"></span>
                        </template>
                        <template x-if="!infoUser.permissions || infoUser.permissions.length === 0">
                            <span class="text-slate-600 font-normal italic text-[10px] p-1">Nenhum privilégio de segurança associado</span>
                        </template>
                    </div>
                </div>

            </div>

            <!-- Rodapé -->
            <div class="pt-2 flex justify-end select-none">
                <button type="button" @click="showInfoModal = false" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl text-xs font-bold transition cursor-pointer">
                    Fechar
                </button>
            </div>
        </div>
    </div>

</div>
