<?php

declare(strict_types=1);

namespace app\modules\tickets;

use yii\base\Module as BaseModule;

class Module extends BaseModule
{
    public $controllerNamespace = 'app\modules\tickets\controllers';

    public function init()
    {
        parent::init();
    }
}
