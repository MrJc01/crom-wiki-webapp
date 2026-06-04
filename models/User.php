<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Class User
 * @package app\models
 *
 * @property int $id
 * @property string $username
 * @property string $password_hash
 * @property string|null $access_token
 * @property int $is_guardiao
 * @property int $is_pilar
 * @property int $is_forja
 * @property int $is_membro
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $email
 * @property string|null $whatsapp
 * @property string|null $discord
 * @property string|null $github
 * @property string|null $registration_ip
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'core_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password_hash', 'created_at', 'updated_at'], 'required'],
            [['username', 'password_hash', 'access_token', 'email', 'whatsapp', 'discord', 'github'], 'string', 'max' => 255],
            [['registration_ip'], 'string', 'max' => 45],
            [['username'], 'unique'],
            [['access_token'], 'unique'],
            [['created_at', 'updated_at', 'is_guardiao', 'is_pilar', 'is_forja', 'is_membro'], 'integer'],
            [['is_guardiao', 'is_pilar', 'is_forja'], 'default', 'value' => 0],
            [['is_membro'], 'default', 'value' => 1],
            [['email'], 'email'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return (string) $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->access_token;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to be validated
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
}
