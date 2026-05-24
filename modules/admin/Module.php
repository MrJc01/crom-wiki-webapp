<?php

namespace app\modules\admin;

use Yii;

/**
 * Módulo de Administração para controle de usuários, configurações do portal e auditoria de logs.
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
    }
}
