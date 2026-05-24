<?php
/** @var yii\web\View $this */
?>
<div class="space-y-8 select-none max-w-6xl mx-auto pb-16">
    <!-- Título Centralizado -->
    <div class="text-center space-y-2">
        <h2 class="text-3xl font-extrabold text-slate-100 tracking-tight font-sans">Comunidades</h2>
    </div>

    <!-- Barra de Pesquisa Redonda com Lupa -->
    <div class="max-w-xl mx-auto relative">
        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.637 10.637z" />
            </svg>
        </div>
        <input type="text" 
               placeholder="Search" 
               class="w-full bg-slate-900 border border-slate-800/80 focus:border-slate-700/60 focus:ring-1 focus:ring-slate-700/20 text-slate-200 placeholder-slate-500 rounded-full pl-11 pr-6 py-3 text-sm focus:outline-none transition-all shadow-inner">
    </div>

    <!-- Filtros Member / Not a member -->
    <div class="flex justify-center gap-2 text-xs">
        <button class="py-1.5 px-4 bg-slate-900 border border-slate-800/80 hover:border-slate-700 text-slate-400 hover:text-slate-200 rounded-lg transition font-medium">Member</button>
        <button class="py-1.5 px-4 bg-slate-900 border border-slate-800/80 hover:border-slate-700 text-slate-400 hover:text-slate-200 rounded-lg transition font-medium">Not a member</button>
    </div>

    <!-- Grid de Cards de Comunidades (Google Style) -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6">
        
        <!-- Comunidade 1 -->
        <div class="bg-slate-900/30 border border-slate-800/80 rounded-3xl p-6 hover:border-slate-700 transition duration-200 flex flex-col justify-between group">
            <div class="space-y-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 rounded-2xl flex items-center justify-center text-xl shadow-inner flex-shrink-0">
                        🚀
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm font-extrabold text-slate-200 group-hover:text-sky-400 transition truncate">Code Wiki Early Access</h3>
                        <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider font-mono">85.000+ members</span>
                    </div>
                </div>
                <p class="text-xs text-slate-400 leading-relaxed">
                    We're excited to launch the Gemini CLI extension for Code Wiki! Join our waitlist to be the first to know when it's ready to try.
                </p>
            </div>
            <div class="mt-6 flex justify-start">
                <button class="py-2 px-6 bg-sky-600 hover:bg-sky-500 text-white text-xs font-bold rounded-xl transition shadow-md shadow-sky-600/10">
                    Faça parte
                </button>
            </div>
        </div>

        <!-- Comunidade 2 -->
        <div class="bg-slate-900/30 border border-slate-800/80 rounded-3xl p-6 hover:border-slate-700 transition duration-200 flex flex-col justify-between group">
            <div class="space-y-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-rose-500/10 text-rose-400 border border-rose-500/20 rounded-2xl flex items-center justify-center text-xl shadow-inner flex-shrink-0">
                        🔥
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm font-extrabold text-slate-200 group-hover:text-sky-400 transition truncate">Firebase Studio Developer</h3>
                        <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider font-mono">55.000+ members</span>
                    </div>
                </div>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Firebase Studio helps you build and ship full-stack traditional and AI-infused apps. Get started quickly right from your browser.
                </p>
            </div>
            <div class="mt-6 flex justify-start">
                <button class="py-2 px-6 bg-sky-600 hover:bg-sky-500 text-white text-xs font-bold rounded-xl transition shadow-md shadow-sky-600/10">
                    Faça parte
                </button>
            </div>
        </div>

        <!-- Comunidade 3 -->
        <div class="bg-slate-900/30 border border-slate-800/80 rounded-3xl p-6 hover:border-slate-700 transition duration-200 flex flex-col justify-between group">
            <div class="space-y-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-500/10 text-amber-400 border border-amber-500/20 rounded-2xl flex items-center justify-center text-xl shadow-inner flex-shrink-0">
                        🤖
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm font-extrabold text-slate-200 group-hover:text-sky-400 transition truncate">Gemini Enterprise Agent Ready</h3>
                        <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider font-mono">408.000+ members</span>
                    </div>
                </div>
                <p class="text-xs text-slate-400 leading-relaxed">
                    GEAR (Gemini Enterprise Agent Ready) is a skilling program for builders and leaders developing for the future of enterprise AI.
                </p>
            </div>
            <div class="mt-6 flex justify-start">
                <button class="py-2 px-6 bg-sky-600 hover:bg-sky-500 text-white text-xs font-bold rounded-xl transition shadow-md shadow-sky-600/10">
                    Faça parte
                </button>
            </div>
        </div>

        <!-- Comunidade 4 -->
        <div class="bg-slate-900/30 border border-slate-800/80 rounded-3xl p-6 hover:border-slate-700 transition duration-200 flex flex-col justify-between group">
            <div class="space-y-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-2xl flex items-center justify-center text-xl shadow-inner flex-shrink-0">
                        🟢
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm font-extrabold text-slate-200 group-hover:text-sky-400 transition truncate">Google Cloud & NVIDIA</h3>
                        <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider font-mono">106.000+ members</span>
                    </div>
                </div>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Google Cloud and NVIDIA have partnered to create this community for developers, data scientists, AI/ML engineers, and technical practitioners.
                </p>
            </div>
            <div class="mt-6 flex justify-start">
                <button class="py-2 px-6 bg-sky-600 hover:bg-sky-500 text-white text-xs font-bold rounded-xl transition shadow-md shadow-sky-600/10">
                    Faça parte
                </button>
            </div>
        </div>

    </section>
</div>
