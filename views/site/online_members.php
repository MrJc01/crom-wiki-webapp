<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var array $onlineUsers */

use yii\helpers\Html;

$this->title = 'Membros Online — CROM';

// Lista de gradientes de cores premium para os avatares dos membros de forma pseudorrandômica
$avatarGradients = [
    'from-emerald-400 to-teal-600',
    'from-sky-400 to-indigo-600',
    'from-purple-400 to-pink-600',
    'from-amber-400 to-rose-600',
    'from-indigo-400 to-violet-600',
];
?>

<div class="max-w-5xl mx-auto space-y-8 select-none"
     x-data="{ 
         accentColor: localStorage.getItem('crom-accent') || 'sky'
     }">

    <!-- Cabeçalho Dinâmico Premium -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800/80 pb-6">
        <div class="flex items-center gap-3">
            <!-- Icone Reluzente Atômico -->
            <div class="h-10 w-10 rounded-2xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center justify-center text-xl shadow-inner shadow-emerald-500/5 animate-pulse">
                🟢
            </div>
            <div>
                <h2 class="text-xl font-bold text-white tracking-tight">Membros Online</h2>
                <p class="text-xs text-slate-500 mt-0.5">Lista de desenvolvedores e administradores com atividades de sessão atômicas registradas nos últimos 15 minutos.</p>
            </div>
        </div>

        <div class="flex items-center gap-2 self-start sm:self-auto bg-slate-900/60 border border-slate-800/80 px-3 py-1.5 rounded-xl font-mono text-[10px] font-extrabold text-slate-400">
            <span>SISTEMA ATIVO</span>
            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-ping"></span>
        </div>
    </div>

    <!-- Grid de Cartões de Usuário -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (empty($onlineUsers)): ?>
            <div class="col-span-full bg-slate-900/40 border border-slate-800 rounded-2xl p-12 text-center text-slate-500 text-xs font-semibold select-none flex flex-col items-center gap-3">
                <svg class="h-8 w-8 text-slate-600 animate-bounce" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z" />
                </svg>
                <span>Nenhum membro ativo detectado no momento.</span>
            </div>
        <?php else: ?>
            <?php foreach ($onlineUsers as $index => $u): ?>
                <?php 
                $gradient = $avatarGradients[$index % count($avatarGradients)]; 
                $initials = strtoupper(substr($u['username'], 0, 2));
                ?>
                <div class="bg-slate-900/30 border border-slate-800/80 hover:border-emerald-500/30 rounded-2xl p-5 flex items-center gap-4 hover:bg-slate-900/50 transition-all duration-300 backdrop-blur-sm group relative overflow-hidden">
                    
                    <!-- Glow radial sutil ao passar o mouse -->
                    <div class="absolute -right-6 -bottom-6 w-16 h-16 bg-emerald-500/5 rounded-full blur-md group-hover:scale-150 transition-all duration-500"></div>

                    <!-- Avatar Redondo Estilizado -->
                    <div class="relative flex-shrink-0">
                        <div class="h-11 w-11 rounded-xl bg-gradient-to-tr <?= $gradient ?> flex items-center justify-center text-sm font-extrabold text-white shadow-md shadow-black/35 transform group-hover:scale-105 transition-all duration-300 font-sans tracking-wider">
                            <?= $initials ?>
                        </div>
                        <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-emerald-500 ring-2 ring-slate-950 animate-pulse"></span>
                    </div>

                    <!-- Dados Cadastrais e Tempo de Atividade -->
                    <div class="flex-1 space-y-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-extrabold text-white truncate"><?= Html::encode($u['username']) ?></span>
                            <!-- Badge de Acesso Especial se for admin -->
                            <?php if ($u['username'] === 'admin'): ?>
                                <span class="px-1.5 py-0.2 rounded bg-amber-500/10 text-amber-400 border border-amber-500/20 text-[8px] font-extrabold font-mono tracking-wide uppercase">Admin</span>
                            <?php endif; ?>
                        </div>
                        <p class="text-[10px] font-bold text-slate-500 font-mono">
                            Última atividade: <span class="text-slate-400 group-hover:text-emerald-400 transition-colors duration-300"><?= Yii::$app->formatter->asRelativeTime($u['last_activity']) ?></span>
                        </p>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
