<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'CROM Ecosystem — Wiki & Recursos';
?>

<div class="min-h-screen bg-slate-950 text-slate-100 flex flex-col justify-between font-sans relative overflow-hidden">
    <!-- Efeito de Brilho Mesh de Fundo (Glow radial premium) -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[500px] h-[500px] bg-sky-500/10 rounded-full blur-[120px] pointer-events-none z-0"></div>
    <div class="absolute bottom-0 left-1/4 w-[300px] h-[300px] bg-indigo-500/5 rounded-full blur-[100px] pointer-events-none z-0"></div>

    <!-- Header/Navbar Pública -->
    <header class="w-full max-w-6xl mx-auto px-6 py-6 flex items-center justify-between border-b border-slate-900/60 flex-shrink-0 select-none z-10 relative">
        <div class="flex items-center gap-2 group">
            <div class="w-9 h-9 bg-sky-500/10 text-sky-400 rounded-xl flex items-center justify-center font-bold text-lg border border-sky-500/20 group-hover:border-sky-500/40 transition duration-300">
                Ω
            </div>
            <span class="text-sm font-extrabold tracking-wider uppercase text-slate-100 font-mono">CROM WIKI</span>
        </div>
        
        <div>
            <?php if (Yii::$app->user->isGuest): ?>
                <a href="<?= Url::to(['/site/login']) ?>" 
                   class="py-2.5 px-5 bg-slate-900 border border-slate-800 hover:border-sky-500/40 text-slate-300 hover:text-sky-400 rounded-xl text-xs font-bold transition duration-300 shadow-lg shadow-black/20">
                    Acessar o Portal
                </a>
            <?php else: ?>
                <a href="<?= Url::to(['/site/index']) ?>" 
                   class="py-2.5 px-5 bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white rounded-xl text-xs font-bold transition duration-300 shadow-lg shadow-sky-600/15">
                    Área de Membros
                </a>
            <?php endif; ?>
        </div>
    </header>

    <!-- Seção Hero Principal -->
    <main class="flex-grow flex items-center justify-center py-16 px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center space-y-8 select-none">
            
            <!-- Badge de subdomínio premium -->
            <div class="inline-flex items-center gap-2 bg-gradient-to-r from-sky-500/5 to-indigo-500/5 text-sky-400 border border-sky-500/10 px-3.5 py-1.5 rounded-full text-[10px] font-mono font-bold tracking-widest uppercase shadow-md shadow-black/10">
                <span class="w-1.5 h-1.5 rounded-full bg-sky-400 animate-pulse"></span>
                WIKI.CROM.RUN — CENTRAL MODULAR DE WIKI & RECURSOS
            </div>

            <!-- Título e Slogan Principal -->
            <h1 class="text-4xl sm:text-5xl md:text-7xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-b from-slate-50 via-slate-100 to-slate-400 leading-tight font-sans">
                Soberania tecnológica não se pede,<br class="hidden md:block">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-indigo-400">constrói-se.</span>
            </h1>

            <p class="max-w-2xl mx-auto text-slate-400 text-xs sm:text-sm md:text-base leading-relaxed font-sans font-medium">
                Bem-vindo ao **wiki.crom.run**, o portal interno modular e repositório de documentação oficial do ecossistema **[crom.run](https://crom.run)**. Gerencie recursos de VPS, consulte a árvore de diretórios GitOps e colabore na central de forma isolada e portável.
            </p>

            <!-- Call to Actions -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                <?php if (Yii::$app->user->isGuest): ?>
                    <a href="<?= Url::to(['/site/login']) ?>" 
                       class="w-full sm:w-auto py-3 px-8 bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white rounded-xl text-sm font-bold shadow-lg shadow-sky-600/15 hover:shadow-sky-500/25 transition-all duration-300 transform active:scale-95 text-center">
                        Acessar Portal Crom
                    </a>
                <?php else: ?>
                    <a href="<?= Url::to(['/site/index']) ?>" 
                       class="w-full sm:w-auto py-3 px-8 bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white rounded-xl text-sm font-bold shadow-lg shadow-sky-600/15 hover:shadow-sky-500/25 transition-all duration-300 transform active:scale-95 text-center">
                        Ir para o Dashboard
                    </a>
                <?php endif; ?>
                
                <a href="https://crom.run" 
                   target="_blank"
                   class="w-full sm:w-auto py-3 px-8 bg-slate-900/60 border border-slate-800/80 hover:border-slate-700 text-slate-300 hover:text-slate-200 rounded-xl text-sm font-bold transition duration-300 shadow-md text-center">
                    Conhecer crom.run
                </a>
            </div>

            <!-- Divisão dos Pilares/Recursos -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-12 text-left max-w-4xl mx-auto">
                <!-- Pilar 1 -->
                <div class="bg-gradient-to-br from-slate-900/30 to-slate-950 border border-slate-900/60 p-6 rounded-3xl space-y-3 hover:border-slate-800 transition duration-300 shadow-lg group">
                    <div class="w-10 h-10 bg-amber-500/5 text-amber-400 rounded-xl flex items-center justify-center border border-amber-500/10 group-hover:border-amber-500/30 transition duration-300">
                        🌐
                    </div>
                    <h4 class="font-extrabold text-slate-200 text-sm">crom.run</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed font-sans font-medium">
                        O site central do ecossistema CROM, gerenciando VPSs, infraestrutura soberana baseada em Docker Swarm e automações de deploy.
                    </p>
                </div>

                <!-- Pilar 2 -->
                <div class="bg-gradient-to-br from-slate-900/30 to-slate-950 border border-slate-900/60 p-6 rounded-3xl space-y-3 hover:border-slate-800 transition duration-300 shadow-lg group">
                    <div class="w-10 h-10 bg-sky-500/5 text-sky-400 rounded-xl flex items-center justify-center border border-sky-500/10 group-hover:border-sky-500/30 transition duration-300">
                        📖
                    </div>
                    <h4 class="font-extrabold text-slate-200 text-sm">wiki.crom.run</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed font-sans font-medium">
                        Este portal. Funciona como a central GitOps, permitindo edições colaborativas seguras assinadas via Token OAuth e cache RAG local.
                    </p>
                </div>

                <!-- Pilar 3 -->
                <div class="bg-gradient-to-br from-slate-900/30 to-slate-950 border border-slate-900/60 p-6 rounded-3xl space-y-3 hover:border-slate-800 transition duration-300 shadow-lg group">
                    <div class="w-10 h-10 bg-indigo-500/5 text-indigo-400 rounded-xl flex items-center justify-center border border-indigo-500/10 group-hover:border-indigo-500/30 transition duration-300">
                        📄
                    </div>
                    <h4 class="font-extrabold text-slate-200 text-sm">Páginas Documentadas</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed font-sans font-medium">
                        Central modular (`page_crud`) integrada ao SQLite para criar, gerenciar e editar documentos colaborativos em Markdown sob governança eleita.
                    </p>
                </div>
            </div>
        </div>
    </main>

    <!-- Rodapé -->
    <footer class="w-full max-w-6xl mx-auto px-6 py-6 border-t border-slate-900/60 text-center flex-shrink-0 relative z-10 select-none">
        <p class="text-[10px] text-slate-600 font-mono">
            &copy; 2026 CROM Ecosystem. Todos os direitos reservados. Soberania tecnológica garantida.
        </p>
    </footer>
</div>
