<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var array $servers */

use yii\helpers\Url;

$this->title = 'CROM Terminal — Multi-VPS SSH';
?>

<!-- Importação de Estilos do Xterm.js -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/xterm@5.3.0/css/xterm.css" />

<div class="h-[calc(100vh-130px)] md:h-[calc(100vh-112px)] bg-slate-900/40 border border-slate-800/80 rounded-2xl overflow-hidden backdrop-blur-md flex flex-col relative"
     x-data="{
         connected: false,
         connecting: false,
         serverMode: '192.168.1.69',
         customHost: '',
         username: 'root',
         password: '',
         sessionId: '',

         init() {
             this.sessionId = 'term_' + Math.random().toString(36).substring(2, 15) + '_' + Date.now();
         },

         getHost() {
             return this.serverMode === 'custom' ? this.customHost : this.serverMode;
         }
     }">

    <!-- CABEÇALHO DO PAINEL -->
    <header class="h-16 border-b border-slate-800/80 px-4 md:px-6 flex items-center justify-between bg-slate-950/60 backdrop-blur-md z-10 flex-shrink-0 select-none">
        <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-xl bg-sky-500/10 border border-sky-500/20 text-sky-400 flex items-center justify-center font-bold text-lg shadow-inner">
                💻
            </div>
            <div>
                <h3 class="text-xs font-extrabold text-white tracking-wide uppercase">CROM Terminal</h3>
                <span class="text-[9px] font-semibold text-slate-500 font-mono tracking-wider">Acesso Multi-VPS SSH Web</span>
            </div>
        </div>

        <!-- Indicador de Status -->
        <div class="flex items-center gap-2">
            <template x-if="connected">
                <span class="text-[9px] font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded-full flex items-center gap-1">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                    CONECTADO
                </span>
            </template>
            <template x-if="!connected && !connecting">
                <span class="text-[9px] font-bold text-slate-500 bg-slate-950 border border-slate-800 px-2 py-0.5 rounded-full">
                    DESCONECTADO
                </span>
            </template>
            <template x-if="connecting">
                <span class="text-[9px] font-bold text-sky-400 bg-sky-500/10 border border-sky-500/20 px-2 py-0.5 rounded-full animate-pulse">
                    CONECTANDO...
                </span>
            </template>
        </div>
    </header>

    <!-- FORMULÁRIO DE CONEXÃO (Exibido se desconectado) -->
    <div x-show="!connected && !connecting" 
         class="flex-1 flex items-center justify-center p-6 bg-slate-950/20 relative"
         x-transition>
        <div class="w-full max-w-md bg-slate-900 border border-slate-800/80 rounded-2xl shadow-2xl p-6 sm:p-8 space-y-5">
            <div class="text-center space-y-1">
                <h4 class="text-sm font-extrabold text-white uppercase tracking-wider">Nova Conexão SSH</h4>
                <p class="text-[11px] text-slate-500">Escolha o servidor da infraestrutura ou digite um endereço IP customizado.</p>
            </div>

            <div class="space-y-4">
                <!-- Dropdown de Servidor -->
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Servidor de Destino</label>
                    <select x-model="serverMode" 
                            class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-3 py-2.5 text-white outline-none font-sans font-semibold cursor-pointer">
                        <?php foreach ($servers as $host => $name): ?>
                            <option value="<?= $host ?>" class="bg-slate-950 text-white"><?= $name ?></option>
                        <?php endforeach; ?>
                        <option value="custom" class="bg-slate-950 text-white">Outro Servidor (Digitar IP/Host)...</option>
                    </select>
                </div>

                <!-- Campo IP Manual (se 'custom' selecionado) -->
                <div x-show="serverMode === 'custom'" x-transition class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Endereço IP / Host</label>
                    <input type="text" 
                           x-model="customHost"
                           placeholder="Ex: 191.243.165.182"
                           class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-4 py-2.5 text-white outline-none font-sans font-semibold">
                </div>

                <!-- Usuário SSH -->
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Usuário SSH</label>
                    <input type="text" 
                           x-model="username"
                           placeholder="Ex: root"
                           class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-4 py-2.5 text-white outline-none font-sans font-semibold">
                </div>

                <!-- Senha SSH -->
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Senha SSH</label>
                    <input type="password" 
                           x-model="password"
                           placeholder="Digite a chave de acesso ou senha"
                           class="w-full bg-slate-950 border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20 text-xs rounded-xl px-4 py-2.5 text-white outline-none font-sans font-semibold">
                </div>

                <!-- Botão de Conexão -->
                <button type="button" 
                        @click="startConnection($data)"
                        class="w-full py-3 mt-2 bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white rounded-xl text-xs font-bold uppercase tracking-wider shadow-lg shadow-sky-600/10 hover:shadow-sky-500/25 transition duration-300 cursor-pointer">
                    Iniciar Sessão Terminal
                </button>
            </div>
        </div>
    </div>

    <!-- TELA DE LOADING/AGUARDANDO -->
    <div x-show="connecting" 
         class="flex-1 flex flex-col items-center justify-center p-6 bg-slate-950/40 space-y-4"
         x-transition>
        <svg class="animate-spin h-8 w-8 text-sky-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <div class="text-center space-y-1 select-none">
            <h5 class="text-xs font-bold text-white uppercase tracking-wider">Abrindo Canal Seguro SSH...</h5>
            <p class="text-[10px] text-slate-500 font-mono" x-text="'Conectando a ' + getHost() + ' como ' + username"></p>
        </div>
    </div>

    <!-- TELA DO TERMINAL ATIVO -->
    <div class="flex-1 bg-black p-4 relative"
         :class="connected ? 'block' : 'hidden'"
         style="min-height: 400px;">
        
        <!-- Container do Xterm.js -->
        <div id="terminal-container" class="w-full h-full"></div>

        <!-- Botão Flutuante de Desconexão -->
        <button @click="disconnect($data)"
                class="absolute right-4 top-4 px-3 py-1.5 bg-rose-500/10 border border-rose-500/20 hover:bg-rose-500 hover:text-white text-rose-400 rounded-lg text-[10px] font-bold uppercase tracking-wider cursor-pointer shadow-lg transition duration-200">
            Encerrar Conexão
        </button>
    </div>

</div>

<!-- Importações do Xterm.js e Addons -->
<script src="https://cdn.jsdelivr.net/npm/xterm@5.3.0/lib/xterm.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xterm-addon-fit@0.8.0/lib/xterm-addon-fit.js"></script>

<script>
    let term = null;
    let fitAddon = null;
    let eventSource = null;
    let pingInterval = null;

    function base64ToUtf8(str) {
        try {
            return decodeURIComponent(atob(str).split('').map(function(c) {
                return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
            }).join(''));
        } catch (e) {
            // Fallback caso a conversão directa falhe
            return atob(str);
        }
    }

    function startConnection(alpine) {
        const host = alpine.getHost();
        const user = alpine.username;
        const pass = alpine.password;
        
        if (!host || !user) {
            alert('Por favor, preencha o host do servidor e o usuário SSH.');
            return;
        }

        alpine.connecting = true;

        // Inicializa o Xterm.js
        if (!term) {
            term = new Terminal({
                cursorBlink: true,
                fontFamily: 'Courier New, Courier, monospace',
                fontSize: 13,
                theme: {
                    background: '#000000',
                    foreground: '#a6e22e', // Verde cibernético Matrix
                    cursor: '#38bdf8'
                }
            });

            fitAddon = new FitAddon.FitAddon();
            term.loadAddon(fitAddon);
        }

        // Abre a stream de eventos SSE
        const streamUrl = '<?= Url::to(['/terminal/default/stream']) ?>' + 
                          '?host=' + encodeURIComponent(host) + 
                          '&user=' + encodeURIComponent(user) + 
                          '&pass=' + encodeURIComponent(pass) + 
                          '&id=' + encodeURIComponent(alpine.sessionId);

        eventSource = new EventSource(streamUrl);

        eventSource.onmessage = function(event) {
            const data = JSON.parse(event.data);

            if (data.error) {
                alert(data.error);
                disconnect(alpine);
                return;
            }

            if (data.status === 'connected') {
                alpine.connecting = false;
                alpine.connected = true;
                
                // Inicializa a renderização física do container
                setTimeout(() => {
                    term.open(document.getElementById('terminal-container'));
                    fitAddon.fit();
                    term.focus();
                }, 100);

                term.write(data.msg);
            }

            if (data.output) {
                term.write(base64ToUtf8(data.output));
            }
        };

        eventSource.onerror = function() {
            term.write('\r\n\r\n[Conexão encerrada pelo servidor]\r\n');
            disconnect(alpine);
        };

        // Captura o input do usuário e envia para a VPS via POST assíncrono
        term.onData(data => {
            fetch('<?= Url::to(['/terminal/default/write']) ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id=' + encodeURIComponent(alpine.sessionId) + 
                      '&data=' + encodeURIComponent(data)
            });
        });

        // Batimento cardíaco do frontend (ping a cada 15 segundos) para manter conexão viva
        pingInterval = setInterval(() => {
            if (alpine.connected) {
                fetch('<?= Url::to(['/terminal/default/write']) ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id=' + encodeURIComponent(alpine.sessionId) + '&data='
                });
            }
        }, 15000);
    }

    function disconnect(alpine) {
        alpine.connected = false;
        alpine.connecting = false;
        alpine.password = ''; // Limpa a senha por segurança

        if (eventSource) {
            eventSource.close();
            eventSource = null;
        }

        if (term) {
            term.dispose();
            term = null;
        }

        if (pingInterval) {
            clearInterval(pingInterval);
            pingInterval = null;
        }

        // Gera novo SessionID para a próxima conexão
        alpine.sessionId = 'term_' + Math.random().toString(36).substring(2, 15) + '_' + Date.now();
    }

    // Redimensiona o terminal dinamicamente caso a janela mude de tamanho
    window.addEventListener('resize', () => {
        if (term && fitAddon) {
            fitAddon.fit();
        }
    });
</script>
