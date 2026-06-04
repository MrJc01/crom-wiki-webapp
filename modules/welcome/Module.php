<?php

declare(strict_types=1);

namespace app\modules\welcome;

use yii\base\Module as BaseModule;

class Module extends BaseModule
{
    public $controllerNamespace = 'app\modules\welcome\controllers';

    public function init()
    {
        parent::init();
    }
}
