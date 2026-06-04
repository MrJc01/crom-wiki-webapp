<?php

declare(strict_types=1);

namespace app\modules\mapp;

use yii\base\Module as BaseModule;

class Module extends BaseModule
{
    public $controllerNamespace = 'app\modules\mapp\controllers';

    public function init()
    {
        parent::init();
    }
}
