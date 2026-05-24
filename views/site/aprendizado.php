<?php
/** @var yii\web\View $this */
?>
<div class="space-y-10 select-none max-w-6xl mx-auto pb-16">
    <!-- Seção Recomendado -->
    <section class="space-y-4">
        <h3 class="text-lg font-bold text-slate-200 tracking-tight">Recomendado</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- Card 1 -->
            <div class="bg-slate-900/30 border border-slate-800/80 rounded-3xl p-5 flex flex-col justify-between hover:border-slate-700 transition duration-200">
                <div class="space-y-3">
                    <div class="w-8 h-8 bg-slate-800 rounded-lg flex items-center justify-center text-xs text-slate-400 border border-slate-700/60">
                        📱
                    </div>
                    <h4 class="text-xs font-bold text-slate-200 leading-snug">
                        Adicionar a verificação de número de telefone do Firebase ao seu app Android
                    </h4>
                    <span class="inline-block text-[8px] font-bold text-slate-500 font-mono tracking-wider">CONCLUSÃO: CERCA DE 30 MINUTOS</span>
                </div>
                <div class="mt-6 flex justify-end">
                    <button class="py-1.5 px-4 bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-slate-300 text-xs font-bold rounded-xl transition flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                        Iniciar
                    </button>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-slate-900/30 border border-slate-800/80 rounded-3xl p-5 flex flex-col justify-between hover:border-slate-700 transition duration-200">
                <div class="space-y-3">
                    <div class="w-8 h-8 bg-slate-800 rounded-lg flex items-center justify-center text-xs text-slate-400 border border-slate-700/60">
                        🔍
                    </div>
                    <h4 class="text-xs font-bold text-slate-200 leading-snug">
                        Agente de ciência de dados com estado no Agent Engine
                    </h4>
                    <span class="inline-block text-[8px] font-bold text-slate-500 font-mono tracking-wider">PROMPT TO PRODUCTION</span>
                </div>
                <div class="mt-6 flex justify-end">
                    <button class="py-1.5 px-4 bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-slate-300 text-xs font-bold rounded-xl transition flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                        Iniciar
                    </button>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-slate-900/30 border border-slate-800/80 rounded-3xl p-5 flex flex-col justify-between hover:border-slate-700 transition duration-200">
                <div class="space-y-3">
                    <div class="w-8 h-8 bg-slate-800 rounded-lg flex items-center justify-center text-xs text-slate-400 border border-slate-700/60">
                        ⚙️
                    </div>
                    <h4 class="text-xs font-bold text-slate-200 leading-snug">
                        Agente de geração de código no GKE
                    </h4>
                    <span class="inline-block text-[8px] font-bold text-slate-500 font-mono tracking-wider">KUBERNETES & GEMINI</span>
                </div>
                <div class="mt-6 flex justify-end">
                    <button class="py-1.5 px-4 bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-slate-300 text-xs font-bold rounded-xl transition flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                        Iniciar
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Seção Codelabs Central -->
    <section class="space-y-6 pt-6">
        <!-- Título Centralizado -->
        <div class="text-center space-y-2">
            <h2 class="text-3xl font-extrabold text-slate-100 tracking-tight font-sans">Codelabs</h2>
        </div>

        <!-- Barra de Pesquisa Redonda com Lupa -->
        <div class="max-w-xl mx-auto relative">
            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.637 10.637z" />
                </svg>
            </div>
            <input type="text" 
                   placeholder="Pesquisar todos os codelabs" 
                   class="w-full bg-slate-900 border border-slate-800/80 focus:border-slate-700/60 focus:ring-1 focus:ring-slate-700/20 text-slate-200 placeholder-slate-500 rounded-full pl-11 pr-6 py-3 text-sm focus:outline-none transition-all shadow-inner">
        </div>

        <!-- Filtros em Pílulas Cinzas -->
        <div class="flex flex-wrap justify-center gap-2 text-xs">
            <button class="py-1.5 px-4 bg-slate-900 border border-slate-800/80 hover:border-slate-700 text-slate-400 hover:text-slate-200 rounded-lg transition font-medium">Topic</button>
            <button class="py-1.5 px-4 bg-slate-900 border border-slate-800/80 hover:border-slate-700 text-slate-400 hover:text-slate-200 rounded-lg transition font-medium">Language</button>
            <button class="py-1.5 px-4 bg-slate-900 border border-slate-800/80 hover:border-slate-700 text-slate-400 hover:text-slate-200 rounded-lg transition font-medium">Cloud Next '26</button>
        </div>

        <!-- Grid de Cards de Codelabs (Google Style) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6">
            
            <!-- Codelab 1 -->
            <div class="bg-slate-900/30 border border-slate-800/80 rounded-3xl p-5 flex flex-col justify-between hover:border-slate-700 transition duration-200">
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="text-xs">⌚</span>
                        <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider font-mono">Wear OS</span>
                    </div>
                    <h4 class="text-xs font-bold text-slate-200 leading-snug">
                        (Descontinuado) Como adicionar complementos ao mostrador de um relógio Wear OS
                    </h4>
                </div>
                <div class="mt-6 flex justify-end">
                    <button class="py-1.5 px-4 bg-slate-900 border border-slate-800 hover:border-slate-750 text-slate-300 hover:text-slate-100 text-[10px] font-bold rounded-lg transition flex items-center gap-1 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                        Iniciar
                    </button>
                </div>
            </div>

            <!-- Codelab 2 -->
            <div class="bg-slate-900/30 border border-slate-800/80 rounded-3xl p-5 flex flex-col justify-between hover:border-slate-700 transition duration-200">
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="text-xs">📁</span>
                        <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider font-mono">Vertex AI</span>
                    </div>
                    <h4 class="text-xs font-bold text-slate-200 leading-snug">
                        A Vertex AI acessa endpoints de previsão on-line de forma particular usando o PSC
                    </h4>
                </div>
                <div class="mt-6 flex justify-end">
                    <button class="py-1.5 px-4 bg-slate-900 border border-slate-800 hover:border-slate-750 text-slate-300 hover:text-slate-100 text-[10px] font-bold rounded-lg transition flex items-center gap-1 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                        Iniciar
                    </button>
                </div>
            </div>

            <!-- Codelab 3 -->
            <div class="bg-slate-900/30 border border-slate-800/80 rounded-3xl p-5 flex flex-col justify-between hover:border-slate-700 transition duration-200">
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="text-xs">📁</span>
                        <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider font-mono">Google Cloud Agentic</span>
                    </div>
                    <h4 class="text-xs font-bold text-slate-200 leading-snug">
                        A pilha de agentes do Google em ação: ADK, A2A e MCP no Google Cloud
                    </h4>
                </div>
                <div class="mt-6 flex justify-end">
                    <button class="py-1.5 px-4 bg-slate-900 border border-slate-800 hover:border-slate-750 text-slate-300 hover:text-slate-100 text-[10px] font-bold rounded-lg transition flex items-center gap-1 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                        Iniciar
                    </button>
                </div>
            </div>

        </div>
    </section>
</div>
