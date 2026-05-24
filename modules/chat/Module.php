<?php

namespace app\modules\chat;

/**
 * Módulo de Chat Premium (Estilo WhatsApp) para o Portal CROM.
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\chat\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // Inicialização customizada do módulo de conversas e grupos
    }
}
