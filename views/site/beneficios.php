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

    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pt-6">
        
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
</div>