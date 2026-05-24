<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var array $messages */
/** @var int $userId */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<!-- Loop de Mensagens formatado em balões WhatsApp Style -->
<?php if (empty($messages)): ?>
    <div class="flex items-center justify-center h-full text-slate-500 text-[11px] font-semibold italic">
        Nenhuma mensagem nesta conversa. Envie uma mensagem para começar!
    </div>
<?php else: ?>
    <div class="space-y-3.5 flex flex-col justify-end min-h-full">
        <?php foreach ($messages as $index => $msg): ?>
            
            <!-- 1. MENSAGEM DE SISTEMA -->
            <?php if ($msg['is_system']): ?>
                <div class="flex justify-center select-none">
                    <span class="bg-slate-900/60 border border-slate-800 text-slate-400 font-mono text-[9px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                        <?= Html::encode($msg['message']) ?>
                    </span>
                </div>
            <?php else: ?>
                
                <?php 
                $isOwn = ((int)$msg['sender_id'] === (int)$userId); 
                
                // Tratamento especial para convite de grupo estruturado
                $isGroupInvite = (strpos((string)$msg['message'], '[GROUP_INVITE]::') === 0);
                ?>
                
                <!-- 2. BALÃO DE MENSAGEM TRADICIONAL OU CARD DE CONVITE -->
                <div class="flex w-full <?= $isOwn ? 'justify-end' : 'justify-start' ?>">
                    
                    <?php if ($isGroupInvite): 
                        // Parse do convite estruturado: [GROUP_INVITE]::groupId::inviteCode::groupName
                        $parts = explode('::', (string)$msg['message']);
                        $inviteGroupId = $parts[1] ?? '';
                        $inviteCode = $parts[2] ?? '';
                        $inviteGroupName = $parts[3] ?? 'Grupo';
                    ?>
                        <!-- Card Glassmorphic de Convite de Grupo -->
                        <div class="max-w-xs md:max-w-sm rounded-2xl p-4 border shadow-2xl flex flex-col gap-3 backdrop-blur-md relative overflow-hidden group transition-all duration-300
                                    <?= $isOwn ? 'bg-sky-500/10 border-sky-500/30 text-sky-200' : 'bg-slate-900 border-slate-800 text-slate-100' ?>">
                            <div class="absolute -right-6 -bottom-6 w-14 h-14 bg-emerald-500/5 rounded-full blur-md group-hover:scale-125 transition-all duration-500"></div>
                            
                            <div class="flex items-center gap-2">
                                <span class="text-xs">✉️</span>
                                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Convite de Grupo</span>
                            </div>

                            <div class="space-y-1">
                                <p class="text-xs font-semibold leading-relaxed">
                                    Você foi convidado para fazer parte do grupo <strong class="text-white"><?= Html::encode($inviteGroupName) ?></strong>.
                                </p>
                            </div>

                            <div class="flex justify-between items-center gap-3 pt-1">
                                <span class="text-[9px] font-semibold text-slate-500 font-mono"><?= date('H:i', (int)$msg['created_at']) ?></span>
                                <a href="<?= Url::to(['/chat/default/join-group', 'code' => $inviteCode]) ?>"
                                   class="py-1.5 px-4 bg-emerald-500 hover:bg-emerald-400 text-slate-950 rounded-lg text-[10px] font-extrabold uppercase tracking-wider cursor-pointer shadow-md shadow-emerald-500/10 hover:shadow-emerald-500/25 active:scale-95 transition-all duration-300">
                                    Aceitar & Entrar
                                </a>
                            </div>
                        </div>

                    <?php else: ?>
                        <!-- Balão de Texto Convencional -->
                        <div class="max-w-[70%] rounded-2xl px-4 py-2.5 shadow-md flex flex-col relative
                                    <?= $isOwn ? 'bg-sky-500/10 border border-sky-500/20 text-sky-200 rounded-tr-none' : 'bg-slate-900 border border-slate-800 text-slate-200 rounded-tl-none' ?>">
                            
                            <!-- Nome do Remetente em Chats de Grupo (Apenas para outros membros) -->
                            <?php if (!$isOwn && $msg['sender_username']): ?>
                                <span class="text-[9px] font-extrabold text-sky-400 uppercase tracking-wider block mb-0.5"><?= Html::encode($msg['sender_username']) ?></span>
                            <?php endif; ?>

                            <!-- Mensagem -->
                            <p class="text-xs font-medium leading-relaxed break-words"><?= Html::encode($msg['message']) ?></p>

                            <!-- Horário de Envio -->
                            <span class="text-[8px] font-semibold text-slate-500 font-mono text-right mt-1.5 block leading-none">
                                <?= date('H:i', (int)$msg['created_at']) ?>
                            </span>
                        </div>
                    <?php endif; ?>

                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <!-- Script de Autoscroll para manter o foco na última mensagem do chat -->
    <script>
        (function() {
            const container = document.getElementById('chat-messages-container');
            if (container) {
                // Se a barra estiver perto do fundo ou se acabamos de entrar na sala, faz scroll para baixo
                // Evita interromper a leitura do usuário caso ele suba a barra
                const threshold = 150;
                const isNearBottom = container.scrollHeight - container.clientHeight - container.scrollTop < threshold;
                // Na primeira carga, forçamos o scroll
                if (!container.getAttribute('hx-scrolled') || isNearBottom) {
                    container.scrollTop = container.scrollHeight;
                    container.setAttribute('hx-scrolled', 'true');
                }
            }
        })();
    </script>
<?php endif; ?>
