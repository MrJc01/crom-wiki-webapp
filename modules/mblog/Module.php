<?php

declare(strict_types=1);

namespace app\modules\mblog;

use yii\base\Module as BaseModule;

class Module extends BaseModule
{
    public $controllerNamespace = 'app\modules\mblog\controllers';

    public function init()
    {
        parent::init();
    }
}
