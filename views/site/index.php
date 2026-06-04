<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'CROM Ecosystem — Wiki & Recursos';
?>

<div class="min-h-screen bg-slate-950 text-slate-100 flex flex-col justify-between font-sans relative overflow-hidden">
    <!-- Efeito de Brilho Mesh de Fundo (Glow radial premium) -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[700px] h-[500px] bg-sky-500/10 rounded-full blur-[140px] pointer-events-none z-0"></div>
    <div class="absolute bottom-0 left-1/4 w-[400px] h-[400px] bg-indigo-500/5 rounded-full blur-[120px] pointer-events-none z-0"></div>

    <!-- Header/Navbar Pública -->
    <header class="w-full max-w-6xl mx-auto px-6 py-6 flex items-center justify-between border-b border-slate-900/60 flex-shrink-0 select-none z-10 relative">
        <div class="flex items-center gap-2.5 group">
            <div class="w-9 h-9 bg-sky-500/10 text-sky-400 rounded-xl flex items-center justify-center font-bold text-lg border border-sky-500/20 group-hover:border-sky-500/40 transition duration-300">
                Ω
            </div>
            <span class="text-xs font-extrabold tracking-widest uppercase text-slate-100 font-mono">CROM WIKI</span>
        </div>
        
        <div class="flex items-center gap-3">
            <?php if (Yii::$app->user->isGuest): ?>
                <a href="<?= Url::to(['/site/login']) ?>" 
                   class="py-2 px-4 bg-slate-900 border border-slate-800 hover:border-sky-500/40 text-slate-350 hover:text-sky-450 rounded-xl text-xs font-bold transition duration-300">
                    Entrar
                </a>
                <a href="<?= Url::to(['/site/register']) ?>" 
                   class="py-2.5 px-4.5 bg-gradient-to-r from-sky-650 to-indigo-650 hover:from-sky-500 hover:to-indigo-500 text-white rounded-xl text-xs font-extrabold transition duration-300 shadow-md shadow-sky-650/15">
                    Solicitar Ingresso
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
    <main class="flex-grow flex flex-col justify-center items-center py-16 px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center space-y-12 select-none">
            
            <!-- Badge de subdomínio premium -->
            <div class="inline-flex items-center gap-2 bg-gradient-to-r from-sky-500/5 to-indigo-500/5 text-sky-400 border border-sky-500/10 px-3.5 py-1.5 rounded-full text-[9px] font-mono font-bold tracking-widest uppercase shadow-md shadow-black/10">
                <span class="w-1.5 h-1.5 rounded-full bg-sky-400 anonymity animate-pulse"></span>
                WIKI.CROM.RUN — CENTRAL MODULAR DE WIKI & RECURSOS
            </div>

            <!-- Título e Slogan Principal -->
            <div class="space-y-6">
                <h1 class="text-4xl sm:text-5xl md:text-7xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-b from-slate-50 via-slate-100 to-slate-455 leading-tight font-sans">
                    Soberania tecnológica não se pede,<br class="hidden md:block">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-indigo-400">constrói-se.</span>
                </h1>
                <p class="max-w-2xl mx-auto text-slate-400 text-xs sm:text-sm md:text-base leading-relaxed font-sans font-medium">
                    Junte-se à CROM, uma comunidade descentralizada e horizontal inspirada em modelos como a Valve e o Bitcoin. Desenvolva com autonomia radical, obtenha servidores dedicados, APIs de IA integradas e acompanhe as documentações no wiki.
                </p>
            </div>

            <!-- Call to Actions -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-2">
                <?php if (Yii::$app->user->isGuest): ?>
                    <a href="<?= Url::to(['/site/register']) ?>" 
                       class="w-full sm:w-auto py-3 px-8 bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white rounded-xl text-xs font-extrabold uppercase tracking-widest shadow-lg shadow-sky-600/15 hover:shadow-sky-500/25 transition-all duration-300 transform active:scale-95 text-center">
                        Cadastre-se na CROM
                    </a>
                    <a href="<?= Url::to(['/site/login']) ?>" 
                       class="w-full sm:w-auto py-3 px-8 bg-slate-900/65 border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-white rounded-xl text-xs font-bold uppercase tracking-wider transition duration-300 shadow-md text-center">
                        Acessar Portal (Login)
                    </a>
                <?php else: ?>
                    <a href="<?= Url::to(['/site/index']) ?>" 
                       class="w-full sm:w-auto py-3 px-8 bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white rounded-xl text-sm font-bold shadow-lg shadow-sky-600/15 hover:shadow-sky-500/25 transition-all duration-300 transform active:scale-95 text-center">
                        Ir para o Dashboard
                    </a>
                <?php endif; ?>
            </div>

            <!-- Divisão dos Pilares e Benefícios de Membro -->
            <div class="space-y-6 pt-16">
                <div class="text-left max-w-lg">
                    <h3 class="text-[10px] font-extrabold text-sky-400 font-mono tracking-widest uppercase">Prerrogativas do Ecossistema</h3>
                    <h2 class="text-xl font-bold text-white tracking-tight">O que você ganha ao se registrar?</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left max-w-4xl mx-auto">
                    <!-- Pilar 1 -->
                    <div class="bg-gradient-to-br from-slate-900/30 to-slate-950 border border-slate-900/60 p-6 rounded-3xl space-y-3 hover:border-slate-800 transition duration-300 shadow-lg group">
                        <div class="w-10 h-10 bg-indigo-500/5 text-indigo-400 rounded-xl flex items-center justify-center border border-indigo-500/10 group-hover:border-indigo-500/30 transition duration-300">
                            🖥️
                        </div>
                        <h4 class="font-extrabold text-slate-200 text-sm">VPS Linux dedicada</h4>
                        <p class="text-[11px] text-slate-500 leading-relaxed font-sans font-medium">
                            Acesso SSH completo à infraestrutura isolada para rodar seus microsserviços, executar scripts e subir containers Docker rootless de forma livre.
                        </p>
                    </div>

                    <!-- Pilar 2 -->
                    <div class="bg-gradient-to-br from-slate-900/30 to-slate-950 border border-slate-900/60 p-6 rounded-3xl space-y-3 hover:border-slate-800 transition duration-300 shadow-lg group">
                        <div class="w-10 h-10 bg-purple-500/5 text-purple-400 rounded-xl flex items-center justify-center border border-purple-500/10 group-hover:border-purple-500/30 transition duration-300">
                            🤖
                        </div>
                        <h4 class="font-extrabold text-slate-200 text-sm">Créditos de IA (CromIA)</h4>
                        <p class="text-[11px] text-slate-500 leading-relaxed font-sans font-medium">
                            Acesso direto e gratuito a modelos de linguagem de ponta e playgrounds para testes direto do navegador ou via chamadas de API unificadas.
                        </p>
                    </div>

                    <!-- Pilar 3 -->
                    <div class="bg-gradient-to-br from-slate-900/30 to-slate-950 border border-slate-900/60 p-6 rounded-3xl space-y-3 hover:border-slate-800 transition duration-300 shadow-lg group">
                        <div class="w-10 h-10 bg-emerald-500/5 text-emerald-400 rounded-xl flex items-center justify-center border border-emerald-500/10 group-hover:border-emerald-500/30 transition duration-300">
                            📜
                        </div>
                        <h4 class="font-extrabold text-slate-200 text-sm">Contrato de Cuidado</h4>
                        <p class="text-[11px] text-slate-500 leading-relaxed font-sans font-medium">
                            Todo o seu tempo investido e impacto gerado no ecossistema são documentados e auditados de forma aberta e auditável permanentemente.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Rodapé -->
    <footer class="w-full max-w-6xl mx-auto px-6 py-6 border-t border-slate-900/60 text-center flex-shrink-0 relative z-10 select-none">
        <p class="text-[10px] text-slate-650 font-mono">
            &copy; 2026 CROM Ecosystem. Todos os direitos reservados. Soberania tecnológica garantida.
        </p>
    </footer>
</div>
