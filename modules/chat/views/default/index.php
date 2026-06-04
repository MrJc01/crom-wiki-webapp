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

<!-- Card Informativo: Canais Principais de Comunicação -->
<div class="mb-4 bg-gradient-to-r from-indigo-500/5 via-slate-900/20 to-purple-500/5 border border-indigo-500/15 rounded-2xl p-4 flex flex-col md:flex-row items-center justify-between gap-4 select-none">
    <div class="flex items-center gap-3.5 text-left">
        <div class="w-10 h-10 bg-indigo-500/10 border border-indigo-500/20 rounded-xl flex items-center justify-center text-xl shadow-lg flex-shrink-0">
            📢
        </div>
        <div>
            <h3 class="text-xs font-bold text-white tracking-tight">A maioria das conversas acontece no Discord</h3>
            <p class="text-[10.5px] text-slate-400 mt-0.5 leading-relaxed max-w-lg">
                Este chat local serve para comunicações internas rápidas entre membros do portal. Para discussões em tempo real, canais temáticos e suporte da comunidade, junte-se aos nossos canais principais.
            </p>
        </div>
    </div>
    <div class="flex items-center gap-2 flex-shrink-0">
        <a href="https://discord.com/invite/4b5wqdxreZ" target="_blank" rel="noopener noreferrer"
           class="px-4 py-2 bg-[#5865F2]/15 hover:bg-[#5865F2]/25 border border-[#5865F2]/30 hover:border-[#5865F2]/50 text-[#7289DA] hover:text-white font-bold rounded-xl text-[11px] transition flex items-center gap-2 cursor-pointer shadow-lg">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028 14.09 14.09 0 0 0 1.226-1.994.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.095 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.095 2.157 2.42 0 1.333-.947 2.418-2.157 2.418z"/></svg>
            <span>Discord</span>
        </a>
        <a href="https://chat.whatsapp.com/BczBBFD4rD4GT3i8hM2qeG" target="_blank" rel="noopener noreferrer"
           class="px-4 py-2 bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/20 hover:border-emerald-500/40 text-emerald-400 hover:text-white font-bold rounded-xl text-[11px] transition flex items-center gap-2 cursor-pointer shadow-lg">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>
            <span>WhatsApp</span>
        </a>
    </div>
</div>

<div class="h-[calc(100vh-130px)] md:h-[calc(100vh-180px)] bg-slate-900/40 border border-slate-800/80 rounded-2xl overflow-hidden backdrop-blur-md flex flex-col md:flex-row relative"
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
                    $isSystem = isset($room['is_system']) && (int)$room['is_system'] === 1;
                    $roomName = $isSystem ? 'Notificações do Sistema' : ($room['is_group'] ? $room['name'] : $room['direct_username']);
                    $initials = $isSystem ? '🔔' : strtoupper(substr((string)$roomName, 0, 2));
                    
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
                            <?php if ($isSystem): ?>
                                <div class="h-10 w-10 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center font-bold text-lg tracking-wide shadow-inner select-none">
                                    🔔
                                </div>
                            <?php elseif ($room['is_group']): ?>
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
                                <h4 class="text-xs font-bold text-white truncate flex items-center gap-1.5">
                                    <?= Html::encode($roomName) ?>
                                    <?php if ($isSystem): ?>
                                        <span class="px-1.5 py-0.2 bg-amber-500/10 border border-amber-500/20 text-amber-400 text-[8px] font-mono rounded font-extrabold uppercase tracking-wide">Sistema</span>
                                    <?php endif; ?>
                                </h4>
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
