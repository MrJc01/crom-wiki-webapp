<?php

/** @var yii\web\View $this */
/** @var app\models\LoginForm $model */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Portal CROM — Autenticação';
?>

<div class="w-full max-w-md mx-4 p-6 sm:p-8 bg-slate-900/50 border border-slate-800/80 rounded-2xl backdrop-blur-md shadow-2xl flex flex-col items-center">
    <!-- Logo e Título -->
    <div class="flex flex-col items-center mb-6">
        <div class="w-14 h-14 bg-sky-500/10 text-sky-400 rounded-2xl flex items-center justify-center font-bold text-3xl border border-sky-500/20 shadow-[0_0_20px_rgba(56,189,248,0.15)] animate-pulse">
            Ω
        </div>
        <h1 class="text-xl font-bold tracking-tight text-slate-100 mt-4">Portal CROM</h1>
        <p class="text-xs text-slate-400 mt-1 font-medium">Soberania não se pede, constrói-se.</p>
    </div>

    <!-- Formulário -->
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'w-full space-y-4'],
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1'],
            'inputOptions' => ['class' => 'w-full bg-slate-950/80 border border-slate-800/80 rounded-xl p-3 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-all font-sans text-sm'],
            'errorOptions' => ['class' => 'text-xs text-rose-400 mt-1 font-semibold'],
        ],
    ]); ?>

    <?= $form->field($model, 'username')->textInput([
        'placeholder' => 'Digite seu nome de usuário',
        'autofocus' => true,
        'autocomplete' => 'off'
    ])->label('Membro') ?>

    <?= $form->field($model, 'password')->passwordInput([
        'placeholder' => 'Digite sua senha'
    ])->label('Chave de Acesso') ?>

    <div class="flex items-center justify-between py-1">
        <div class="flex items-center">
            <?= $form->field($model, 'rememberMe', [
                'template' => "<div class=\"flex items-center gap-2\">{input}\n{label}</div>\n{error}",
                'labelOptions' => ['class' => 'text-xs text-slate-400 cursor-pointer select-none font-medium'],
                'inputOptions' => ['class' => 'rounded bg-slate-950 border-slate-800 text-sky-500 focus:ring-sky-500/20 w-4 h-4 cursor-pointer']
            ])->checkbox([], false)->label('Lembrar Conexão') ?>
        </div>
    </div>

    <div>
        <?= Html::submitButton('Acessar o Portal', [
            'class' => 'w-full py-3 bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white rounded-xl text-sm font-bold shadow-md shadow-sky-600/10 hover:shadow-sky-500/20 transition-all duration-300 transform active:scale-[0.98]',
            'name' => 'login-button'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="mt-6 text-center border-t border-slate-800/60 pt-4 w-full">
        <p class="text-[10px] text-slate-500 font-mono">
            Ambiente Interno Restrito. Acesso criptografado.
        </p>
    </div>
</div>
