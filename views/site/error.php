<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception $exception */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\HttpException;

$statusCode = $exception instanceof HttpException ? $exception->statusCode : 500;
$this->title = "Erro " . $statusCode . " — CROM Developer";

// Mapeamento de diagnósticos amigáveis
$icon = "⚠️";
$headline = "Instabilidade na Mainframe";
$explain = "Ocorreu um erro interno inesperado enquanto processávamos sua solicitação. Nossos engenheiros de confiabilidade de sistema (SRE) já foram notificados.";
$badgeColor = "bg-rose-500/10 text-rose-400 border-rose-500/20";
$glowColor = "rgba(244,63,94,0.15)";

if ($statusCode === 403) {
    $icon = "🛡️";
    $headline = "Acesso Restrito / Chave Ausente";
    $explain = "Você não possui o nível de autorização ou privilégios administrativos necessários para acessar este recurso. Este módulo exige políticas de controle de acesso RBAC específicas.";
    $badgeColor = "bg-amber-500/10 text-amber-400 border-amber-500/20";
    $glowColor = "rgba(245,158,11,0.15)";
} elseif ($statusCode === 404) {
    $icon = "🔍";
    $headline = "Recurso Não Localizado";
    $explain = "O caminho de rota, arquivo de documentação ou recurso solicitado não pôde ser encontrado no ecossistema CROM. Verifique se o endereço está correto.";
    $badgeColor = "bg-sky-500/10 text-sky-400 border-sky-500/20";
    $glowColor = "rgba(14,165,233,0.15)";
}
?>

<div class="min-h-[500px] w-full flex flex-col items-center justify-center p-6 text-center select-text font-sans bg-slate-950 text-slate-100 flex-grow py-16 animate-fade-in relative overflow-hidden">
    <!-- Efeito luminoso de fundo -->
    <div class="absolute w-80 h-80 rounded-full blur-3xl pointer-events-none -translate-y-12"
         style="background: radial-gradient(circle, <?= $glowColor ?> 0%, transparent 70%);"></div>

    <div class="max-w-md w-full relative z-10 space-y-6">
        <!-- Ícone Premium -->
        <div class="w-20 h-20 mx-auto bg-slate-900 border border-slate-800 rounded-3xl flex items-center justify-center text-4xl shadow-2xl relative group">
            <div class="absolute inset-0 rounded-3xl bg-sky-500/5 blur group-hover:blur-md transition-all duration-300"></div>
            <span class="z-10"><?= $icon ?></span>
        </div>

        <!-- Código de Status -->
        <div class="space-y-1">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-mono font-bold uppercase tracking-widest border rounded-full <?= $badgeColor ?>">
                <span>Status Code:</span>
                <span><?= Html::encode($statusCode) ?></span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-100 tracking-tight leading-tight pt-2">
                <?= Html::encode($headline) ?>
            </h1>
        </div>

        <!-- Explicação Amigável -->
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-5 shadow-inner text-left space-y-3">
            <p class="text-xs text-slate-300 leading-relaxed">
                <?= Html::encode($explain) ?>
            </p>
            
            <?php if (!empty($message) && $message !== $name): ?>
                <div class="border-t border-slate-800/80 pt-3">
                    <p class="text-[10px] font-mono text-slate-500 uppercase tracking-wider font-bold">Mensagem do Sistema:</p>
                    <p class="text-[11px] font-mono text-rose-400 bg-rose-500/5 border border-rose-500/10 rounded-lg p-2.5 mt-1.5 break-all max-h-24 overflow-y-auto scrollbar-thin">
                        <?= Html::encode($message) ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sugestões de Ação para o Membro -->
        <div class="text-xs text-slate-500 space-y-1.5 border-t border-slate-900 pt-6 text-left">
            <p class="font-bold text-slate-400 select-none">💡 Como resolver:</p>
            <ul class="list-disc pl-4 space-y-1">
                <?php if ($statusCode === 403): ?>
                    <li>Certifique-se de que está autenticado com o usuário correto.</li>
                    <li>Solicite a permissão <code class="text-amber-400 font-mono text-[10px] bg-amber-500/10 px-1 py-0.5 rounded border border-amber-500/10">access-wiki</code> a um administrador.</li>
                <?php elseif ($statusCode === 404): ?>
                    <li>Volte ao painel e navegue utilizando os menus da barra lateral.</li>
                    <li>Confirme se o slug ou ID do documento não foi deletado do banco de dados.</li>
                <?php else: ?>
                    <li>Tente atualizar a página ou recarregar a requisição.</li>
                    <li>Caso persista, reporte o código de status à equipe de SRE.</li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Ações Reativas SPA -->
        <div class="flex flex-wrap items-center justify-center gap-3 pt-4 select-none">
            <!-- Botão Principal: Retornar ao Início -->
            <a href="<?= Url::to(['/site/index']) ?>"
               class="py-2.5 px-5 bg-gradient-to-r from-sky-500 to-indigo-600 hover:from-sky-400 hover:to-indigo-500 text-slate-100 text-xs font-bold rounded-xl transition duration-300 shadow-lg shadow-sky-950/20 flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                Voltar ao Início
            </a>

            <!-- Botão Secundário: Tentar Novamente (Recarrega via JS) -->
            <button onclick="window.location.reload();"
                    class="py-2.5 px-4 bg-slate-900 hover:bg-slate-850 border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-white text-xs font-bold rounded-xl transition duration-300 flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                Tentar Novamente
            </button>

            <!-- Ação de Autenticação condicional -->
            <?php if (!Yii::$app->user->isGuest): ?>
                <a href="<?= Url::to(['/site/logout']) ?>" data-method="post"
                   class="py-2.5 px-4 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 hover:border-rose-500/30 text-rose-400 hover:text-rose-300 text-xs font-bold rounded-xl transition duration-300 flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                    Trocar Conta
                </a>
            <?php else: ?>
                <a href="<?= Url::to(['/site/login']) ?>"
                   class="py-2.5 px-4 bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/20 hover:border-emerald-500/30 text-emerald-400 hover:text-emerald-300 text-xs font-bold rounded-xl transition duration-300 flex items-center gap-1.5">
                    🔑 Entrar no Portal
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
