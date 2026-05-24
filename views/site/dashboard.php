<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Portal CROM — Painel';

// Consulta de usuários ativos nos últimos 5 minutos
$timeThreshold = time() - 300;
$onlineUsers = [];
try {
    $onlineUsers = Yii::$app->db->createCommand("
        SELECT u.username 
        FROM core_session_status s
        JOIN core_users u ON s.user_id = u.id
        WHERE s.last_activity >= :threshold AND s.is_online = 1
    ", [':threshold' => $timeThreshold])->queryColumn();
} catch (\Exception $e) {
    // Silencia
}
$onlineCount = count($onlineUsers);
?>

<!-- Elemento invisível/visível capturado pelo HTMX Polling para atualizar o Topbar -->
<div id="online-badge" class="flex items-center gap-1.5 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-2.5 py-1 rounded-full font-mono text-[10px] tracking-wide animate-pulse" title="Membros online nos últimos 5 minutos">
    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
    <span><?= $onlineCount ?> ONLINE</span>
</div>

<div class="space-y-10 pb-16">
    <!-- 1. BANNER PRINCIPAL ARREDONDADO "CROM I/O" (Google Style) -->
    <section class="bg-[#e8f0fe] rounded-[32px] p-8 md:p-12 flex flex-col md:flex-row justify-between items-center relative overflow-hidden text-slate-900 border border-sky-200/50 shadow-lg select-none">
        <!-- Detalhes de Grafismos Geométricos Coloridos em Absoluto -->
        <div class="absolute top-0 left-0 w-24 h-full bg-gradient-to-br from-sky-400/20 to-indigo-500/0 rounded-r-full blur-2xl"></div>
        <div class="absolute bottom-0 right-0 w-64 h-full bg-gradient-to-tl from-emerald-500/10 to-amber-500/0 rounded-l-full blur-3xl"></div>
        
        <!-- Botão Hide no Topo Esquerdo -->
        <div class="absolute top-4 left-4">
            <button class="px-3 py-1 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold rounded-full transition flex items-center gap-1 shadow-sm">
                Hide
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                </svg>
            </button>
        </div>

        <!-- Conteúdo do Banner (Esquerda) -->
        <div class="max-w-xl space-y-4 md:space-y-6 text-center md:text-left pt-6 md:pt-0 z-10">
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-slate-900 font-sans flex items-center justify-center md:justify-start gap-1">
                CROM <span class="font-black text-slate-800">I/O</span>
                <span class="inline-block w-4 h-4 bg-slate-900 rounded-full ml-1"></span>
            </h1>
            <p class="text-sm md:text-base text-slate-600 font-medium leading-relaxed">
                Crie e colabore em documentos Markdown locais com governança de membros direto na base.
            </p>
            <div class="flex justify-center md:justify-start pt-2">
                <!-- Abre a aba de Páginas via Alpine -->
                <button @click="openTab('page_crud')"
                        class="py-3 px-8 bg-sky-600 hover:bg-sky-500 text-white rounded-full text-sm font-bold shadow-lg shadow-sky-600/30 transition-all duration-200 transform active:scale-95">
                    Consultar Documentos Internos
                </button>
            </div>
        </div>

        <!-- Ilustrações Coloridas Premium (Direita) -->
        <div class="relative w-full md:w-80 h-48 md:h-56 mt-6 md:mt-0 flex items-center justify-center z-10">
            <!-- Robô Android / Gota de Gradientes Google -->
            <div class="w-36 h-36 bg-gradient-to-tr from-sky-400 via-indigo-500 to-rose-500 rounded-full blur-sm opacity-20 animate-pulse absolute"></div>
            
            <!-- Desenho colorido abstrato do Android à esquerda -->
            <div class="absolute left-4 w-20 h-20 bg-gradient-to-br from-amber-400 via-rose-500 to-sky-400 rounded-tl-[40px] rounded-br-[40px] shadow-lg flex items-center justify-center transform -rotate-12 border border-white/20">
                <span class="text-white text-3xl font-black">Ω</span>
            </div>
            
            <!-- Símbolo da Gota Google Maps à direita -->
            <div class="absolute right-4 w-24 h-24 bg-gradient-to-tr from-sky-500 via-emerald-400 to-amber-300 rounded-full shadow-2xl flex items-center justify-center transform rotate-12 border border-white/20">
                <div class="w-10 h-10 bg-slate-900 rounded-full flex items-center justify-center shadow-inner">
                    <span class="w-3 h-3 bg-sky-400 rounded-full"></span>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. SEÇÃO DE CARDS DO ECOSSISTEMA "Build using CROM's ecosystem" -->
    <section class="space-y-6">
        <h2 class="text-xl font-bold text-slate-100 tracking-tight select-none">Build using CROM's ecosystem</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            
            <!-- Card 1 (Azul): Páginas Documentadas -->
            <div class="bg-[#1a73e8] rounded-[28px] p-6 flex flex-col justify-between text-white border border-blue-400/20 hover:shadow-2xl hover:shadow-blue-500/10 transition duration-300 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/20 rounded-full blur-xl group-hover:scale-125 transition duration-300"></div>
                <div>
                    <div class="flex justify-between items-start">
                        <div class="w-9 h-9 bg-white/10 rounded-xl flex items-center justify-center text-xl shadow-inner border border-white/10">
                            📄
                        </div>
                        <span class="px-2.5 py-0.5 bg-white/20 text-white rounded-full text-[9px] font-bold font-mono tracking-wide uppercase border border-white/10">Produtividade local</span>
                    </div>
                    <h3 class="text-base font-extrabold mt-6 leading-tight">Criação & Edição de Páginas em Banco</h3>
                    <p class="text-[11px] text-blue-100 mt-2 leading-relaxed font-medium">
                        Crie e gerencie artigos organizacionais em Markdown salvos diretamente no SQLite, com controle de membros e administradores.
                    </p>
                </div>
                <div class="mt-8">
                    <!-- Abre a aba de page_crud -->
                    <button @click="openTab('page_crud')"
                            class="w-full py-2.5 px-4 bg-white hover:bg-slate-50 text-blue-600 rounded-full text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                        Acessar Páginas Documentadas
                    </button>
                </div>
            </div>

            <!-- Card 2 (Roxo): Terminal Integrado -->
            <div class="bg-[#ab47bc] rounded-[28px] p-6 flex flex-col justify-between text-white border border-purple-400/20 hover:shadow-2xl hover:shadow-purple-500/10 transition duration-300 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 bg-purple-500/20 rounded-full blur-xl group-hover:scale-125 transition duration-300"></div>
                <div>
                    <div class="flex justify-between items-start">
                        <div class="w-9 h-9 bg-white/10 rounded-xl flex items-center justify-center text-xl shadow-inner border border-white/10">
                            💻
                        </div>
                        <span class="px-2.5 py-0.5 bg-white/20 text-white rounded-full text-[9px] font-bold font-mono tracking-wide uppercase border border-white/10">Advanced development</span>
                    </div>
                    <h3 class="text-base font-extrabold mt-6 leading-tight">CLI do CROM & Terminal VPS</h3>
                    <p class="text-[11px] text-purple-100 mt-2 leading-relaxed font-medium">
                        Query and edit large codebases, generate apps from images or PDFs, all from your secure console.
                    </p>
                </div>
                <div class="mt-8">
                    <button disabled class="w-full py-2.5 px-4 bg-white/20 text-purple-200 border border-white/10 rounded-full text-xs font-bold cursor-not-allowed flex items-center justify-center gap-1.5">
                        Ver opções de instalação
                    </button>
                </div>
            </div>

            <!-- Card 3 (Preto Gradiente): Antigravity para Linux -->
            <div class="bg-[#0f172a] rounded-[28px] p-6 flex flex-col justify-between text-slate-100 border border-slate-800 hover:border-sky-500/40 hover:shadow-2xl hover:shadow-sky-500/10 transition duration-300 relative overflow-hidden group">
                <!-- Borda em Gradiente Neon -->
                <div class="absolute inset-0 border border-transparent rounded-[28px] bg-gradient-to-r from-sky-400 via-emerald-400 to-indigo-500 opacity-20 pointer-events-none group-hover:opacity-40 transition duration-300"></div>
                <div>
                    <div class="flex justify-between items-start">
                        <div class="w-9 h-9 bg-slate-800 rounded-xl flex items-center justify-center text-xl shadow-inner border border-slate-700">
                            🚀
                        </div>
                        <span class="px-2.5 py-0.5 bg-slate-800 text-slate-300 border border-slate-700 rounded-full text-[9px] font-bold font-mono tracking-wide uppercase">AI agents</span>
                    </div>
                    <h3 class="text-base font-extrabold mt-6 leading-tight text-transparent bg-clip-text bg-gradient-to-r from-slate-100 to-sky-400">CROM Antigravity para Linux</h3>
                    <p class="text-[11px] text-slate-400 mt-2 leading-relaxed font-medium">
                        CROM Antigravity is our agentic development platform, evolving the IDE into the agent-first era.
                    </p>
                </div>
                <div class="mt-8 z-10">
                    <button class="w-full py-2.5 px-4 bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-200 rounded-full text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Baixar para Linux
                    </button>
                </div>
            </div>

            <!-- Card 4 (Preto com Órbitas): Bate-Papo / Chat -->
            <div class="bg-[#0f172a] rounded-[28px] p-6 flex flex-col justify-between text-slate-100 border border-slate-800/80 hover:shadow-2xl hover:shadow-black/40 transition duration-300 relative overflow-hidden group">
                <!-- Órbitas e Círculos Geométricos Decorativos de Fundo -->
                <div class="absolute bottom-[-20px] right-[-20px] w-36 h-36 border border-slate-800/60 rounded-full pointer-events-none group-hover:scale-110 transition duration-300"></div>
                <div class="absolute bottom-[-10px] right-[-10px] w-24 h-24 border border-slate-800/40 rounded-full pointer-events-none"></div>
                
                <div>
                    <div class="flex justify-between items-start">
                        <div class="w-9 h-9 bg-slate-800 rounded-xl flex items-center justify-center text-xl shadow-inner border border-slate-700">
                            💬
                        </div>
                        <span class="px-2.5 py-0.5 bg-slate-800 text-slate-300 border border-slate-700 rounded-full text-[9px] font-bold font-mono tracking-wide uppercase">Vibecode with Gen AI</span>
                    </div>
                    <h3 class="text-base font-extrabold mt-6 leading-tight">CROM AI Studio</h3>
                    <p class="text-[11px] text-slate-400 mt-2 leading-relaxed font-medium">
                        The fastest path from prompt to production with Gemini APIs and collaborative tools.
                    </p>
                </div>
                <div class="mt-8 z-10">
                    <button class="w-full py-2.5 px-4 bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-200 rounded-full text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                        </svg>
                        Inscrição e primeiros passos
                    </button>
                </div>
            </div>

        </div>
    </section>

    <!-- 3. SEÇÕES INFERIORES: BENEFÍCIOS, PROJETOS E APRENDIZADO -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- COLUNA ESQUERDA: BENEFÍCIOS E PROJETOS -->
        <div class="space-y-10">
            <!-- Seção Benefícios -->
            <section class="space-y-4">
                <div class="flex justify-between items-center select-none">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 bg-rose-500/10 text-rose-400 border border-rose-500/20 rounded-full flex items-center justify-center text-xs shadow-inner">
                            ❤️
                        </div>
                        <h3 class="text-sm font-bold text-slate-200">Benefícios</h3>
                    </div>
                    <!-- Link do Alpine para ir à aba de Benefícios -->
                    <button @click="openTab('beneficios')" class="text-xs font-bold text-sky-400 hover:text-sky-300 transition">Ver tudo ></button>
                </div>

                <!-- Card de Benefício -->
                <div class="bg-slate-900/30 border border-slate-800/80 rounded-2xl p-6 hover:border-slate-700/60 transition duration-200">
                    <h4 class="text-base font-extrabold text-slate-100">$ 10 monthly Gen AI & Cloud credits</h4>
                    <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                        Start building in CROM AI Studio and CROM Vertex AI or any CROM Cloud product with complimentary monthly credits.
                    </p>
                    <button @click="openTab('beneficios')" class="mt-4 py-2 px-5 bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-200 text-xs font-bold rounded-xl transition">
                        See all benefits
                    </button>
                </div>
            </section>

            <!-- Seção Projetos -->
            <section class="space-y-4">
                <div class="flex justify-between items-center select-none">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 bg-sky-500/10 text-sky-400 border border-sky-500/20 rounded-full flex items-center justify-center text-xs shadow-inner">
                            📋
                        </div>
                        <h3 class="text-sm font-bold text-slate-200">Projetos</h3>
                    </div>
                    <button @click="openTab('projetos')" class="text-xs font-bold text-sky-400 hover:text-sky-300 transition">Ver tudo ></button>
                </div>

                <!-- Card de Projetos -->
                <div class="bg-slate-900/30 border border-slate-800/80 rounded-2xl p-6 hover:border-slate-700/60 transition duration-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="w-4 h-4 text-xs">🔥</span>
                            <h4 class="text-sm font-bold text-slate-200">CROM Cloud e Firebase</h4>
                        </div>
                        <p class="text-[10px] text-slate-500 leading-relaxed max-w-sm">
                            Permitir que o CROM Developer Program leia os dados dos seus projetos do Cloud e do Firebase.
                        </p>
                    </div>
                    <div>
                        <button class="py-2 px-5 bg-sky-600 hover:bg-sky-500 text-white text-xs font-bold rounded-xl transition shadow-md shadow-sky-600/10 whitespace-nowrap">
                            Ativar para o CROM Cloud e Firebase
                        </button>
                    </div>
                </div>
            </section>
        </div>

        <!-- COLUNA DIREITA: APRENDIZADO / CODELABS -->
        <section class="space-y-4">
            <div class="flex justify-between items-center select-none">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full flex items-center justify-center text-xs shadow-inner">
                        🎓
                    </div>
                    <h3 class="text-sm font-bold text-slate-200">Aprendizado</h3>
                </div>
                <button @click="openTab('aprendizado')" class="text-xs font-bold text-sky-400 hover:text-sky-300 transition">Ver tudo ></button>
            </div>

            <!-- Lista Vertical de Codelabs -->
            <div class="bg-slate-900/30 border border-slate-800/80 rounded-2xl divide-y divide-slate-800/60 overflow-hidden">
                
                <!-- Item 1 -->
                <div class="p-5 flex items-center justify-between gap-4 hover:bg-slate-800/10 transition duration-200">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="text-xl flex-shrink-0 text-slate-500">⌚</span>
                        <div class="min-w-0">
                            <h4 class="text-xs font-bold text-slate-300 truncate" title="(Descontinuado) Como adicionar complementos ao mostrador de um relógio Wear OS">(Descontinuado) Como adicionar complementos ao mostrador de um relógio Wear OS</h4>
                            <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider font-mono">Wear OS</span>
                        </div>
                    </div>
                    <button @click="openTab('aprendizado')" class="py-1.5 px-4 bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-slate-100 text-[10px] font-bold rounded-lg transition flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                        Iniciar
                    </button>
                </div>

                <!-- Item 2 -->
                <div class="p-5 flex items-center justify-between gap-4 hover:bg-slate-800/10 transition duration-200">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="text-xl flex-shrink-0 text-slate-500">📁</span>
                        <div class="min-w-0">
                            <h4 class="text-xs font-bold text-slate-300 truncate" title="A Vertex AI acessa endpoints de previsão on-line de forma particular usando o PSC">A Vertex AI acessa endpoints de previsão on-line de forma particular usando o PSC</h4>
                            <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider font-mono">Completion: Approx 140 minutes</span>
                        </div>
                    </div>
                    <button @click="openTab('aprendizado')" class="py-1.5 px-4 bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-slate-100 text-[10px] font-bold rounded-lg transition flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                        Iniciar
                    </button>
                </div>

                <!-- Item 3 -->
                <div class="p-5 flex items-center justify-between gap-4 hover:bg-slate-800/10 transition duration-200">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="text-xl flex-shrink-0 text-slate-500">📁</span>
                        <div class="min-w-0">
                            <h4 class="text-xs font-bold text-slate-300 truncate" title="A pilha de agentes da CROM em ação: ADK, A2A e MCP no CROM Cloud">A pilha de agentes da CROM em ação: ADK, A2A e MCP no CROM Cloud</h4>
                            <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider font-mono">CROM Cloud Agentic</span>
                        </div>
                    </div>
                    <button @click="openTab('aprendizado')" class="py-1.5 px-4 bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-slate-100 text-[10px] font-bold rounded-lg transition flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                        Iniciar
                    </button>
                </div>

            </div>
        </section>

    </div>
</div>
