<?php
/** @var yii\web\View $this */
/** @var app\modules\tickets\models\SupportTicket[] $ticketsAvailable */
/** @var app\modules\tickets\models\SupportTicket[] $myCreatedTickets */
/** @var app\modules\tickets\models\SupportTicket[] $myAssignedTickets */
/** @var app\modules\tickets\models\SupportTicket[] $allTickets */

use yii\helpers\Url;
use yii\helpers\Html;
use app\modules\tickets\models\SupportTicket;
?>

<div class="space-y-6"
     x-data="{
        activeSubTab: 'available',
        showCreateModal: false,
        ticketType: 'idea',
        reqGuardiao: false,
        reqPilar: false,
        reqForja: false,
        init() {
            // Escuta eventos globais HTMX para recarregar ou fechar modals
            document.body.addEventListener('ticketCreated', () => {
                this.showCreateModal = false;
                this.reqGuardiao = false;
                this.reqPilar = false;
                this.reqForja = false;
            });
        }
     }">

    <!-- Cabeçalho Principal -->
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 select-none">
        <div class="space-y-1">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-sky-500/10 border border-sky-500/20 text-sky-400 text-[10px] font-mono font-bold tracking-widest uppercase rounded-full">
                <span class="w-1.5 h-1.5 rounded-full bg-sky-400 animate-pulse"></span>
                Suporte & Governança Modular
            </div>
            <h2 class="text-xl font-extrabold text-white tracking-tight">Central de Tickets</h2>
            <p class="text-[10px] text-slate-500 font-semibold leading-relaxed max-w-xl">
                Crie e assuma tarefas técnicas, ideias ou correções do ecossistema CROM de acordo com seu escopo de tags.
            </p>
        </div>
        <div>
            <button @click="showCreateModal = true"
                    class="w-full sm:w-auto px-4 py-2.5 bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer shadow-lg shadow-sky-600/15 select-none">
                <i class="material-icons text-base">add_circle</i>
                Criar Novo Ticket
            </button>
        </div>
    </div>

    <!-- Navegação de Sub-Abas -->
    <div class="flex border-b border-slate-800/80 gap-2 select-none text-xs font-bold">
        <button @click="activeSubTab = 'available'"
                class="pb-3 px-4 border-b-2 transition duration-200 cursor-pointer flex items-center gap-1.5"
                :class="activeSubTab === 'available' ? 'border-sky-500 text-sky-400' : 'border-transparent text-slate-500 hover:text-slate-300'">
            <i class="material-icons text-base">explore</i>
            Tickets Disponíveis
            <span class="px-1.5 py-0.2 bg-slate-900 border border-slate-800 text-[9px] font-mono rounded-full text-slate-400 font-bold"><?= count($ticketsAvailable) ?></span>
        </button>
        <button @click="activeSubTab = 'created'"
                class="pb-3 px-4 border-b-2 transition duration-200 cursor-pointer flex items-center gap-1.5"
                :class="activeSubTab === 'created' ? 'border-sky-500 text-sky-400' : 'border-transparent text-slate-500 hover:text-slate-300'">
            <i class="material-icons text-base">create</i>
            Criados por Mim
            <span class="px-1.5 py-0.2 bg-slate-900 border border-slate-800 text-[9px] font-mono rounded-full text-slate-400 font-bold"><?= count($myCreatedTickets) ?></span>
        </button>
        <button @click="activeSubTab = 'assigned'"
                class="pb-3 px-4 border-b-2 transition duration-200 cursor-pointer flex items-center gap-1.5"
                :class="activeSubTab === 'assigned' ? 'border-sky-500 text-sky-400' : 'border-transparent text-slate-500 hover:text-slate-300'">
            <i class="material-icons text-base">assignment_ind</i>
            Assumidos por Mim
            <span class="px-1.5 py-0.2 bg-slate-900 border border-slate-800 text-[9px] font-mono rounded-full text-slate-400 font-bold"><?= count($myAssignedTickets) ?></span>
        </button>
        <button @click="activeSubTab = 'all'"
                class="pb-3 px-4 border-b-2 transition duration-200 cursor-pointer flex items-center gap-1.5"
                :class="activeSubTab === 'all' ? 'border-sky-500 text-sky-400' : 'border-transparent text-slate-500 hover:text-slate-300'">
            <i class="material-icons text-base">list_alt</i>
            Todos os Tickets
            <span class="px-1.5 py-0.2 bg-slate-900 border border-slate-800 text-[9px] font-mono rounded-full text-slate-400 font-bold"><?= count($allTickets) ?></span>
        </button>
    </div>

    <!-- LISTAS DE TICKETS -->
    <div class="space-y-4">
        
        <!-- 1. ABA: TICKETS DISPONÍVEIS -->
        <div x-show="activeSubTab === 'available'" class="space-y-4">
            <?php if (empty($ticketsAvailable)): ?>
                <div class="py-12 border border-slate-800/80 rounded-2xl bg-slate-950/20 text-center select-none font-medium">
                    <span class="text-3xl block mb-2">🔍</span>
                    <h4 class="text-xs text-slate-400">Nenhum ticket disponível para seu perfil técnico</h4>
                    <p class="text-[10px] text-slate-600 mt-1">Todos os tickets foram assumidos ou exigem tags que você não possui no momento.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($ticketsAvailable as $ticket): ?>
                        <div class="border border-slate-800/80 rounded-2xl p-5 bg-slate-950/40 backdrop-blur-md hover:border-slate-700/60 transition duration-300 flex flex-col justify-between group">
                            <div class="space-y-4">
                                <div class="flex justify-between items-start gap-2 select-none">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded text-[8px] font-extrabold font-mono tracking-wide uppercase border 
                                            <?= $ticket->type === 'bug_fix' ? 'bg-rose-500/10 border-rose-500/20 text-rose-400' : 
                                               ($ticket->type === 'idea' ? 'bg-purple-500/10 border-purple-500/20 text-purple-400' : 
                                               'bg-sky-500/10 border-sky-500/20 text-sky-400') ?>">
                                            <?= $ticket->getTypeLabel() ?>
                                        </span>
                                    </div>
                                    <div class="flex gap-1 select-none">
                                        <?php if ($ticket->req_guardiao): ?>
                                            <span class="px-1.5 py-0.5 bg-amber-500/10 border border-amber-500/20 text-amber-400 text-[8px] font-mono rounded font-extrabold uppercase">Guardião</span>
                                        <?php endif; ?>
                                        <?php if ($ticket->req_pilar): ?>
                                            <span class="px-1.5 py-0.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[8px] font-mono rounded font-extrabold uppercase">Pilar</span>
                                        <?php endif; ?>
                                        <?php if ($ticket->req_forja): ?>
                                            <span class="px-1.5 py-0.5 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-[8px] font-mono rounded font-extrabold uppercase">Forja</span>
                                        <?php endif; ?>
                                        <?php if (!$ticket->req_guardiao && !$ticket->req_pilar && !$ticket->req_forja): ?>
                                            <span class="px-1.5 py-0.5 bg-slate-800 text-slate-400 text-[8px] font-mono rounded font-bold uppercase">Qualquer um</span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <h4 class="text-sm font-extrabold text-white tracking-wide" id="t-title-<?= $ticket->id ?>"><?= Html::encode($ticket->title) ?></h4>
                                    <p class="text-[10px] text-slate-500 font-semibold font-mono">
                                        Criado por: <strong class="text-slate-400">@<?= Html::encode($ticket->creator->username) ?></strong> &bull; <?= date('d/m/Y H:i', $ticket->created_at) ?>
                                    </p>
                                    <p class="text-xs text-slate-400 font-medium leading-relaxed pt-2 line-clamp-3">
                                        <?= Html::encode($ticket->description) ?>
                                    </p>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end select-none pt-4 border-t border-slate-900">
                                <button hx-post="<?= Url::to(['/tickets/default/take', 'id' => $ticket->id]) ?>"
                                        hx-target="#container-tickets"
                                        hx-swap="innerHTML"
                                        class="px-4 py-1.5 bg-sky-600 hover:bg-sky-500 text-white rounded-xl text-[10px] font-bold transition flex items-center gap-1 cursor-pointer">
                                    <i class="material-icons text-xs">add_box</i>
                                    Pegar Ticket
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- 2. ABA: MEUS TICKETS CRIADOS -->
        <div x-show="activeSubTab === 'created'" class="space-y-4" style="display: none;">
            <?php if (empty($myCreatedTickets)): ?>
                <div class="py-12 border border-slate-800/80 rounded-2xl bg-slate-950/20 text-center select-none font-medium">
                    <span class="text-3xl block mb-2">📋</span>
                    <h4 class="text-xs text-slate-400">Você ainda não criou nenhum ticket de suporte</h4>
                    <p class="text-[10px] text-slate-600 mt-1">Crie tickets para reportar bugs, sugerir ideias ou propor novos projetos de governança.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($myCreatedTickets as $ticket): ?>
                        <div class="border border-slate-800/80 rounded-2xl p-5 bg-slate-950/40 backdrop-blur-md hover:border-slate-700/60 transition duration-300 flex flex-col justify-between group">
                            <div class="space-y-4">
                                <div class="flex justify-between items-start gap-2 select-none">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded text-[8px] font-extrabold font-mono tracking-wide uppercase border 
                                            <?= $ticket->type === 'bug_fix' ? 'bg-rose-500/10 border-rose-500/20 text-rose-400' : 
                                               ($ticket->type === 'idea' ? 'bg-purple-500/10 border-purple-500/20 text-purple-400' : 
                                               'bg-sky-500/10 border-sky-500/20 text-sky-400') ?>">
                                            <?= $ticket->getTypeLabel() ?>
                                        </span>
                                        <?= $ticket->getStatusBadge() ?>
                                    </div>
                                    <div class="flex gap-1 select-none">
                                        <?php if ($ticket->req_guardiao): ?>
                                            <span class="px-1.5 py-0.5 bg-amber-500/10 border border-amber-500/20 text-amber-400 text-[8px] font-mono rounded font-extrabold uppercase">Guardião</span>
                                        <?php endif; ?>
                                        <?php if ($ticket->req_pilar): ?>
                                            <span class="px-1.5 py-0.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[8px] font-mono rounded font-extrabold uppercase">Pilar</span>
                                        <?php endif; ?>
                                        <?php if ($ticket->req_forja): ?>
                                            <span class="px-1.5 py-0.5 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-[8px] font-mono rounded font-extrabold uppercase">Forja</span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <h4 class="text-sm font-extrabold text-white tracking-wide"><?= Html::encode($ticket->title) ?></h4>
                                    <p class="text-[10px] text-slate-500 font-semibold font-mono">
                                        Assumido por: 
                                        <strong class="text-slate-400">
                                            <?= $ticket->assignee ? '@' . Html::encode($ticket->assignee->username) : 'Ninguém ainda' ?>
                                        </strong> &bull; Criado em: <?= date('d/m/Y H:i', $ticket->created_at) ?>
                                    </p>
                                    <p class="text-xs text-slate-400 font-medium leading-relaxed pt-2 line-clamp-3">
                                        <?= Html::encode($ticket->description) ?>
                                    </p>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-between items-center select-none pt-4 border-t border-slate-900">
                                <div>
                                    <?php if ($ticket->status !== SupportTicket::STATUS_CLOSED): ?>
                                        <button hx-post="<?= Url::to(['/tickets/default/close', 'id' => $ticket->id]) ?>"
                                                hx-target="#container-tickets"
                                                hx-swap="innerHTML"
                                                class="px-3 py-1.5 bg-rose-500/10 border border-rose-500/20 hover:bg-rose-500/20 text-rose-400 rounded-xl text-[10px] font-bold transition flex items-center gap-1 cursor-pointer">
                                            <i class="material-icons text-xs">close</i>
                                            Fechar Ticket
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <button hx-get="<?= Url::to(['/tickets/default/view', 'id' => $ticket->id]) ?>"
                                        hx-target="#container-tickets"
                                        hx-swap="innerHTML"
                                        class="px-4 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white rounded-xl text-[10px] font-bold transition flex items-center gap-1.5 cursor-pointer">
                                    <i class="material-icons text-xs">chat</i>
                                    Abrir Chat
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- 3. ABA: MEUS TICKETS PEGOS (ASSUMIDOS) -->
        <div x-show="activeSubTab === 'assigned'" class="space-y-4" style="display: none;">
            <?php if (empty($myAssignedTickets)): ?>
                <div class="py-12 border border-slate-800/80 rounded-2xl bg-slate-950/20 text-center select-none font-medium">
                    <span class="text-3xl block mb-2">🤝</span>
                    <h4 class="text-xs text-slate-400">Você não assumiu nenhum ticket de outro membro</h4>
                    <p class="text-[10px] text-slate-600 mt-1">Navegue nos tickets disponíveis e assuma projetos ou correções técnicas para contribuir.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($myAssignedTickets as $ticket): ?>
                        <div class="border border-slate-800/80 rounded-2xl p-5 bg-slate-950/40 backdrop-blur-md hover:border-slate-700/60 transition duration-300 flex flex-col justify-between group">
                            <div class="space-y-4">
                                <div class="flex justify-between items-start gap-2 select-none">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded text-[8px] font-extrabold font-mono tracking-wide uppercase border 
                                            <?= $ticket->type === 'bug_fix' ? 'bg-rose-500/10 border-rose-500/20 text-rose-400' : 
                                               ($ticket->type === 'idea' ? 'bg-purple-500/10 border-purple-500/20 text-purple-400' : 
                                               'bg-sky-500/10 border-sky-500/20 text-sky-400') ?>">
                                            <?= $ticket->getTypeLabel() ?>
                                        </span>
                                        <?= $ticket->getStatusBadge() ?>
                                    </div>
                                    <div class="flex gap-1 select-none">
                                        <?php if ($ticket->req_guardiao): ?>
                                            <span class="px-1.5 py-0.5 bg-amber-500/10 border border-amber-500/20 text-amber-400 text-[8px] font-mono rounded font-extrabold uppercase">Guardião</span>
                                        <?php endif; ?>
                                        <?php if ($ticket->req_pilar): ?>
                                            <span class="px-1.5 py-0.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[8px] font-mono rounded font-extrabold uppercase">Pilar</span>
                                        <?php endif; ?>
                                        <?php if ($ticket->req_forja): ?>
                                            <span class="px-1.5 py-0.5 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-[8px] font-mono rounded font-extrabold uppercase">Forja</span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <h4 class="text-sm font-extrabold text-white tracking-wide"><?= Html::encode($ticket->title) ?></h4>
                                    <p class="text-[10px] text-slate-500 font-semibold font-mono">
                                        Criador: <strong class="text-slate-400">@<?= Html::encode($ticket->creator->username) ?></strong> &bull; Atualizado em: <?= date('d/m/Y H:i', $ticket->updated_at) ?>
                                    </p>
                                    <p class="text-xs text-slate-400 font-medium leading-relaxed pt-2 line-clamp-3">
                                        <?= Html::encode($ticket->description) ?>
                                    </p>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-between items-center select-none pt-4 border-t border-slate-900">
                                <div>
                                    <?php if ($ticket->status !== SupportTicket::STATUS_CLOSED): ?>
                                        <button hx-post="<?= Url::to(['/tickets/default/close', 'id' => $ticket->id]) ?>"
                                                hx-target="#container-tickets"
                                                hx-swap="innerHTML"
                                                class="px-3 py-1.5 bg-rose-500/10 border border-rose-500/20 hover:bg-rose-500/20 text-rose-400 rounded-xl text-[10px] font-bold transition flex items-center gap-1 cursor-pointer">
                                            <i class="material-icons text-xs">done_all</i>
                                            Marcar Resolvido
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <button hx-get="<?= Url::to(['/tickets/default/view', 'id' => $ticket->id]) ?>"
                                        hx-target="#container-tickets"
                                        hx-swap="innerHTML"
                                        class="px-4 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white rounded-xl text-[10px] font-bold transition flex items-center gap-1.5 cursor-pointer">
                                    <i class="material-icons text-xs">chat</i>
                                    Abrir Chat
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- 4. ABA: TODOS OS TICKETS (GERAL) -->
        <div x-show="activeSubTab === 'all'" class="space-y-4" style="display: none;">
            <?php if (empty($allTickets)): ?>
                <div class="py-12 border border-slate-800/80 rounded-2xl bg-slate-950/20 text-center select-none font-medium">
                    <span class="text-3xl block mb-2">📋</span>
                    <h4 class="text-xs text-slate-400">Nenhum ticket foi criado no portal ainda</h4>
                    <p class="text-[10px] text-slate-600 mt-1">Crie um novo ticket usando o botão superior para iniciar.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($allTickets as $ticket): ?>
                        <div class="border border-slate-800/80 rounded-2xl p-5 bg-slate-950/40 backdrop-blur-md hover:border-slate-700/60 transition duration-300 flex flex-col justify-between group">
                            <div class="space-y-4">
                                <div class="flex justify-between items-start gap-2 select-none">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded text-[8px] font-extrabold font-mono tracking-wide uppercase border 
                                            <?= $ticket->type === 'bug_fix' ? 'bg-rose-500/10 border-rose-500/20 text-rose-400' : 
                                               ($ticket->type === 'idea' ? 'bg-purple-500/10 border-purple-500/20 text-purple-400' : 
                                               'bg-sky-500/10 border-sky-500/20 text-sky-400') ?>">
                                            <?= $ticket->getTypeLabel() ?>
                                        </span>
                                        <?= $ticket->getStatusBadge() ?>
                                    </div>
                                    <div class="flex gap-1 select-none">
                                        <?php if ($ticket->req_guardiao): ?>
                                            <span class="px-1.5 py-0.5 bg-amber-500/10 border border-amber-500/20 text-amber-400 text-[8px] font-mono rounded font-extrabold uppercase">Guardião</span>
                                        <?php endif; ?>
                                        <?php if ($ticket->req_pilar): ?>
                                            <span class="px-1.5 py-0.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[8px] font-mono rounded font-extrabold uppercase">Pilar</span>
                                        <?php endif; ?>
                                        <?php if ($ticket->req_forja): ?>
                                            <span class="px-1.5 py-0.5 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-[8px] font-mono rounded font-extrabold uppercase">Forja</span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <h4 class="text-sm font-extrabold text-white tracking-wide"><?= Html::encode($ticket->title) ?></h4>
                                    <p class="text-[10px] text-slate-500 font-semibold font-mono">
                                        Criador: <strong class="text-slate-400">@<?= Html::encode($ticket->creator->username) ?></strong> 
                                        &bull; Responsável: <strong class="text-slate-400"><?= $ticket->assignee ? '@' . Html::encode($ticket->assignee->username) : 'Ninguém' ?></strong>
                                    </p>
                                    <p class="text-xs text-slate-400 font-medium leading-relaxed pt-2 line-clamp-3">
                                        <?= Html::encode($ticket->description) ?>
                                    </p>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-between items-center select-none pt-4 border-t border-slate-900">
                                <div>
                                    <!-- Apenas os envolvidos no ticket podem fechar -->
                                    <?php if ($ticket->status !== SupportTicket::STATUS_CLOSED && ($ticket->created_by === $userId || $ticket->assigned_to === $userId)): ?>
                                        <button hx-post="<?= Url::to(['/tickets/default/close', 'id' => $ticket->id]) ?>"
                                                hx-target="#container-tickets"
                                                hx-swap="innerHTML"
                                                class="px-3 py-1.5 bg-rose-500/10 border border-rose-500/20 hover:bg-rose-500/20 text-rose-400 rounded-xl text-[10px] font-bold transition flex items-center gap-1 cursor-pointer">
                                            <i class="material-icons text-xs">close</i>
                                            Fechar Ticket
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <button hx-get="<?= Url::to(['/tickets/default/view', 'id' => $ticket->id]) ?>"
                                        hx-target="#container-tickets"
                                        hx-swap="innerHTML"
                                        class="px-4 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white rounded-xl text-[10px] font-bold transition flex items-center gap-1.5 cursor-pointer">
                                    <i class="material-icons text-xs">chat</i>
                                    Abrir Chat
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <!-- 1. MODAL: CRIAR TICKET -->
    <div x-show="showCreateModal"
         x-transition
         class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-[100] flex items-center justify-center p-4"
         style="display: none;">
        
        <div class="w-full max-w-lg bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl p-6 space-y-4"
             @click.outside="showCreateModal = false">
            
            <div class="flex justify-between items-center select-none">
                <div class="flex items-center gap-1.5">
                    <span class="text-lg">🎟️</span>
                    <h3 class="text-sm font-extrabold text-white tracking-wide">Criar Novo Ticket de Suporte</h3>
                </div>
                <button @click="showCreateModal = false" class="text-slate-500 hover:text-white cursor-pointer transition">
                    <i class="material-icons text-base">close</i>
                </button>
            </div>

            <form hx-post="<?= Url::to(['/tickets/default/create']) ?>"
                  hx-target="#container-tickets"
                  hx-swap="innerHTML"
                  class="space-y-4 text-left">
                
                <div class="space-y-1.5">
                    <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest font-mono">Título do Ticket</label>
                    <input type="text"
                           name="title"
                           required
                           placeholder="Ex: Corrigir erro de conexão SSH do terminal"
                           class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-4 py-2.5 text-white outline-none font-sans font-semibold transition-all">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest font-mono">Tipo de Ticket</label>
                        <select name="type"
                                x-model="ticketType"
                                class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 text-xs rounded-xl px-3 py-2.5 text-white outline-none font-semibold transition-all">
                            <option value="idea">💡 Ideia / Sugestão</option>
                            <option value="bug_fix">🛠️ Correção / Bugfix</option>
                            <option value="project">🚀 Projeto Técnico</option>
                            <option value="other">📦 Outro</option>
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest font-mono">Segurança & Restrição</label>
                        <div class="text-[9px] text-slate-500 font-semibold leading-relaxed mt-1">
                            Marque abaixo quais membros têm competência/permissão para assumir este ticket. Se nenhum for marcado, será aberto a todos.
                        </div>
                    </div>
                </div>

                <!-- Checkboxes Premium de Tags necessárias -->
                <div class="p-3 bg-slate-950/40 border border-slate-800 rounded-xl flex items-center justify-between gap-4 select-none">
                    <label class="flex items-center gap-2 cursor-pointer p-1">
                        <input type="checkbox" name="req_guardiao" x-model="reqGuardiao" class="rounded border-slate-800 text-sky-500 bg-slate-950 h-4.5 w-4.5">
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-slate-200">Guardião</span>
                            <span class="text-[8px] text-slate-500 font-semibold">Infraestrutura Central</span>
                        </div>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer p-1">
                        <input type="checkbox" name="req_pilar" x-model="reqPilar" class="rounded border-slate-800 text-sky-500 bg-slate-950 h-4.5 w-4.5">
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-slate-200">Pilar</span>
                            <span class="text-[8px] text-slate-500 font-semibold">Garante do Ecossistema</span>
                        </div>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer p-1">
                        <input type="checkbox" name="req_forja" x-model="reqForja" class="rounded border-slate-800 text-sky-500 bg-slate-950 h-4.5 w-4.5">
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-slate-200">Forja</span>
                            <span class="text-[8px] text-slate-500 font-semibold">Desenvolvimento de Apps</span>
                        </div>
                    </label>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest font-mono">Descrição Detalhada</label>
                    <textarea name="description"
                              rows="5"
                              required
                              placeholder="Descreva o escopo técnico do ticket, os passos para reproduzir o problema ou a especificação da ideia..."
                              class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-4 py-2.5 text-white outline-none font-sans font-semibold transition-all resize-none"></textarea>
                </div>

                <div class="pt-2 flex justify-end gap-2 select-none">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-400 hover:text-white bg-slate-800/50 cursor-pointer">Cancelar</button>
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-950 bg-sky-400 hover:bg-sky-300 cursor-pointer transition">
                        Criar Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
