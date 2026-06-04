<?php

/** @var yii\web\View $this */
/** @var app\models\RegisterForm $model */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'CROM — Registro de Novo Membro';
?>

<div class="min-h-screen w-full bg-slate-950 text-slate-100 flex flex-col md:flex-row relative font-sans">
    <!-- Efeito de Brilho Mesh de Fundo (Glow radial premium) -->
    <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-sky-500/10 rounded-full blur-[140px] pointer-events-none z-0"></div>
    <div class="absolute bottom-0 right-0 w-[400px] h-[400px] bg-indigo-500/5 rounded-full blur-[120px] pointer-events-none z-0"></div>

    <!-- PAINEL ESQUERDO: Filosofia, Benefícios e Informações (Visível e Lindo) -->
    <div class="w-full md:w-3/5 p-8 sm:p-12 md:p-16 flex flex-col justify-between border-r border-slate-900/60 relative z-10 select-none bg-slate-900/10 backdrop-blur-sm">
        <div class="space-y-8 max-w-2xl">
            <!-- Logo e Título do Portal -->
            <div class="flex items-center gap-3">
                <a href="<?= Url::to(['/site/index']) ?>" class="w-10 h-10 bg-sky-500/10 text-sky-400 rounded-2xl flex items-center justify-center font-bold text-xl border border-sky-500/20 shadow-md shadow-sky-500/5 hover:border-sky-500/40 transition duration-300 no-underline">
                    Ω
                </a>
                <div>
                    <span class="text-xs font-extrabold text-sky-400 font-mono tracking-widest uppercase block">MEMBRO CROM</span>
                    <h2 class="text-sm font-extrabold text-white uppercase tracking-wider font-sans">Ficha de Ingressante</h2>
                </div>
            </div>

            <!-- Filosofia CROM -->
            <div class="space-y-4">
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-white leading-tight font-sans">
                    Soberania não se pede,<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-indigo-400">constrói-se.</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-400 leading-relaxed font-sans font-medium">
                    A CROM rejeita o conceito tradicional corporativo de subordinação. Funcionamos como um organismo descentralizado baseado em tempo, confiança e impacto histórico auditável. Torne-se dono da sua própria infraestrutura e decida seus projetos.
                </p>
            </div>

            <!-- Grid de Recursos e Benefícios -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-4">
                <!-- Benefício 1 -->
                <div class="bg-slate-900/30 border border-slate-850 p-5 rounded-2xl space-y-2 hover:border-slate-800 transition duration-300">
                    <div class="text-xl">🖥️</div>
                    <h4 class="font-bold text-slate-200 text-xs uppercase tracking-wider">VPS Dedicada</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Acesso SSH completo à infraestrutura isolada para rodar seus microsserviços e subir containers em Docker rootless.
                    </p>
                </div>

                <!-- Benefício 2 -->
                <div class="bg-slate-900/30 border border-slate-850 p-5 rounded-2xl space-y-2 hover:border-slate-800 transition duration-300">
                    <div class="text-xl">🤖</div>
                    <h4 class="font-bold text-slate-200 text-xs uppercase tracking-wider">CromIA API Gateway</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Acesso unificado e direto a modelos de IA avançados com cotas mensais flexíveis e rate limiting dedicado.
                    </p>
                </div>

                <!-- Benefício 3 -->
                <div class="bg-slate-900/30 border border-slate-850 p-5 rounded-2xl space-y-2 hover:border-slate-800 transition duration-300">
                    <div class="text-xl">📜</div>
                    <h4 class="font-bold text-slate-200 text-xs uppercase tracking-wider">Contrato de Cuidado</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Mecanismo transparente onde todo o seu tempo e impacto no ecossistema são documentados de forma aberta.
                    </p>
                </div>

                <!-- Benefício 4 -->
                <div class="bg-slate-900/30 border border-slate-850 p-5 rounded-2xl space-y-2 hover:border-slate-800 transition duration-300">
                    <div class="text-xl">⚔️</div>
                    <h4 class="font-bold text-slate-200 text-xs uppercase tracking-wider">Autonomia Radical</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Não existem gerentes designando tarefas. Você escolhe o que quer construir, estender e manter.
                    </p>
                </div>
            </div>

            <!-- Alerta Informativo Importante -->
            <div class="bg-amber-500/5 border border-amber-500/10 rounded-2xl p-4 flex gap-3 text-left">
                <span class="text-lg mt-0.5">⚠️</span>
                <div>
                    <h5 class="text-xs font-bold text-amber-400">Restrição Inicial de Membro</h5>
                    <p class="text-[10px] text-slate-450 mt-1 leading-relaxed">
                        Ao se cadastrar, você ingressará na camada de <strong>Membro comum</strong>. Acesso a recursos avançados (como servidores SSH, Central de Deploy e API de IA) exige aprovação manual dos Guardiões da CROM.
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer do Painel -->
        <div class="pt-8 border-t border-slate-900/60 mt-8">
            <p class="text-[10px] text-slate-600 font-mono">
                &copy; 2026 CROM Ecosystem. Soberania tecnológica garantida.
            </p>
        </div>
    </div>

    <!-- PAINEL DIREITO: Formulário de Registro -->
    <div class="w-full md:w-2/5 p-8 sm:p-12 md:p-16 flex flex-col justify-center relative z-10 bg-slate-950/90 backdrop-blur-md">
        <div class="w-full max-w-md mx-auto space-y-6">
            <!-- Título do Formulário -->
            <div>
                <h2 class="text-xl font-extrabold text-white tracking-tight">Criar Nova Conta</h2>
                <p class="text-xs text-slate-400 mt-1">Preencha sua credencial de acesso e canais de contato.</p>
            </div>

            <!-- Exibição de Erros Globais da Validação do IP / Banco -->
            <?php if ($model->hasErrors()): ?>
                <div class="p-4 bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-bold rounded-2xl flex items-start gap-2.5 shadow-lg select-none">
                    <span class="text-base mt-0.5">❌</span>
                    <div class="flex-1 space-y-1">
                        <span class="block">Falha no Registro</span>
                        <ul class="list-disc pl-4 space-y-0.5 text-[11px] font-semibold text-rose-350 leading-relaxed">
                            <?php foreach ($model->getFirstErrors() as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Formulário de Registro -->
            <?php $form = ActiveForm::begin([
                'id' => 'register-form',
                'options' => ['class' => 'space-y-4'],
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest font-mono mb-1'],
                    'inputOptions' => ['class' => 'w-full bg-slate-900 border border-slate-850 rounded-xl p-3 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-all font-sans text-xs font-semibold'],
                    'errorOptions' => ['class' => 'text-[10px] text-rose-400 mt-1 font-bold'],
                ],
            ]); ?>

            <div class="grid grid-cols-1 gap-4">
                <?= $form->field($model, 'username')->textInput([
                    'placeholder' => 'Escolha seu username (ex: joao.dev)',
                    'autocomplete' => 'off'
                ])->label('Membro (Nome de Usuário)') ?>

                <?= $form->field($model, 'email')->textInput([
                    'type' => 'email',
                    'placeholder' => 'Digite seu e-mail principal',
                    'autocomplete' => 'off'
                ])->label('E-mail Principal') ?>

                <div class="grid grid-cols-2 gap-3">
                    <?= $form->field($model, 'password')->passwordInput([
                        'placeholder' => 'Senha (mín. 6 char)'
                    ])->label('Senha') ?>

                    <?= $form->field($model, 'password_confirm')->passwordInput([
                        'placeholder' => 'Repita a senha'
                    ])->label('Confirmar Senha') ?>
                </div>

                <div class="border-t border-slate-900/60 my-2 pt-2">
                    <span class="block text-[9px] font-extrabold text-slate-500 uppercase tracking-widest font-mono mb-3">Redes de Comunicação (Pelo menos WhatsApp ou Discord)</span>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <?= $form->field($model, 'whatsapp')->textInput([
                            'placeholder' => '(DDD) 99999-9999',
                            'autocomplete' => 'off'
                        ])->label('WhatsApp') ?>

                        <?= $form->field($model, 'discord')->textInput([
                            'placeholder' => 'username#0000',
                            'autocomplete' => 'off'
                        ])->label('Discord') ?>
                    </div>

                    <?= $form->field($model, 'github')->textInput([
                        'placeholder' => 'Link do seu GitHub (ex: github.com/joao)',
                        'autocomplete' => 'off'
                    ])->label('GitHub (Perfil)') ?>
                </div>
            </div>

            <div>
                <?= Html::submitButton('Solicitar Ingresso na CROM', [
                    'class' => 'w-full py-3 bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white rounded-xl text-xs font-bold uppercase tracking-wider shadow-lg shadow-sky-600/10 hover:shadow-sky-500/20 transition-all duration-300 transform active:scale-[0.98]',
                    'name' => 'register-button'
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>

            <!-- Link de retorno para o Login -->
            <div class="text-center pt-4 border-t border-slate-900/60 w-full flex flex-col gap-2 select-none">
                <span class="text-xs text-slate-500">Já possui uma conta ativa?</span>
                <a href="<?= Url::to(['/site/login']) ?>" class="text-xs text-sky-400 hover:text-sky-300 font-bold hover:underline">
                    Efetuar Acesso (Login)
                </a>
            </div>
        </div>
    </div>
</div>
