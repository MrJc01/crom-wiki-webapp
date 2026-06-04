<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'CROM Ecosystem — Autonomia Técnica & Soberania Digital';
?>

<div class="min-h-screen bg-slate-950 text-slate-100 flex flex-col justify-between font-sans relative overflow-x-hidden">
    <!-- Efeitos de Brilho Mesh de Fundo (Glow radiais premium) -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[600px] bg-sky-500/10 rounded-full blur-[140px] pointer-events-none z-0"></div>
    <div class="absolute top-[1200px] right-[-100px] w-[500px] h-[500px] bg-indigo-500/5 rounded-full blur-[130px] pointer-events-none z-0"></div>
    <div class="absolute top-[2600px] left-[-200px] w-[600px] h-[600px] bg-purple-500/5 rounded-full blur-[150px] pointer-events-none z-0"></div>
    <div class="absolute bottom-0 left-1/4 w-[500px] h-[500px] bg-sky-500/5 rounded-full blur-[120px] pointer-events-none z-0"></div>

    <!-- Header/Navbar Pública -->
    <header class="w-full max-w-6xl mx-auto px-6 py-6 flex items-center justify-between border-b border-slate-900/60 flex-shrink-0 select-none z-10 relative">
        <div class="flex items-center gap-2.5 group">
            <div class="w-9 h-9 bg-sky-500/10 text-sky-400 rounded-xl flex items-center justify-center font-bold text-lg border border-sky-500/20 group-hover:border-sky-500/40 transition duration-300">
                Ω
            </div>
            <span class="text-xs font-extrabold tracking-widest uppercase text-slate-100 font-mono">CROM ECOSYSTEM</span>
        </div>
        
        <div class="flex items-center gap-3">
            <?php if (Yii::$app->user->isGuest): ?>
                <a href="<?= Url::to(['/site/login']) ?>" 
                   class="py-2 px-4 bg-slate-900 border border-slate-800 hover:border-sky-500/40 text-slate-300 hover:text-sky-400 rounded-xl text-xs font-bold transition duration-300">
                    Entrar
                </a>
                <a href="<?= Url::to(['/site/register']) ?>" 
                   class="py-2.5 px-4.5 bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white rounded-xl text-xs font-extrabold transition duration-300 shadow-md shadow-sky-600/15">
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

    <!-- Conteúdo Principal -->
    <main class="flex-grow relative z-10">
        
        <!-- SECTION 1: HERO -->
        <section class="max-w-5xl mx-auto py-20 px-6 text-center space-y-8 select-none">
            <!-- Badge de subdomínio premium -->
            <div class="inline-flex items-center gap-2 bg-gradient-to-r from-sky-500/5 to-indigo-500/5 text-sky-400 border border-sky-500/10 px-3.5 py-1.5 rounded-full text-[9px] font-mono font-bold tracking-widest uppercase shadow-md shadow-black/10">
                <span class="w-1.5 h-1.5 rounded-full bg-sky-400 anonymity animate-pulse"></span>
                CROM.RUN — AUTONOMIA RADICAL & SOBERANIA DIGITAL
            </div>

            <!-- Título e Slogan Principal -->
            <div class="space-y-6">
                <h1 class="text-4xl sm:text-6xl md:text-8xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-b from-slate-50 via-slate-100 to-slate-400 leading-[1.1] font-sans">
                    Soberania tecnológica<br>não se pede, <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 via-indigo-400 to-purple-400">constrói-se.</span>
                </h1>
                <p class="max-w-2xl mx-auto text-slate-400 text-xs sm:text-sm md:text-base leading-relaxed font-sans font-medium">
                    A CROM não é uma empresa de software. É um protocolo organizacional e um ecossistema que existe para reduzir a latência entre a intenção humana e a execução no mundo material. Desenvolva com autonomia total, possua sua infraestrutura e gerencie seus dados.
                </p>
            </div>

            <!-- Call to Actions -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                <?php if (Yii::$app->user->isGuest): ?>
                    <a href="<?= Url::to(['/site/register']) ?>" 
                       class="w-full sm:w-auto py-3.5 px-8 bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white rounded-xl text-xs font-extrabold uppercase tracking-widest shadow-lg shadow-sky-600/15 hover:shadow-sky-500/25 transition-all duration-300 transform active:scale-95 text-center">
                        Cadastre-se na CROM
                    </a>
                    <a href="<?= Url::to(['/site/login']) ?>" 
                       class="w-full sm:w-auto py-3.5 px-8 bg-slate-900/60 border border-slate-800 hover:border-slate-700 text-slate-350 hover:text-white rounded-xl text-xs font-bold uppercase tracking-wider transition duration-300 shadow-md text-center">
                        Acessar Portal (Login)
                    </a>
                <?php else: ?>
                    <a href="<?= Url::to(['/site/index']) ?>" 
                       class="w-full sm:w-auto py-3.5 px-8 bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white rounded-xl text-xs font-bold uppercase tracking-wider shadow-lg shadow-sky-600/15 hover:shadow-sky-500/25 transition-all duration-300 transform active:scale-95 text-center">
                        Ir para o Dashboard
                    </a>
                <?php endif; ?>
            </div>
        </section>

        <!-- SECTION 2: MANIFESTO & FILOSOFIA -->
        <section class="max-w-6xl mx-auto px-6 py-16 border-t border-slate-900/60">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Coluna 1: Títulos e Filosofia -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="space-y-2">
                        <span class="text-[10px] font-extrabold tracking-widest text-sky-400 font-mono uppercase">0x1 — Manifesto</span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-tight">
                            Descolonização Neural &<br>Alquimia do Bit
                        </h2>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                        A CROM rejeita a subordinação corporativa. Operamos como um organismo descentralizado e horizontal inspirado nos modelos resilientes da <strong>Valve</strong>, do <strong>Bitcoin</strong> e da <strong>Linux Foundation</strong>.
                    </p>
                    <div class="p-4 bg-slate-900/40 border border-slate-800 rounded-2xl space-y-2 text-left">
                        <h4 class="text-xs font-bold text-sky-300 flex items-center gap-1.5">
                            <span>🧠</span> O Mutunicismo
                        </h4>
                        <p class="text-[11px] text-slate-400 leading-relaxed">
                            Onde o indivíduo é soberano e único, mas sua força é amplificada pela troca mútua. Sem corporações monopolistas ditando as regras de sua cognição.
                        </p>
                    </div>
                </div>

                <!-- Coluna 2: 4 Pilares de Pensamento -->
                <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Pilar 1 -->
                    <div class="bg-gradient-to-br from-slate-900/30 to-slate-950 border border-slate-800 p-5 rounded-2xl space-y-3 hover:border-slate-700 transition duration-300 group">
                        <div class="w-9 h-9 bg-indigo-500/10 text-indigo-400 rounded-lg flex items-center justify-center font-mono text-xs font-bold border border-indigo-500/20 group-hover:border-indigo-500/40 transition">
                            0x0
                        </div>
                        <h3 class="font-bold text-slate-200 text-xs uppercase tracking-wider">Protopia Constante</h3>
                        <p class="text-[11px] text-slate-500 leading-relaxed">
                            Buscamos o progresso incremental contínuo. Cada linha de código é uma refatoração da realidade. Nosso lema é "1% melhor a cada ciclo".
                        </p>
                    </div>

                    <!-- Pilar 2 -->
                    <div class="bg-gradient-to-br from-slate-900/30 to-slate-950 border border-slate-800 p-5 rounded-2xl space-y-3 hover:border-slate-700 transition duration-300 group">
                        <div class="w-9 h-9 bg-purple-500/10 text-purple-400 rounded-lg flex items-center justify-center font-mono text-xs font-bold border border-purple-500/20 group-hover:border-purple-500/40 transition">
                            0x1
                        </div>
                        <h3 class="font-bold text-slate-200 text-xs uppercase tracking-wider">Alquimia do Bit</h3>
                        <p class="text-[11px] text-slate-500 leading-relaxed">
                            Open source como infraestrutura estratégica, e não como caridade. Transformação de conhecimento livre em soberania digital individual.
                        </p>
                    </div>

                    <!-- Pilar 3 -->
                    <div class="bg-gradient-to-br from-slate-900/30 to-slate-950 border border-slate-800 p-5 rounded-2xl space-y-3 hover:border-slate-700 transition duration-300 group">
                        <div class="w-9 h-9 bg-emerald-500/10 text-emerald-400 rounded-lg flex items-center justify-center font-mono text-xs font-bold border border-emerald-500/20 group-hover:border-emerald-500/40 transition">
                            0x2
                        </div>
                        <h3 class="font-bold text-slate-200 text-xs uppercase tracking-wider">Mente Simbiótica</h3>
                        <p class="text-[11px] text-slate-500 leading-relaxed">
                            O software deve operar como extensão direta de sua consciência. Uma mitocôndria lógica para processar dados em segundo plano.
                        </p>
                    </div>

                    <!-- Pilar 4 -->
                    <div class="bg-gradient-to-br from-slate-900/30 to-slate-950 border border-slate-800 p-5 rounded-2xl space-y-3 hover:border-slate-700 transition duration-300 group">
                        <div class="w-9 h-9 bg-amber-500/10 text-amber-400 rounded-lg flex items-center justify-center font-mono text-xs font-bold border border-amber-500/20 group-hover:border-amber-500/40 transition">
                            0x3
                        </div>
                        <h3 class="font-bold text-slate-200 text-xs uppercase tracking-wider">Infra Local-First</h3>
                        <p class="text-[11px] text-slate-500 leading-relaxed">
                            Local-first significa controle real sobre inteligência e dados. Se você aluga sua inteligência de corporações, é inquilino de si mesmo.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 3: AS TRÊS CAMADAS DE GOVERNANÇA -->
        <section class="max-w-6xl mx-auto px-6 py-16 border-t border-slate-900/60">
            <div class="text-center space-y-3 mb-12">
                <span class="text-[10px] font-extrabold tracking-widest text-indigo-400 font-mono uppercase">Governança Horizontal</span>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight">As Três Camadas do Crisol</h2>
                <p class="text-xs text-slate-400 max-w-xl mx-auto leading-relaxed">
                    Não existem cargos gerenciais na CROM. A governança é dividida em estágios de comprometimento, responsabilidade técnica e auditabilidade viva.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Camada 1: Forja -->
                <div class="bg-gradient-to-b from-slate-900/40 to-slate-950/80 border border-slate-800 hover:border-slate-700/65 rounded-[32px] p-6 space-y-6 transition duration-300 relative flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold font-mono tracking-widest uppercase text-amber-400 bg-amber-500/10 border border-amber-500/20 px-2.5 py-0.5 rounded-full">
                                🔨 Camada 1
                            </span>
                            <span class="text-xs text-slate-500 font-mono">Porta de Entrada</span>
                        </div>
                        <div class="space-y-2">
                            <h3 class="text-lg font-extrabold text-white tracking-tight">A Forja</h3>
                            <p class="text-[11px] text-slate-450 leading-relaxed">
                                Espaço de validação mútua para novos construtores. Aqui você integra scripts, testa novas ideias e se familiariza com a comunidade.
                            </p>
                        </div>
                        <div class="border-t border-slate-900/80 pt-4 space-y-3">
                            <div>
                                <h4 class="text-[10px] font-bold text-slate-300 uppercase tracking-widest font-mono">Prerrogativas (Direitos)</h4>
                                <ul class="list-disc pl-4 text-[10.5px] text-slate-450 space-y-1 mt-1">
                                    <li>Acesso à VPS dedicada Forja (`vps2.crom.me`)</li>
                                    <li>Uso de APIs unificadas da CromIA (tier Forja)</li>
                                    <li>Criação de notas e arquivos pessoais na Wiki</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="text-[10px] font-bold text-slate-300 uppercase tracking-widest font-mono">Responsabilidades</h4>
                                <ul class="list-disc pl-4 text-[10.5px] text-slate-455 space-y-1 mt-1">
                                    <li>Criar automações e códigos open source úteis</li>
                                    <li>Debater e colaborar ativamente no `#forja`</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Camada 2: Pilares -->
                <div class="bg-gradient-to-b from-slate-900/40 to-slate-950/80 border border-sky-500/20 hover:border-sky-500/35 rounded-[32px] p-6 space-y-6 transition duration-300 relative flex flex-col justify-between shadow-lg shadow-sky-500/2">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold font-mono tracking-widest uppercase text-sky-400 bg-sky-500/10 border border-sky-500/20 px-2.5 py-0.5 rounded-full">
                                🏛️ Camada 2
                            </span>
                            <span class="text-xs text-sky-400/80 font-mono">Core Operacional</span>
                        </div>
                        <div class="space-y-2">
                            <h3 class="text-lg font-extrabold text-white tracking-tight">Os Pilares</h3>
                            <p class="text-[11px] text-slate-450 leading-relaxed">
                                A espinha dorsal da CROM. Responsáveis por liderar projetos de produção, participar de rituais e manter a infraestrutura de dados viva.
                            </p>
                        </div>
                        <div class="border-t border-slate-900/80 pt-4 space-y-3">
                            <div>
                                <h4 class="text-[10px] font-bold text-slate-300 uppercase tracking-widest font-mono">Prerrogativas (Direitos)</h4>
                                <ul class="list-disc pl-4 text-[10.5px] text-slate-450 space-y-1 mt-1">
                                    <li>Escrita na Wiki GitOps + Voto ativo</li>
                                    <li>VPS dedicada aos Pilares (`vps1.crom.me`)</li>
                                    <li>Contrato de Cuidado e quotas de Vesting</li>
                                    <li>Cotas mais altas de tokens da CromIA</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="text-[10px] font-bold text-slate-300 uppercase tracking-widest font-mono">Responsabilidades</h4>
                                <ul class="list-disc pl-4 text-[10.5px] text-slate-455 space-y-1 mt-1">
                                    <li>Ownership de microsserviços críticos</li>
                                    <li>Mentoria e orientação técnica da Forja</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Camada 3: Guardiões -->
                <div class="bg-gradient-to-b from-slate-900/40 to-slate-950/80 border border-slate-800 hover:border-slate-700/65 rounded-[32px] p-6 space-y-6 transition duration-300 relative flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold font-mono tracking-widest uppercase text-purple-400 bg-purple-500/10 border border-purple-500/20 px-2.5 py-0.5 rounded-full">
                                🛡️ Camada 3
                            </span>
                            <span class="text-xs text-slate-500 font-mono">Zeladoria & Cofre</span>
                        </div>
                        <div class="space-y-2">
                            <h3 class="text-lg font-extrabold text-white tracking-tight">Os Guardiões</h3>
                            <p class="text-[11px] text-slate-455 leading-relaxed">
                                Curadores encarregados de salvaguardar a visão de longo prazo, gerenciar o Cofre, a segurança estrutural e a integridade do manifesto.
                            </p>
                        </div>
                        <div class="border-t border-slate-900/80 pt-4 space-y-3">
                            <div>
                                <h4 class="text-[10px] font-bold text-slate-300 uppercase tracking-widest font-mono">Prerrogativas (Direitos)</h4>
                                <ul class="list-disc pl-4 text-[10.5px] text-slate-455 space-y-1 mt-1">
                                    <li>Acesso completo de infra (Dokploy/Root)</li>
                                    <li>Poder de veto constitucional e de regras</li>
                                    <li>Administração do Cofre (recursos)</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="text-[10px] font-bold text-slate-300 uppercase tracking-widest font-mono">Responsabilidades</h4>
                                <ul class="list-disc pl-4 text-[10.5px] text-slate-455 space-y-1 mt-1">
                                    <li>Garantir a execução do Contrato de Cuidado</li>
                                    <li>Monitoramento de limites de custos e tokens</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 4: CONTRATO DE CUIDADO & VESTING -->
        <section class="max-w-6xl mx-auto px-6 py-16 border-t border-slate-900/60 bg-slate-900/10 rounded-[32px] border border-slate-900/40 backdrop-blur-sm">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <div class="space-y-2">
                        <span class="text-[10px] font-extrabold tracking-widest text-emerald-400 font-mono uppercase">Engenharia de Retribuição</span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-tight">
                            Contrato de Cuidado &<br>Vesting de Impacto
                        </h2>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                        A CROM rejeita o rompimento corporativo clássico onde a história do desenvolvedor é apagada da noite para o dia. Adotamos o princípio de <strong>Retribuição Perpétua</strong> através de métricas de impacto transparentes baseadas em Git.
                    </p>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="border-l-2 border-emerald-500/40 pl-3">
                            <span class="block text-[10px] font-mono font-bold text-slate-550 uppercase tracking-wider">Tempo</span>
                            <span class="text-[11px] text-slate-400 leading-normal">Tempo de contribuição ativa no ecossistema CROM.</span>
                        </div>
                        <div class="border-l-2 border-emerald-500/40 pl-3">
                            <span class="block text-[10px] font-mono font-bold text-slate-550 uppercase tracking-wider">Auditoria Viva</span>
                            <span class="text-[11px] text-slate-400 leading-normal">Mapeamento auditável de commits, PRs e documentações.</span>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-950/40 border border-slate-900 p-6 rounded-2xl space-y-4">
                    <h3 class="text-sm font-bold text-slate-200 uppercase tracking-wider font-mono">🔑 O Passivo de Gratidão</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Se um Pilar da comunidade contribuir significativamente para um microsserviço comercial ou projeto B2B e decidir se desligar, ele **mantém** seus direitos de participação residual e de vesting. Seu impacto histórico é preservado permanentemente nos registros Git da Wiki.
                    </p>
                    <div class="border-t border-slate-900 pt-4 space-y-2.5">
                        <h4 class="text-[10px] font-bold text-slate-350 uppercase tracking-widest font-mono">Regras de Desligamento:</h4>
                        <div class="space-y-1.5 text-[10px] text-slate-500 font-sans">
                            <p>🚪 <strong>Saída Voluntária:</strong> Histórico e participação residual do Vesting salvaguardados.</p>
                            <p>⏳ <strong>Inatividade:</strong> 30 dias sem commit ou status remove acessos, mantendo o histórico amigável.</p>
                            <p>⚠️ <strong>Violação:</strong> Ações nocivas (vazamento de chaves ou abuso de VPS) revogam acessos e geram registros públicos.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 5: RITUAIS & CADÊNCIAS -->
        <section class="max-w-6xl mx-auto px-6 py-16 border-t border-slate-900/60">
            <div class="text-center space-y-3 mb-10">
                <span class="text-[10px] font-extrabold tracking-widest text-purple-400 font-mono uppercase">A Engrenagem CROM</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-tight">Rituais e Cadências Regulares</h2>
                <p class="text-xs text-slate-400 max-w-lg mx-auto">
                    A descentralização exige cadência clara. Não gostamos de reuniões desnecessárias, por isso estruturamos rituais focados em transparência assíncrona.
                </p>
            </div>

            <!-- Tabela de Rituais Responsiva -->
            <div class="w-full overflow-x-auto border border-slate-800 rounded-3xl bg-slate-950/40">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                        <tr class="border-b border-slate-800 bg-slate-900/20 text-[10px] font-bold text-slate-300 uppercase tracking-widest font-mono select-none">
                            <th class="p-4">Ritual</th>
                            <th class="p-4">Cadência</th>
                            <th class="p-4">Formato</th>
                            <th class="p-4">Participantes</th>
                            <th class="p-4">Propósito</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs text-slate-400 divide-y divide-slate-900">
                        <tr class="hover:bg-slate-900/10 transition">
                            <td class="p-4 font-bold text-slate-200 font-sans">Status Semanal</td>
                            <td class="p-4 font-mono text-[10.5px]">Sexta / Sábado</td>
                            <td class="p-4 text-[11px]">Assíncrono (`status.md`)</td>
                            <td class="p-4 text-[11px]">Todos os Membros</td>
                            <td class="p-4 text-[11px] text-slate-500">Transparência. Saber o progresso geral sem chamadas chatas.</td>
                        </tr>
                        <tr class="hover:bg-slate-900/10 transition">
                            <td class="p-4 font-bold text-slate-200 font-sans">Revisão de Metas</td>
                            <td class="p-4 font-mono text-[10.5px]">1º dia útil do mês</td>
                            <td class="p-4 text-[11px]">Assíncrono (`metas.md`)</td>
                            <td class="p-4 text-[11px]">Todos os Membros</td>
                            <td class="p-4 text-[11px] text-slate-500">Garante foco operacional e clareza a curto/médio prazo.</td>
                        </tr>
                        <tr class="hover:bg-slate-900/10 transition">
                            <td class="p-4 font-bold text-slate-200 font-sans">Sync de Guardiões</td>
                            <td class="p-4 font-mono text-[10.5px]">Quinzenal</td>
                            <td class="p-4 text-[11px]">Síncrono (Discord Call)</td>
                            <td class="p-4 text-[11px] text-purple-400 font-semibold">Guardiões</td>
                            <td class="p-4 text-[11px] text-slate-500">Decisões de segurança e revisão rápida de infraestrutura crítica.</td>
                        </tr>
                        <tr class="hover:bg-slate-900/10 transition">
                            <td class="p-4 font-bold text-slate-200 font-sans">Auditoria de Infra</td>
                            <td class="p-4 font-mono text-[10.5px]">Quinzenal</td>
                            <td class="p-4 text-[11px]">Assíncrono</td>
                            <td class="p-4 text-[11px]">Responsável de Ops</td>
                            <td class="p-4 text-[11px] text-slate-500">Monitorar servidores VPS e chaves de tokens da CromIA.</td>
                        </tr>
                        <tr class="hover:bg-slate-900/10 transition">
                            <td class="p-4 font-bold text-slate-200 font-sans">Retrospectiva</td>
                            <td class="p-4 font-mono text-[10.5px]">Último dia do mês</td>
                            <td class="p-4 text-[11px]">Flexível (Discord/Texto)</td>
                            <td class="p-4 text-[11px]">Recomendado</td>
                            <td class="p-4 text-[11px] text-slate-500">Aprender com os erros e reajustar o fluxo para o próximo ciclo.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- SECTION 6: ECOSSISTEMA DE FERRAMENTAS LOCAIS & STACK -->
        <section class="max-w-6xl mx-auto px-6 py-16 border-t border-slate-900/60">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                
                <!-- Coluna 1: Ferramentas Locais -->
                <div class="lg:col-span-6 space-y-6">
                    <div class="space-y-2">
                        <span class="text-[10px] font-extrabold tracking-widest text-sky-400 font-mono uppercase">Conexão VPS</span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-tight">
                            Ambiente Local & CLIs Próprias
                        </h2>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Ao se conectar no cluster da CROM via SSH, você dispensa comandos complexos. Disponibilizamos ferramentas nativas exclusivas para gerenciar seu espaço:
                    </p>

                    <div class="space-y-4">
                        <div class="flex gap-4">
                            <span class="text-2xl flex-shrink-0">💻</span>
                            <div>
                                <h4 class="text-xs font-bold text-slate-200 uppercase tracking-wider font-mono">CROM CLI (`./crom.sh`)</h4>
                                <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                                    Navegador de terminal interativo para ler e editar artigos na Wiki GitOps. Commits e pushes são automatizados no salvamento do arquivo.
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <span class="text-2xl flex-shrink-0">⚡</span>
                            <div>
                                <h4 class="text-xs font-bold text-slate-200 uppercase tracking-wider font-mono">CROM Workspace (`crom-ws`)</h4>
                                <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                                    Crie projetos estruturados (`crom-ws init`), suba containers isolados Podman e publique portas web com HTTPS/SSL automáticos.
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <span class="text-2xl flex-shrink-0">🛡️</span>
                            <div>
                                <h4 class="text-xs font-bold text-slate-200 uppercase tracking-wider font-mono">Rosa IA SRE Integrada</h4>
                                <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                                    Suporte especializado direto no terminal. Pressione `c` no CLI para interagir com a Rosa (Gemini 2.5 Flash), alimentada com toda a Wiki.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Coluna 2: Stack Tecnológico -->
                <div class="lg:col-span-6 space-y-6">
                    <div class="space-y-2">
                        <span class="text-[10px] font-extrabold tracking-widest text-indigo-400 font-mono uppercase">Arquitetura</span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-tight">
                            Tecnologias do Ecossistema
                        </h2>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-900/30 border border-slate-800 p-4 rounded-xl">
                            <span class="text-[9px] font-mono font-bold text-slate-550 uppercase tracking-wider block">Linguagens Core</span>
                            <span class="text-xs text-slate-300 font-bold block mt-1">PHP (Yii2), Rust, Go</span>
                            <span class="text-[10px] text-slate-500 block mt-0.5 leading-relaxed">Desempenho de sistema, automações e portal web robusto.</span>
                        </div>
                        <div class="bg-slate-900/30 border border-slate-800 p-4 rounded-xl">
                            <span class="text-[9px] font-mono font-bold text-slate-550 uppercase tracking-wider block">Interface Web</span>
                            <span class="text-xs text-slate-300 font-bold block mt-1">Alpine.js, HTMX</span>
                            <span class="text-[10px] text-slate-500 block mt-0.5 leading-relaxed">Foco em SPA server-driven leve, rápido e responsivo.</span>
                        </div>
                        <div class="bg-slate-900/30 border border-slate-800 p-4 rounded-xl">
                            <span class="text-[9px] font-mono font-bold text-slate-550 uppercase tracking-wider block">Containers</span>
                            <span class="text-xs text-slate-300 font-bold block mt-1">Podman Rootless</span>
                            <span class="text-[10px] text-slate-500 block mt-0.5 leading-relaxed">Isolamento de privilégios contra escalação root no sistema local.</span>
                        </div>
                        <div class="bg-slate-900/30 border border-slate-800 p-4 rounded-xl">
                            <span class="text-[9px] font-mono font-bold text-slate-550 uppercase tracking-wider block">Orquestração</span>
                            <span class="text-xs text-slate-300 font-bold block mt-1">Systemd Quadlets</span>
                            <span class="text-[10px] text-slate-500 block mt-0.5 leading-relaxed">Processos nativos orquestrados e persistentes com reinicialização automática.</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 7: COMUNIDADE & LINKS DE ACESSO -->
        <section class="max-w-5xl mx-auto px-6 py-20 text-center space-y-8 select-none border-t border-slate-900/60">
            <div class="space-y-4">
                <span class="text-xs font-bold text-indigo-400 font-mono uppercase tracking-widest">Quer fazer parte?</span>
                <h2 class="text-3xl md:text-5xl font-extrabold text-slate-100 tracking-tight leading-tight">Junte-se à nossa colmeia digital</h2>
                <p class="max-w-xl mx-auto text-slate-400 text-xs sm:text-sm">
                    A maior parte de nossas discussões diárias, rituais e debates acontecem em nossos canais oficiais. Escolha um para iniciar.
                </p>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="https://discord.com/invite/4b5wqdxreZ" 
                   target="_blank"
                   class="inline-flex py-3 px-6 bg-slate-900 border border-slate-800 hover:border-[#5865F2]/40 text-[#5865F2] hover:text-white rounded-xl text-xs font-bold transition duration-300 items-center gap-2">
                    💬 Entrar no Discord
                </a>
                <a href="https://chat.whatsapp.com/BczBBFD4rD4GT3i8hM2qeG" 
                   target="_blank"
                   class="inline-flex py-3 px-6 bg-slate-900 border border-slate-800 hover:border-[#25D366]/40 text-[#25D366] hover:text-white rounded-xl text-xs font-bold transition duration-300 items-center gap-2">
                    🟢 Grupo de WhatsApp
                </a>
            </div>
        </section>

    </main>

    <!-- Rodapé -->
    <footer class="w-full max-w-6xl mx-auto px-6 py-6 border-t border-slate-900/60 text-center flex-shrink-0 relative z-10 select-none">
        <p class="text-[10px] text-slate-650 font-mono">
            &copy; 2026 CROM Ecosystem. Todos os direitos reservados. Soberania tecnológica garantida.
        </p>
    </footer>
</div>
