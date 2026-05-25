<?php
/** @var app\modules\tickets\models\SupportTicket $ticket */

use yii\helpers\Url;
use yii\helpers\Html;

$userId = (int)Yii::$app->user->id;
?>

<!-- TIMELINE DE MENSAGENS -->
<div class="flex-grow overflow-y-auto p-6 space-y-4 min-h-[350px] max-h-[480px] scrollbar-thin scroll-smooth" id="chat-messages-timeline">
    <?php if (empty($ticket->messages)): ?>
        <div class="h-full flex items-center justify-center text-slate-500 font-semibold italic text-xs select-none">
             O chat está aberto. Inicie o diálogo de alinhamento técnico aqui.
        </div>
    <?php else: ?>
        <?php foreach ($ticket->messages as $msg): ?>
            <?php
            $isMe = $msg->user_id === $userId;
            $isSystem = str_contains($msg->message, 'Mensagem do Sistema:');
            
            if ($isSystem): ?>
                <!-- Mensagem de Auditoria do Sistema -->
                <div class="flex justify-center my-2 select-none animate-fade-in">
                    <div class="px-3.5 py-1.5 bg-slate-900/60 border border-slate-800 text-[10px] font-mono rounded-xl text-slate-400 font-bold flex items-center gap-1.5 shadow-md">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                        <span><?= Html::encode($msg->message) ?></span>
                    </div>
                </div>
            <?php else: ?>
                <!-- Balão de Chat Normal -->
                <div class="flex <?= $isMe ? 'justify-end' : 'justify-start' ?> animate-fade-in">
                    <div class="max-w-[75%] space-y-1">
                        <!-- Nome e Hora -->
                        <div class="flex items-center gap-1.5 text-[9px] font-bold text-slate-500 select-none <?= $isMe ? 'justify-end' : 'justify-start' ?>">
                            <span class="text-slate-400">@<?= Html::encode($msg->user->username) ?></span>
                            <span>&bull;</span>
                            <span><?= date('H:i', $msg->created_at) ?></span>
                        </div>
                        <!-- Balão com efeito Glass -->
                        <div class="px-4 py-2.5 rounded-2xl text-xs font-medium leading-relaxed shadow-lg
                            <?= $isMe 
                                ? 'bg-gradient-to-r from-sky-600 to-indigo-600 text-white rounded-tr-none' 
                                : 'bg-slate-900 border border-slate-800 text-slate-200 rounded-tl-none' ?>">
                            <p class="whitespace-pre-wrap font-sans"><?= Html::encode($msg->message) ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- CAIXA DE DIGITAÇÃO / AVISO DE FECHAMENTO -->
<div class="p-4 border-t border-slate-900 bg-slate-950/60 select-none">
    <?php if ($ticket->status === \app\modules\tickets\models\SupportTicket::STATUS_CLOSED): ?>
        <div class="p-3 bg-emerald-500/5 border border-emerald-500/10 rounded-2xl flex items-center justify-center gap-2 text-emerald-400 font-mono text-[10px] font-bold">
            <i class="material-icons text-sm">lock</i>
            TICKET RESOLVIDO — ESTE CHAT FOI CONCLUÍDO E ARQUIVADO.
        </div>
    <?php else: ?>
        <form hx-post="<?= Url::to(['/tickets/default/send-message', 'id' => $ticket->id]) ?>"
              hx-target="#chat-messages-timeline-wrapper"
              hx-swap="innerHTML"
              hx-on::after-request="this.reset(); const el = document.getElementById('chat-messages-timeline'); if (el) { el.scrollTop = el.scrollHeight; }"
              class="flex gap-2">
            
            <input type="text"
                   name="message"
                   required
                   autocomplete="off"
                   placeholder="Digite uma mensagem para o alinhamento técnico..."
                   class="flex-grow bg-slate-950 border border-slate-800/80 focus:border-sky-500/50 text-slate-200 placeholder-slate-600 rounded-xl px-4 py-3 text-xs outline-none focus:ring-0 transition duration-200 font-medium">
            
            <button type="submit"
                    class="px-5 py-3 bg-sky-600 hover:bg-sky-500 text-white rounded-xl text-xs font-bold transition flex items-center gap-1 cursor-pointer shadow-lg shadow-sky-600/15">
                <i class="material-icons text-base">send</i>
                Enviar
            </button>
        </form>
    <?php endif; ?>
</div>

<!-- Scroll automático preventivo ao renderizar fragmento -->
<script>
    (function() {
        const el = document.getElementById('chat-messages-timeline');
        if (el) {
            el.scrollTop = el.scrollHeight;
        }
    })();
</script>
