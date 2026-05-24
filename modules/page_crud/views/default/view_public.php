<?php
/** @var yii\web\View $this */
/** @var app\modules\page_crud\models\PageDocumented $page */
/** @var string $adminNames */

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = $page->title . ' — CROM Documentação Pública';
?>

<div class="min-h-screen w-full bg-slate-950 text-slate-100 flex flex-col justify-between py-12 px-4 sm:px-6 lg:px-8 select-none font-sans"
     x-data="{
        content: <?= json_encode($page->content) ?>,
        init() {
            // Renderiza o Markdown na div leitora
            this.$refs.reader.innerHTML = marked.parse(this.content || '');
        }
     }">
     
     <!-- 1. Header do Leitor Público -->
     <header class="max-w-3xl w-full mx-auto flex items-center justify-between border-b border-slate-800/80 pb-6 mb-10">
         <a href="<?= Url::to(['/site/login']) ?>" class="flex items-center gap-2 text-slate-400 hover:text-sky-400 transition duration-300 group">
             <!-- Ícone Infinito (Logo do Portal) -->
             <svg class="w-6 h-6 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                 <path d="M7 9C5.34315 9 4 10.3431 4 12C4 13.6569 5.34315 15 7 15C8.65685 15 10 13.6569 10 12C10 10.3431 8.65685 9 7 9Z" stroke="#4285F4" stroke-width="2.5"/>
                 <path d="M17 9C15.3431 9 14 10.3431 14 12C14 13.6569 15.3431 15 17 15C18.6569 15 20 13.6569 20 12C20 10.3431 18.6569 9 17 9Z" stroke="#34A853" stroke-width="2.5"/>
             </svg>
             <span class="text-xs font-bold font-mono tracking-widest uppercase">CROM Developer</span>
         </a>
         
         <a href="<?= Url::to(['/site/login']) ?>" 
            class="text-xs font-bold text-sky-400 hover:text-sky-300 border border-sky-500/20 hover:border-sky-500/40 bg-sky-500/5 px-4 py-2 rounded-full transition duration-300 shadow-md">
             🔑 Entrar no Portal
         </a>
     </header>

     <!-- 2. Conteúdo de Leitura Minimalista Premium -->
     <main class="max-w-3xl w-full mx-auto bg-slate-900/40 border border-slate-800/80 rounded-3xl p-8 sm:p-12 shadow-2xl backdrop-blur-sm relative overflow-hidden flex-grow mb-12">
         <!-- Decoração premium no fundo -->
         <div class="absolute -right-24 -top-24 w-48 h-48 bg-sky-500/5 rounded-full blur-3xl"></div>
         
         <!-- Metadados do Artigo -->
         <div class="space-y-4 mb-8">
             <div class="flex flex-wrap items-center gap-2 text-[10px] font-mono select-none">
                 <span class="px-2.5 py-0.5 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 rounded-full font-bold uppercase tracking-wider">
                     <?= Html::encode($page->category) ?>
                 </span>
                 <span class="text-slate-500">&bull;</span>
                 <span class="text-slate-400">📅 <?= date('d/m/Y H:i', $page->updated_at) ?></span>
                 <span class="text-slate-500">&bull;</span>
                 <span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full font-bold uppercase tracking-wider flex items-center gap-1">
                     <span class="w-1 h-1 rounded-full bg-emerald-500"></span>
                     Publico
                 </span>
             </div>
             
             <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-100 tracking-tight leading-tight">
                 <?= Html::encode($page->title) ?>
             </h1>
             
             <!-- Donos e Criadores -->
             <div class="flex items-center gap-3 pt-2 text-xs text-slate-400 select-none border-b border-slate-800/40 pb-6">
                 <div class="w-8 h-8 rounded-full bg-slate-800 text-slate-300 flex items-center justify-center font-bold font-mono border border-slate-700">
                     <?= strtoupper(substr($page->created_by, 0, 2)) ?>
                 </div>
                 <div>
                     <p class="font-bold text-slate-200">Criado por: <span class="text-slate-400"><?= Html::encode($page->created_by) ?></span></p>
                     <p class="text-[10px] text-slate-500 font-mono mt-0.5">Responsáveis: <span class="text-sky-400/90 font-bold"><?= Html::encode($adminNames) ?></span></p>
                 </div>
             </div>
         </div>

         <!-- Div do Markdown Renderizado -->
         <article ref="reader" class="prose prose-invert max-w-none text-slate-300 font-sans leading-relaxed
                      prose-headings:text-slate-100 prose-headings:font-extrabold prose-headings:tracking-tight
                      prose-h1:text-2xl prose-h2:text-xl prose-h3:text-lg
                      prose-a:text-sky-400 hover:prose-a:text-sky-300 prose-a:font-semibold prose-a:no-underline prose-a:border-b prose-a:border-sky-500/30 hover:prose-a:border-sky-400/80 prose-a:transition-all
                      prose-strong:text-slate-200 prose-strong:font-bold
                      prose-code:text-sky-300 prose-code:font-mono prose-code:text-xs prose-code:bg-slate-950 prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded-md prose-code:before:content-none prose-code:after:content-none
                      prose-pre:bg-slate-950 prose-pre:border prose-pre:border-slate-850 prose-pre:rounded-2xl prose-pre:p-4 prose-pre:shadow-inner
                      prose-blockquote:border-l-4 prose-blockquote:border-sky-500 prose-blockquote:bg-sky-500/5 prose-blockquote:px-4 prose-blockquote:py-2 prose-blockquote:rounded-r-xl prose-blockquote:text-slate-400 prose-blockquote:italic">
              <!-- Renderização em tempo real via AlpineJS + MarkedJS -->
         </article>
     </main>

     <!-- 3. Footer de Conversão Premium -->
     <footer class="max-w-3xl w-full mx-auto text-center space-y-4 border-t border-slate-900 pt-8 select-none">
         <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
             Este documento faz parte da mainframe de governança descentralizada **CROM**. Para colaborar, revisar ou criar novos artigos documentados locais, faça login no painel de membros.
         </p>
         <div class="inline-flex gap-4 items-center pt-2">
             <a href="<?= Url::to(['/site/login']) ?>" 
                class="py-2.5 px-6 bg-gradient-to-r from-sky-500 to-indigo-600 hover:from-sky-400 hover:to-indigo-500 text-slate-100 text-xs font-bold rounded-xl transition duration-300 shadow-lg shadow-sky-950/30 flex items-center gap-2">
                 🔑 Acessar Mainframe
                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                   <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                 </svg>
             </a>
         </div>
         <p class="text-[9px] font-mono text-slate-700 tracking-wider pt-4 uppercase">
             Portal CROM &copy; <?= date('Y') ?> &bull; Neural Governance Engine
         </p>
     </footer>
</div>
