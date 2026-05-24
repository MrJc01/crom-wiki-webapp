<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var array $recentChats */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="space-y-2 p-1">
    <?php if (empty($recentChats)): ?>
        <div class="text-center text-slate-500 py-8 text-xs font-semibold select-none">
            Nenhuma conversa recente com mensagens.
        </div>
    <?php else: ?>
        <?php foreach ($recentChats as $chat): 
            $chatName = $chat['is_group'] ? $chat['name'] : $chat['direct_username'];
            $initials = strtoupper(substr((string)$chatName, 0, 2));
            
            $lastMsg = $chat['last_message'];
            if (strpos((string)$lastMsg, '[GROUP_INVITE]::') === 0) {
                $parts = explode('::', (string)$lastMsg);
                $lastMsg = "Convite para o grupo: " . ($parts[3] ?? 'Grupo');
            }
        ?>
            <button @click="
                        showChatDrawer = false; 
                        const isLoaded = document.getElementById('btn-nav-chat')?.getAttribute('hx-loaded') === 'true';
                        if (isLoaded) {
                            openTab('chat');
                            window.dispatchEvent(new CustomEvent('openChatRoom', { detail: { roomId: <?= $chat['id'] ?> } }));
                        } else {
                            window.location.href = '<?= Url::to(['/chat/default/index']) ?>?room_id=' + <?= $chat['id'] ?>;
                        }
                    "
                    class="w-full text-left p-3 rounded-xl hover:bg-slate-800/60 flex items-start gap-3 transition-all cursor-pointer">
                
                <!-- Avatar -->
                <div class="flex-shrink-0">
                    <?php if ($chat['is_group']): ?>
                        <div class="h-9 w-9 rounded-lg bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center font-bold text-xs shadow-inner">
                            👥
                        </div>
                    <?php else: ?>
                        <div class="h-9 w-9 rounded-lg bg-sky-500/10 border border-sky-500/20 text-sky-400 flex items-center justify-center font-bold text-xs shadow-inner">
                            <?= $initials ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Detalhes -->
                <div class="flex-1 min-w-0 space-y-0.5">
                    <div class="flex justify-between items-baseline gap-2">
                        <span class="text-xs font-bold text-white truncate"><?= Html::encode($chatName) ?></span>
                        <span class="text-[8px] font-semibold text-slate-500 font-mono"><?= date('H:i', (int)$chat['last_message_time']) ?></span>
                    </div>
                    <p class="text-[10px] text-slate-400 truncate font-semibold">
                        <?= Html::encode($lastMsg) ?>
                    </p>
                </div>

            </button>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- abrir pagina do chat -->
    <button @click="
    openTab('chat');
    showChatDrawer = false;
    " class="w-full text-left p-3 rounded-xl hover:bg-slate-800/60 flex items-start gap-3 transition-all cursor-pointer">
                        <span class="text-xs font-bold text-white truncate">Abrir Página do Chat</span>
                    </button>
</div>
