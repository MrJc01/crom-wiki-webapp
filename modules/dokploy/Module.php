<?php

declare(strict_types=1);

namespace app\modules\dokploy;

use yii\base\Module as BaseModule;

class Module extends BaseModule
{
    public $controllerNamespace = 'app\modules\dokploy\controllers';

    public function init()
    {
        parent::init();
    }
}
