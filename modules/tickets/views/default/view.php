<?php
/** @var yii\web\View $this */
/** @var app\modules\tickets\models\SupportTicket $ticket */

use yii\helpers\Url;
use yii\helpers\Html;
?>

<div class="space-y-6">
    
    <!-- Header de Navegação -->
    <div class="flex items-center justify-between select-none">
        <button hx-get="<?= Url::to(['/tickets/default/index']) ?>"
                hx-target="#container-tickets"
                hx-swap="innerHTML"
                class="px-4 py-2 border border-slate-800 bg-slate-900/60 hover:bg-slate-850 hover:border-slate-700 text-slate-300 hover:text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer">
            <i class="material-icons text-base">arrow_back</i>
            Voltar para Tickets
        </button>
        
        <div class="flex items-center gap-2">
            <span class="text-[10px] text-slate-500 font-mono font-bold">TICKET ID: #<?= $ticket->id ?></span>
        </div>
    </div>

    <!-- Layout Dividido (2 colunas: Chat à esquerda, Metadados à direita) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <!-- COLUNA 1 & 2: CONTAINER DO CHAT -->
        <div class="lg:col-span-2 border border-slate-800/80 rounded-2xl overflow-hidden bg-slate-950/40 backdrop-blur-md flex flex-col justify-between"
             id="chat-messages-timeline-wrapper">
             
             <?= $this->render('_chat', ['ticket' => $ticket]) ?>
             
        </div>

        <!-- COLUNA 3: METADADOS & AÇÕES DO TICKET -->
        <div class="border border-slate-800/80 rounded-2xl p-5 bg-slate-950/40 backdrop-blur-md space-y-6 text-left">
            
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="px-2 py-0.5 rounded text-[8px] font-extrabold font-mono tracking-wide uppercase border 
                        <?= $ticket->type === 'bug_fix' ? 'bg-rose-500/10 border-rose-500/20 text-rose-400' : 
                           ($ticket->type === 'idea' ? 'bg-purple-500/10 border-purple-500/20 text-purple-400' : 
                           'bg-sky-500/10 border-sky-500/20 text-sky-400') ?>">
                        <?= $ticket->getTypeLabel() ?>
                    </span>
                    <?= $ticket->getStatusBadge() ?>
                </div>
                <h3 class="text-base font-extrabold text-white tracking-wide"><?= Html::encode($ticket->title) ?></h3>
                <p class="text-[10px] text-slate-500 font-mono font-bold">
                    Criado em: <?= date('d/m/Y \à\s H:i', $ticket->created_at) ?>
                </p>
            </div>

            <div class="space-y-1 pt-2 border-t border-slate-900">
                <h4 class="text-[9px] font-extrabold text-slate-500 uppercase tracking-widest font-mono">Descrição do Ticket</h4>
                <div class="text-xs text-slate-300 leading-relaxed font-sans max-h-36 overflow-y-auto scrollbar-thin whitespace-pre-wrap pt-1 font-medium">
                    <?= Html::encode($ticket->description) ?>
                </div>
            </div>

            <!-- Equipe Envolvida -->
            <div class="space-y-3 pt-4 border-t border-slate-900 select-none">
                <h4 class="text-[9px] font-extrabold text-slate-500 uppercase tracking-widest font-mono">Governança Envolvida</h4>
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-sky-500/10 text-sky-400 flex items-center justify-center font-bold text-[9px] border border-sky-500/20">
                            <?= strtoupper(substr($ticket->creator->username, 0, 2)) ?>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-slate-300">@<?= Html::encode($ticket->creator->username) ?></span>
                            <span class="text-[8px] text-slate-500 font-mono uppercase">Criador</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-indigo-500/10 text-indigo-400 flex items-center justify-center font-bold text-[9px] border border-indigo-500/20">
                            <?= $ticket->assignee ? strtoupper(substr($ticket->assignee->username, 0, 2)) : '?' ?>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-slate-300">
                                <?= $ticket->assignee ? '@' . Html::encode($ticket->assignee->username) : 'Aguardando responsável...' ?>
                            </span>
                            <span class="text-[8px] text-slate-500 font-mono uppercase">Responsável</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Requisitos de Tags -->
            <div class="space-y-2 pt-4 border-t border-slate-900 select-none">
                <h4 class="text-[9px] font-extrabold text-slate-500 uppercase tracking-widest font-mono">Restrição de Acesso</h4>
                <div class="flex flex-wrap gap-1.5 pt-1">
                    <?php if ($ticket->req_guardiao): ?>
                        <span class="px-2 py-0.5 bg-amber-500/10 border border-amber-500/20 text-amber-400 text-[8px] font-mono rounded font-extrabold uppercase shadow-sm">Guardião</span>
                    <?php endif; ?>
                    <?php if ($ticket->req_pilar): ?>
                        <span class="px-2 py-0.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[8px] font-mono rounded font-extrabold uppercase shadow-sm">Pilar</span>
                    <?php endif; ?>
                    <?php if ($ticket->req_forja): ?>
                        <span class="px-2 py-0.5 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-[8px] font-mono rounded font-extrabold uppercase shadow-sm">Forja</span>
                    <?php endif; ?>
                    <?php if (!$ticket->req_guardiao && !$ticket->req_pilar && !$ticket->req_forja): ?>
                        <span class="px-2 py-0.5 bg-slate-800 text-slate-400 text-[8px] font-mono rounded font-bold uppercase shadow-sm">Livre para Todos</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Ações rápidas de fechamento -->
            <?php if ($ticket->status !== \app\modules\tickets\models\SupportTicket::STATUS_CLOSED): ?>
                <div class="pt-4 border-t border-slate-900 select-none">
                    <button hx-post="<?= Url::to(['/tickets/default/close', 'id' => $ticket->id]) ?>"
                            hx-target="#container-tickets"
                            hx-swap="innerHTML"
                            class="w-full py-2.5 px-4 bg-rose-600 hover:bg-rose-500 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer shadow-lg shadow-rose-600/15">
                        <i class="material-icons text-base">check_circle</i>
                        Resolver & Fechar Ticket
                    </button>
                </div>
            <?php endif; ?>

        </div>
        
    </div>

</div>
