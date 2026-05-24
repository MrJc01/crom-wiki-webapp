<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var array $room */
/** @var array $members */
/** @var array $nonMembers */

use yii\helpers\Html;
use yii\helpers\Url;

$roomName = $room['is_group'] ? $room['name'] : $room['direct_username'];
$initials = strtoupper(substr((string)$roomName, 0, 2));
?>

<div class="h-full flex flex-col justify-between"
     x-data="{ 
         showInviteMenu: false,
         inviteSuccess: ''
     }">
    
    <!-- 1. CABEÇALHO DO CHAT ATIVO -->
    <header class="h-16 border-b border-slate-800/80 px-4 md:px-6 flex items-center justify-between bg-slate-900/40 backdrop-blur-md z-10 flex-shrink-0 select-none">
        <div class="flex items-center gap-3 min-w-0">
            <!-- Botão Voltar para Mobile -->
            <button @click="selectedRoomId = null" 
                    class="md:hidden p-1 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition cursor-pointer">
                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
            </button>

            <!-- Imagem do Chat -->
            <div>
                <?php if ($room['is_group']): ?>
                    <div class="h-10 w-10 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center font-bold text-sm tracking-wide shadow-inner">
                        👥
                    </div>
                <?php else: ?>
                    <div class="h-10 w-10 rounded-xl bg-sky-500/10 border border-sky-500/20 text-sky-400 flex items-center justify-center font-bold text-sm tracking-wide shadow-inner">
                        <?= $initials ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Dados do Chat -->
            <div class="min-w-0">
                <h3 class="text-xs font-bold text-white truncate leading-tight"><?= Html::encode($roomName) ?></h3>
                <?php if ($room['is_group']): ?>
                    <span class="text-[9px] font-semibold text-slate-500 uppercase tracking-wider font-mono">
                        <?= count($members) ?> Membros
                    </span>
                <?php else: ?>
                    <span class="text-[9px] font-semibold text-emerald-400 flex items-center gap-1 leading-none mt-0.5">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-ping flex-shrink-0"></span>
                        <span>Conversa Privada</span>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Controles de Grupo (Convidar Membros) -->
        <div class="flex items-center gap-3">
            <?php if ($room['is_group']): ?>
                <div class="relative" @click.outside="showInviteMenu = false">
                    <button @click="showInviteMenu = !showInviteMenu"
                            class="flex items-center gap-1.5 py-1.5 px-3 rounded-lg border border-slate-800 bg-slate-900/60 hover:bg-slate-800 text-[10px] font-bold text-slate-300 hover:text-white transition cursor-pointer">
                        <span>Convidar Membro</span>
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                    </button>
                    
                    <!-- Menu Dropdown de Convite -->
                    <div x-show="showInviteMenu" 
                         x-transition
                         class="absolute right-0 mt-2 w-56 rounded-xl bg-slate-900 border border-slate-800 shadow-2xl py-1.5 z-50 p-2 space-y-1.5"
                         style="display: none;">
                        <span class="text-[9px] font-extrabold text-slate-500 uppercase tracking-wider px-2 block">Selecionar Usuário</span>
                        
                        <div class="max-h-40 overflow-y-auto divide-y divide-slate-800/40 border border-slate-800/80 rounded-lg p-1 bg-slate-950/40">
                            <?php if (empty($nonMembers)): ?>
                                <span class="text-[9px] text-slate-500 p-2 block font-semibold text-center">Todos já no grupo</span>
                            <?php else: ?>
                                <?php foreach ($nonMembers as $nonM): ?>
                                    <button hx-post="<?= Url::to(['/chat/default/send-invite']) ?>"
                                            hx-vals='{"group_id": <?= $room['id'] ?>, "target_user_id": <?= $nonM['id'] ?>}'
                                            hx-target="#invite-status-<?= $nonM['id'] ?>"
                                            hx-swap="innerHTML"
                                            @click="setTimeout(() => showInviteMenu = false, 1500)"
                                            class="w-full text-left px-2.5 py-1.5 rounded hover:bg-slate-800 text-[11px] font-bold text-slate-300 hover:text-white flex justify-between items-center transition cursor-pointer">
                                        <span class="truncate"><?= Html::encode($nonM['username']) ?></span>
                                        <span id="invite-status-<?= $nonM['id'] ?>" class="text-[9px] text-sky-400 font-extrabold uppercase">Convidar</span>
                                    </button>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <!-- 2. CONTAINER DE MENSAGENS (HTMX Polling) -->
    <div hx-get="<?= Url::to(['/chat/default/messages', 'roomId' => $room['id']]) ?>"
         hx-trigger="load, every 3s"
         hx-swap="innerHTML"
         class="flex-1 overflow-y-auto p-4 md:p-6 space-y-4 bg-slate-950/40 relative min-h-[250px] scrollbar-thin"
         id="chat-messages-container">
         
         <!-- Carregador Animado SRE -->
         <div class="flex items-center justify-center h-full text-slate-500 text-sm">
              <div class="flex flex-col items-center gap-2">
                  <svg class="animate-spin h-5 w-5 text-sky-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <span class="font-mono text-xs text-slate-400 tracking-wider">carregando mensagens...</span>
              </div>
         </div>

    </div>

    <!-- 3. RODAPÉ DE ENVIO DE MENSAGEM (WhatsApp Style Input) -->
    <footer class="p-3 bg-slate-900/40 border-t border-slate-800/80 flex-shrink-0">
        <form hx-post="<?= Url::to(['/chat/default/send-message', 'roomId' => $room['id']]) ?>"
              hx-target="#chat-messages-container"
              hx-swap="innerHTML"
              hx-on::after-request="if(event.detail.successful) { this.reset(); const container = document.getElementById('chat-messages-container'); setTimeout(() => { container.scrollTop = container.scrollHeight; }, 100); }"
              class="flex items-center gap-3">
            <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
            
            <input type="text" 
                   name="message" 
                   required
                   autocomplete="off"
                   placeholder="Digite uma mensagem..." 
                   class="flex-grow bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-4 py-2.5 text-white outline-none font-sans font-semibold transition-all">

            <button type="submit" 
                    class="h-9 w-9 rounded-xl bg-sky-400 text-slate-950 flex items-center justify-center flex-shrink-0 cursor-pointer hover:bg-sky-300 hover:shadow-lg hover:shadow-sky-500/10 active:scale-95 transition-all">
                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg>
            </button>
        </form>
    </footer>

</div>
