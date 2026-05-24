<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var array $rooms */
/** @var array $contacts */
/** @var int|null $activeRoomId */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Conversas & Grupos';

// Se foi passado um room_id inicial na URL (ex: redirecionamento após entrar no grupo)
$initialRoomId = Yii::$app->request->get('room_id');
?>

<div class="h-[calc(100vh-130px)] md:h-[calc(100vh-112px)] bg-slate-900/40 border border-slate-800/80 rounded-2xl overflow-hidden backdrop-blur-md flex flex-col md:flex-row relative"
     x-data="{ 
         searchQuery: '',
         showGroupModal: false,
         showContactModal: false,
         selectedRoomId: <?= $initialRoomId ? (int)$initialRoomId : 'null' ?>,
         contacts: <?= Html::encode(json_encode($contacts)) ?>,
         
         init() {
             // Se tiver um room_id inicial, força o clique no chat correspondente
             if (this.selectedRoomId) {
                 this.$nextTick(() => {
                     const btn = document.getElementById('room-item-' + this.selectedRoomId);
                     if (btn) btn.click();
                 });
             }
             
             // Escuta o trigger de salas atualizadas enviado pelo backend
             document.body.addEventListener('chatRoomsUpdated', () => {
                 // Recarrega a página de chat de forma isomórfica / HTMX
                 const btnNav = document.getElementById('btn-nav-chat');
                 if (btnNav) {
                     btnNav.removeAttribute('hx-loaded');
                     btnNav.click();
                 }
             });

             // Escuta eventos globais para abrir conversas de forma assíncrona
             window.addEventListener('openChatRoom', (e) => {
                 this.selectedRoomId = e.detail.roomId;
                 this.$nextTick(() => {
                     const btn = document.getElementById('room-item-' + e.detail.roomId);
                     if (btn) {
                         btn.click();
                     }
                 });
             });
         },
         filteredContacts() {
             if (this.searchQuery.trim() === '') return this.contacts;
             return this.contacts.filter(c => c.username.toLowerCase().includes(this.searchQuery.toLowerCase()));
         }
     }">

    <!-- COLUNA ESQUERDA: LISTA DE CONVERSAS (WhatsApp Style Sidebar) -->
    <aside class="w-full md:w-80 border-r border-slate-800/80 flex flex-col h-full bg-slate-950/60 flex-shrink-0"
           :class="selectedRoomId !== null ? 'hidden md:flex' : 'flex'">
        
        <!-- Header da barra lateral -->
        <div class="p-4 border-b border-slate-800/80 space-y-3">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-extrabold text-white tracking-wide">Conversas</h2>
                
                <div class="flex items-center gap-1">
                    <!-- Botão Novo Chat Direto -->
                    <button @click="showContactModal = true" 
                            class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition cursor-pointer"
                            title="Nova Conversa Privada">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </button>
                    <!-- Botão Novo Grupo -->
                    <button @click="showGroupModal = true" 
                            class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition cursor-pointer"
                            title="Criar Novo Grupo">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Input de Busca -->
            <div class="relative">
                <input type="text" 
                       placeholder="Buscar conversa..." 
                       x-model="searchQuery"
                       class="w-full bg-slate-900/60 border border-slate-800 hover:border-slate-700/60 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl pl-8 pr-4 py-2 text-white outline-none transition-all font-sans font-semibold">
                <svg class="absolute left-3 top-2.5 h-3.5 w-3.5 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.637 10.637z" />
                </svg>
            </div>
        </div>

        <!-- Lista de conversas ativas -->
        <div class="flex-1 overflow-y-auto divide-y divide-slate-800/40 scrollbar-thin">
            <?php if (empty($rooms)): ?>
                <div class="p-6 text-center text-slate-500 text-xs font-semibold">
                    Nenhum chat ativo. Clique em "+" para iniciar.
                </div>
            <?php else: ?>
                <?php foreach ($rooms as $room): 
                    $roomName = $room['is_group'] ? $room['name'] : $room['direct_username'];
                    $initials = strtoupper(substr((string)$roomName, 0, 2));
                    
                    // Formata a última mensagem para não quebrar a UI se for convite estruturado
                    $lastMsgDisplay = $room['last_message'];
                    if (strpos((string)$lastMsgDisplay, '[GROUP_INVITE]::') === 0) {
                        $parts = explode('::', (string)$lastMsgDisplay);
                        $lastMsgDisplay = "Convite para o grupo: " . ($parts[3] ?? 'Grupo');
                    }
                ?>
                    <button id="room-item-<?= $room['id'] ?>"
                            @click="selectedRoomId = <?= $room['id'] ?>"
                            hx-get="<?= Url::to(['/chat/default/chat-area', 'roomId' => $room['id']]) ?>"
                            hx-target="#chat-active-window"
                            hx-swap="innerHTML"
                            :class="selectedRoomId === <?= $room['id'] ?> ? 'bg-slate-900 border-l-2 border-sky-400 bg-opacity-70' : 'hover:bg-slate-900/30'"
                            class="w-full text-left p-4 flex items-start gap-3 transition-all duration-200 cursor-pointer">
                        
                        <!-- Avatar do Chat -->
                        <div class="flex-shrink-0">
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

                        <!-- Detalhes do Chat -->
                        <div class="flex-1 min-w-0 space-y-0.5">
                            <div class="flex justify-between items-baseline gap-2">
                                <h4 class="text-xs font-bold text-white truncate"><?= Html::encode($roomName) ?></h4>
                                <?php if ($room['last_message_time']): ?>
                                    <span class="text-[9px] font-semibold text-slate-500 font-mono"><?= date('H:i', (int)$room['last_message_time']) ?></span>
                                <?php endif; ?>
                            </div>
                            <p class="text-[11px] text-slate-400 truncate font-semibold">
                                <?= $lastMsgDisplay ? Html::encode($lastMsgDisplay) : 'Nenhuma mensagem' ?>
                            </p>
                        </div>
                    </button>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </aside>

    <!-- COLUNA DIREITA: JANELA DE MENSAGENS ATIVA (WhatsApp Style Chat Area) -->
    <main class="flex-1 flex flex-col h-full bg-slate-950/20"
          :class="selectedRoomId === null ? 'hidden md:flex' : 'flex'">
        <div id="chat-active-window" class="h-full w-full flex flex-col">
            
            <!-- Janela de Boas-vindas Padrão -->
            <div class="flex-1 flex flex-col items-center justify-center p-8 text-center space-y-4 select-none">
                <div class="h-16 w-16 rounded-3xl bg-slate-900 border border-slate-800 flex items-center justify-center text-3xl shadow-xl shadow-black/10 animate-bounce">
                    💬
                </div>
                <div class="space-y-1 max-w-sm">
                    <h3 class="text-sm font-extrabold text-white">Conversas do CROM</h3>
                    <p class="text-xs text-slate-500">Selecione uma conversa ativa na lista lateral ou inicie um novo chat com os membros locais do portal.</p>
                </div>
            </div>

        </div>
    </main>

    <!-- 1. MODAL: CRIAR NOVO GRUPO -->
    <div x-show="showGroupModal" 
         x-transition 
         class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         style="display: none;">
        <div class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl p-6 space-y-4"
             @click.outside="showGroupModal = false">
            <div class="flex justify-between items-center">
                <h3 class="text-sm font-extrabold text-white tracking-wide">Criar Novo Grupo</h3>
                <button @click="showGroupModal = false" class="text-slate-400 hover:text-white cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form action="<?= Url::to(['/chat/default/create-group']) ?>" method="POST" class="space-y-4">
                <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
                
                <div class="space-y-1.5">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Nome do Grupo</label>
                    <input type="text" 
                           name="group_name" 
                           required
                           placeholder="Ex: Infraestrutura Swarm"
                           class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-4 py-2.5 text-white outline-none font-sans font-semibold">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Selecionar Membros</label>
                    <div class="max-h-40 overflow-y-auto border border-slate-800 rounded-xl divide-y divide-slate-800/40 p-2 space-y-1.5 bg-slate-950/40">
                        <template x-for="contact in contacts" :key="contact.id">
                            <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-800/30 cursor-pointer">
                                <input type="checkbox" name="members[]" :value="contact.id" class="rounded border-slate-800 text-sky-500 focus:ring-0 bg-slate-950 h-4 w-4">
                                <span class="text-xs font-bold text-slate-300" x-text="contact.username"></span>
                            </label>
                        </template>
                    </div>
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" @click="showGroupModal = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-400 hover:text-white bg-slate-800/50 cursor-pointer">Cancelar</button>
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-950 bg-sky-400 hover:bg-sky-300 cursor-pointer transition">Criar Grupo</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 2. MODAL: INICIAR CHAT DIRETO -->
    <div x-show="showContactModal" 
         x-transition 
         class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         style="display: none;">
        <div class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl p-6 space-y-4"
             @click.outside="showContactModal = false">
            <div class="flex justify-between items-center">
                <h3 class="text-sm font-extrabold text-white tracking-wide">Novo Chat Direto</h3>
                <button @click="showContactModal = false" class="text-slate-400 hover:text-white cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <!-- Caixa de Filtro de Contatos -->
            <div class="relative">
                <input type="text" 
                       placeholder="Buscar membro por nome..." 
                       x-model="searchQuery"
                       class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl pl-8 pr-4 py-2.5 text-white outline-none font-sans font-semibold">
                <svg class="absolute left-3 top-3 h-3.5 w-3.5 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.637 10.637z" />
                </svg>
            </div>

            <!-- Lista de membros -->
            <div class="max-h-60 overflow-y-auto divide-y divide-slate-800/40 border border-slate-800 rounded-xl p-2 bg-slate-950/40">
                <template x-for="contact in filteredContacts()" :key="contact.id">
                    <button @click="showContactModal = false; selectedRoomId = -1;"
                            hx-get="<?= Url::to(['/chat/default/open-private-chat']) ?>"
                            :hx-vals="JSON.stringify({contactId: contact.id})"
                            hx-target="#chat-active-window"
                            hx-swap="innerHTML"
                            class="w-full text-left p-3 rounded-lg hover:bg-slate-800/40 flex items-center gap-3 transition cursor-pointer">
                        <div class="h-8 w-8 rounded-lg bg-sky-500/10 text-sky-400 border border-sky-500/20 flex items-center justify-center font-bold text-xs tracking-wide">
                            <span x-text="contact.username.substring(0,2).toUpperCase()"></span>
                        </div>
                        <span class="text-xs font-bold text-slate-300" x-text="contact.username"></span>
                    </button>
                </template>
            </div>
        </div>
    </div>

</div>
