<?php

declare(strict_types=1);

namespace app\tests\Unit\Models;

use app\models\User;

final class UserTest extends \Codeception\Test\Unit
{
    public function testFindUserById()
    {
        /** @var User $user */
        $user = User::findIdentity(1);

        verify($user)->notEmpty();
        verify($user->username)->equals('admin');
        verify(User::findIdentity(999))->empty();
    }

    public function testFindUserByAccessToken()
    {
        /** @var User $user */
        $user = User::findIdentityByAccessToken('xS224BNfcMoqZYRmcwksgI39igIQ7NnK');

        verify($user)->notEmpty();
        verify($user->username)->equals('admin');
        verify(User::findIdentityByAccessToken('non-existing'))->empty();
    }

    public function testFindUserByUsername()
    {
        /** @var User $user */
        $user = User::findByUsername('admin');

        verify($user)->notEmpty();
        verify(User::findByUsername('not-admin'))->empty();
    }

    /**
     * @depends testFindUserByUsername
     */
    public function testValidateUser()
    {
        /** @var User $user */
        $user = User::findByUsername('admin');

        verify($user->validateAuthKey('xS224BNfcMoqZYRmcwksgI39igIQ7NnK'))->notEmpty();
        verify($user->validateAuthKey('invalid-key'))->empty();
    }
}
