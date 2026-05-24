<?php

declare(strict_types=1);

namespace app\tests\Unit\Models;

use app\models\LoginForm;
use Yii;
use yii\base\Security;

final class LoginFormTest extends \Codeception\Test\Unit
{
    private $_model;

    protected function _before()
    {
        $demoUser = \app\models\User::findByUsername('demo');
        if (!$demoUser) {
            Yii::$app->db->createCommand()->insert('core_users', [
                'username' => 'demo',
                'password_hash' => Yii::$app->security->generatePasswordHash('demo'),
                'created_at' => time(),
                'updated_at' => time(),
            ])->execute();
        }
    }

    protected function _after()
    {
        Yii::$app->user->logout();
        Yii::$app->db->createCommand()->delete('core_users', ['username' => 'demo'])->execute();
    }

    public function testLoginNoUser()
    {
        $this->_model = new LoginForm([
            'username' => 'not_existing_username',
            'password' => 'not_existing_password',
        ]);

        verify($this->_model->login())->false();
        verify(Yii::$app->user->isGuest)->true();
    }

    public function testLoginWrongPassword()
    {
        $this->_model = new LoginForm([
            'username' => 'demo',
            'password' => 'wrong_password',
        ]);

        verify($this->_model->login())->false();
        verify(Yii::$app->user->isGuest)->true();
        verify($this->_model->errors)->arrayHasKey('password');
    }

    public function testLoginCorrect()
    {
        $this->_model = new LoginForm([
            'username' => 'demo',
            'password' => 'demo',
        ]);

        verify($this->_model->login())->true();
        verify(Yii::$app->user->isGuest)->false();
        verify($this->_model->errors)->arrayHasNotKey('password');
    }
}
