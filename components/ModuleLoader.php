<?php

namespace app\components;

use yii\base\BootstrapInterface;

class ModuleLoader implements BootstrapInterface
{
    public function bootstrap($app)
    {
        try {
            $db = $app->db;
            $tableSchema = $db->getTableSchema('core_modules');
            if ($tableSchema !== null) {
                $command = $db->createCommand("SELECT id FROM core_modules WHERE is_active = 1");
                $modules = $command->queryColumn();
                foreach ($modules as $moduleId) {
                    $app->setModule($moduleId, [
                        'class' => "app\\modules\\{$moduleId}\\Module",
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Silencia falhas caso o DB não esteja pronto ou as migrações não tenham rodado
        }
    }
}
