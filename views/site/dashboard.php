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
// Carrega as configurações do SQLite (com mocks como fallback de deploy)
$settingsMap = [];
try {
    $settingsList = Yii::$app->db->createCommand("SELECT * FROM core_settings")->queryAll();
    foreach ($settingsList as $s) {
        $settingsMap[$s['key']] = $s['value'];
    }
} catch (\Exception $e) {
    // Silencia se a tabela não existir
}

$dashboardBadge = $settingsMap['dashboard_badge'] ?? 'Soberania';
$dashboardTitle = $settingsMap['dashboard_title'] ?? 'CROM';
$dashboardDesc = $settingsMap['dashboard_desc'] ?? 'Crie e colabore em documentações locais em Markdown com autonomia radical e controle de governança direto na base.';
$dashboardBtnText = $settingsMap['dashboard_btn_text'] ?? 'Consultar Documentos Internos';
$dashboardBtnTab = $settingsMap['dashboard_btn_tab'] ?? 'page_crud';

// 1. Frases e palavra do dia
$dailyQuoteTitle = $settingsMap['daily_quote_title'] ?? $dailyQuoterandomDayTitle;
$dailyQuoteText = $settingsMap['daily_quote_text'] ?? $dailyQuoterandomDay;

// 2. Ecossistema (JSON)
$ecosystemCards = [];
if (!empty($settingsMap['ecosystem_cards_json'])) {
    $ecosystemCards = json_decode($settingsMap['ecosystem_cards_json'], true);
}
if (empty($ecosystemCards) || !is_array($ecosystemCards)) {
    $ecosystemCards = [
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
            'tab'         => 'cromia'
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
            'nome'        => 'JSON Store',
            'tag'         => 'Desenvolvimento',
            'tag_style'   => 'bg-sky-500/20 text-sky-300 border-sky-500/30',
            'bg_style'    => 'bg-gradient-to-br from-sky-950/40 to-slate-900 border-sky-900/60 hover:border-sky-500/40',
            'icone'       => '⚡',
            'descricao'   => 'Crie e gerencie endpoints JSON dinâmicos. Compartilhe dados públicos ou privados protegidos por tokens de API de forma rápida e segura.',
            'btn_texto'   => 'Gerenciar JSONs',
            'btn_style'   => 'bg-sky-600 hover:bg-sky-500 text-white',
            'disabled'    => false,
            'tab'         => 'json_store'
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
    ];
}

// 3. Alinhamento Operacional (JSON)
$alignmentCards = [];
if (!empty($settingsMap['alignment_cards_json'])) {
    $alignmentCards = json_decode($settingsMap['alignment_cards_json'], true);
}
if (empty($alignmentCards) || !is_array($alignmentCards)) {
    $alignmentCards = [
        [
            'titulo'    => 'Contrato de Cuidado & Vesting',
            'descricao' => 'Seu histórico e impacto de contribuição técnico permanecem registrados em blockchain interna/wiki. Distribuição de proventos comerciais B2B prioritária para Pilares.',
            'btn_texto' => 'Ver benefícios',
            'tab'       => 'beneficios'
        ],
        [
            'titulo'    => 'Manifesto do Ecossistema Local-First',
            'tag'       => 'Filosofia',
            'descricao' => 'Leia as diretrizes completas sobre Soberania Digital, infraestruturas resilientes offline e o porquê de rejeitarmos modelos centralizados.',
            'btn_texto' => 'Acessar Manifesto Externo',
            'link'      => 'https://crom.me/manifesto'
        ]
    ];
}

$dashboard = [
    'banners' => [
        [
            'badge'            => $dashboardBadge,
            'titulo_principal' => $dashboardTitle,
            'titulo_accent'    => '',
            'descricao'        => $dashboardDesc,
            'btn_texto'        => $dashboardBtnText,
            'btn_tab'          => $dashboardBtnTab,
            'gradiente'        => 'from-sky-400/20 to-indigo-500/0'
        ]
    ],
    'ecossistema' => $ecosystemCards,
    'beneficios_preview' => $alignmentCards,
    'projetos_hook' => [
        'titulo'    => $dailyQuoteTitle,
        'subtitulo' => $dailyQuoteText
    ],
];

// Consulta de usuários ativos nos últimos 15 minutos (Otimização SRE)
$timeThreshold = time() - 900;
$onlineUsers = [];
try {
    $onlineUsers = Yii::$app->db->createCommand("
        SELECT u.username, s.last_activity
        FROM core_session_status s
        JOIN core_users u ON s.user_id = u.id
        WHERE s.last_activity >= :threshold AND s.is_online = 1
        ORDER BY u.username ASC
    ", [':threshold' => $timeThreshold])->queryAll();
} catch (\Exception $e) {
    // Silencia
}
$onlineCount = count($onlineUsers);

// Consulta de tickets disponíveis
$userId = (int)Yii::$app->user->id;
$user = Yii::$app->user->identity;
$ticketsAvailable = [];
try {
    $allOpenTickets = \app\modules\tickets\models\SupportTicket::find()
        ->where(['status' => \app\modules\tickets\models\SupportTicket::STATUS_OPEN])
        ->andWhere(['!=', 'created_by', $userId])
        ->orderBy(['created_at' => SORT_DESC])
        ->all();
    foreach ($allOpenTickets as $ticket) {
        if ($ticket->canUserTake($user)) {
            $ticketsAvailable[] = $ticket;
        }
    }
} catch (\Exception $e) {
    // Silencia se a tabela/modelo não existirem
}
?>

<style>
    /* Customização dos bullets do Swiper Vertical e desabilitados */
    .swiper-button-disabled { opacity: 0.25; cursor: not-allowed !important; }
    .swiper-pagination-banner .swiper-pagination-bullet { background: #0f172a; opacity: 0.2; width: 8px; height: 8px; transition: all 0.2s; }
    .swiper-pagination-banner .swiper-pagination-bullet-active { background: #0284c7; opacity: 1; height: 20px; border-radius: 4px; }
    
    /* Ajuste de altura auto para impedir o estiramento 100% herdado do contêiner pai */
    .cromSwiperEcosystem,
    .cromSwiperEcosystem .swiper-wrapper,
    .cromSwiperEcosystem .swiper-slide {
        height: auto !important;
    }
</style>

<div class="space-y-10 pb-16 selection:bg-sky-500/20"
     x-data="{
         widgets: [],
         showCustomizer: false,
         notes: '',
         serverCpu: 24,
         serverRam: 52,
         serverDisk: 57,
         serverPing: 42,
         
         init() {
             const saved = localStorage.getItem('crom_dashboard_widgets_v2');
             const defaultWidgets = [
                 { id: 'eco_swiper', name: 'Ecossistema CROM', enabled: true, icon: '🚀', size: 'full' },
                 { id: 'quick_access', name: 'Acessos Rápidos', enabled: true, icon: '⚡', size: '1col' },
                 { id: 'tickets', name: 'Tickets Disponíveis', enabled: true, icon: '🎟️', size: '1col' },
                 { id: 'quote', name: 'Palavra do Dia', enabled: true, icon: '🔥', size: '1col' },
                 { id: 'alignment', name: 'Alinhamento Operacional', enabled: true, icon: '❤️', size: '1col' },
                 { id: 'online_users', name: 'Membros Online', enabled: true, icon: '🟢', size: '1col' },
                 { id: 'server_status', name: 'Status de VPS/Servidor', enabled: true, icon: '🖥️', size: '1col' },
                 { id: 'quick_notes', name: 'Notas Rápidas', enabled: true, icon: '📝', size: '1col' }
             ];
             
             if (saved) {
                 try {
                     const parsed = JSON.parse(saved);
                     this.widgets = defaultWidgets.map(def => {
                         const found = parsed.find(w => w.id === def.id);
                         return found ? { ...def, enabled: found.enabled } : def;
                     });
                     const orderMap = parsed.map(w => w.id);
                     this.widgets.sort((a, b) => {
                         let idxA = orderMap.indexOf(a.id);
                         let idxB = orderMap.indexOf(b.id);
                         if (idxA === -1) idxA = 999;
                         if (idxB === -1) idxB = 999;
                         return idxA - idxB;
                     });
                 } catch (e) {
                     this.widgets = defaultWidgets;
                 }
             } else {
                 this.widgets = defaultWidgets;
             }
             
             this.notes = localStorage.getItem('crom_dashboard_notes') || '';
             
             // Simulação de pulso de monitoramento do servidor
             setInterval(() => {
                 this.serverCpu = Math.floor(20 + Math.random() * 15);
                 this.serverPing = Math.floor(38 + Math.random() * 10);
             }, 4000);
         },
         
         save() {
             localStorage.setItem('crom_dashboard_widgets_v2', JSON.stringify(this.widgets));
         },
         
         saveNotes() {
             localStorage.setItem('crom_dashboard_notes', this.notes);
         },
         
         isWidgetEnabled(id) {
             const w = this.widgets.find(w => w.id === id);
             return w ? w.enabled : false;
         },
         
         getWidgetOrder(id) {
             return this.widgets.findIndex(w => w.id === id);
         },
         
         toggleWidget(id) {
             const w = this.widgets.find(w => w.id === id);
             if (w) {
                 w.enabled = !w.enabled;
                 this.save();
             }
         },
         
         moveWidget(index, direction) {
             const newIndex = index + direction;
             if (newIndex < 0 || newIndex >= this.widgets.length) return;
             const temp = this.widgets[index];
             this.widgets[index] = this.widgets[newIndex];
             this.widgets[newIndex] = temp;
             this.save();
             setTimeout(() => {
                 if (typeof window.initDashboardSwipers === 'function') {
                     window.initDashboardSwipers();
                 }
             }, 50);
         },
         
         resetWidgets() {
             localStorage.removeItem('crom_dashboard_widgets_v2');
             this.init();
             setTimeout(() => {
                 if (typeof window.initDashboardSwipers === 'function') {
                     window.initDashboardSwipers();
                 }
             }, 50);
         }
     }">
    
    <!-- 1. BANNER FIXO DO TOPO -->
    <section class="bg-[#e8f0fe] rounded-[32px] border border-sky-200/50 shadow-lg relative overflow-hidden select-none min-h-[420px] md:min-h-0 md:h-[320px] py-4 md:py-0">
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

    <!-- Barra de Controle do Dashboard -->
    <div class="flex justify-between items-center bg-slate-900/40 border border-slate-800/80 rounded-[24px] p-4 backdrop-blur-md">
        <div class="flex items-center gap-2.5">
            <span class="text-sm">👋</span>
            <span class="text-xs font-semibold text-slate-300">Olá, <strong class="text-slate-100"><?= htmlspecialchars(Yii::$app->user->identity->username) ?></strong>. Personalize seu painel com widgets.</span>
        </div>
        <button @click="showCustomizer = true"
                class="py-2.5 px-4 bg-slate-800 hover:bg-slate-750 text-slate-200 hover:text-white rounded-xl text-xs font-bold transition flex items-center gap-2 border border-slate-700/60 hover:border-slate-600 shadow-md">
            <span>⚙️ Customizar Painel</span>
        </button>
    </div>

    <!-- Grid Dinâmico de Widgets -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- WIDGET: ECO_SWIPER -->
        <div x-show="isWidgetEnabled('eco_swiper')"
             :style="{ order: getWidgetOrder('eco_swiper') }"
             class="col-span-full space-y-5 pb-6"
             style="display: none;">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 select-none">
                <h2 class="text-lg font-bold text-slate-100 tracking-tight flex items-center gap-2">
                    <span>🚀</span> Ecossistema CROM
                </h2>
                
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
                                    
                                    $isExternal = false;
                                    if (!empty($card['link'])) {
                                        $link = $card['link'];
                                        if (strpos($link, 'http://') === 0 || strpos($link, 'https://') === 0 || strpos($link, '//') === 0) {
                                            $isExternal = true;
                                        }
                                    }
                                    
                                    if ($isExternal): ?>
                                        <a href="<?= htmlspecialchars($card['link']) ?>" class="w-full py-2.5 px-4 text-center rounded-full text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-md <?= $card['btn_style'] ?>" target="_blank">
                                            <?= htmlspecialchars($card['btn_texto']) ?>
                                        </a>
                                    <?php elseif (!empty($card['tab'])): ?>
                                        <button @click="openTab('<?= $card['tab'] ?>')" class="w-full py-2.5 px-4 text-center rounded-full text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-md <?= $card['btn_style'] ?>">
                                            <?= htmlspecialchars($card['btn_texto']) ?>
                                        </button>
                                    <?php elseif (!empty($card['link'])): ?>
                                        <a href="<?= htmlspecialchars($card['link']) ?>" class="w-full py-2.5 px-4 text-center rounded-full text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-md <?= $card['btn_style'] ?>" target="_blank">
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
        </div>

        <!-- WIDGET: QUICK_ACCESS -->
        <div x-show="isWidgetEnabled('quick_access')"
             :style="{ order: getWidgetOrder('quick_access') }"
             class="col-span-1 bg-slate-900/30 border border-slate-800/80 rounded-[28px] p-6 hover:border-slate-700/60 transition duration-300 flex flex-col justify-between"
             style="display: none;">
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 bg-sky-500/10 text-sky-400 border border-sky-500/20 rounded-full flex items-center justify-center text-xs shadow-inner">
                        ⚡
                    </div>
                    <h3 class="text-sm font-bold text-slate-200">Acessos Rápidos</h3>
                </div>
                <p class="text-[10px] text-slate-400 leading-relaxed font-sans">
                    Atalhos diretos para as principais seções e ferramentas da nossa SPA.
                </p>
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <button @click="openTab('discover')" class="p-3 bg-slate-950/60 hover:bg-slate-850 border border-slate-800 hover:border-slate-700 rounded-2xl flex flex-col items-center justify-center gap-2 text-center transition group cursor-pointer">
                        <span class="text-xl group-hover:scale-110 transition duration-200">🧭</span>
                        <span class="text-[10px] font-bold text-slate-300">Discover</span>
                    </button>
                    <button @click="openTab('wiki')" class="p-3 bg-slate-950/60 hover:bg-slate-850 border border-slate-800 hover:border-slate-700 rounded-2xl flex flex-col items-center justify-center gap-2 text-center transition group cursor-pointer">
                        <span class="text-xl group-hover:scale-110 transition duration-200">📖</span>
                        <span class="text-[10px] font-bold text-slate-300">Wiki Docs</span>
                    </button>
                    <button @click="openTab('chat')" class="p-3 bg-slate-950/60 hover:bg-slate-850 border border-slate-800 hover:border-slate-700 rounded-2xl flex flex-col items-center justify-center gap-2 text-center transition group cursor-pointer">
                        <span class="text-xl group-hover:scale-110 transition duration-200">💬</span>
                        <span class="text-[10px] font-bold text-slate-300">Chat Geral</span>
                    </button>
                    <button @click="openTab('terminal')" class="p-3 bg-slate-950/60 hover:bg-slate-850 border border-slate-800 hover:border-slate-700 rounded-2xl flex flex-col items-center justify-center gap-2 text-center transition group cursor-pointer">
                        <span class="text-xl group-hover:scale-110 transition duration-200">🖥️</span>
                        <span class="text-[10px] font-bold text-slate-300">VPS SSH</span>
                    </button>
                </div>
            </div>
            <div class="mt-6 pt-3 border-t border-slate-800/80 flex items-center justify-between">
                <span class="text-[9px] font-mono text-slate-500 uppercase tracking-widest">Painel CROM</span>
                <button @click="openTab('profile')" class="text-[10px] font-extrabold text-sky-400 hover:text-sky-350 transition flex items-center gap-1 cursor-pointer">
                    Perfil <span>⚙️</span>
                </button>
            </div>
        </div>

        <!-- WIDGET: TICKETS -->
        <div x-show="isWidgetEnabled('tickets')"
             :style="{ order: getWidgetOrder('tickets') }"
             class="col-span-1 bg-slate-900/30 border border-slate-800/80 rounded-[28px] p-6 hover:border-slate-700/60 transition duration-300 flex flex-col justify-between"
             style="display: none;">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 bg-amber-500/10 text-amber-400 border border-amber-500/20 rounded-full flex items-center justify-center text-xs shadow-inner">
                            🎟️
                        </div>
                        <h3 class="text-sm font-bold text-slate-200">Tickets Disponíveis</h3>
                    </div>
                    <span class="px-2.5 py-0.5 bg-amber-500/15 border border-amber-500/30 text-amber-400 text-[9px] font-mono rounded-full font-bold">
                        <?= count($ticketsAvailable) ?>
                    </span>
                </div>
                
                <div class="space-y-2.5 overflow-y-auto max-h-[160px] pr-1">
                    <?php if (empty($ticketsAvailable)): ?>
                        <div class="py-6 text-center text-[10px] text-slate-500 leading-relaxed">
                            Todos os tickets foram assumidos ou exigem tags que você não possui no momento.
                        </div>
                    <?php else: ?>
                        <?php foreach (array_slice($ticketsAvailable, 0, 3) as $ticket): ?>
                            <div class="bg-slate-950/40 border border-slate-800/80 rounded-xl p-3 hover:border-slate-700/60 transition flex items-center justify-between gap-2">
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-xs font-bold text-slate-200 truncate"><?= htmlspecialchars($ticket->title) ?></h4>
                                    <p class="text-[9px] text-slate-500 font-mono mt-0.5"><?= $ticket->getTypeLabel() ?></p>
                                </div>
                                <button hx-post="<?= Url::to(['/tickets/default/take', 'id' => $ticket->id]) ?>"
                                        hx-target="#container-tickets"
                                        @click="setTimeout(() => openTab('tickets'), 100)"
                                        class="py-1 px-2.5 bg-amber-600 hover:bg-amber-500 text-white font-extrabold text-[9px] rounded-lg transition whitespace-nowrap cursor-pointer">
                                    Assumir
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between">
                <span class="text-[9px] font-mono text-slate-500 uppercase tracking-widest">Suporte</span>
                <button @click="openTab('tickets')" class="text-[10px] font-extrabold text-amber-400 hover:text-amber-350 transition flex items-center gap-1 cursor-pointer">
                    Central de Tickets <span>→</span>
                </button>
            </div>
        </div>

        <!-- WIDGET: QUOTE -->
        <div x-show="isWidgetEnabled('quote')"
             :style="{ order: getWidgetOrder('quote') }"
             class="col-span-1 bg-slate-900/30 border border-slate-800/80 rounded-[28px] p-6 hover:border-slate-700/60 transition duration-300 flex flex-col justify-between"
             style="display: none;">
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 bg-amber-500/10 text-amber-400 border border-amber-500/20 rounded-full flex items-center justify-center text-xs shadow-inner">
                        🔥
                    </div>
                    <h3 class="text-sm font-bold text-slate-200">Palavra do Dia</h3>
                </div>
                
                <div class="bg-gradient-to-br from-slate-950/60 to-slate-900 border border-slate-850 rounded-2xl p-4 flex flex-col justify-between relative overflow-hidden group">
                    <div class="absolute -right-6 -bottom-6 w-16 h-16 bg-amber-500/5 rounded-full blur-md group-hover:scale-150 transition duration-500"></div>
                    <p class="text-xs text-slate-350 leading-relaxed font-sans italic">
                        "<?= htmlspecialchars($dashboard['projetos_hook']['subtitulo']) ?>"
                    </p>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-[9px] font-bold text-amber-400 uppercase tracking-wider bg-amber-500/10 px-2 py-0.5 rounded border border-amber-500/20">
                            <?= htmlspecialchars($dashboard['projetos_hook']['titulo']) ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-800/80 text-[9px] font-mono text-slate-500 uppercase tracking-widest text-right">
                Reflexão Diária
            </div>
        </div>

        <!-- WIDGET: ALIGNMENT -->
        <div x-show="isWidgetEnabled('alignment')"
             :style="{ order: getWidgetOrder('alignment') }"
             class="col-span-1 bg-slate-900/30 border border-slate-800/80 rounded-[28px] p-6 hover:border-slate-700/60 transition duration-300 flex flex-col justify-between"
             style="display: none;">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 bg-rose-500/10 text-rose-400 border border-rose-500/20 rounded-full flex items-center justify-center text-xs shadow-inner">
                            ❤️
                        </div>
                        <h3 class="text-sm font-bold text-slate-200">Alinhamento Operacional</h3>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <?php foreach ($dashboard['beneficios_preview'] as $previewItem): ?>
                        <div class="bg-slate-950/40 border border-slate-850 hover:border-slate-750 p-4 rounded-2xl transition duration-200 flex flex-col gap-2 relative overflow-hidden group">
                            <div class="flex items-start justify-between gap-1">
                                <h4 class="text-xs font-bold text-slate-200 line-clamp-1">
                                    <?= htmlspecialchars($previewItem['titulo']) ?>
                                </h4>
                                <?php if (isset($previewItem['tag'])): ?>
                                    <span class="text-[8px] uppercase font-mono font-extrabold px-1.5 py-0.2 rounded bg-slate-850 border border-slate-800 text-slate-550 flex-shrink-0">
                                        <?= htmlspecialchars($previewItem['tag']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <p class="text-[10px] text-slate-400 leading-relaxed line-clamp-2">
                                <?= htmlspecialchars($previewItem['descricao']) ?>
                            </p>
                            <div class="mt-1">
                                <?php 
                                $isExternal = false;
                                if (!empty($previewItem['link'])) {
                                    $link = $previewItem['link'];
                                    if (strpos($link, 'http://') === 0 || strpos($link, 'https://') === 0 || strpos($link, '//') === 0) {
                                        $isExternal = true;
                                    }
                                }
                                
                                if ($isExternal): ?>
                                    <a href="<?= htmlspecialchars($previewItem['link']) ?>" class="inline-block py-1 px-3 bg-slate-900 border border-slate-800 hover:border-slate-755 text-slate-300 text-[9px] font-bold rounded-lg transition" target="_blank">
                                        <?= htmlspecialchars($previewItem['btn_texto']) ?> ↗
                                    </a>
                                <?php elseif (!empty($previewItem['tab'])): ?>
                                    <button @click="openTab('<?= $previewItem['tab'] ?>')" class="py-1 px-3 bg-slate-900 border border-slate-800 hover:border-slate-755 text-slate-300 text-[9px] font-bold rounded-lg transition cursor-pointer">
                                        <?= htmlspecialchars($previewItem['btn_texto']) ?>
                                    </button>
                                <?php elseif (!empty($previewItem['link'])): ?>
                                    <a href="<?= htmlspecialchars($previewItem['link']) ?>" class="inline-block py-1 px-3 bg-slate-900 border border-slate-800 hover:border-slate-755 text-slate-300 text-[9px] font-bold rounded-lg transition" target="_blank">
                                        <?= htmlspecialchars($previewItem['btn_texto']) ?> ↗
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-800/80 text-[9px] font-mono text-slate-500 uppercase tracking-widest text-right">
                Diretrizes
            </div>
        </div>

        <!-- WIDGET: ONLINE_USERS -->
        <div x-show="isWidgetEnabled('online_users')"
             :style="{ order: getWidgetOrder('online_users') }"
             class="col-span-1 bg-slate-900/30 border border-slate-800/80 rounded-[28px] p-6 hover:border-slate-700/60 transition duration-300 flex flex-col justify-between"
             style="display: none;">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full flex items-center justify-center text-xs shadow-inner">
                            🟢
                        </div>
                        <h3 class="text-sm font-bold text-slate-200">Membros Online</h3>
                    </div>
                    <span class="px-2.5 py-0.5 bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 text-[9px] font-mono rounded-full font-bold">
                        <?= count($onlineUsers) ?>
                    </span>
                </div>
                
                <div class="space-y-3 overflow-y-auto max-h-[160px] pr-1">
                    <?php if (empty($onlineUsers)): ?>
                        <div class="py-6 text-center text-[10px] text-slate-500">
                            Nenhum membro ativo detectado no momento.
                        </div>
                    <?php else: ?>
                        <?php foreach (array_slice($onlineUsers, 0, 4) as $index => $u): ?>
                            <div class="flex items-center gap-3 bg-slate-950/40 border border-slate-800/80 p-2.5 rounded-xl hover:border-slate-700 transition">
                                <div class="w-6 h-6 rounded-lg bg-gradient-to-tr from-sky-500 to-indigo-600 flex items-center justify-center text-[9px] font-extrabold text-white shadow-inner">
                                    <?= strtoupper(substr($u['username'], 0, 2)) ?>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-xs font-bold text-slate-200 truncate"><?= htmlspecialchars($u['username']) ?></h4>
                                    <p class="text-[8px] text-slate-500 font-mono mt-0.2">ativo</p>
                                </div>
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            </div>
                        <?php endforeach; ?>
                        <?php if (count($onlineUsers) > 4): ?>
                            <div class="text-[9px] font-bold text-slate-500 text-center font-mono pt-1">
                                + <?= count($onlineUsers) - 4 ?> outros ativos
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between">
                <span class="text-[9px] font-mono text-slate-500 uppercase tracking-widest">Sessões</span>
                <button @click="openTab('online_members')" class="text-[10px] font-extrabold text-emerald-400 hover:text-emerald-350 transition flex items-center gap-1 cursor-pointer">
                    Ver todos <span>→</span>
                </button>
            </div>
        </div>

        <!-- WIDGET: SERVER_STATUS -->
        <div x-show="isWidgetEnabled('server_status')"
             :style="{ order: getWidgetOrder('server_status') }"
             class="col-span-1 bg-slate-900/30 border border-slate-800/80 rounded-[28px] p-6 hover:border-slate-700/60 transition duration-300 flex flex-col justify-between"
             style="display: none;">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 bg-purple-500/10 text-purple-400 border border-purple-500/20 rounded-full flex items-center justify-center text-xs shadow-inner">
                            🖥️
                        </div>
                        <h3 class="text-sm font-bold text-slate-200">Status do Servidor</h3>
                    </div>
                    <span class="px-2 py-0.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[8px] font-extrabold font-mono tracking-wider rounded uppercase">
                        ONLINE
                    </span>
                </div>
                
                <!-- Monitor de Indicadores SRE -->
                <div class="space-y-3.5 pt-1">
                    <!-- CPU -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-[10px] font-bold">
                            <span class="text-slate-400 font-mono">Uso de CPU</span>
                            <span class="text-emerald-400 font-mono" x-text="serverCpu + '%'"></span>
                        </div>
                        <div class="w-full h-1.5 bg-slate-950 rounded-full overflow-hidden border border-slate-850">
                            <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full transition-all duration-1000"
                                 :style="{ width: serverCpu + '%' }"></div>
                        </div>
                    </div>
                    
                    <!-- RAM -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-[10px] font-bold">
                            <span class="text-slate-400 font-mono">Memória RAM</span>
                            <span class="text-sky-400 font-mono" x-text="serverRam + '% (4.2GB / 8GB)'"></span>
                        </div>
                        <div class="w-full h-1.5 bg-slate-950 rounded-full overflow-hidden border border-slate-850">
                            <div class="h-full bg-gradient-to-r from-sky-500 to-indigo-500 rounded-full"
                                 :style="{ width: serverRam + '%' }"></div>
                        </div>
                    </div>

                    <!-- Armazenamento -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-[10px] font-bold">
                            <span class="text-slate-400 font-mono">Armazenamento</span>
                            <span class="text-amber-400 font-mono" x-text="serverDisk + '% (68GB / 120GB)'"></span>
                        </div>
                        <div class="w-full h-1.5 bg-slate-950 rounded-full overflow-hidden border border-slate-850">
                            <div class="h-full bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"
                                 :style="{ width: serverDisk + '%' }"></div>
                        </div>
                    </div>

                    <!-- Network Ping -->
                    <div class="flex items-center justify-between bg-slate-950/40 border border-slate-850 p-2.5 rounded-xl">
                        <span class="text-[10px] font-bold text-slate-400 font-mono">Latência de Rede</span>
                        <div class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                            <span class="text-[10px] font-bold text-emerald-400 font-mono" x-text="serverPing + 'ms'"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between">
                <span class="text-[9px] font-mono text-slate-500 uppercase tracking-widest">Servidor VPS</span>
                <button @click="openTab('terminal')" class="text-[10px] font-extrabold text-purple-400 hover:text-purple-350 transition flex items-center gap-1 cursor-pointer">
                    Abrir Terminal <span>→</span>
                </button>
            </div>
        </div>

        <!-- WIDGET: QUICK_NOTES -->
        <div x-show="isWidgetEnabled('quick_notes')"
             :style="{ order: getWidgetOrder('quick_notes') }"
             class="col-span-1 bg-slate-900/30 border border-slate-800/80 rounded-[28px] p-6 hover:border-slate-700/60 transition duration-300 flex flex-col justify-between"
             style="display: none;">
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 bg-amber-500/10 text-amber-400 border border-amber-500/20 rounded-full flex items-center justify-center text-xs shadow-inner">
                        📝
                    </div>
                    <h3 class="text-sm font-bold text-slate-200">Notas Rápidas</h3>
                </div>
                
                <div class="relative">
                    <textarea x-model="notes"
                              @input="saveNotes()"
                              placeholder="Escreva notas, tarefas ou links rápidos aqui... (Salvo automaticamente)"
                              rows="6"
                              class="w-full p-4 bg-slate-950/60 border border-slate-800 focus:border-slate-700 rounded-2xl text-xs text-slate-200 placeholder-slate-600 focus:outline-none resize-none font-mono leading-relaxed focus:ring-1 focus:ring-slate-750 transition"></textarea>
                    <div class="absolute bottom-3 right-3 text-[8px] font-mono text-slate-500 select-none uppercase tracking-widest">
                        LOCAL STORAGE
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-800/80 text-[9px] font-mono text-slate-500 uppercase tracking-widest text-right">
                Bloco de Notas
            </div>
        </div>

    </div>

    <!-- Drawer Customizador de Painel -->
    <div x-show="showCustomizer" 
         class="fixed inset-0 z-50 overflow-hidden" 
         style="display: none;">
        <!-- Overlay Escuro de Fundo -->
        <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" 
             @click="showCustomizer = false"></div>

        <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
            <div class="pointer-events-auto w-screen max-w-md transform bg-slate-900 border-l border-slate-800 text-slate-100 shadow-2xl transition-all duration-300 backdrop-blur-xl bg-opacity-95"
                 x-show="showCustomizer"
                 x-transition:enter="transform transition ease-in-out duration-300"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in-out duration-300"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full">
                 
                <!-- Cabeçalho do Drawer -->
                <div class="flex items-center justify-between border-b border-slate-800 p-6">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">⚙️</span>
                        <h2 class="text-lg font-bold text-white tracking-tight font-sans">Customizar Painel</h2>
                    </div>
                    <button @click="showCustomizer = false" class="text-slate-400 hover:text-slate-100 focus:outline-none cursor-pointer">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Lista de Widgets -->
                <div class="flex flex-col h-[calc(100%-160px)] overflow-y-auto p-6 space-y-4">
                    <p class="text-xs text-slate-400">Ative, desative ou reordene os widgets abaixo para personalizar sua página de entrada conforme o seu fluxo de trabalho.</p>
                    
                    <div class="space-y-3">
                        <template x-for="(w, idx) in widgets" :key="w.id">
                            <div class="bg-slate-950/40 border border-slate-800 rounded-2xl p-4 flex items-center justify-between gap-3 hover:border-slate-700/60 transition">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-lg bg-slate-850 border border-slate-800 flex items-center justify-center text-sm shadow-inner" x-text="w.icon"></div>
                                    <div class="min-w-0">
                                        <h4 class="text-xs font-bold text-slate-200 truncate" x-text="w.name"></h4>
                                        <span class="text-[9px] uppercase font-mono font-extrabold text-slate-500" x-text="w.size === 'full' ? 'Largura Total' : '1 Coluna'"></span>
                                    </div>
                                </div>
                                
                                <!-- Controles -->
                                <div class="flex items-center gap-2">
                                    <!-- Reordenar -->
                                    <div class="flex items-center border border-slate-800 rounded-lg overflow-hidden bg-slate-900/60">
                                        <button @click="moveWidget(idx, -1)" 
                                                :disabled="idx === 0"
                                                class="p-1 px-2 text-xs text-slate-450 hover:text-slate-100 disabled:opacity-30 disabled:cursor-not-allowed hover:bg-slate-800 transition cursor-pointer">
                                            ▲
                                        </button>
                                        <button @click="moveWidget(idx, 1)" 
                                                :disabled="idx === widgets.length - 1"
                                                class="p-1 px-2 text-xs text-slate-455 hover:text-slate-100 disabled:opacity-30 disabled:cursor-not-allowed hover:bg-slate-800 transition cursor-pointer">
                                            ▼
                                        </button>
                                    </div>
                                    
                                    <!-- Toggle Ativar/Desativar -->
                                    <button @click="toggleWidget(w.id)"
                                            class="h-7 px-3 text-[10px] font-bold rounded-lg transition cursor-pointer"
                                            :class="w.enabled ? 'bg-emerald-600/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-600/20' : 'bg-slate-850 text-slate-400 border border-slate-800 hover:border-slate-700'">
                                        <span x-text="w.enabled ? 'Ativo' : 'Oculto'"></span>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                
                <!-- Ações Finais (Resetar) -->
                <div class="border-t border-slate-800 p-6 bg-slate-900/60 absolute bottom-0 left-0 right-0 h-24">
                    <button @click="resetWidgets()" class="w-full py-2.5 bg-slate-950 border border-slate-800 hover:border-rose-500/30 text-rose-450 hover:text-rose-350 text-xs font-bold rounded-xl transition shadow-md cursor-pointer">
                        Restaurar Configurações Originais
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    window.initDashboardSwipers = function() {
        if (typeof Swiper === 'undefined') return;

        // Remove instâncias antigas se houver para evitar vazamento ou duplicação de comportamentos
        const bannerEl = document.querySelector('.cromSwiperBanner');
        if (bannerEl && bannerEl.swiper) {
            try { bannerEl.swiper.destroy(true, true); } catch(e) {}
        }
        const ecoEl = document.querySelector('.cromSwiperEcosystem');
        if (ecoEl && ecoEl.swiper) {
            try { ecoEl.swiper.destroy(true, true); } catch(e) {}
        }

        // 1. Inicializador do Banner em Modo Vertical
        new Swiper('.cromSwiperBanner', {
            direction: 'vertical',
            loop: true,
            grabCursor: true,
            observer: true,
            observeParents: true,
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
            observer: true,
            observeParents: true,
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
    };

    // Auto-inicializa com retentativas caso carregado inicialmente
    (function autoInit() {
        let retries = 0;
        function tryInit() {
            if (typeof Swiper !== 'undefined') {
                window.initDashboardSwipers();
            } else if (retries < 30) {
                retries++;
                setTimeout(tryInit, 100);
            }
        }
        tryInit();
    })();
</script>