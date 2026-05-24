<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var app\models\User $user */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Configurações do Perfil';
?>

<!-- Interface Premium de Configurações SPA -->
<div class="max-w-6xl mx-auto space-y-8" 
     x-data="{ 
         subTab: 'profile',
         accentColor: localStorage.getItem('crom-accent') || 'sky',
         density: localStorage.getItem('crom-density') || 'comfortable',
         init() {
             this.applyAccent(this.accentColor);
             this.applyDensity(this.density);
             
             // Escuta evento de alteração de username para atualizar elementos globais
             document.body.addEventListener('usernameUpdated', () => {
                 const newUsername = document.getElementById('input-username').value;
                 const displayEl = document.getElementById('profile-display-username');
                 if (displayEl) displayEl.innerText = newUsername;
                 
                 // Atualiza também as iniciais do avatar gigante e superior
                 const avatarGiant = document.getElementById('avatar-giant-text');
                 if (avatarGiant && newUsername.length >= 2) {
                     avatarGiant.innerText = newUsername.substring(0, 2).toUpperCase();
                 }
             });
         },
         applyAccent(color) {
             this.accentColor = color;
             localStorage.setItem('crom-accent', color);
             // Aplica classes de cor de acento dinamicamente se necessário
         },
         applyDensity(density) {
             this.density = density;
             localStorage.setItem('crom-density', density);
         },
         playClick() {
             try {
                 const ctx = new (window.AudioContext || window.webkitAudioContext)();
                 const osc = ctx.createOscillator();
                 const gain = ctx.createGain();
                 osc.type = 'sine';
                 osc.frequency.setValueAtTime(1200, ctx.currentTime);
                 osc.frequency.exponentialRampToValueAtTime(600, ctx.currentTime + 0.08);
                 gain.gain.setValueAtTime(0.02, ctx.currentTime);
                 gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.08);
                 osc.connect(gain);
                 gain.connect(ctx.destination);
                 osc.start();
                 osc.stop(ctx.currentTime + 0.08);
             } catch (e) {}
         }
     }">

    <!-- 1. BANNER PRINCIPAL PREMIUM (Glassmorphic Glow) -->
    <div class="relative overflow-hidden rounded-3xl border border-slate-800/80 bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 p-8 shadow-2xl backdrop-blur-md">
        <!-- Efeitos de Brilho de Fundo -->
        <div class="absolute -right-20 -top-20 h-60 w-60 rounded-full blur-3xl opacity-15 transition-all duration-500"
             :class="{
                 'bg-sky-500': accentColor === 'sky',
                 'bg-emerald-500': accentColor === 'emerald',
                 'bg-indigo-500': accentColor === 'indigo',
                 'bg-amber-500': accentColor === 'amber',
                 'bg-rose-500': accentColor === 'rose',
             }"></div>
        <div class="absolute -left-20 -bottom-20 h-60 w-60 rounded-full blur-3xl opacity-10 transition-all duration-500"
             :class="{
                 'bg-sky-500': accentColor === 'sky',
                 'bg-emerald-500': accentColor === 'emerald',
                 'bg-indigo-500': accentColor === 'indigo',
                 'bg-amber-500': accentColor === 'amber',
                 'bg-rose-500': accentColor === 'rose',
             }"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center gap-6">
            <!-- Avatar Gigante -->
            <div class="relative group">
                <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-sky-500 to-indigo-500 opacity-20 blur-md group-hover:opacity-40 transition-all duration-300"></div>
                <div class="relative h-24 w-24 rounded-2xl bg-slate-800/50 border border-slate-700/80 flex items-center justify-center font-black text-3xl shadow-inner transition-colors duration-500"
                     :class="{
                         'text-sky-400 border-sky-500/20': accentColor === 'sky',
                         'text-emerald-400 border-emerald-500/20': accentColor === 'emerald',
                         'text-indigo-400 border-indigo-500/20': accentColor === 'indigo',
                         'text-amber-400 border-amber-500/20': accentColor === 'amber',
                         'text-rose-400 border-rose-500/20': accentColor === 'rose',
                     }">
                     <span id="avatar-giant-text"><?= strtoupper(substr($user->username, 0, 2)) ?></span>
                </div>
            </div>

            <!-- Dados Rápidos -->
            <div class="flex-1 text-center md:text-left space-y-2">
                <div class="flex flex-col md:flex-row md:items-center gap-2 justify-center md:justify-start">
                    <h2 class="text-2xl font-extrabold text-white tracking-tight"><?= Html::encode($user->username) ?></h2>
                    <span class="inline-flex items-center self-center md:self-auto px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-sky-500/10 text-sky-400 border border-sky-500/20 uppercase tracking-wider">Dev Program</span>
                </div>
                <p class="text-xs font-semibold text-slate-400 flex items-center justify-center md:justify-start gap-1.5 font-mono">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-ping"></span>
                    <span>ONLINE NO CROM LABS</span>
                </p>
                <div class="text-[11px] font-semibold text-slate-500">
                    Membro desde: <span class="text-slate-300 font-mono"><?= date('d/m/Y H:i', $user->created_at) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. CONTAINER DE ALERTAS GLOBAIS DO PERFIL -->
    <div id="profile-alert-container" class="space-y-2 min-h-[50px] transition-all duration-300 empty:hidden"></div>

    <!-- 3. GRID PRINCIPAL DE DUAS COLUNAS -->
    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Sidebar Esquerda (Abas) -->
        <aside class="w-full lg:w-64 flex flex-row lg:flex-col gap-1.5 overflow-x-auto lg:overflow-x-visible pb-3 lg:pb-0 scrollbar-none border-b lg:border-b-0 lg:border-r border-slate-800/80 pr-0 lg:pr-6 flex-shrink-0">
            <button @click="subTab = 'profile'; playClick()" 
                    :class="subTab === 'profile' ? 'bg-slate-900 text-white border-l-2 lg:border-l-2 lg:border-t-0 border-t-2 border-sky-400 bg-opacity-60' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900/30'"
                    class="w-full flex items-center justify-center lg:justify-start gap-3 px-4 py-3 rounded-xl text-xs font-extrabold tracking-wide transition-all duration-300 cursor-pointer whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                <span>Meu Perfil</span>
            </button>

            <button @click="subTab = 'security'; playClick()" 
                    :class="subTab === 'security' ? 'bg-slate-900 text-white border-l-2 lg:border-l-2 lg:border-t-0 border-t-2 border-sky-400 bg-opacity-60' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900/30'"
                    class="w-full flex items-center justify-center lg:justify-start gap-3 px-4 py-3 rounded-xl text-xs font-extrabold tracking-wide transition-all duration-300 cursor-pointer whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
                <span>Segurança & Senha</span>
            </button>

            <button @click="subTab = 'activity'; playClick()" 
                    :class="subTab === 'activity' ? 'bg-slate-900 text-white border-l-2 lg:border-l-2 lg:border-t-0 border-t-2 border-sky-400 bg-opacity-60' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900/30'"
                    class="w-full flex items-center justify-center lg:justify-start gap-3 px-4 py-3 rounded-xl text-xs font-extrabold tracking-wide transition-all duration-300 cursor-pointer whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                </svg>
                <span>Sessões & Atividades</span>
            </button>

            <button @click="subTab = 'preferences'; playClick()" 
                    :class="subTab === 'preferences' ? 'bg-slate-900 text-white border-l-2 lg:border-l-2 lg:border-t-0 border-t-2 border-sky-400 bg-opacity-60' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900/30'"
                    class="w-full flex items-center justify-center lg:justify-start gap-3 px-4 py-3 rounded-xl text-xs font-extrabold tracking-wide transition-all duration-300 cursor-pointer whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-1.305 1.764L7.5 21h9l-.725-3.114a3 3 0 00-1.305-1.764L12 14.25l-2.47 1.872z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 14.25a3 3 0 100-6 3 3 0 000 6z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Preferências Visuais</span>
            </button>
        </aside>

        <!-- Área de Conteúdo da Direita -->
        <main class="flex-1 bg-slate-900/20 rounded-2xl border border-slate-800/80 p-6 md:p-8 backdrop-blur-sm min-h-[400px]">
            
            <!-- SUB-ABA 1: MEU PERFIL -->
            <div x-show="subTab === 'profile'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" class="space-y-6">
                <div>
                    <h3 class="text-lg font-bold text-white tracking-wide">Dados Cadastrais</h3>
                    <p class="text-xs text-slate-500 mt-1">Gerencie os detalhes básicos de identificação da sua conta no ecossistema CROM.</p>
                </div>
                
                <form hx-post="<?= Url::to(['/site/profile']) ?>"
                      hx-target="#profile-alert-container"
                      hx-swap="innerHTML"
                      class="space-y-4 max-w-lg">
                    <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
                    <input type="hidden" name="action_type" value="update_profile">

                    <div class="space-y-1.5">
                        <label for="input-username" class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Nome de Usuário</label>
                        <input type="text" 
                               name="username" 
                               id="input-username" 
                               value="<?= Html::encode($user->username) ?>" 
                               class="w-full bg-slate-950 border border-slate-800 hover:border-slate-700/80 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 text-sm rounded-xl px-4 py-2.5 text-white transition-all outline-none font-sans font-semibold">
                    </div>

                    <div class="pt-2">
                        <button type="submit" 
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold text-slate-950 bg-sky-400 hover:bg-sky-300 hover:shadow-lg hover:shadow-sky-500/10 cursor-pointer transition-all duration-300">
                            Salvar Alterações
                        </button>
                    </div>
                </form>

                <!-- Divisor -->
                <div class="border-t border-slate-800/80 my-6"></div>

                <!-- Detalhes Físicos de Registro -->
                <div class="space-y-4">
                    <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Metadados da Conta</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-slate-950/40 p-4 rounded-xl border border-slate-800/50 flex flex-col gap-1">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Identificador Interno (UID)</span>
                            <span class="text-sm font-bold text-slate-300 font-mono">#<?= $user->id ?></span>
                        </div>
                        <div class="bg-slate-950/40 p-4 rounded-xl border border-slate-800/50 flex flex-col gap-1">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Mecanismo de Segurança</span>
                            <span class="text-sm font-bold text-emerald-400 flex items-center gap-1.5 text-xs font-bold">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Cryptography (Bcrypt SHA-256)
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SUB-ABA 2: SEGURANÇA & SENHA -->
            <div x-show="subTab === 'security'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" class="space-y-6" style="display: none;">
                <div>
                    <h3 class="text-lg font-bold text-white tracking-wide">Segurança da Conta</h3>
                    <p class="text-xs text-slate-500 mt-1">Troque sua senha periodicamente para manter a integridade dos seus dados nos servidores do CROM.</p>
                </div>

                <form hx-post="<?= Url::to(['/site/profile']) ?>"
                      hx-target="#profile-alert-container"
                      hx-swap="innerHTML"
                      hx-on::after-request="if(event.detail.successful) this.reset()"
                      class="space-y-4 max-w-lg">
                    <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
                    <input type="hidden" name="action_type" value="change_password">

                    <div class="space-y-1.5">
                        <label for="input-current-password" class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Senha Atual</label>
                        <input type="password" 
                               name="current_password" 
                               id="input-current-password" 
                               placeholder="••••••••••••" 
                               class="w-full bg-slate-950 border border-slate-800 hover:border-slate-700/80 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 text-sm rounded-xl px-4 py-2.5 text-white transition-all outline-none font-sans font-semibold">
                    </div>

                    <div class="space-y-1.5">
                        <label for="input-new-password" class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Nova Senha</label>
                        <input type="password" 
                               name="new_password" 
                               id="input-new-password" 
                               placeholder="••••••••••••" 
                               class="w-full bg-slate-950 border border-slate-800 hover:border-slate-700/80 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 text-sm rounded-xl px-4 py-2.5 text-white transition-all outline-none font-sans font-semibold">
                    </div>

                    <div class="space-y-1.5">
                        <label for="input-confirm-password" class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Confirmar Nova Senha</label>
                        <input type="password" 
                               name="confirm_password" 
                               id="input-confirm-password" 
                               placeholder="••••••••••••" 
                               class="w-full bg-slate-950 border border-slate-800 hover:border-slate-700/80 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 text-sm rounded-xl px-4 py-2.5 text-white transition-all outline-none font-sans font-semibold">
                    </div>

                    <div class="pt-2">
                        <button type="submit" 
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold text-slate-950 bg-sky-400 hover:bg-sky-300 hover:shadow-lg hover:shadow-sky-500/10 cursor-pointer transition-all duration-300">
                            Atualizar Credencial
                        </button>
                    </div>
                </form>
            </div>

            <!-- SUB-ABA 3: SESSÕES & ATIVIDADES -->
            <div x-show="subTab === 'activity'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" class="space-y-6" style="display: none;">
                <div>
                    <h3 class="text-lg font-bold text-white tracking-wide">Sessões Ativas</h3>
                    <p class="text-xs text-slate-500 mt-1">Monitore os dispositivos, IPs e conexões ativas na sua conta.</p>
                </div>

                <div class="space-y-4">
                    <div class="bg-slate-950/40 border border-slate-800/80 rounded-2xl p-5 relative overflow-hidden">
                        <div class="absolute right-4 top-4 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full px-2.5 py-0.5 text-[9px] font-bold tracking-wider uppercase">Sessão Atual</div>
                        <div class="flex items-start gap-4">
                            <div class="h-10 w-10 rounded-xl bg-slate-800 flex items-center justify-center text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <h4 class="text-xs font-bold text-white">Dispositivo Linux (Browser)</h4>
                                <p class="text-[11px] font-semibold text-slate-400 font-mono truncate max-w-[280px] md:max-w-md"><?= Html::encode(Yii::$app->request->userAgent) ?></p>
                                <div class="flex flex-wrap items-center gap-3 text-[10px] font-bold text-slate-500 font-mono mt-1">
                                    <span>IP: <span class="text-slate-300"><?= Yii::$app->request->userIP ?: '127.0.0.1' ?></span></span>
                                    <span>•</span>
                                    <span>Atividade: <span class="text-emerald-400 animate-pulse">Ativo agora</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SUB-ABA 4: PREFERÊNCIAS VISUAIS -->
            <div x-show="subTab === 'preferences'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" class="space-y-6" style="display: none;">
                <div>
                    <h3 class="text-lg font-bold text-white tracking-wide">Preferências Visuais</h3>
                    <p class="text-xs text-slate-500 mt-1">Personalize o design e densidade da interface de acordo com a sua preferência (Salvo localmente).</p>
                </div>

                <div class="space-y-6">
                    <!-- Seletor de Destaque -->
                    <div class="space-y-3">
                        <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Cor de Destaque</h4>
                        <div class="flex flex-wrap gap-3">
                            <!-- Sky -->
                            <button @click="applyAccent('sky'); playClick()"
                                    :class="accentColor === 'sky' ? 'ring-2 ring-sky-400 border-sky-400 bg-sky-950/40 text-sky-400' : 'border-slate-800 hover:border-slate-700 text-slate-400'"
                                    class="flex items-center gap-2 px-3 py-2 rounded-xl border text-xs font-bold transition cursor-pointer">
                                <span class="h-3.5 w-3.5 rounded-full bg-sky-500"></span>
                                <span>Sky Blue</span>
                            </button>
                            <!-- Emerald -->
                            <button @click="applyAccent('emerald'); playClick()"
                                    :class="accentColor === 'emerald' ? 'ring-2 ring-emerald-400 border-emerald-400 bg-emerald-950/40 text-emerald-400' : 'border-slate-800 hover:border-slate-700 text-slate-400'"
                                    class="flex items-center gap-2 px-3 py-2 rounded-xl border text-xs font-bold transition cursor-pointer">
                                <span class="h-3.5 w-3.5 rounded-full bg-emerald-500"></span>
                                <span>Emerald</span>
                            </button>
                            <!-- Indigo -->
                            <button @click="applyAccent('indigo'); playClick()"
                                    :class="accentColor === 'indigo' ? 'ring-2 ring-indigo-400 border-indigo-400 bg-indigo-950/40 text-indigo-400' : 'border-slate-800 hover:border-slate-700 text-slate-400'"
                                    class="flex items-center gap-2 px-3 py-2 rounded-xl border text-xs font-bold transition cursor-pointer">
                                <span class="h-3.5 w-3.5 rounded-full bg-indigo-500"></span>
                                <span>Indigo</span>
                            </button>
                            <!-- Amber -->
                            <button @click="applyAccent('amber'); playClick()"
                                    :class="accentColor === 'amber' ? 'ring-2 ring-amber-400 border-amber-400 bg-amber-950/40 text-amber-400' : 'border-slate-800 hover:border-slate-700 text-slate-400'"
                                    class="flex items-center gap-2 px-3 py-2 rounded-xl border text-xs font-bold transition cursor-pointer">
                                <span class="h-3.5 w-3.5 rounded-full bg-amber-500"></span>
                                <span>Amber</span>
                            </button>
                            <!-- Rose -->
                            <button @click="applyAccent('rose'); playClick()"
                                    :class="accentColor === 'rose' ? 'ring-2 ring-rose-400 border-rose-400 bg-rose-950/40 text-rose-400' : 'border-slate-800 hover:border-slate-700 text-slate-400'"
                                    class="flex items-center gap-2 px-3 py-2 rounded-xl border text-xs font-bold transition cursor-pointer">
                                <span class="h-3.5 w-3.5 rounded-full bg-rose-500"></span>
                                <span>Rose Neon</span>
                            </button>
                        </div>
                    </div>

                    <!-- Divisor -->
                    <div class="border-t border-slate-800/80 my-4"></div>

                    <!-- Seletor de Densidade -->
                    <div class="space-y-3">
                        <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Densidade da Interface</h4>
                        <div class="flex gap-4">
                            <button @click="applyDensity('comfortable'); playClick()"
                                    :class="density === 'comfortable' ? 'border-sky-500 bg-sky-500/10 text-sky-400' : 'border-slate-800 text-slate-400 hover:text-slate-200'"
                                    class="flex-1 flex flex-col items-center gap-1 p-4 rounded-xl border text-xs font-bold cursor-pointer transition">
                                <span>Confortável</span>
                                <span class="text-[9px] font-semibold text-slate-500">Paddings maiores, visual relaxado</span>
                            </button>

                            <button @click="applyDensity('compact'); playClick()"
                                    :class="density === 'compact' ? 'border-sky-500 bg-sky-500/10 text-sky-400' : 'border-slate-800 text-slate-400 hover:text-slate-200'"
                                    class="flex-1 flex flex-col items-center gap-1 p-4 rounded-xl border text-xs font-bold cursor-pointer transition">
                                <span>Compacto</span>
                                <span class="text-[9px] font-semibold text-slate-500">Aproveitamento máximo de pixels</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>
