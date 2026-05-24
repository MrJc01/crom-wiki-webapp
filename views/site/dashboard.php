<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Portal CROM — Painel';

$dailyQuoters = [
    // --- PERSEVERANÇA & RESILIÊNCIA ---
    "O sucesso é a soma de pequenos esforços repetidos dia após dia.",
    "A queda não é o fim, mas o convite para um recomeço mais forte.",
    "Grandes batalhas só são dadas a grandes guerreiros.",
    "O único lugar onde o sucesso vem antes do trabalho é no dicionário.",
    "Não importa o quão devagar você vá, desde que não pare.",
    "A persistência é o caminho do êxito.",
    "Obstáculos são as coisas assustadoras que você vê quando tira os olhos do alvo.",
    "É no meio da dificuldade que se encontra a oportunidade.",
    "O que não me mata, me torna mais forte.",
    "Se você está atravessando um inferno, continue caminhando.",
    "A maior glória não é nunca cair, mas levantar-se a cada queda.",
    "A força não vem da capacidade física, vem de uma vontade indomável.",
    "O rio atinge seus objetivos porque aprendeu a contornar os obstáculos.",
    "Calmaria nunca fez bom marinheiro.",
    "A paciência é amarga, mas seus frutos são doces.",
    "Continue. Tudo o que você precisa virá no momento perfeito.",
    "Quem tem um 'porquê' enfrenta qualquer 'como'.",
    "Não desanime se a vitória demorar; o que se constrói rápido, desmorona fácil.",
    "A dor é temporária, mas o orgulho de ter vencido é para sempre.",
    "Tudo parece impossível até que seja feito.",

    // --- MOTIVAÇÃO & ATITUDE ---
    "O que você decide fazer agora pode mudar o resto da sua vida.",
    "Acredite que você pode e você já está no meio do caminho.",
    "A melhor maneira de prever o futuro é criá-lo.",
    "Você é do tamanho dos seus sonhos.",
    "O entusiasmo é a maior força da alma.",
    "Não espere por circunstâncias ideais, crie-as.",
    "Sua única limitação é aquela que você impõe em sua própria mente.",
    "Comece onde você está, use o que você tem e faça o que você pode.",
    "A vida começa onde termina a sua zona de conforto.",
    "Seja a mudança que você deseja ver no mundo.",
    "O otimismo é a fé que leva à realização.",
    "Se você pode sonhar, você pode realizar.",
    "A motivação é o que te faz começar. O hábito é o que te faz continuar.",
    "Sua mente é um jardim. Seus pensamentos são as sementes.",
    "Acredite em si mesmo e o universo conspirará a seu favor.",
    "Fazer o que você gosta é liberdade. Gostar do que você faz é felicidade.",
    "Cada dia é uma nova página na sua história. Escreva-a bem.",
    "Se a oportunidade não bater, construa uma porta.",
    "O destino não é uma questão de sorte, é uma questão de escolha.",
    "Descubra o que te acende e ilumine o mundo.",

    // --- FOCO & DISCIPLINA ---
    "O sucesso não é o resultado de um acerto, mas de uma rotina de acertos.",
    "Foco é dizer não para centenas de outras boas ideias.",
    "A disciplina é a ponte entre metas e realizações.",
    "Não olhe para o topo da montanha, foque no próximo passo.",
    "Quem quer fazer algo encontra um meio, quem não quer encontra uma desculpa.",
    "A consistência supera o talento quando o talento não tem consistência.",
    "Produtividade nunca é um acidente; é o resultado de compromisso com a excelência.",
    "Sua energia flui para onde sua atenção está direcionada.",
    "Simplifique a sua vida e foque no que realmente importa.",
    "O segredo do seu futuro está escondido na sua rotina diária.",
    "Menos distração, mais ação.",
    "O preço da disciplina é sempre menor que o preço do arrependimento.",
    "Grandes realizações são construídas com pequenos tijolos diários.",
    "Não se distraia com o barulho dos outros. Siga o seu plano.",
    "Foco absoluto no processo, desapego total do resultado imediato.",
    "A maestria exige paciência e repetição.",
    "Seja senhor da sua mente e escravo dos seus bons hábitos.",
    "O sucesso ama a preparação e detesta a procrastinação.",
    "Organize sua mente, dite o seu ritmo e domine o seu dia.",
    "Saber o que deixar de fazer é tão importante quanto saber o que fazer.",

    // --- VISÃO & CRESCIMENTO ---
    "Não compita com os outros, compita com quem você era ontem.",
    "O conhecimento fala, mas a sabedoria escuta.",
    "Erros são provas de que você está tentando.",
    "Seja grato pelo que tem enquanto luta pelo que deseja.",
    "O maior erro que você pode cometer é o medo de cometer erros.",
    "A sabedoria começa na reflexão.",
    "Mude seus pensamentos e você mudará seu mundo.",
    "Investir em conhecimento rende sempre os melhores juros.",
    "O aprendizado é um tesouro que seguirá seu dono por toda parte.",
    "A mente que se abre a uma nova ideia jamais voltará ao seu tamanho original.",
    "As crises não criam o caráter, apenas o revelam.",
    "Crescer dói, mas permanecer preso onde você não pertence dói muito mais.",
    "A vida é 10% o que acontece com você e 90% como você reage a isso.",
    "O segredo da mudança é focar toda a sua energia não na luta contra o velho, mas na construção do novo.",
    "Nenhum obstáculo é grande demais se a sua vontade de crescer for maior.",
    "A evolução pessoal é um processo contínuo, não um destino final.",
    "Quem teme o julgamento dos outros nunca conhecerá o próprio potencial.",
    "A humildade é o primeiro passo para o verdadeiro aprendizado.",
    "Permita-se ser um iniciante. Ninguém começa sabendo tudo.",
    "O sucesso sem gratidão é apenas um ego inflado.",

    // --- INSPIRAÇÃO & LIDERANÇA ---
    "A coragem não é a ausência do medo, mas o triunfo sobre ele.",
    "A melhor liderança é o exemplo.",
    "Grandes mentes discutem ideias; mentes médias discutem eventos; mentes pequenas discutem pessoas.",
    "Gentileza gera gentileza.",
    "O que fazemos por nós mesmos morre conosco. O que fazemos pelos outros permanece.",
    "Seja a luz na escuridão de alguém.",
    "O sucesso é mais gratificante quando compartilhado.",
    "Não siga o caminho que a vida te impõe; vá por onde não há caminho e deixe uma trilha.",
    "A integridade é fazer o certo mesmo quando ninguém está olhando.",
    "Para liderar a si mesmo, use a cabeça; para liderar os outros, use o coração.",
    "O valor de um homem é medido pelo que ele dá, não pelo que ele é capaz de receber.",
    "Nenhum de nós é tão inteligente quanto todos nós juntos.",
    "Inspire pelo caráter, lidere pela atitude.",
    "O respeito se conquista, a confiança se adquire e a lealdade se retribui.",
    "Grandes líderes não criam seguidores, criam novos líderes.",
    "A verdadeira grandeza consiste em fazer com que todos ao seu redor se sintam grandes.",
    "Um dia sem rir é um dia desperdiçado.",
    "A generosidade enriquece quem a pratica.",
    "Sua marca no mundo é o impacto positivo que você deixa nas pessoas.",
    "Viva de forma que sua presença faça a diferença e sua ausência seja sentida."
];

$dailyQuotersTitle = [
    // --- PERSEVERANÇA & RESILIÊNCIA ---
    "Perseverança",
    "Resiliência",
    "Superação",
    "Trabalho",
    "Constância",
    "Persistência",
    "Determinação",
    "Oportunidade",
    "Fortaleza",
    "Continuidade",
    "Superação",
    "Vontade",
    "Adaptação",
    "Experiência",
    "Paciência",
    "Confiança",
    "Propósito",
    "Construção",
    "Resistência",
    "Possibilidade",

    // --- MOTIVAÇÃO & ATITUDE ---
    "Motivação",
    "Autoestima",
    "Visão",
    "Sonhos",
    "Entusiasmo",
    "Iniciativa",
    "Atitude",
    "Ação",
    "Coragem",
    "Transformação",
    "Otimismo",
    "Realização",
    "Hábito",
    "Mentalidade",
    "Fé",
    "Liberdade",
    "Renovação",
    "Oportunidade",
    "Escolhas",
    "Inspiração",

    // --- FOCO & DISCIPLINA ---
    "Foco",
    "Prioridade",
    "Disciplina",
    "Progresso",
    "Compromisso",
    "Consistência",
    "Produtividade",
    "Atenção",
    "Simplicidade",
    "Rotina",
    "Execução",
    "Responsabilidade",
    "Base",
    "Blindagem",
    "Processo",
    "Maestria",
    "Autocontrole",
    "Preparação",
    "Ritmo",
    "Estratégia",

    // --- VISÃO & CRESCIMENTO ---
    "Evolução",
    "Sabedoria",
    "Aprendizado",
    "Gratidão",
    "Experiência",
    "Reflexão",
    "Mudança",
    "Conhecimento",
    "Crescimento",
    "Expansão",
    "Caráter",
    "Amadurecimento",
    "Reação",
    "Renovação",
    "Vontade",
    "Jornada",
    "Autonomia",
    "Humildade",
    "Início",
    "Reconhecimento",

    // --- INSPIRAÇÃO & LIDERANÇA ---
    "Coragem",
    "Exemplo",
    "Grandeza",
    "Gentileza",
    "Legado",
    "Altruísmo",
    "Compartilhar",
    "Liderança",
    "Integridade",
    "Empatia",
    "Valor",
    "União",
    "Caráter",
    "Confiança",
    "Legado",
    "Influência",
    "Alegria",
    "Generosidade",
    "Impacto",
    "Presença"
];
$dailyQuoterandomDay = $dailyQuoters[date('d') % count($dailyQuoters)]; 
$dailyQuoterandomDayTitle = $dailyQuotersTitle[date('d') % count($dailyQuotersTitle)];



// --- CONFIGURAÇÃO GLOBAL DO DASHBOARD ---
// Centralização de dados da infra, rituais, camadas e soluções da CROM.
$dashboard = [
    'banners' => [
        [
            'badge'            => 'Soberania',
            'titulo_principal' => 'CROM',
            'titulo_accent'    => '',
            'descricao'        => 'Crie e colabore em documentações locais em Markdown com autonomia radical e controle de governança direto na base.',
            'btn_texto'        => 'Consultar Documentos Internos',
            'btn_tab'          => 'page_crud',
            'gradiente'        => 'from-sky-400/20 to-indigo-500/0'
        ]
    ],
    'ecossistema' => [
        [
            'nome'        => 'CromIA Gateway',
            'tag'         => 'Inteligência',
            'tag_style'   => 'bg-purple-500/20 text-purple-300 border-purple-500/30',
            'bg_style'    => 'bg-gradient-to-br from-purple-950/40 to-slate-900 border-purple-900/60 hover:border-purple-500/40',
            'icone'       => '🤖',
            'descricao'   => 'Acesso unificado via chaves privadas a modelos avançados (Deepseek V4, Gemma 4, GLM) sem vazamento de escopo corporativo.',
            'btn_texto'   => 'Acessar Token Privado',
            'btn_style'   => 'bg-purple-600 hover:bg-purple-500 text-white',
            'disabled'    => false,
            'tab'         => 'beneficios'
        ],
        [
            'nome'        => 'P2P Secure Share',
            'tag'         => 'Privacidade',
            'tag_style'   => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
            'bg_style'    => 'bg-gradient-to-br from-emerald-950/40 to-slate-900 border-emerald-900/60 hover:border-emerald-500/40',
            'icone'       => '🔒',
            'descricao'   => 'Transferência direta de arquivos de dispositivo para dispositivo via WebRTC, rodando sem backend centralizado.',
            'btn_texto'   => 'Abrir P2PFile',
            'btn_style'   => 'bg-emerald-600 hover:bg-emerald-500 text-white',
            'disabled'    => false,
            'tab'         => 'projetos'
        ],
        [
            'nome'        => 'Ferramentas',
            'tag'         => 'Infraestrutura',
            'tag_style'   => 'bg-indigo-500/20 text-indigo-300 border-indigo-500/30',
            'bg_style'    => 'bg-gradient-to-br from-indigo-950/40 to-slate-900 border-indigo-900/60 hover:border-indigo-500/40',
            'icone'       => '🗜️',
            'descricao'   => 'Coleção de ferramentas web essenciais, gratuitas e 100% privadas. Inclui conversores, geradores e utilitários para desenvolvedores e criadores.',
            'btn_texto'   => 'Acessar Ferramentas',
            'btn_style'   => 'bg-indigo-600 hover:bg-indigo-500 text-white',
            'disabled'    => false,
            'link'        => 'https://crom.run/ferramentas'
        ],
        [
            'nome'        => 'Cromva',
            'tag'         => 'IA',
            'tag_style'   => 'bg-indigo-500/20 text-indigo-300 border-indigo-500/30',
            'bg_style'    => 'bg-gradient-to-br from-indigo-950/40 to-slate-900 border-indigo-900/60 hover:border-indigo-500/40',
            'icone'       => '🤖',
            'descricao'   => 'Focado na privacidade, o Cromva é uma plataforma voltada para o consumo e organização de conteúdo em markdown. Com uma interface intuitiva, serve como um hub central para anotações.',
            'btn_texto'   => 'Acessar Cromva',
            'btn_style'   => 'bg-indigo-600 hover:bg-indigo-500 text-white',
            'disabled'    => false,
            'link'        => 'https://cromva.crom.run/'
        ],
    ],
    // --- SEÇÃO TRANSFORMADA EM ARRAY MUTIDIMENSIONAL EXPANSÍVEL ---
    'beneficios_preview' => [
        [
            'titulo'    => 'Contrato de Cuidado & Vesting',
            'descricao' => 'Seu histórico e impacto de contribuição técnico permanecem registrados em blockchain interna/wiki. Distribuição de proventos comerciais B2B prioritária para Pilares.',
            'btn_texto' => 'Ver benefícios',
            'tab'       => 'beneficios' // Roteamento interno via openTab
        ],
        [
            'titulo'    => 'Manifesto do Ecossistema Local-First',
            'tag'       => 'Filosofia',
            'descricao' => 'Leia as diretrizes completas sobre Soberania Digital, infraestruturas resilientes offline e o porquê de rejeitarmos modelos centralizados.',
            'btn_texto' => 'Acessar Manifesto Externo',
            'link'      => 'https://crom.me/manifesto' // Roteamento externo via tag <a>
        ]
    ],
    'projetos_hook' => [
        'titulo'    => $dailyQuoterandomDayTitle,
        'subtitulo' => $dailyQuoterandomDay
    ],
    'aprendizado' => [
        [
            'titulo' => 'Escrita de Processos na Wiki GitOps',
            'tag'    => 'Governança',
            'icone'  => '🏛️'
        ],
        [
            'titulo' => 'Provisionamento de VPS e Acesso com Chave Pública',
            'tag'    => 'Infraestrutura',
            'icone'  => '🖥️'
        ],
        [
            'titulo' => 'Gerenciamento Rootless via Podman e Isolamento',
            'tag'    => 'Segurança',
            'icone'  => '🐳'
        ]
    ]
];

// Consulta de usuários ativos nos últimos 15 minutos (Otimização SRE)
$timeThreshold = time() - 900;
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

// Injeta assets do Swiper.js limpos e sem duplicidade
$this->registerCssFile('https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js');
?>

<style>
    /* Customização dos bullets do Swiper Vertical e desabilitados */
    .swiper-button-disabled { opacity: 0.25; cursor: not-allowed !important; }
    .swiper-pagination-banner .swiper-pagination-bullet { background: #0f172a; opacity: 0.2; width: 8px; height: 8px; transition: all 0.2s; }
    .swiper-pagination-banner .swiper-pagination-bullet-active { background: #0284c7; opacity: 1; height: 20px; border-radius: 4px; }
</style>

<div class="space-y-10 pb-16 selection:bg-sky-500/20">
    
    <section class="bg-[#e8f0fe] rounded-[32px] border border-sky-200/50 shadow-lg relative overflow-hidden select-none h-[420px] md:h-[320px]">
        <div class="swiper cromSwiperBanner h-full w-full">
            <div class="swiper-wrapper">
                
                <?php foreach ($dashboard['banners'] as $banner): ?>
                    <div class="swiper-slide h-full w-full flex flex-col md:flex-row justify-between items-center p-8 md:p-12 relative overflow-hidden">
                        
                        <div class="absolute top-0 left-0 w-24 h-full bg-gradient-to-br <?= $banner['gradiente'] ?> rounded-r-full blur-2xl pointer-events-none"></div>
                        
                        <div class="max-w-xl space-y-3 md:space-y-4 text-center md:text-left pt-4 md:pt-0 z-10">
                            <span class="inline-block px-3 py-1 bg-sky-600/10 border border-sky-600/20 text-sky-700 font-mono font-bold text-[10px] tracking-wide uppercase rounded-full">
                                <?= htmlspecialchars($banner['badge']) ?>
                            </span>
                            
                            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight text-slate-900 font-sans flex items-center justify-center md:justify-start gap-1">
                                <?= htmlspecialchars($banner['titulo_principal']) ?> 
                                <span class="font-black text-slate-800"><?= htmlspecialchars($banner['titulo_accent']) ?></span>
                                <span class="inline-block w-3.5 h-3.5 bg-slate-900 rounded-full ml-1"></span>
                            </h1>
                            
                            <p class="text-xs md:text-sm text-slate-600 font-medium leading-relaxed max-w-lg">
                                <?= htmlspecialchars($banner['descricao']) ?>
                            </p>
                            
                            <div class="flex justify-center md:justify-start pt-1">
                                <button @click="openTab('<?= $banner['btn_tab'] ?>')"
                                        class="py-2.5 px-6 bg-sky-600 hover:bg-sky-500 text-white rounded-full text-xs font-bold shadow-md shadow-sky-600/20 transition-all duration-200 transform active:scale-95">
                                    <?= htmlspecialchars($banner['btn_texto']) ?>
                                </button>
                            </div>
                        </div>

                        <div class="relative w-full md:w-72 h-36 md:h-48 flex items-center justify-center z-10 mt-4 md:mt-0">
                            <div class="w-28 h-28 bg-gradient-to-tr from-sky-400 via-indigo-500 to-rose-500 rounded-full blur-sm opacity-15 animate-pulse absolute"></div>
                            <div class="absolute left-6 w-16 h-16 bg-gradient-to-br from-amber-400 via-rose-500 to-sky-400 rounded-tl-[28px] rounded-br-[28px] shadow-md flex items-center justify-center transform -rotate-12 border border-white/20">
                                <span class="text-white text-xl font-black">Ω</span>
                            </div>
                            <div class="absolute right-6 w-20 h-20 bg-gradient-to-tr from-sky-500 via-emerald-400 to-amber-300 rounded-full shadow-xl flex items-center justify-center transform rotate-12 border border-white/20">
                                <div class="w-8 h-8 bg-slate-900 rounded-full flex items-center justify-center shadow-inner">
                                    <span class="w-2 h-2 bg-sky-400 rounded-full"></span>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                <?php endforeach; ?>

            </div>
            <div class="swiper-pagination-banner absolute right-4 top-1/2 -translate-y-1/2 z-20 flex flex-col gap-2"></div>
        </div>
    </section>

    <section class="space-y-5 h-80 pb-10">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 select-none">
            <h2 class="text-xl font-bold text-slate-100 tracking-tight">Build using CROM's ecosystem</h2>
            
            <div class="flex items-center gap-2">
                <button class="swiper-pilar-prev w-9 h-9 border border-slate-800 bg-slate-900/60 hover:bg-slate-800 text-slate-400 hover:text-slate-100 rounded-full flex items-center justify-center text-xs transition cursor-pointer">
                    ◀
                </button>
                <button class="swiper-pilar-next w-9 h-9 border border-slate-800 bg-slate-900/60 hover:bg-slate-800 text-slate-400 hover:text-slate-100 rounded-full flex items-center justify-center text-xs transition cursor-pointer">
                    ▶
                </button>
            </div>
        </div>
        
        <div class="swiper cromSwiperEcosystem overflow-hidden rounded-[28px] px-1">
            <div class="swiper-wrapper">
                
                <?php foreach ($dashboard['ecossistema'] as $card): ?>
                    <div class="swiper-slide h-auto flex pb-4">
                        
                        <div class="w-full rounded-[28px] p-6 flex flex-col justify-between text-slate-100 border transition duration-300 relative overflow-hidden group <?= $card['bg_style'] ?>">
                            <div class="absolute top-0 right-0 w-24 h-24 bg-white/5 rounded-full blur-xl group-hover:scale-125 transition duration-300"></div>
                            
                            <div>
                                <div class="flex justify-between items-start gap-2">
                                    <div class="w-9 h-9 bg-slate-800/80 rounded-xl flex items-center justify-center text-xl shadow-inner border border-slate-700/60">
                                        <?= $card['icone'] ?>
                                    </div>
                                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold font-mono tracking-wide uppercase border <?= $card['tag_style'] ?>">
                                        <?= $card['tag'] ?>
                                    </span>
                                </div>
                                <h3 class="text-base font-extrabold mt-6 leading-tight tracking-tight text-slate-100">
                                    <?= htmlspecialchars($card['nome']) ?>
                                </h3>
                                <p class="text-[11px] text-slate-400 mt-2 leading-relaxed font-medium">
                                    <?= htmlspecialchars($card['descricao']) ?>
                                </p>
                            </div>
                            
                            <div class="mt-8 z-10">
                                <?php if ($card['disabled']): ?>
                                    <button disabled class="w-full py-2.5 px-4 bg-slate-900/50 text-slate-500 border border-slate-800/80 rounded-full text-xs font-bold cursor-not-allowed text-center">
                                        Em Estágio Skunkworks
                                    </button>
                                <?php else: 
                                
                                if (isset($card['tab'])): ?>
                                    <button @click="openTab('<?= $card['tab'] ?>')" class="w-full py-2.5 px-4 text-center rounded-full text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-md <?= $card['btn_style'] ?>">
                                        <?= htmlspecialchars($card['btn_texto']) ?>
                                    </button>
                                <?php elseif (isset($card['link'])): ?>
                                    <a href="<?= $card['link'] ?>" class="w-full py-2.5 px-4 text-center rounded-full text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-md <?= $card['btn_style'] ?>" target="_blank">
                                        <?= htmlspecialchars($card['btn_texto']) ?>
                                    </a>
                                <?php endif; ?>
                                <?php endif; ?>
                                
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <div class="space-y-10">

            <section class="space-y-4">
                <div class="flex justify-between items-center select-none">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 bg-sky-500/10 text-sky-400 border border-sky-500/20 rounded-full flex items-center justify-center text-xs shadow-inner">
                            💬
                        </div>
                        <h3 class="text-sm font-bold text-slate-200">Palavra e frase do dia</h3>
                    </div>
                </div>

                <div class="bg-slate-900/30 border border-slate-800/80 rounded-2xl p-6 hover:border-slate-700/60 transition duration-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="w-4 h-4 text-xs">🔥</span>
                            <h4 class="text-sm font-bold text-slate-200"><?= htmlspecialchars($dashboard['projetos_hook']['titulo']) ?></h4>
                        </div>
                       
                    </div>
                    <div>
                        <button class="py-2 px-5 bg-sky-600 hover:bg-sky-500 text-white text-xs font-bold rounded-xl transition shadow-md shadow-sky-600/10 whitespace-nowrap">
                            <?= htmlspecialchars($dashboard['projetos_hook']['subtitulo']) ?>
                        </button>
                    </div>
                </div>
            </section>
            <section class="space-y-4">
                <div class="flex justify-between items-center select-none">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 bg-rose-500/10 text-rose-400 border border-rose-500/20 rounded-full flex items-center justify-center text-xs shadow-inner">
                            ❤️
                        </div>
                        <h3 class="text-sm font-bold text-slate-200">Alinhamento Operacional</h3>
                    </div>
                    <button @click="openTab('beneficios')" class="text-xs font-bold text-sky-400 hover:text-sky-300 transition">Ver tudo ></button>
                </div>

                <div class="space-y-4">
                    <?php foreach ($dashboard['beneficios_preview'] as $index => $previewItem): ?>
                        <div class="bg-slate-900/30 border border-slate-800/80 rounded-2xl p-6 hover:border-slate-700/60 transition duration-200 flex flex-col justify-between gap-4">
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-base font-extrabold text-slate-100">
                                        <?= htmlspecialchars($previewItem['titulo']) ?>
                                    </h4>
                                    <?php if (isset($previewItem['tag'])): ?>
                                        <span class="text-[9px] uppercase font-mono font-bold tracking-wider px-2 py-0.5 rounded bg-slate-800 border border-slate-700 text-slate-400">
                                            <?= htmlspecialchars($previewItem['tag']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-xs text-slate-400 leading-relaxed">
                                    <?= htmlspecialchars($previewItem['descricao']) ?>
                                </p>
                            </div>
                            
                            <div>
                                <?php if (isset($previewItem['tab'])): ?>
                                    <button @click="openTab('<?= $previewItem['tab'] ?>')" class="py-2 px-5 bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-200 text-xs font-bold rounded-xl transition">
                                        <?= htmlspecialchars($previewItem['btn_texto']) ?>
                                    </button>
                                <?php elseif (isset($previewItem['link'])): ?>
                                    <a href="<?= $previewItem['link'] ?>" class="inline-block py-2 px-5 bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-200 text-xs font-bold rounded-xl transition" target="_blank">
                                        <?= htmlspecialchars($previewItem['btn_texto']) ?> ↗
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

        </div>

        <section class="space-y-4" style="display: none">
            <div class="flex justify-between items-center select-none">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full flex items-center justify-center text-xs shadow-inner">
                        🎓
                    </div>
                    <h3 class="text-sm font-bold text-slate-200">Trilhas de Onboarding (3 Camadas)</h3>
                </div>
                <button @click="openTab('aprendizado')" class="text-xs font-bold text-sky-400 hover:text-sky-300 transition">Ver tudo ></button>
            </div>

            <div class="bg-slate-900/30 border border-slate-800/80 rounded-2xl divide-y divide-slate-800/60 overflow-hidden">
                <?php foreach ($dashboard['aprendizado'] as $item): ?>
                    <div class="p-5 flex items-center justify-between gap-4 hover:bg-slate-800/10 transition duration-200">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="text-xl flex-shrink-0 text-slate-500"><?= $item['icone'] ?></span>
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-slate-300 truncate" title="<?= htmlspecialchars($item['titulo']) ?>">
                                    <?= htmlspecialchars($item['titulo']) ?>
                                </h4>
                                <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider font-mono">
                                    <?= htmlspecialchars($item['tag']) ?>
                                </span>
                            </div>
                        </div>
                        <button @click="openTab('aprendizado')" class="py-1.5 px-4 bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-slate-100 text-[10px] font-bold rounded-lg transition flex items-center gap-1 whitespace-nowrap">
                            Iniciar Trilha
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Swiper !== 'undefined') {
            
            // 1. Inicializador do Banner em Modo Vertical
            new Swiper('.cromSwiperBanner', {
                direction: 'vertical',
                loop: true,
                grabCursor: true,
                autoplay: {
                    delay: 6000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination-banner',
                    clickable: true,
                }
            });

            // 2. Inicializador do Ecossistema em Modo Horizontal Responsivo
            new Swiper('.cromSwiperEcosystem', {
                slidesPerView: 1,
                spaceBetween: 20,
                grabCursor: true,
                loop: false,
                navigation: {
                    nextEl: '.swiper-pilar-next',
                    prevEl: '.swiper-pilar-prev',
                },
                breakpoints: {
                    480: {
                        slidesPerView: 1,
                        slidesPerGroup: 1,
                    },
                    640: {
                        slidesPerView: 2,
                        slidesPerGroup: 2,
                    },
                    1024: {
                        slidesPerView: 3,
                        slidesPerGroup: 3,
                    },
                    1280: {
                        slidesPerView: 4,
                        slidesPerGroup: 4,
                        spaceBetween: 24
                    }
                }
            });
        }
    });
</script>