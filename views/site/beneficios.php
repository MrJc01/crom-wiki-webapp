<?php
/** @var yii\web\View $this */

// --- CONFIGURAÇÃO COMPLETA DOS BENEFÍCIOS CROM ---
// Centralizado em uma única array estruturada para fácil manutenção e escalabilidade.
$beneficios = [
    [
        'icone'          => '🖥️',
        'icone_style'    => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
        'titulo'         => 'Servidor Linux Dedicado (VPS)',
        'descricao'      => 'Acesso SSH completo à infraestrutura isolada para rodar seus microsserviços, executar scripts (Go, Python, Node, Bash) e subir containers em Podman/Docker rootless.',
        'badge'          => 'Infraestrutura',
        'badge_style'    => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
        'como_conseguir' => 'Concedido automaticamente para desenvolvedores de todas as camadas. Entre em contato com um dos Guardiões para solicitá-lo e receber as credenciais de acesso.',
        'como_acessar'   => 'ssh usuario@ip'
    ],
    [
        'icone'          => '🤖',
        'icone_style'    => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
        'titulo'         => 'Créditos e API de IA (CromIA Gateway)',
        'descricao'      => 'Acesso unificado e direto a modelos de linguagem avançados (Deepseek V4, Gemma 4, GLM) com cotas mensais flexíveis e rate limiting dedicado.',
        'badge'          => 'Inteligência',
        'badge_style'    => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
        'como_conseguir' => 'Entre em contato com um dos Guardiões para solicitá-lo e receber as credenciais de acesso.',
        'como_acessar'   => 'Acesse: https://cromia-api.crom.me/ para obter mais informações e solicitar acesso.'
    ],
    [
        'icone'          => '📜',
        'icone_style'    => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
        'titulo'         => 'Contrato de Cuidado (Vesting de Impacto)',
        'descricao'      => 'Mecanismo transparente onde todo o seu tempo investido e impacto gerado no ecossistema são documentados e auditados de forma aberta e perpétua.',
        'badge'          => 'Governança',
        'badge_style'    => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
        'como_conseguir' => 'Ativado e contabilizado a partir do momento de entrada e da execução de tarefas chaves pela equipe orgânica.',
        'como_acessar'   => 'Acompanhado e editado publicamente no livro-razão de contribuições estruturado no arquivo financeiro/vesting.md.'
    ],
    [
        'icone'          => '💰',
        'icone_style'    => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
        'titulo'         => 'Participação nos Resultados B2B',
        'descricao'      => 'Garantia de que o valor gerado não desaparece. Conforme soluções comerciais do ecossistema ganham tração, quem ajudou a erguer a base retém participação permanente.',
        'badge'          => 'Financeiro',
        'badge_style'    => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
        'como_conseguir' => 'Prerrogativa exclusiva dos membros mantenedores que possuem histórico de contribuição ativo e validado.',
        'como_acessar'   => 'As regras de distribuição financeira automatizada e repasses seguem as diretrizes contidas em governanca/contrato-cuidado.md.'
    ],
    [
        'icone'          => '🦅',
        'icone_style'    => 'bg-sky-500/10 text-sky-400 border-sky-500/20',
        'titulo'         => 'Autonomia Radical & Ownership',
        'descricao'      => 'Estrutura 100% horizontal inspirada em modelos como Bitcoin e Valve. Não existem gerentes designando tarefas; você escolhe o que quer construir ou estender.',
        'badge'          => 'Cultura',
        'badge_style'    => 'bg-sky-500/10 text-sky-400 border-sky-500/20',
        'como_conseguir' => 'Princípio filosófico fundamental aplicado de forma irrestrita a todos os construtores integrados.',
        'como_acessar'   => 'Explore os repositórios abertos (como Crompressor, P2PFile, Sume) e simplesmente assuma a responsabilidade técnica pelas modificações.'
    ],
    [
        'icone'          => '✍️',
        'icone_style'    => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
        'titulo'         => 'Poder de Escrita GitOps e Wiki',
        'descricao'      => 'Acesso direto de escrita nas documentações e nos processos decisórios centrais, permitindo mudar e otimizar as regras do jogo do laboratório.',
        'badge'          => 'Decisão',
        'badge_style'    => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
        'como_conseguir' => 'Entre em contato com um dos Guardiões para solicitá-lo e receber as credenciais de acesso.',
        'como_acessar'   => 'Através de commits diretos na árvore Git ou via Pull Requests integrados à organização do GitHub. https://github.com/MrJc01/crom-wiki'
    ],
    [
        'icone'          => '⚔️',
        'icone_style'    => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
        'titulo'         => 'Formação de Cabais Orgânicos',
        'descricao'      => 'Liberdade para convocar, estruturar e liderar equipes de engenharia temporárias e descentralizadas focadas em resolver desafios complexos de segurança ou novos produtos.',
        'badge'          => 'Estratégia',
        'badge_style'    => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
        'como_conseguir' => 'Disponível sempre que uma fraqueza arquitetural ou oportunidade de deploy ágil for identificada.',
        'como_acessar'   => 'Utilize o modelo estrutural de governança localizado em _templates/cabal.md e publique a convocação para os membros.'
    ],
    [
        'icone'          => '🧠',
        'icone_style'    => 'bg-pink-500/10 text-pink-400 border-pink-500/20',
        'titulo'         => 'Mentoria de Baixo Nível com Fundadores',
        'descricao'      => 'Sessões de alinhamento técnico direto focado em engenharia fina, otimização de código nativo (Go, Rust/Tauri, linguagens customizadas) e Soberania Digital local-first.',
        'badge'          => 'Evolução',
        'badge_style'    => 'bg-pink-500/10 text-pink-400 border-pink-500/20',
        'como_conseguir' => 'Entre em contato com um o membro que tem interesse, veja a disponibilidade de tempo, qualquer duvida peça ajuda aos Guardiões.',
        'como_acessar'   => 'Através das frentes de comunicação discord ou whatsapp, encontre em: https://crom.run/comunidade'
    ],
    [
        'icone'          => '🌐',
        'icone_style'    => 'bg-teal-500/10 text-teal-400 border-teal-500/20',
        'titulo'         => 'Networking e Canais',
        'descricao'      => 'Acesso a uma rede  de engenharia independente que discute de forma obstinada infraestruturas resilientes e privacidade de dados de ponta a ponta.',
        'badge'          => 'Comunidade',
        'badge_style'    => 'bg-teal-500/10 text-teal-400 border-teal-500/20',
        'como_conseguir' => 'Acesso livre para todos os membros do Coletivo.',
        'como_acessar'   => 'Através dos links disponíveis em: https://crom.run/comunidade'
    ]
];
?>

<div class="space-y-8 select-none max-w-6xl mx-auto pb-16">
    <div class="text-center space-y-2">
        <h2 class="text-3xl font-extrabold text-slate-100 tracking-tight font-sans">Benefícios de Membro da CROM</h2>
        <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
            Explore as prerrogativas soberanas e recursos concedidos aos Pilares que sustentam nossa base operacional. <span class="text-sky-400 font-medium">Clique em qualquer card</span> para expandir as rotas de acesso.
        </p>
    </div>

    <!-- Banner de Chamada para o Welcome Slider -->
    <div class="bg-gradient-to-r from-sky-500/10 via-indigo-500/5 to-purple-500/10 border border-sky-500/20 rounded-[28px] p-6 flex flex-col md:flex-row items-center justify-between gap-4 shadow-xl shadow-sky-500/5 select-none mb-4">
        <div class="flex items-center gap-4 text-left">
            <div class="w-12 h-12 bg-sky-500/10 border border-sky-500/20 rounded-2xl flex items-center justify-center text-2xl shadow-lg">
                👋
            </div>
            <div>
                <h3 class="text-sm font-bold text-white tracking-tight">Quer rever o Guia de Boas-Vindas?</h3>
                <p class="text-[11px] text-slate-400 max-w-lg mt-0.5 leading-relaxed">
                    Assista à apresentação interativa com os slides explicativos sobre a filosofia da CROM, o funcionamento das camadas e as ferramentas locais do ecossistema.
                </p>
            </div>
        </div>
        <button @click="openTab('paravoce'); $nextTick(() => { window.dispatchEvent(new CustomEvent('open-welcome-slider', { detail: { id: 1 } })) })"
                class="px-5 py-2.5 bg-sky-500 hover:bg-sky-400 text-slate-950 font-extrabold rounded-xl text-xs transition transform active:scale-95 cursor-pointer shadow-lg shadow-sky-500/20 hover:shadow-sky-500/30 flex items-center gap-2 whitespace-nowrap">
            <span>Iniciar Apresentação</span>
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
            </svg>
        </button>
    </div>

    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pt-2">
        
        <?php foreach ($beneficios as $item): ?>
            <details class="group bg-slate-900/30 border border-slate-800/80 rounded-3xl p-6 hover:border-slate-700 transition duration-200 cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                
                <summary class="space-y-4 focus:outline-none list-none">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 border rounded-xl flex items-center justify-center text-xl shadow-inner <?= $item['icone_style'] ?>">
                            <?= $item['icone'] ?>
                        </div>
                        <span class="text-[9px] uppercase tracking-widest font-bold border px-2.5 py-0.5 rounded-full <?= $item['badge_style'] ?>">
                            <?= $item['badge'] ?>
                        </span>
                    </div>
                    
                    <div class="space-y-2">
                        <h3 class="text-base font-bold text-slate-200 flex items-center justify-between">
                            <?= htmlspecialchars($item['titulo']) ?>
                            <span class="text-[10px] text-slate-500 group-open:rotate-180 transition-transform duration-200 ease-in-out">▼</span>
                        </h3>
                        <p class="text-xs text-slate-400 leading-relaxed group-open:text-slate-300">
                            <?= htmlspecialchars($item['descricao']) ?>
                        </p>
                    </div>
                </summary>

                <div class="mt-4 pt-4 border-t border-slate-800/60 space-y-4 text-xs cursor-default" onclick="event.stopPropagation();">
                    
                    <div class="space-y-1.5">
                        <h4 class="font-bold text-slate-300 flex items-center gap-1.5 text-[11px]">
                            <span>🔑</span> <span class="text-slate-200 tracking-wide">Como Conseguir:</span>
                        </h4>
                        <p class="text-slate-400 leading-relaxed bg-slate-950/40 p-2.5 rounded-xl border border-slate-900/80 text-[11px]">
                            <?= htmlspecialchars($item['como_conseguir']) ?>
                        </p>
                    </div>

                    <div class="space-y-1.5">
                        <h4 class="font-bold text-slate-300 flex items-center gap-1.5 text-[11px]">
                            <span>🚀</span> <span class="text-slate-200 tracking-wide">Como Acessar:</span>
                        </h4>
                        <div class="text-slate-400 leading-relaxed bg-slate-950/60 p-2.5 rounded-xl border border-slate-900/80 font-mono text-[10.5px] overflow-x-auto selection:bg-sky-500/20">
                            <?= htmlspecialchars($item['como_acessar']) ?>
                        </div>
                    </div>

                </div>
            </details>
        <?php endforeach; ?>

    </section>

    <!-- SEÇÃO COMPLEMENTAR: DINÂMICA DE MEMBROS CROM -->
    <hr class="border-slate-800/80 my-10" />

    <div class="space-y-12">
        <!-- 1. Filosofia: O que é ser CROM? -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <div class="space-y-4">
                <div class="flex items-center gap-2.5">
                    <span class="text-2xl">👁️</span>
                    <h3 class="text-lg font-bold text-white tracking-tight font-sans">1. A Filosofia: O que é ser CROM?</h3>
                </div>
                <p class="text-xs text-slate-450 leading-relaxed font-medium">
                    A CROM rejeita o conceito tradicional corporativo de chefe, subordinação e burocracia. Inspirada em modelos horizontais e resilientes (como a <strong class="text-slate-200">Valve</strong>, <strong class="text-slate-200">Bitcoin</strong> e <strong class="text-slate-200">Linux Foundation</strong>), a comunidade funciona como um organismo descentralizado baseado em <strong class="text-sky-400">tempo, confiança e impacto histórico auditável</strong>.
                </p>
            </div>
            
            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Autonomia Radical -->
                <div class="bg-slate-900/20 border border-slate-800/80 rounded-2xl p-5 hover:border-slate-700/60 transition duration-150">
                    <div class="text-sky-400 text-lg mb-2">⚡</div>
                    <h4 class="text-xs font-bold text-slate-200 uppercase tracking-wider mb-2 font-mono">Autonomia Radical</h4>
                    <p class="text-[11px] text-slate-400 leading-relaxed">Ninguém designa tarefas para você. Você decide em quais projetos quer atuar e adota ownership sobre as entregas.</p>
                </div>
                <!-- Responsabilidade Distribuída -->
                <div class="bg-slate-900/20 border border-slate-800/80 rounded-2xl p-5 hover:border-slate-700/60 transition duration-150">
                    <div class="text-emerald-400 text-lg mb-2">✍️</div>
                    <h4 class="text-xs font-bold text-slate-200 uppercase tracking-wider mb-2 font-mono">Responsabilidade</h4>
                    <p class="text-[11px] text-slate-400 leading-relaxed">Quem executa o trabalho é responsável por registrá-lo de forma aberta e auditável na Wiki e na árvore Git.</p>
                </div>
                <!-- Soberania Tecnológica -->
                <div class="bg-slate-900/20 border border-slate-800/80 rounded-2xl p-5 hover:border-slate-700/60 transition duration-150">
                    <div class="text-purple-400 text-lg mb-2">🔒</div>
                    <h4 class="text-xs font-bold text-slate-200 uppercase tracking-wider mb-2 font-mono">Soberania</h4>
                    <p class="text-[11px] text-slate-400 leading-relaxed">A busca constante por independência de softwares proprietários estrangeiros e infraestruturas centralizadas.</p>
                </div>
            </div>
        </div>

        <hr class="border-slate-800/40 my-6" />

        <!-- 2. As Três Camadas de Membros -->
        <div class="space-y-6">
            <div class="flex items-center gap-2.5">
                <span class="text-2xl">🏛️</span>
                <h3 class="text-lg font-bold text-white tracking-tight font-sans">2. As Três Camadas de Membros</h3>
            </div>
            <p class="text-xs text-slate-400 leading-relaxed max-w-2xl font-medium">
                A hierarquia da CROM não é gerencial ou de comando, mas sim baseada em níveis de <span class="text-sky-400">curadoria, responsabilidade operacional e segurança de acesso</span> à infraestrutura.
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Camada 1: A Forja -->
                <div class="bg-gradient-to-b from-amber-500/5 to-transparent border border-amber-500/10 rounded-2xl p-6 space-y-4 hover:border-amber-500/20 transition duration-150 flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 bg-amber-500/10 border border-amber-500/20 text-amber-400 text-[9px] uppercase tracking-wider rounded-full font-bold">Camada 1</span>
                            <span class="text-2xl">🔥</span>
                        </div>
                        <h4 class="text-sm font-bold text-white font-sans">A Forja</h4>
                        <p class="text-[11px] text-slate-450 leading-relaxed font-medium">Porta de entrada para novos construtores. Espaço para validação de alinhamento com a nossa filosofia.</p>
                    </div>
                    <div class="space-y-2 text-[10.5px]">
                        <div class="border-t border-slate-800/80 pt-2 text-slate-400"><strong class="text-amber-400">Direitos:</strong> VPS dedicada (<code class="text-slate-350 text-[10px]">vps2.crom.me</code>), chaves de API CromIA, notas pessoais.</div>
                        <div class="text-slate-400"><strong class="text-amber-400">Deveres:</strong> Criar ferramentas úteis, manter documentações e debater no Discord.</div>
                    </div>
                </div>

                <!-- Camada 2: Os Pilares -->
                <div class="bg-gradient-to-b from-indigo-500/5 to-transparent border border-indigo-500/10 rounded-2xl p-6 space-y-4 hover:border-indigo-500/20 transition duration-150 flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-[9px] uppercase tracking-wider rounded-full font-bold">Camada 2</span>
                            <span class="text-2xl">🏛️</span>
                        </div>
                        <h4 class="text-sm font-bold text-white font-sans">Os Pilares</h4>
                        <p class="text-[11px] text-slate-450 leading-relaxed font-medium">O núcleo operacional e técnico que sustenta o ecossistema CROM e suas soluções de produção no dia a dia.</p>
                    </div>
                    <div class="space-y-2 text-[10.5px]">
                        <div class="border-t border-slate-800/80 pt-2 text-slate-400"><strong class="text-indigo-400">Direitos:</strong> Escrita Wiki GitOps, VPS Pilares (<code class="text-slate-350 text-[10px]">vps1.crom.me</code>), IA avançada, quotas de vesting.</div>
                        <div class="text-slate-400"><strong class="text-indigo-400">Deveres:</strong> Liderar projetos, guiar a Forja e debater soluções com os Guardiões.</div>
                    </div>
                </div>

                <!-- Camada 3: Os Guardiões -->
                <div class="bg-gradient-to-b from-sky-500/5 to-transparent border border-sky-500/10 rounded-2xl p-6 space-y-4 hover:border-sky-500/20 transition duration-150 flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 bg-sky-500/10 border border-sky-500/20 text-sky-400 text-[9px] uppercase tracking-wider rounded-full font-bold">Camada 3</span>
                            <span class="text-2xl">👁️</span>
                        </div>
                        <h4 class="text-sm font-bold text-white font-sans">Os Guardiões</h4>
                        <p class="text-[11px] text-slate-450 leading-relaxed font-medium">Os curadores e zeladores focados na segurança de longo prazo, administração geral e governança do ecossistema.</p>
                    </div>
                    <div class="space-y-2 text-[10.5px]">
                        <div class="border-t border-slate-800/80 pt-2 text-slate-400"><strong class="text-sky-400">Direitos:</strong> Acesso admin total (Dokploy e produção), gestão de Cofre comunitário.</div>
                        <div class="text-slate-400"><strong class="text-sky-400">Deveres:</strong> Cumprir o Contrato de Cuidado, monitorar consumo de infra e mentoria.</div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="border-slate-800/40 my-6" />

        <!-- 3. Contrato de Cuidado e Vesting de Impacto -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center bg-slate-900/10 border border-slate-800/60 rounded-[28px] p-6 lg:p-8">
            <div class="space-y-4">
                <div class="flex items-center gap-2.5">
                    <span class="text-2xl">🤝</span>
                    <h3 class="text-lg font-bold text-white tracking-tight font-sans">3. Contrato de Cuidado & Vesting</h3>
                </div>
                <p class="text-xs text-slate-450 leading-relaxed font-medium">
                    A CROM adota a <strong class="text-emerald-400 font-semibold">Engenharia de Retribuição</strong> através do princípio da <strong class="text-slate-200">Retribuição Perpétua</strong>: qualquer trabalho registrado de forma transparente na Wiki ou no Git gera participação proporcional sobre receitas futuras de projetos B2B comerciais.
                </p>
                <div class="p-3 bg-emerald-500/5 border border-emerald-500/10 rounded-xl text-[10.5px] text-emerald-400 flex items-start gap-2 leading-relaxed">
                    <span class="text-sm">💡</span>
                    <span><strong>Passivo de Gratidão:</strong> Se um Pilar contribuir ativamente para a criação de um serviço comercial e depois se desligar, ele continua detendo o direito residual histórico. Seu esforço nunca é apagado ou esquecido.</span>
                </div>
            </div>
            
            <div class="space-y-4">
                <h4 class="text-xs font-bold text-slate-200 uppercase tracking-wider font-mono">Como funciona o Vesting de Impacto?</h4>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-lg bg-slate-850 border border-slate-800 flex items-center justify-center text-xs text-slate-350 font-bold font-mono">1</div>
                        <div class="text-[11px] text-slate-450 leading-relaxed font-medium"><strong class="text-slate-200">Tempo de contribuição:</strong> Meses ativo de dedicação constante no ecossistema.</div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-lg bg-slate-850 border border-slate-800 flex items-center justify-center text-xs text-slate-350 font-bold font-mono">2</div>
                        <div class="text-[11px] text-slate-450 leading-relaxed font-medium"><strong class="text-slate-200">Impacto registrado:</strong> Commits diretos, documentações criadas e tarefas chaves entregues.</div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-lg bg-slate-850 border border-slate-800 flex items-center justify-center text-xs text-slate-350 font-bold font-mono">3</div>
                        <div class="text-[11px] text-slate-450 leading-relaxed font-medium"><strong class="text-slate-200">Consistência:</strong> Regularidade de contribuição em vez de picos esporádicos seguidos por hiatos.</div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-lg bg-slate-850 border border-slate-800 flex items-center justify-center text-xs text-slate-350 font-bold font-mono">4</div>
                        <div class="text-[11px] text-slate-450 leading-relaxed font-medium"><strong class="text-slate-200">Responsabilidade:</strong> Nível de criticidade dos sistemas mantidos ou mentoria oferecida.</div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="border-slate-800/40 my-6" />

        <!-- 4. Rituais e Cadências: A Engrenagem da Comunidade -->
        <div class="space-y-6">
            <div class="flex items-center gap-2.5">
                <span class="text-2xl">🔄</span>
                <h3 class="text-lg font-bold text-white tracking-tight font-sans">4. Rituais & Cadências da Engrenagem</h3>
            </div>
            <p class="text-xs text-slate-400 leading-relaxed max-w-2xl font-medium">
                Nossas cadências operacionais garantem que a comunidade permaneça sincronizada, transparente e alinhada sem o desperdício de reuniões síncronas burocráticas e cansativas.
            </p>
            
            <div class="border border-slate-800/80 rounded-2xl overflow-hidden bg-slate-900/10">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse min-w-[600px]">
                        <thead>
                            <tr class="border-b border-slate-800 bg-slate-900/30 text-slate-300 font-bold text-[10px] uppercase tracking-wider font-mono">
                                <th class="p-4">Ritual</th>
                                <th class="p-4">Frequência</th>
                                <th class="p-4">Formato / Arquivo</th>
                                <th class="p-4">Obrigatoriedade</th>
                                <th class="p-4">Propósito</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-900/60 text-slate-400 font-medium text-[11px]">
                            <tr class="hover:bg-slate-900/10">
                                <td class="p-4 font-bold text-slate-200">Status Semanal</td>
                                <td class="p-4">Sexta ou Sábado</td>
                                <td class="p-4 font-mono text-[10px] text-sky-400">status.md</td>
                                <td class="p-4"><span class="px-2 py-0.5 bg-rose-500/10 border border-rose-500/20 text-rose-450 text-[9px] uppercase font-black rounded font-mono">Obrigatório</span></td>
                                <td class="p-4 text-slate-450">Compartilhar progresso, entregáveis e próximos passos de forma transparente.</td>
                            </tr>
                            <tr class="hover:bg-slate-900/10">
                                <td class="p-4 font-bold text-slate-200">Revisão de Metas</td>
                                <td class="p-4">1º dia útil do mês</td>
                                <td class="p-4 font-mono text-[10px] text-sky-400">metas.md</td>
                                <td class="p-4"><span class="px-2 py-0.5 bg-rose-500/10 border border-rose-500/20 text-rose-450 text-[9px] uppercase font-black rounded font-mono">Obrigatório</span></td>
                                <td class="p-4 text-slate-450">Alinhamento estratégico do que será construído ou estendido a curto e médio prazo.</td>
                            </tr>
                            <tr class="hover:bg-slate-900/10">
                                <td class="p-4 font-bold text-slate-200">Sync de Guardiões</td>
                                <td class="p-4">Quinzenal</td>
                                <td class="p-4 text-slate-350">Reunião (Discord)</td>
                                <td class="p-4 text-slate-500 font-mono text-[10px]">Apenas Guardiões</td>
                                <td class="p-4 text-slate-450">Tomada de decisão estratégica ágil e avaliação da saúde comunitária.</td>
                            </tr>
                            <tr class="hover:bg-slate-900/10">
                                <td class="p-4 font-bold text-slate-200">Auditoria de Infra</td>
                                <td class="p-4">Quinzenal</td>
                                <td class="p-4 text-slate-350">Assíncrono</td>
                                <td class="p-4 text-slate-500 font-mono text-[10px]">Resp. pela Infra</td>
                                <td class="p-4 text-slate-450">Verificar limites de VPS, consumo de disco e quotas das APIs de inteligência artificial.</td>
                            </tr>
                            <tr class="hover:bg-slate-900/10">
                                <td class="p-4 font-bold text-slate-200">Retrospectiva</td>
                                <td class="p-4">Último dia do mês</td>
                                <td class="p-4 text-slate-350">Livre (Discord/Texto)</td>
                                <td class="p-4 text-slate-500 font-mono text-[10px]">Recomendado</td>
                                <td class="p-4 text-slate-450">Avaliar o que foi executado, identificar gargalos e planejar melhorias de processo.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <hr class="border-slate-800/40 my-6" />

        <!-- 5. O Ecossistema de Ferramentas Locais -->
        <div class="space-y-6">
            <div class="flex items-center gap-2.5">
                <span class="text-2xl">💻</span>
                <h3 class="text-lg font-bold text-white tracking-tight font-sans">5. O Ecossistema de Ferramentas Locais</h3>
            </div>
            <p class="text-xs text-slate-400 leading-relaxed max-w-2xl font-medium">
                Sempre que estiver operando sob a infraestrutura da CROM, utilize os utilitários de CLI desenvolvidos sob medida para garantir eficiência e segurança.
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- CROM CLI -->
                <div class="bg-slate-900/20 border border-slate-800/80 rounded-2xl p-5 hover:border-slate-700/60 transition duration-150 space-y-3 flex flex-col justify-between">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold text-slate-200 uppercase tracking-wider font-mono">CROM CLI</h4>
                            <span class="text-[10px] font-mono bg-slate-950 px-2 py-0.5 border border-slate-900 rounded text-slate-400">./crom.sh</span>
                        </div>
                        <p class="text-[11px] text-slate-400 leading-relaxed">
                            Utilitário TUI rodando na raiz do repositório local. Permite ler, editar e gerenciar notas da Wiki diretamente no terminal. Automatiza o versionamento Git (add, commit, push) de forma transparente ao salvar os arquivos.
                        </p>
                    </div>
                    <div class="text-[10px] text-sky-400 font-bold border-t border-slate-900 pt-2 flex items-center gap-1.5 leading-relaxed">
                        <span>🤖</span>
                        <span>Pressione <code class="bg-slate-950 px-1.5 py-0.5 rounded border border-slate-850 text-slate-300 font-mono text-[9px]">c</code> para chamar a Rosa IA.</span>
                    </div>
                </div>

                <!-- CROM Workspace -->
                <div class="bg-slate-900/20 border border-slate-800/80 rounded-2xl p-5 hover:border-slate-700/60 transition duration-150 space-y-3 flex flex-col justify-between">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold text-slate-200 uppercase tracking-wider font-mono">Workspace</h4>
                            <span class="text-[10px] font-mono bg-slate-950 px-2 py-0.5 border border-slate-900 rounded text-slate-400">crom-ws</span>
                        </div>
                        <p class="text-[11px] text-slate-400 leading-relaxed">
                            Canivete suíço instalado globalmente nas VPSs para agilizar fluxos. Permite criar projetos baseados em templates (<code class="font-mono text-slate-350 text-[10px]">init</code>) e publicar micro-serviços com certificados HTTPS/SSL automáticos (<code class="font-mono text-slate-350 text-[10px]">publish &lt;porta&gt;</code>).
                        </p>
                    </div>
                </div>

                <!-- Isolamento de Segurança -->
                <div class="bg-slate-900/20 border border-slate-800/80 rounded-2xl p-5 hover:border-slate-700/60 transition duration-150 space-y-3 flex flex-col justify-between">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold text-slate-200 uppercase tracking-wider font-mono">Isolamento</h4>
                            <span class="text-[10px] font-mono bg-slate-950 px-2 py-0.5 border border-slate-900 rounded text-slate-400">Podman</span>
                        </div>
                        <p class="text-[11px] text-slate-400 leading-relaxed">
                            Restringimos o uso de Docker com privilégios de root apenas para serviços core (como o Dokploy). Os membros executam seus contêineres de forma isolada e rootless usando Podman persistente por meio da CLI <code class="font-mono text-slate-350 text-[10px]">crom-ws</code>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>